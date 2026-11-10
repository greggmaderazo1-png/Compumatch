<?php
// =============================================================
// Shared MySQL connection + page bootstrap for the .php pages.
//
// LIVE DEPLOYMENT: InfinityFree's own MySQL database (not Aiven).
// InfinityFree blocks outbound connections to external MySQL servers,
// so this uses the free MySQL database included with the hosting
// account instead — no SSL/ca.pem needed since it's on the same host.
//
// Credentials are NOT in this file any more — they're in config.php,
// which is private and blocked from the web by .htaccess.
// =============================================================
require_once __DIR__ . '/config.php';   // DB credentials live in config.php (private)
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');

// All display timestamps are shown in Philippine Time, regardless of what
// timezone the DB server or PHP server actually run in.
date_default_timezone_set('Asia/Manila');

// The DB server's NOW()/CURRENT_TIMESTAMP columns run on ITS OWN clock,
// which is often UTC (or some other region) on shared hosts like
// InfinityFree — not Asia/Manila. This finds out how far off the DB
// server's clock is from true UTC, once per request, so we can correct
// for it when displaying any stored timestamp.
function getDbUtcOffsetSeconds($pdo) {
    static $offset = null;
    if ($offset === null) {
        try {
            $row = $pdo->query('SELECT TIMESTAMPDIFF(SECOND, UTC_TIMESTAMP(), NOW()) AS diff')->fetch();
            $offset = (int)($row['diff'] ?? 0);
        } catch (Throwable $e) {
            $offset = 0;
        }
    }
    return $offset;
}

// Converts a raw DB timestamp string (e.g. from created_at / last_seen,
// as produced by NOW()/CURRENT_TIMESTAMP) into Philippine time for display.
function formatPHTime($pdo, $dbTimestamp) {
    if (!$dbTimestamp) return '';
    try {
        $offset = getDbUtcOffsetSeconds($pdo);
        $dt = new DateTime($dbTimestamp, new DateTimeZone('UTC'));
        $dt->modify(($offset >= 0 ? '-' : '+') . abs($offset) . ' seconds'); // now true UTC
        $dt->setTimezone(new DateTimeZone('Asia/Manila'));
        return $dt->format('M j, Y g:i A');
    } catch (Throwable $e) {
        return $dbTimestamp; // fall back to the raw value rather than erroring out
    }
}

function getPDO() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            // Give up after 5 s instead of hanging until the host shows a
            // 502 page; pages then fall back to their cached data.
            PDO::ATTR_TIMEOUT            => 5,
        ];
        // Aiven (and most managed MySQL hosts) require SSL. Only enabled
        // when DB_SSL_CA is set, so local XAMPP is unaffected.
        if (DB_SSL_CA !== '') {
            $options[PDO::MYSQL_ATTR_SSL_CA] = DB_SSL_CA;
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
        }
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }
    return $pdo;
}

// Item photos live in inventory_items.image (a path like
// "uploads/items/xxx.jpg"). This makes sure that column exists, adding it the
// first time it's needed, so no manual database import is required. Returns
// false if it's missing and couldn't be added — callers then simply carry on
// without photos instead of breaking the page.
function itemImageColumnReady($pdo) {
    static $ready = null;
    if ($ready === null) {
        try {
            $has = $pdo->query("SHOW COLUMNS FROM inventory_items LIKE 'image'")->fetch();
            if (!$has) {
                $pdo->exec('ALTER TABLE inventory_items ADD COLUMN image VARCHAR(255) NULL');
            }
            $ready = true;
        } catch (Throwable $e) {
            $ready = false;
        }
    }
    return $ready;
}

// =============================================================
// SECURITY HELPERS
// =============================================================

// Starts the PHP session with safer cookie settings: JavaScript can't read
// the cookie (HttpOnly), other sites can't send it along with forged
// requests (SameSite=Lax), and on HTTPS it's never sent over plain HTTP.
function startSecureSession() {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
          || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    @ini_set('session.use_strict_mode', '1');
    @ini_set('session.use_only_cookies', '1');
    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'secure' => $https, 'httponly' => true, 'samesite' => 'Lax']);
    } else {
        session_set_cookie_params(0, '/; samesite=Lax', '', $https, true);
    }
    session_start();
}

// New session ID after logging in, so a session ID someone planted or
// saw before login is useless afterwards.
function renewSessionAfterLogin() {
    if (session_status() === PHP_SESSION_ACTIVE) session_regenerate_id(true);
}

