<?php
// =============================================================
// CompuMatch — minimal JSON API for account registration/login.
// Called via fetch() from signup.php, login.php, and admin.php so
// that client + store accounts are saved for real in MySQL instead
// of only living in the browser's localStorage.
// =============================================================
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/mailer.php';
startSecureSession();
header('Content-Type: application/json');

function respond($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

// Accepts both the new hashed passwords and the plaintext demo/seed
// passwords already sitting in compumatch.sql, so old seed accounts
// keep working after this file is added.
function verifyPassword($input, $stored) {
    $stored = (string)$stored;
    if ($stored === '' || (string)$input === '') return false;
    $info = password_get_info($stored);
    if (!empty($info['algo'])) return password_verify($input, $stored);   // normal (hashed) account
    // Old plain-text seed account: compare once; login then re-saves it hashed.
    return hash_equals($stored, (string)$input);
}

// Confirms a store_id + password pair before allowing inventory changes.
// Only APPROVED stores pass — a pending/rejected store can't change
// inventory even if its browser still has an old login cached.
// Inventory changes are authorised by the store's login SESSION (set by
// login_store), so the browser no longer has to keep the password around.
// A password is still accepted as a fallback for pages opened before this
// change. Either way the store must be APPROVED.
function verifyStoreAuth($pdo, $storeId, $password) {
    if ($storeId === '') return false;
    $stmt = $pdo->prepare('SELECT password, permit_status FROM stores WHERE store_id = ?');
    $stmt->execute([$storeId]);
    $row = $stmt->fetch();
    if (!$row || $row['permit_status'] !== 'approved') return false;
    if (!empty($_SESSION['store_id']) && hash_equals((string)$_SESSION['store_id'], (string)$storeId)) return true;
    return $password !== '' && verifyPassword($password, $row['password']);
}

// Where uploaded business permits are saved. The folder has its own
// .htaccess that blocks direct browser access — permits are only viewable
// through view_permit.php, which requires a System Admin login.
define('PERMIT_DIR', __DIR__ . '/uploads/permits');
define('PERMIT_MAX_BYTES', 5 * 1024 * 1024); // 5 MB
require_once __DIR__ . '/permit_check.php'; // auto-approve: compares uploads against sample permits

// Validates + saves an uploaded permit image. Returns the stored file name,
// or calls respond() with an error. Never trusts the browser's file name or
// MIME type — the real type is detected from the file contents.
function savePermitUpload($file, $storeId) {
    if (!$file || !isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        respond(['ok' => false, 'error' => 'Please upload a photo of your Business Permit or DTI Certificate.'], 400);
    }
    if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
        respond(['ok' => false, 'error' => 'Permit image is too large (max 5 MB).'], 400);
    }
    if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
        respond(['ok' => false, 'error' => 'Permit upload failed. Please try again.'], 400);
    }
    if ($file['size'] > PERMIT_MAX_BYTES) {
        respond(['ok' => false, 'error' => 'Permit image is too large (max 5 MB).'], 400);
    }

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $info = @getimagesize($file['tmp_name']);
    $mime = $info['mime'] ?? '';
    if (!isset($allowed[$mime])) {
        respond(['ok' => false, 'error' => 'Permit must be a JPG, PNG, or WEBP image.'], 400);
    }

    if (!permitEnsureDir(PERMIT_DIR)) {
        respond(['ok' => false, 'error' => 'Server could not create the upload folder.'], 500);
    }
    // Random name: nobody can guess another store's permit URL.
    $fileName = $storeId . '_' . bin2hex(random_bytes(12)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], PERMIT_DIR . '/' . $fileName)) {
        respond(['ok' => false, 'error' => 'Server could not save the permit image.'], 500);
    }
    return $fileName;
}

// Item photos. Unlike permits these are public (customers see them), so the
// folder is web-readable — but nothing in it may ever run as code.
define('ITEM_IMAGE_DIR', __DIR__ . '/uploads/items');
define('ITEM_IMAGE_URL', 'uploads/items');
define('ITEM_IMAGE_MAX_BYTES', 2 * 1024 * 1024); // 2 MB (the browser shrinks photos before sending)

function itemImageEnsureDir() {
    if (!is_dir(ITEM_IMAGE_DIR) && !@mkdir(ITEM_IMAGE_DIR, 0755, true)) return false;
    if (!is_file(ITEM_IMAGE_DIR . '/.htaccess')) {
        @file_put_contents(ITEM_IMAGE_DIR . '/.htaccess',
            "<FilesMatch \"\\.(php|phtml|php\\d|phar|pl|py|cgi|sh)$\">\n" .
            "    <IfModule mod_authz_core.c>\n        Require all denied\n    </IfModule>\n" .
            "    <IfModule !mod_authz_core.c>\n        Order allow,deny\n        Deny from all\n    </IfModule>\n" .
            "</FilesMatch>\n");
    }
    return true;
}

// Deletes a stored item photo. Only ever touches files inside ITEM_IMAGE_DIR.
function itemImageDelete($path) {
    if (!$path) return;
    $file = ITEM_IMAGE_DIR . '/' . basename($path);
    if (is_file($file)) @unlink($file);
}

// Accepts either a JSON body (most actions) or a multipart/form-data body
// (register_store, because it carries the permit image file).
if (!empty($_POST['action'])) {
    $input = $_POST;
} else {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) $input = [];
}
$action = $input['action'] ?? '';

// If the upload is bigger than PHP's post_max_size, PHP silently drops the
// whole body — $_POST and $_FILES both come through empty. Catch that so
// the store owner gets a real message instead of "Unknown action".
if ($action === '' && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'
    && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0 && empty($_POST) && empty($_FILES)
    && stripos($_SERVER['CONTENT_TYPE'] ?? '', 'multipart/form-data') !== false) {
    respond(['ok' => false, 'error' => 'Upload is too large. Please use a smaller permit image.'], 413);
}

try {
    $pdo = getPDO();
} catch (Throwable $e) {
    respond(['ok' => false, 'error' => 'Database unavailable'], 503);
}

try {
    switch ($action) {

    case 'register_user':
        $name = trim($input['name'] ?? '');
        $username = strtolower(trim($input['username'] ?? ''));
        $email = trim($input['email'] ?? '');
        $password = (string)($input['password'] ?? '');

        if (!$name || !$email || strlen($password) < 8) {
            respond(['ok' => false, 'error' => 'Missing or invalid fields'], 400);
        }
        if (!preg_match('/^[a-z0-9_]{3,30}$/', $username)) {
            respond(['ok' => false, 'error' => 'Username must be 3–30 characters: lowercase letters, numbers, or underscore only.'], 400);
        }

        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            respond(['ok' => false, 'error' => 'That username is already taken.'], 409);
        }

        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            respond(['ok' => false, 'error' => 'That email is already registered.'], 409);
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $verifyToken = bin2hex(random_bytes(24));
        $stmt = $pdo->prepare('INSERT INTO users (name, username, email, password, email_verified, verify_token) VALUES (?, ?, ?, ?, 0, ?)');
        $stmt->execute([$name, $username, $email, $hash, $verifyToken]);

        $link = baseUrl() . '/verify_email.php?token=' . $verifyToken;
        sendMail($email, 'Verify your CompuMatch account', '
            <p>Hi ' . htmlspecialchars($name) . ',</p>
            <p>Thanks for signing up for CompuMatch! Please verify your email by clicking the link below:</p>
            <p><a href="' . $link . '">' . $link . '</a></p>
        ');

        // SECURITY: never return this link in the HTTP response — the caller
        // isn't necessarily the account owner. For local dev without SMTP,
        // it's written to the PHP error log instead, which only the developer
        // running the server can read.
        if (DEV_MODE_SHOW_LINK) error_log('[CompuMatch dev] verify link for ' . $email . ': ' . $link);
        respond(['ok' => true]);
        break;

    // Clients log in with their USERNAME (not email).
    case 'login_user':
        $username = strtolower(trim($input['username'] ?? ''));
        $password = (string)($input['password'] ?? '');

        $locked = loginLockedMinutes($pdo, 'user', $username);
        if ($locked) {
            respond(['ok' => false, 'error' => "Too many failed attempts. Please wait $locked minute" . ($locked > 1 ? 's' : '') . ' and try again.'], 429);
        }

        $stmt = $pdo->prepare('SELECT id, name, username, email, password, email_verified FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $u = $stmt->fetch();

        if ($username === '' || !$u || !verifyPassword($password, $u['password'])) {
            recordFailedLogin($pdo, 'user', $username);
            logActivity($pdo, 'failed_login', 'client', $username ?: null, $username ?: 'Unknown client', 'Invalid username or password');
            respond(['ok' => false, 'error' => 'Invalid username or password.'], 401);
        }
        clearFailedLogins($pdo, 'user', $username);
        upgradePasswordHash($pdo, 'users', 'id', $u['id'], $u['password'], $password);
        renewSessionAfterLogin();
        // Establish the session now — list_builds/delete_build rely on this
        // instead of trusting a client-supplied email (see SECURITY note below).
        $_SESSION['user_id'] = $u['id'];
        $_SESSION['user_email'] = $u['email'];
        $_SESSION['user_name'] = $u['name'];
        $_SESSION['user_username'] = $u['username'];
        logActivity($pdo, 'successful_login', 'client', $u['username'], $u['name'], 'Client account signed in');
        // email is still returned: the rest of the site (saved builds,
        // profile settings) identifies the account by it behind the scenes.
        respond(['ok' => true, 'user' => ['name' => $u['name'], 'username' => $u['username'], 'email' => $u['email'], 'verified' => (bool)$u['email_verified']]]);
        break;

    // Lets the front-end verify its cached "logged in" state against the
    // real server session — the client only stores localStorage flags, which
    // can go stale (session expired, cookies cleared, different browser)
    // while the UI still thinks it's logged in. Called on page load.
    case 'whoami':
        if (empty($_SESSION['user_id'])) {
            respond(['ok' => true, 'logged_in' => false]);
        }
        respond(['ok' => true, 'logged_in' => true, 'user' => [
            'name' => $_SESSION['user_name'] ?? '',
            'username' => $_SESSION['user_username'] ?? '',
            'email' => $_SESSION['user_email'] ?? '',
        ]]);
        break;

    case 'logout_user':
        unset($_SESSION['user_id'], $_SESSION['user_email'], $_SESSION['user_name'], $_SESSION['user_username']);
        respond(['ok' => true]);
        break;

    case 'register_store':
        $storeId = strtolower(trim($input['store_id'] ?? ''));
        $name = trim($input['name'] ?? '');
        $password = (string)($input['password'] ?? '');

        if (!$storeId || !preg_match('/^[a-z0-9]+$/', $storeId) || !$name || strlen($password) < 6) {
            respond(['ok' => false, 'error' => 'Missing or invalid fields'], 400);
        }

        $stmt = $pdo->prepare('SELECT store_id FROM stores WHERE store_id = ?');
        $stmt->execute([$storeId]);
        if ($stmt->fetch()) {
            respond(['ok' => false, 'error' => 'That Store ID is already taken.'], 409);
        }

        // Map pin (required). Rough bounding box of the Philippines so a
        // stray click on the other side of the world gets rejected.
        $lat = isset($input['latitude']) && $input['latitude'] !== '' ? (float)$input['latitude'] : null;
        $lng = isset($input['longitude']) && $input['longitude'] !== '' ? (float)$input['longitude'] : null;
        if ($lat === null || $lng === null) {
            respond(['ok' => false, 'error' => 'Please pin your store location on the map.'], 400);
        }
        if ($lat < 4 || $lat > 22 || $lng < 116 || $lng > 127) {
            respond(['ok' => false, 'error' => 'The map pin must be inside the Philippines.'], 400);
        }

        // Validate + save the permit only AFTER every other check passes,
        // so failed sign-ups don't leave orphan files on the server.
        $permitFile = savePermitUpload($_FILES['permit'] ?? null, $storeId);

        // Auto-check the permit against the sample permit(s). Approved right
        // away if it matches; otherwise stays pending for System Admin.
        // Text the browser read from the permit photo (OCR). Saved next to
        // the image so "Re-check pending stores" can reuse it later.
        $permitText = substr((string)($input['permit_text'] ?? ''), 0, 20000);
        if ($permitText !== '') @file_put_contents(PERMIT_DIR . '/' . $permitFile . '.txt', $permitText);

        $check = permitAutoCheck($pdo, PERMIT_DIR . '/' . $permitFile, $storeId, $permitText);

        // The permit text was read in the store's own browser, so it can't be
        // fully trusted. Unless PERMIT_AUTO_APPROVE is on (config.php), a
        // passing check only RECOMMENDS approval; the System Admin decides.
        if ($check['status'] === 'approved' && !(defined('PERMIT_AUTO_APPROVE') && PERMIT_AUTO_APPROVE)) {
            $check['status'] = 'pending';
            $check['by'] = null;
            $check['notes'] = 'Auto-check passed — recommended to approve. ' . $check['notes'];
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare('INSERT INTO stores (store_id, name, address, latitude, longitude, contact, email, password, description,
                                                       permit_image, permit_status, permit_ai_notes, permit_reviewed_by, permit_reviewed_at)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CASE WHEN ? = \'approved\' THEN NOW() ELSE NULL END)');
            $stmt->execute([
                $storeId, $name,
                trim($input['address'] ?? ''), $lat, $lng, trim($input['contact'] ?? ''),
                trim($input['email'] ?? ''), $hash, trim($input['description'] ?? ''),
                $permitFile, $check['status'], $check['notes'], $check['by'], $check['status']
            ]);
        } catch (Throwable $e) {
            // don't keep the files if the DB insert failed
            @unlink(PERMIT_DIR . '/' . $permitFile);
            @unlink(PERMIT_DIR . '/' . $permitFile . '.txt');
            @unlink(PERMIT_DIR . '/' . $permitFile . '.sig');
            throw $e;
        }
        respond(['ok' => true, 'permit_status' => $check['status']]);
        break;

    case 'login_store':
        $storeId = strtolower(trim($input['store_id'] ?? ''));
        $password = (string)($input['password'] ?? '');

        $locked = loginLockedMinutes($pdo, 'store', $storeId);
        if ($locked) {
            respond(['ok' => false, 'error' => "Too many failed attempts. Please wait $locked minute" . ($locked > 1 ? 's' : '') . ' and try again.'], 429);
        }

        $stmt = $pdo->prepare('SELECT store_id, name, password, permit_status FROM stores WHERE store_id = ?');
        $stmt->execute([$storeId]);
        $s = $stmt->fetch();

        if (!$s || !verifyPassword($password, $s['password'])) {
            recordFailedLogin($pdo, 'store', $storeId);
            logActivity($pdo, 'failed_login', 'store', $storeId ?: null, $storeId ?: 'Unknown store', 'Invalid Store ID or password');
            respond(['ok' => false, 'error' => 'Invalid Store ID or password.'], 401);
        }
        clearFailedLogins($pdo, 'store', $storeId);
        upgradePasswordHash($pdo, 'stores', 'store_id', $s['store_id'], $s['password'], $password);
        // Stores can only log in once their business permit is approved.
        // (Checked AFTER the password, so nobody can probe a store's status
        // without knowing its password.)
        if ($s['permit_status'] === 'pending') {
            respond(['ok' => false, 'permit_status' => 'pending',
                     'error' => 'Your store is still waiting for business permit approval. You can log in once the admin approves it.'], 403);
        }
        if ($s['permit_status'] === 'rejected') {
            respond(['ok' => false, 'permit_status' => 'rejected',
                     'error' => 'Your business permit was not approved. Please contact the CompuMatch admin.'], 403);
        }
        // Server-side store session: inventory edits are checked against
        // this, so the browser never needs to remember the password.
        renewSessionAfterLogin();
        $_SESSION['store_id'] = $s['store_id'];
        $_SESSION['store_name'] = $s['name'];
        logActivity($pdo, 'successful_login', 'store', $s['store_id'], $s['name'], 'Store account signed in');
        respond(['ok' => true, 'store' => ['id' => $s['store_id'], 'name' => $s['name']]]);
        break;

    // Is a store logged in on this browser? (Store Dashboard checks on load.)
    case 'whoami_store':
        if (empty($_SESSION['store_id'])) respond(['ok' => true, 'logged_in' => false]);
        $stmt = $pdo->prepare('SELECT store_id, name, permit_status FROM stores WHERE store_id = ?');
        $stmt->execute([$_SESSION['store_id']]);
        $st = $stmt->fetch();
        if (!$st || $st['permit_status'] !== 'approved') {
            unset($_SESSION['store_id'], $_SESSION['store_name']);
            respond(['ok' => true, 'logged_in' => false]);
        }
        respond(['ok' => true, 'logged_in' => true, 'store' => ['id' => $st['store_id'], 'name' => $st['name']]]);
        break;

    case 'logout_store':
        unset($_SESSION['store_id'], $_SESSION['store_name']);
        respond(['ok' => true]);
        break;

    // Pull a store's current inventory straight from the database. Public
    // read (same data the storefront already shows every visitor).
    case 'list_inventory':
        $storeId = strtolower(trim($input['store_id'] ?? ''));
        if (!$storeId) respond(['ok' => false, 'error' => 'Missing store_id'], 400);

        $imageCol = itemImageColumnReady($pdo) ? ', image' : '';
        $imageCol .= sataPortsColumnReady($pdo) ? ', sata_ports' : '';
        $imageCol .= m2SlotsColumnReady($pdo) ? ', m2_slots' : '';
        $imageCol .= ramSlotsColumnReady($pdo) ? ', ram_slots' : '';
        $stmt = $pdo->prepare('SELECT id, category, name, brand, price, stock,
                                       low_stock AS lowStock, specs, sold,
                                       socket, ram_type, form_factor, storage_type' . $imageCol . '
                                FROM inventory_items WHERE store_id = ? ORDER BY category, name');
        $stmt->execute([$storeId]);
        $items = array_map(function ($it) {
            $it['price'] = (float)$it['price'];
            $it['stock'] = (int)$it['stock'];
            $it['lowStock'] = (int)$it['lowStock'];
            $it['sold'] = (int)$it['sold'];
            $it['sata_ports'] = isset($it['sata_ports']) && $it['sata_ports'] !== null ? (int)$it['sata_ports'] : null;
            $it['m2_slots'] = isset($it['m2_slots']) && $it['m2_slots'] !== null ? (int)$it['m2_slots'] : null;
            $it['ram_slots'] = isset($it['ram_slots']) && $it['ram_slots'] !== null ? (int)$it['ram_slots'] : null;
            return $it;
        }, $stmt->fetchAll());
        respond(['ok' => true, 'items' => $items]);
        break;

    // Add or update one inventory item. Requires the store's own password
    // so only the store owner can change their own listings.
    case 'save_item':
        $storeId = strtolower(trim($input['store_id'] ?? ''));
        $password = (string)($input['password'] ?? '');
        if (!verifyStoreAuth($pdo, $storeId, $password)) {
            respond(['ok' => false, 'error' => 'Store authentication failed.'], 401);
        }

        $itemId   = trim($input['item_id'] ?? '');
        $category = trim($input['category'] ?? '');
        $name     = trim($input['name'] ?? '');
        $brand    = trim($input['brand'] ?? '');
        $price    = (float)($input['price'] ?? 0);
        $stock    = (int)($input['stock'] ?? 0);
        $lowStock = (int)($input['low_stock'] ?? 5);
        $specs    = trim($input['specs'] ?? '');
        $validCats = ['cpu','gpu','motherboard','ram','storage','psu','case'];

        if (!$name || !in_array($category, $validCats, true)) {
            respond(['ok' => false, 'error' => 'Missing or invalid fields'], 400);
        }

        // Compatibility fields. Only kept for the categories that use them:
        //   CPU -> socket · Motherboard -> socket + RAM type · RAM -> RAM type
        // Anything else is stored as NULL so a stale value can't linger after
        // an item's category is changed.
        // CPU socket and RAM type are free text: whatever the store types is
        // accepted (spaces removed and upper-cased, so "lga 1851" = "LGA1851").
        $clean = function ($v) { return substr(preg_replace('/[^\x21-\x7E]/', '', strtoupper((string)$v)), 0, 40); };
        $socket  = $clean($input['socket'] ?? '');
        $ramType = $clean($input['ram_type'] ?? '');
        $needsSocket  = in_array($category, ['cpu','motherboard'], true);
        $needsRamType = in_array($category, ['motherboard','ram'], true);

        if (!$needsSocket)  $socket = '';
        if (!$needsRamType) $ramType = '';
        if (($socket !== '' || $ramType !== '') && !socketColumnFreeText($pdo)) {
            respond(['ok' => false, 'error' => 'The database could not be updated to accept any socket type. Run this in phpMyAdmin: ALTER TABLE inventory_items MODIFY socket VARCHAR(40) NULL, MODIFY ram_type VARCHAR(40) NULL;'], 500);
        }
        if ($needsSocket && $socket === '') {
            respond(['ok' => false, 'error' => 'Please type the CPU Socket Type.'], 400);
        }
        if ($needsRamType && $ramType === '') {
            respond(['ok' => false, 'error' => 'Please type the RAM Socket Type.'], 400);
        }
        $socket  = $socket !== '' ? $socket : null;
        $ramType = $ramType !== '' ? $ramType : null;

        // Motherboards: number of SATA ports (decides how many secondary
        // drives a customer can add). 1-12, anything else is stored as NULL.
        $sataPorts = null;
        if ($category === 'motherboard' && isset($input['sata_ports']) && $input['sata_ports'] !== '' && $input['sata_ports'] !== null) {
            $n = (int)$input['sata_ports'];
            if ($n >= 1 && $n <= 12) $sataPorts = $n;
        }
        $hasSata = sataPortsColumnReady($pdo);

        // Motherboards: number of M.2 slots (decides how many extra NVMe/M.2
        // drives a customer can add to the main storage). 1-8, else NULL.
        $m2Slots = null;
        if ($category === 'motherboard' && isset($input['m2_slots']) && $input['m2_slots'] !== '' && $input['m2_slots'] !== null) {
            $n = (int)$input['m2_slots'];
            if ($n >= 1 && $n <= 8) $m2Slots = $n;
        }
        $hasM2 = m2SlotsColumnReady($pdo);

        // Motherboards: number of RAM slots (decides how many memory sticks a
        // customer can add). 1-24, else NULL.
        $ramSlots = null;
        if ($category === 'motherboard' && isset($input['ram_slots']) && $input['ram_slots'] !== '' && $input['ram_slots'] !== null) {
            $n = (int)$input['ram_slots'];
            if ($n >= 1 && $n <= 24) $ramSlots = $n;
        }
        $hasRamSlots = ramSlotsColumnReady($pdo);

        if ($itemId) {
            // Make sure the item actually belongs to this store before editing it.
            $chk = $pdo->prepare('SELECT id FROM inventory_items WHERE id = ? AND store_id = ?');
            $chk->execute([$itemId, $storeId]);
            if (!$chk->fetch()) respond(['ok' => false, 'error' => 'Item not found for this store.'], 404);

            $stmt = $pdo->prepare('UPDATE inventory_items
                                    SET category=?, name=?, brand=?, price=?, stock=?, low_stock=?, specs=?,
                                        socket=?, ram_type=?
                                    WHERE id = ? AND store_id = ?');
            $stmt->execute([$category, $name, $brand, $price, $stock, $lowStock, $specs, $socket, $ramType, $itemId, $storeId]);
            if ($hasSata) {
                $pdo->prepare('UPDATE inventory_items SET sata_ports = ? WHERE id = ? AND store_id = ?')
                    ->execute([$sataPorts, $itemId, $storeId]);
            }
            if ($hasM2) {
                $pdo->prepare('UPDATE inventory_items SET m2_slots = ? WHERE id = ? AND store_id = ?')
                    ->execute([$m2Slots, $itemId, $storeId]);
            }
            if ($hasRamSlots) {
                $pdo->prepare('UPDATE inventory_items SET ram_slots = ? WHERE id = ? AND store_id = ?')
                    ->execute([$ramSlots, $itemId, $storeId]);
            }
            logActivity($pdo, 'inventory_updated', 'store', $storeId, $storeId, 'Updated inventory item: ' . $name);
            respond(['ok' => true, 'id' => $itemId]);
        } else {
            $newId = $category . '_' . time() . '_' . bin2hex(random_bytes(3));
            $stmt = $pdo->prepare('INSERT INTO inventory_items
                                    (id, store_id, category, name, brand, price, stock, low_stock, specs, socket, ram_type)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$newId, $storeId, $category, $name, $brand, $price, $stock, $lowStock, $specs, $socket, $ramType]);
            if ($hasSata && $sataPorts !== null) {
                $pdo->prepare('UPDATE inventory_items SET sata_ports = ? WHERE id = ?')->execute([$sataPorts, $newId]);
            }
            if ($hasM2 && $m2Slots !== null) {
                $pdo->prepare('UPDATE inventory_items SET m2_slots = ? WHERE id = ?')->execute([$m2Slots, $newId]);
            }
            if ($hasRamSlots && $ramSlots !== null) {
                $pdo->prepare('UPDATE inventory_items SET ram_slots = ? WHERE id = ?')->execute([$ramSlots, $newId]);
            }
            logActivity($pdo, 'inventory_added', 'store', $storeId, $storeId, 'Added inventory item: ' . $name);
            respond(['ok' => true, 'id' => $newId]);
        }
        break;

    case 'delete_item':
        $storeId = strtolower(trim($input['store_id'] ?? ''));
        $password = (string)($input['password'] ?? '');
        if (!verifyStoreAuth($pdo, $storeId, $password)) {
            respond(['ok' => false, 'error' => 'Store authentication failed.'], 401);
        }
        $itemId = trim($input['item_id'] ?? '');
        if (!$itemId) respond(['ok' => false, 'error' => 'Missing item_id'], 400);

        // Remove the item's photo file too, so it doesn't linger on the server.
        $oldImage = null;
        if (itemImageColumnReady($pdo)) {
            $img = $pdo->prepare('SELECT image FROM inventory_items WHERE id = ? AND store_id = ?');
            $img->execute([$itemId, $storeId]);
            $oldImage = $img->fetchColumn() ?: null;
        }

        $stmt = $pdo->prepare('DELETE FROM inventory_items WHERE id = ? AND store_id = ?');
        $stmt->execute([$itemId, $storeId]);
        itemImageDelete($oldImage);
        logActivity($pdo, 'inventory_deleted', 'store', $storeId, $storeId, 'Deleted inventory item: ' . $itemId);
        respond(['ok' => true]);
        break;

    // Sets or replaces an item's photo (multipart upload), or removes it when
    // "remove" = 1. Same store-password check as save_item.
    case 'upload_item_image':
        $storeId = strtolower(trim($input['store_id'] ?? ''));
        $password = (string)($input['password'] ?? '');
        if (!verifyStoreAuth($pdo, $storeId, $password)) {
            respond(['ok' => false, 'error' => 'Store authentication failed.'], 401);
        }
        $itemId = trim($input['item_id'] ?? '');
        if (!$itemId) respond(['ok' => false, 'error' => 'Missing item_id'], 400);
        if (!itemImageColumnReady($pdo)) {
            respond(['ok' => false, 'error' => 'The database could not be prepared for item photos. Run this in phpMyAdmin: ALTER TABLE inventory_items ADD COLUMN image VARCHAR(255) NULL;'], 500);
        }

        $cur = $pdo->prepare('SELECT image FROM inventory_items WHERE id = ? AND store_id = ?');
        $cur->execute([$itemId, $storeId]);
        $row = $cur->fetch();
        if (!$row) respond(['ok' => false, 'error' => 'Item not found for this store.'], 404);
        $oldImage = $row['image'];
        $setImage = $pdo->prepare('UPDATE inventory_items SET image = ? WHERE id = ? AND store_id = ?');

        if (!empty($input['remove'])) {
            $setImage->execute([null, $itemId, $storeId]);
            itemImageDelete($oldImage);
            respond(['ok' => true, 'image' => null]);
        }

        $file = $_FILES['image'] ?? null;
        if (!$file || !isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            respond(['ok' => false, 'error' => 'No photo was received.'], 400);
        }
        if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE || $file['size'] > ITEM_IMAGE_MAX_BYTES) {
            respond(['ok' => false, 'error' => 'Photo is too large (max 2 MB).'], 400);
        }
        if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
            respond(['ok' => false, 'error' => 'Photo upload failed. Please try again.'], 400);
        }
        // Real type from the file contents — never the browser's name or MIME type.
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $info = @getimagesize($file['tmp_name']);
        $mime = $info['mime'] ?? '';
        if (!isset($allowed[$mime])) {
            respond(['ok' => false, 'error' => 'Photo must be a JPG, PNG, or WEBP image.'], 400);
        }
        if (!itemImageEnsureDir()) {
            respond(['ok' => false, 'error' => 'Server could not create the photo folder.'], 500);
        }
        $safeId = preg_replace('/[^A-Za-z0-9_\-]/', '', $itemId) ?: 'item';
        $fileName = $safeId . '_' . bin2hex(random_bytes(6)) . '.' . $allowed[$mime];
        if (!move_uploaded_file($file['tmp_name'], ITEM_IMAGE_DIR . '/' . $fileName)) {
            respond(['ok' => false, 'error' => 'Server could not save the photo.'], 500);
        }
        $newPath = ITEM_IMAGE_URL . '/' . $fileName;
        try {
            $setImage->execute([$newPath, $itemId, $storeId]);
        } catch (Throwable $e) {
            @unlink(ITEM_IMAGE_DIR . '/' . $fileName);
            throw $e;
        }
        itemImageDelete($oldImage);
        respond(['ok' => true, 'image' => $newPath]);
        break;

    // Saves a custom build for a logged-in client so it can be seen from
    // any device. Matches this app's existing trust model (identified by
    // email, same as the rest of the client-side session).
    case 'save_build':
        // SECURITY: the owner comes from the login session, not from an email
        // sent by the browser (anyone could type someone else's email).
        if (empty($_SESSION['user_id'])) {
            respond(['ok' => false, 'error' => 'Please log in again to save builds to your account.'], 401);
        }
        $name = trim($input['name'] ?? 'My Build');
        $storeId = strtolower(trim($input['store_id'] ?? '')) ?: null;
        $totalPrice = (float)($input['total_price'] ?? 0);
        $items = is_array($input['items'] ?? null) ? $input['items'] : [];

        $userId = (int)$_SESSION['user_id'];

        // builds.store_id has a foreign key to stores. A build mixing parts from
        // several stores (the "All Stores" picker sends '__all__') or a store
        // that no longer exists must be saved with store_id = NULL, otherwise
        // the insert fails and the build is silently lost.
        if ($storeId !== null) {
            $chkStore = $pdo->prepare('SELECT store_id FROM stores WHERE store_id = ?');
            $chkStore->execute([$storeId]);
            if (!$chkStore->fetch()) $storeId = null;
        }

        $pdo->beginTransaction();
        try {
        $stmt = $pdo->prepare('INSERT INTO builds (user_id, store_id, name, total_price, is_custom) VALUES (?, ?, ?, ?, 1)');
        $stmt->execute([$userId, $storeId, $name, $totalPrice]);
        $buildId = (int)$pdo->lastInsertId();

        $validSlots = BUILD_SLOTS;
        buildSlotsReady($pdo); // widen the slot list for extra drives if this database is older
        $ins = $pdo->prepare('INSERT INTO build_items (build_id, slot, item_id, item_name, item_price) VALUES (?, ?, ?, ?, ?)');
        foreach ($items as $it) {
            $slot = $it['slot'] ?? '';
            if (!in_array($slot, $validSlots, true)) continue;
            $itemId = !empty($it['item_id']) ? $it['item_id'] : null;
            if ($itemId) {
                // build_items.item_id has a foreign key to inventory_items — if the
                // id doesn't exist there (e.g. a demo/local-only item), store NULL
                // instead so the insert doesn't fail; the name/price snapshot is
                // kept either way.
                $chk = $pdo->prepare('SELECT id FROM inventory_items WHERE id = ?');
                $chk->execute([$itemId]);
                if (!$chk->fetch()) $itemId = null;
            }
            $ins->execute([$buildId, $slot, $itemId, $it['item_name'] ?? '', (float)($it['item_price'] ?? 0)]);
        }
        $pdo->commit();
        logActivity($pdo, 'build_saved', 'client', $_SESSION['user_username'] ?? (string)$userId, $_SESSION['user_name'] ?? 'Client', 'Saved PC build: ' . $name);
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            // A 3rd/4th storage drive needs migration_v7 (adds storage3/storage4 slots).
            $msg = (stripos($e->getMessage(), 'slot') !== false || stripos($e->getMessage(), 'truncated') !== false)
                ? 'Could not save build: the database needs migration_v7_more_storage.sql for 3 or more storage drives.'
                : 'Could not save build.';
            respond(['ok' => false, 'error' => $msg], 500);
        }
        respond(['ok' => true, 'build_id' => $buildId]);
        break;

    case 'list_builds':
        // SECURITY: this used to trust a client-supplied "email" field with no
        // password/session check, so anyone who knew (or guessed) another
        // user's email could pull up their entire saved-builds list. Ownership
        // must be proven via the session set at login instead.
        $userId = $_SESSION['user_id'] ?? null;
        // ok:false (not ok:true with an empty list) so the front-end doesn't
        // wipe its locally-cached builds just because the session expired.
        if (!$userId) respond(['ok' => false, 'error' => 'Not authenticated', 'builds' => []]);

        $stmt = $pdo->prepare('SELECT b.id, b.name, b.total_price, b.is_custom, b.store_id, b.created_at, s.name AS store_name
                                FROM builds b LEFT JOIN stores s ON s.store_id = b.store_id
                                WHERE b.user_id = ? ORDER BY b.created_at DESC');
        $stmt->execute([$userId]);
        $builds = $stmt->fetchAll();

        $itemStmt = $pdo->prepare('SELECT bi.slot, bi.item_id, bi.item_name, bi.item_price, ii.store_id, s.name AS store_name
                                    FROM build_items bi
                                    LEFT JOIN inventory_items ii ON ii.id = bi.item_id
                                    LEFT JOIN stores s ON s.store_id = ii.store_id
                                    WHERE bi.build_id = ?');
        $result = [];
        foreach ($builds as $b) {
            $itemStmt->execute([$b['id']]);
            $components = [];
            foreach ($itemStmt->fetchAll() as $it) {
                $components[$it['slot']] = [
                    'id' => $it['item_id'], 'name' => $it['item_name'], 'price' => (float)$it['item_price'],
                    'storeId' => $it['store_id'], 'storeName' => $it['store_name'],
                    'isStoreItem' => $it['store_id'] !== null,
                ];
            }
            $result[] = [
                'id' => (int)$b['id'],
                'name' => $b['name'],
                'totalPrice' => (float)$b['total_price'],
                'isCustom' => (bool)$b['is_custom'],
                'storeId' => $b['store_id'],
                'storeName' => $b['store_name'] ?? (count(array_unique(array_filter(array_column($components, 'storeId')))) > 1 ? 'Multiple stores' : null),
                'savedAt' => $b['created_at'],
                'components' => $components,
            ];
        }
        respond(['ok' => true, 'builds' => $result]);
        break;

    case 'delete_build':
        // SECURITY: this used to trust a client-supplied "email" field with no
        // password/session check, so anyone who knew another user's email
        // could delete their saved builds. Ownership must be proven via the
        // session set at login instead.
        $userId = $_SESSION['user_id'] ?? null;
        $buildId = (int)($input['build_id'] ?? 0);
        if (!$userId) respond(['ok' => false, 'error' => 'You must be logged in to do that.'], 401);
        if (!$buildId) respond(['ok' => false, 'error' => 'Missing fields'], 400);

        $stmt = $pdo->prepare('SELECT b.id FROM builds b WHERE b.id = ? AND b.user_id = ?');
        $stmt->execute([$buildId, $userId]);
        if (!$stmt->fetch()) respond(['ok' => false, 'error' => 'Build not found for this account.'], 404);

        $del = $pdo->prepare('DELETE FROM builds WHERE id = ?');
        $del->execute([$buildId]);
        respond(['ok' => true]);
        break;

    // Updates display name / email. Requires the current password so only
    // the account owner can make this change.
    case 'update_profile':
        $email = trim($input['email'] ?? '');
        $password = (string)($input['password'] ?? '');
        $newName = trim($input['name'] ?? '');
        $newEmail = trim($input['new_email'] ?? '') ?: $email;

        $stmt = $pdo->prepare('SELECT id, name, password FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $u = $stmt->fetch();
        if (!$u || !verifyPassword($password, $u['password'])) {
            respond(['ok' => false, 'error' => 'Current password is incorrect.'], 401);
        }

        if ($newEmail !== $email) {
            $chk = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
            $chk->execute([$newEmail, $u['id']]);
            if ($chk->fetch()) respond(['ok' => false, 'error' => 'That email is already in use.'], 409);
        }

        $finalName = $newName !== '' ? $newName : $u['name'];
        $upd = $pdo->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
        $upd->execute([$finalName, $newEmail, $u['id']]);
        respond(['ok' => true, 'user' => ['name' => $finalName, 'email' => $newEmail]]);
        break;

    // Sends a reset link. Always responds ok:true even if the email/store
    // wasn't found — this avoids leaking which accounts exist.
    case 'request_password_reset':
        $type = ($input['type'] ?? 'user') === 'store' ? 'store' : 'user';
        $token = bin2hex(random_bytes(24));
        $devLink = null;

        // Limit reset emails (stops someone flooding an inbox). Same answer
        // either way, so this doesn't reveal whether the account exists.
        $resetIdent = $type . ':' . strtolower(trim($type === 'user' ? ($input['email'] ?? '') : ($input['store_id'] ?? '')));
        if (loginLockedMinutes($pdo, 'reset', $resetIdent)) {
            respond(['ok' => true, 'message' => 'If that account exists, a reset link has been sent.']);
        }
        recordFailedLogin($pdo, 'reset', $resetIdent);

        if ($type === 'user') {
            $email = trim($input['email'] ?? '');
            $stmt = $pdo->prepare('SELECT id, name FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $u = $stmt->fetch();
            if ($u) {
                $upd = $pdo->prepare('UPDATE users SET reset_token = ?, reset_expires = NOW() + INTERVAL 1 HOUR WHERE id = ?');
                $upd->execute([$token, $u['id']]);
                $link = baseUrl() . '/reset_password.php?token=' . $token . '&type=user';
                sendMail($email, 'Reset your CompuMatch password', '
                    <p>Hi ' . htmlspecialchars($u['name']) . ',</p>
                    <p>Click the link below to reset your password. This link expires in 1 hour.</p>
                    <p><a href="' . $link . '">' . $link . '</a></p>
                ');
                $devLink = $link;
            }
        } else {
            $storeId = strtolower(trim($input['store_id'] ?? ''));
            $stmt = $pdo->prepare('SELECT store_id, name, email FROM stores WHERE store_id = ?');
            $stmt->execute([$storeId]);
            $s = $stmt->fetch();
            if ($s && $s['email']) {
                $upd = $pdo->prepare('UPDATE stores SET reset_token = ?, reset_expires = NOW() + INTERVAL 1 HOUR WHERE store_id = ?');
                $upd->execute([$token, $s['store_id']]);
                $link = baseUrl() . '/reset_password.php?token=' . $token . '&type=store';
                sendMail($s['email'], 'Reset your CompuMatch store password', '
                    <p>Hi ' . htmlspecialchars($s['name']) . ',</p>
                    <p>Click the link below to reset your store password. This link expires in 1 hour.</p>
                    <p><a href="' . $link . '">' . $link . '</a></p>
                ');
                $devLink = $link;
            }
        }

        // SECURITY: this used to return the reset link/token directly in the
        // JSON response, which meant ANYONE could reset ANY account's password
        // just by knowing their email/store_id, with no access to their inbox
        // required. The link must only ever reach the account owner, via
        // email. For local dev without SMTP configured, it's written to the
        // PHP error log (server-side only) instead of being sent to the caller.
        if (DEV_MODE_SHOW_LINK && $devLink) error_log('[CompuMatch dev] reset link: ' . $devLink);
        respond(['ok' => true, 'message' => 'If that account exists, a reset link has been sent.']);
        break;

    case 'reset_password':
        $type = ($input['type'] ?? 'user') === 'store' ? 'store' : 'user';
        $token = trim($input['token'] ?? '');
        $password = (string)($input['password'] ?? '');
        $minLen = $type === 'store' ? 6 : 8;

        if (!$token || strlen($password) < $minLen) {
            respond(['ok' => false, 'error' => 'Missing or invalid fields'], 400);
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if ($type === 'user') {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()');
            $stmt->execute([$token]);
            $row = $stmt->fetch();
            if (!$row) respond(['ok' => false, 'error' => 'This reset link is invalid or has expired.'], 400);
            $upd = $pdo->prepare('UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?');
            $upd->execute([$hash, $row['id']]);
        } else {
            $stmt = $pdo->prepare('SELECT store_id FROM stores WHERE reset_token = ? AND reset_expires > NOW()');
            $stmt->execute([$token]);
            $row = $stmt->fetch();
            if (!$row) respond(['ok' => false, 'error' => 'This reset link is invalid or has expired.'], 400);
            $upd = $pdo->prepare('UPDATE stores SET password = ?, reset_token = NULL, reset_expires = NULL WHERE store_id = ?');
            $upd->execute([$hash, $row['store_id']]);
        }
        respond(['ok' => true]);
        break;

    // ---------- Site usage tracking (for System Admin analytics) ----------
    // Figures out "who" is pinging in, without requiring anyone to be
    // logged in: falls back to the anonymous PHP session id for guests,
    // uses the logged-in client's name if $_SESSION['user_id'] is set,
    // or the store_id the front-end already has cached in localStorage
    // when someone is browsing/managing a specific store.
    case 'track_visit':
    case 'heartbeat':
        $page = substr(trim((string)($input['page'] ?? 'unknown')), 0, 40);
        $sessionId = session_id();

        $userType = 'guest';
        $userRef = null;
        if (!empty($_SESSION['user_id'])) {
            $userType = 'user';
            $userRef = $_SESSION['user_name'] ?? $_SESSION['user_email'] ?? ('user #' . $_SESSION['user_id']);
        } elseif (!empty($input['store_id'])) {
            $userType = 'store';
            $userRef = substr(trim((string)$input['store_id']), 0, 160);
        }

        // Every page load logs one visit row (for total/unique-visitor counts).
        if ($action === 'track_visit') {
            $stmt = $pdo->prepare('INSERT INTO site_visits (session_id, page, user_type, user_ref) VALUES (?, ?, ?, ?)');
            $stmt->execute([$sessionId, $page, $userType, $userRef]);
        }

        // Both actions refresh the "currently online" heartbeat row.
        $stmt = $pdo->prepare('INSERT INTO active_sessions (session_id, page, user_type, user_ref, last_seen)
                                VALUES (?, ?, ?, ?, NOW())
                                ON DUPLICATE KEY UPDATE page = VALUES(page), user_type = VALUES(user_type),
                                                        user_ref = VALUES(user_ref), last_seen = NOW()');
        $stmt->execute([$sessionId, $page, $userType, $userRef]);

        respond(['ok' => true]);
        break;

    default:
        respond(['ok' => false, 'error' => 'Unknown action'], 400);
    }
} catch (Throwable $e) {
    // ⚠️ For a real public launch, replace $e->getMessage() below with a
    // generic message — showing raw DB errors is only meant to help you
    // debug locally (e.g. spotting "column not found" if migration_v2.sql
    // hasn't been imported yet).
    respond(['ok' => false, 'error' => 'Server error: ' . $e->getMessage()], 500);
}