function clientIp() {
    return substr((string)($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45);
}

// ---- System activity monitoring ----
// The log is written by the same API/database used by CompuMatch. Logging
// failures never interrupt the user's normal action.
function activityLogReady($pdo) {
    static $ready = null;
    if ($ready === null) {
        try {
            $pdo->exec("CREATE TABLE IF NOT EXISTS activity_logs (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                event_type VARCHAR(60) NOT NULL,
                actor_type VARCHAR(30) NOT NULL DEFAULT 'guest',
                actor_id VARCHAR(120) NULL,
                actor_name VARCHAR(160) NULL,
                details VARCHAR(500) NULL,
                source_ip VARCHAR(45) NULL,
                user_agent VARCHAR(255) NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_activity_created (created_at),
                INDEX idx_activity_type (event_type)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $ready = true;
        } catch (Throwable $e) { $ready = false; }
    }
    return $ready;
}

function logActivity($pdo, $eventType, $actorType = 'guest', $actorId = null, $actorName = null, $details = '') {
    if (!$pdo || !activityLogReady($pdo)) return;
    try {
        $ua = substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255);
        $pdo->prepare('INSERT INTO activity_logs (event_type, actor_type, actor_id, actor_name, details, source_ip, user_agent, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())')
            ->execute([substr($eventType,0,60), substr($actorType,0,30), $actorId !== null ? substr((string)$actorId,0,120) : null, $actorName !== null ? substr((string)$actorName,0,160) : null, substr((string)$details,0,500), clientIp(), $ua]);
    } catch (Throwable $e) { /* Monitoring must not break CompuMatch. */ }
}

// ---- Login attempt limit ----
// After LOGIN_MAX_FAILS wrong passwords for the same account (or from the
// same IP) within LOGIN_LOCK_MINUTES, further tries are refused until the
// window passes. Uses a small table that's created automatically.
define('LOGIN_MAX_FAILS', 5);
define('LOGIN_MAX_FAILS_PER_IP', 20);
define('LOGIN_LOCK_MINUTES', 15);

function loginAttemptsReady($pdo) {
    static $ready = null;
    if ($ready === null) {
        try {
            $pdo->exec("CREATE TABLE IF NOT EXISTS login_attempts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                scope VARCHAR(20) NOT NULL,
                ident VARCHAR(160) NOT NULL,
                ip VARCHAR(45) NOT NULL,
                attempted_at DATETIME NOT NULL,
                INDEX idx_scope_ident (scope, ident, attempted_at),
                INDEX idx_ip (ip, attempted_at)
            ) ENGINE=InnoDB");
            $ready = true;
        } catch (Throwable $e) {
            $ready = false;   // can't track -> don't block logins
        }
    }
    return $ready;
}

// Returns how many minutes are left on a lockout, or 0 if allowed to try.
function loginLockedMinutes($pdo, $scope, $ident) {
    if (!loginAttemptsReady($pdo)) return 0;
    try {
        $win = (int)LOGIN_LOCK_MINUTES;
        // failures for this account in the window, and seconds since the oldest one
        $q = $pdo->prepare("SELECT COUNT(*) AS n, TIMESTAMPDIFF(SECOND, MIN(attempted_at), NOW()) AS age
                            FROM login_attempts
                            WHERE scope = ? AND ident = ? AND attempted_at > DATE_SUB(NOW(), INTERVAL $win MINUTE)");
        $q->execute([$scope, strtolower($ident)]);
        $acc = $q->fetch();
        // failures from this IP address in the window (any account)
        $q2 = $pdo->prepare("SELECT COUNT(*) AS n, TIMESTAMPDIFF(SECOND, MIN(attempted_at), NOW()) AS age
                             FROM login_attempts
                             WHERE ip = ? AND attempted_at > DATE_SUB(NOW(), INTERVAL $win MINUTE)");
        $q2->execute([clientIp()]);
        $ip = $q2->fetch();
        $age = null;
        if ((int)$acc['n'] >= LOGIN_MAX_FAILS) $age = (int)$acc['age'];
        elseif ((int)$ip['n'] >= LOGIN_MAX_FAILS_PER_IP) $age = (int)$ip['age'];
        if ($age !== null) return max(1, (int)ceil(($win * 60 - $age) / 60));
    } catch (Throwable $e) { }
    return 0;
}

function recordFailedLogin($pdo, $scope, $ident) {
    if (!loginAttemptsReady($pdo)) return;
    try {
        $pdo->prepare('INSERT INTO login_attempts (scope, ident, ip, attempted_at) VALUES (?, ?, ?, NOW())')
            ->execute([$scope, strtolower($ident), clientIp()]);
        // keep the table small
        if (mt_rand(1, 50) === 1) $pdo->exec('DELETE FROM login_attempts WHERE attempted_at < (NOW() - INTERVAL 1 DAY)');
    } catch (Throwable $e) { }
}

function clearFailedLogins($pdo, $scope, $ident) {
    if (!loginAttemptsReady($pdo)) return;
    try {
        $pdo->prepare('DELETE FROM login_attempts WHERE scope = ? AND ident = ?')->execute([$scope, strtolower($ident)]);
    } catch (Throwable $e) { }
}

// ---- Passwords ----
// Accounts created before passwords were hashed (the demo/seed accounts)
// still have the plain password in the database. The first time such an
// account logs in successfully, its password is re-saved as a hash.
function upgradePasswordHash($pdo, $table, $keyCol, $keyVal, $stored, $plain) {
    $info = password_get_info((string)$stored);
    if (empty($info['algo']) || password_needs_rehash((string)$stored, PASSWORD_DEFAULT)) {
        if (!in_array($table, ['users', 'stores'], true) || !in_array($keyCol, ['id', 'store_id'], true)) return;
        try {
            $pdo->prepare("UPDATE $table SET password = ? WHERE $keyCol = ?")
                ->execute([password_hash($plain, PASSWORD_DEFAULT), $keyVal]);
        } catch (Throwable $e) { }
    }
}

// ---- System Admin password (hash kept in the database once changed) ----
function systemSettingsReady($pdo) {
    static $ready = null;
    if ($ready === null) {
        try {
            $pdo->exec("CREATE TABLE IF NOT EXISTS system_settings (
                name VARCHAR(60) PRIMARY KEY,
                value TEXT NOT NULL,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB");
            $ready = true;
        } catch (Throwable $e) {
            $ready = false;
        }
    }
    return $ready;
}

// Current admin password hash: the one saved from "Change Password", else
// the one in config.php.
function systemAdminPasswordHash($pdo) {
    if ($pdo && systemSettingsReady($pdo)) {
        try {
            $q = $pdo->prepare("SELECT value FROM system_settings WHERE name = 'admin_pass_hash'");
            $q->execute();
            $row = $q->fetch();
            if ($row && $row['value'] !== '') return $row['value'];
        } catch (Throwable $e) { }
    }
    return SYSTEMADMIN_PASS_HASH;
}

function saveSystemAdminPassword($pdo, $plain) {
    if (!systemSettingsReady($pdo)) return false;
    $pdo->prepare("REPLACE INTO system_settings (name, value) VALUES ('admin_pass_hash', ?)")
        ->execute([password_hash($plain, PASSWORD_DEFAULT)]);
    return true;
}

// Motherboards can record how many SATA ports they have
// (inventory_items.sata_ports). Added automatically the first time it's
// needed, like the image column above. Returns false if it couldn't be added.
function sataPortsColumnReady($pdo) {
    static $ready = null;
    if ($ready === null) {
        try {
            $has = $pdo->query("SHOW COLUMNS FROM inventory_items LIKE 'sata_ports'")->fetch();
            if (!$has) {
                $pdo->exec('ALTER TABLE inventory_items ADD COLUMN sata_ports TINYINT UNSIGNED NULL');
            }
            $ready = true;
        } catch (Throwable $e) {
            $ready = false;
        }
    }
    return $ready;
}

// Motherboards can also record how many M.2 slots they have
// (inventory_items.m2_slots). Added automatically the first time it's needed.
function m2SlotsColumnReady($pdo) {
    static $ready = null;
    if ($ready === null) {
        try {
            $has = $pdo->query("SHOW COLUMNS FROM inventory_items LIKE 'm2_slots'")->fetch();
            if (!$has) {
                $pdo->exec('ALTER TABLE inventory_items ADD COLUMN m2_slots TINYINT UNSIGNED NULL');
            }
            $ready = true;
        } catch (Throwable $e) {
            $ready = false;
        }
    }
    return $ready;
}

// Motherboards can also record how many RAM slots they have
// (inventory_items.ram_slots, 1-24). Added automatically when first needed.
function ramSlotsColumnReady($pdo) {
    static $ready = null;
    if ($ready === null) {
        try {
            $has = $pdo->query("SHOW COLUMNS FROM inventory_items LIKE 'ram_slots'")->fetch();
            if (!$has) {
                $pdo->exec('ALTER TABLE inventory_items ADD COLUMN ram_slots TINYINT UNSIGNED NULL');
            }
            $ready = true;
        } catch (Throwable $e) {
            $ready = false;
        }
    }
    return $ready;
}

// Saved builds can hold the main drive + up to 8 secondary drives
// (build_items.slot 'storage2'..'storage9') + up to 6 extra M.2 drives
// ('nvme2'..'nvme7') + up to 23 extra memory kits ('ram2'..'ram24').
// Widens the slot list the first
// time it's needed. Returns false if it couldn't be changed.
define('BUILD_SLOTS', ['cpu','gpu','motherboard','ram','storage','storage2','storage3','storage4','storage5','storage6','storage7','storage8','storage9','nvme2','nvme3','nvme4','nvme5','nvme6','nvme7','ram2','ram3','ram4','ram5','ram6','ram7','ram8','ram9','ram10','ram11','ram12','ram13','ram14','ram15','ram16','ram17','ram18','ram19','ram20','ram21','ram22','ram23','ram24','psu','case']);
function buildSlotsReady($pdo) {
    static $ready = null;
    if ($ready === null) {
        try {
            $col = $pdo->query("SHOW COLUMNS FROM build_items LIKE 'slot'")->fetch();
            if ($col && stripos((string)$col['Type'], "'ram24'") === false) {
                $list = "'" . implode("','", BUILD_SLOTS) . "'";
                $pdo->exec("ALTER TABLE build_items MODIFY slot ENUM($list) NOT NULL");
            }
            $ready = true;
        } catch (Throwable $e) {
            $ready = false;
        }
    }
    return $ready;
}

// The socket and ram_type columns were created as fixed lists (LGA1700, AM5,
// DDR4, …). Stores may type any socket or RAM type (LGA1851, AM3+, DDR3 …),
// so both are widened to plain
// text the first time it's needed. Existing values are kept as they are.
// Returns false if it couldn't be changed — the caller then says so.
function socketColumnFreeText($pdo) {
    static $ready = null;
    if ($ready === null) {
        try {
            foreach (['socket', 'ram_type'] as $name) {
                $col = $pdo->query("SHOW COLUMNS FROM inventory_items LIKE '$name'")->fetch();
                if ($col && stripos((string)$col['Type'], 'enum') === 0) {
                    $pdo->exec("ALTER TABLE inventory_items MODIFY $name VARCHAR(40) NULL");
                }
            }
            $ready = true;
        } catch (Throwable $e) {
            $ready = false;
        }
    }
    return $ready;
}

// Returns the stores+inventory in the exact shape the front-end expects,
// or null if the database can't be reached.
function bootstrapStores() {
    try {
        $pdo = getPDO();
        $stores = $pdo->query('SELECT * FROM stores ORDER BY name')->fetchAll();
        $imageCol = itemImageColumnReady($pdo) ? ', image' : '';
        $imageCol .= sataPortsColumnReady($pdo) ? ', sata_ports' : '';
        $imageCol .= m2SlotsColumnReady($pdo) ? ', m2_slots' : '';
        $imageCol .= ramSlotsColumnReady($pdo) ? ', ram_slots' : '';
        $items  = $pdo->prepare('SELECT id, category, name, brand, price, stock,
                                        low_stock AS lowStock, specs, sold,
                                        socket, ram_type, form_factor, storage_type' . $imageCol . '
                                 FROM inventory_items WHERE store_id = ? ORDER BY category, name');
        $out = [];
        foreach ($stores as $s) {
            $items->execute([$s['store_id']]);
            $inv = array_map(function ($it) {
                $it['price'] = (float)$it['price'];
                $it['stock'] = (int)$it['stock'];
                $it['lowStock'] = (int)$it['lowStock'];
                $it['sold'] = (int)$it['sold'];
                $it['sata_ports'] = isset($it['sata_ports']) && $it['sata_ports'] !== null ? (int)$it['sata_ports'] : null;
                $it['m2_slots'] = isset($it['m2_slots']) && $it['m2_slots'] !== null ? (int)$it['m2_slots'] : null;
                $it['ram_slots'] = isset($it['ram_slots']) && $it['ram_slots'] !== null ? (int)$it['ram_slots'] : null;
                return $it;
            }, $items->fetchAll());
            $out[$s['store_id']] = [
                'id'          => $s['store_id'],
                'name'        => $s['name'],
                'address'     => $s['address'],
                'latitude'    => isset($s['latitude'])  && $s['latitude']  !== null ? (float)$s['latitude']  : null,
                'longitude'   => isset($s['longitude']) && $s['longitude'] !== null ? (float)$s['longitude'] : null,
                // Customers only see stores whose business permit was approved.
                // (The permit file itself is NEVER sent to the browser.)
                'verified'    => ($s['permit_status'] ?? 'approved') === 'approved',
                'contact'     => $s['contact'],
                'email'       => $s['email'],
                'description' => $s['description'],
                'inventory'   => $inv,
            ];
        }
        return $out;
    } catch (Throwable $e) {
        return null; // DB offline -> pages fall back to localStorage
    }
}


// Base URL for email links
if (!function_exists('baseUrl')) {
    function baseUrl() {
        return 'https://compumatch.infinityfreeapp.com';
    }
}

// Development mode constant
if (!defined('DEV_MODE_SHOW_LINK')) {
    define('DEV_MODE_SHOW_LINK', false);
}