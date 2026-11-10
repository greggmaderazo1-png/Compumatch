<?php
// =============================================================
// CompuMatch — System Admin Panel
// Lets YOU (the site owner) see every client account and every
// store account that has registered, straight from MySQL.
//
// Login: username SYSTEMADMIN_USER and a password checked against a
// HASH (config.php, or the newer one saved with "Change Password").
// No plain-text password is stored anywhere. Wrong passwords are
// limited (see loginLockedMinutes in db.php).
// =============================================================

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/permit_check.php';
startSecureSession();

// SECURITY: the delete-user/delete-store forms below used to be plain POSTs
// authenticated only by the session cookie, with no CSRF token — a page
// visited by a logged-in admin could silently submit those forms on the
// admin's behalf. Every state-changing POST now must carry this token.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

function csrfOk() {
    return isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $u = (string)($_POST['username'] ?? '');
        $p = (string)($_POST['password'] ?? '');
        $pdoLogin = null;
        try { $pdoLogin = getPDO(); } catch (Throwable $e) { }
        $locked = $pdoLogin ? loginLockedMinutes($pdoLogin, 'sysadmin', 'admin') : 0;
        if ($locked) {
            $_SESSION['systemadmin_error'] = "Too many failed attempts. Please wait $locked minute" . ($locked > 1 ? 's' : '') . ' and try again.';
        } elseif (hash_equals(SYSTEMADMIN_USER, $u) && password_verify($p, systemAdminPasswordHash($pdoLogin))) {
            if ($pdoLogin) clearFailedLogins($pdoLogin, 'sysadmin', 'admin');
            renewSessionAfterLogin();
            $_SESSION['systemadmin'] = true;
            $_SESSION['systemadmin_user'] = SYSTEMADMIN_USER;
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            logActivity($pdoLogin, 'successful_login', 'system_admin', SYSTEMADMIN_USER, 'System Admin', 'System administrator signed in');
        } else {
            if ($pdoLogin) { recordFailedLogin($pdoLogin, 'sysadmin', 'admin'); logActivity($pdoLogin, 'failed_login', 'system_admin', 'admin', 'System Admin', 'Invalid administrator credentials'); }
            $_SESSION['systemadmin_error'] = 'Invalid username or password.';
        }
        header('Location: systemadmin.php');
        exit;
    }
    if (isset($_POST['logout']) && csrfOk()) {
        unset($_SESSION['systemadmin'], $_SESSION['systemadmin_user']);
        session_regenerate_id(true);
        header('Location: landing.php');
        exit;
    }
    // Change the System Admin password (needs the current one).
    if (isset($_POST['change_admin_password']) && !empty($_SESSION['systemadmin']) && csrfOk()) {
        $cur = (string)($_POST['current_password'] ?? '');
        $new = (string)($_POST['new_password'] ?? '');
        $confirm = (string)($_POST['confirm_password'] ?? '');
        try {
            $pdoPw = getPDO();
            if (!password_verify($cur, systemAdminPasswordHash($pdoPw))) {
                $_SESSION['systemadmin_action_error'] = 'Current password is incorrect.';
            } elseif (strlen($new) < 10 || !preg_match('/[A-Za-z]/', $new) || !preg_match('/\d/', $new)) {
                $_SESSION['systemadmin_action_error'] = 'New password must be at least 10 characters and include letters and numbers.';
            } elseif ($new !== $confirm) {
                $_SESSION['systemadmin_action_error'] = 'New passwords do not match.';
            } elseif (!saveSystemAdminPassword($pdoPw, $new)) {
                $_SESSION['systemadmin_action_error'] = 'Could not save the new password.';
            } else {
                $_SESSION['systemadmin_notice'] = 'Admin password changed.';
            }
        } catch (Throwable $e) {
            $_SESSION['systemadmin_action_error'] = 'Could not save the new password.';
        }
        header('Location: systemadmin.php');
        exit;
    }
    if (!empty($_SESSION['systemadmin']) && csrfOk()) {
        $actionError = '';
        try {
            $pdo = getPDO();
            if (isset($_POST['delete_user'])) {
                $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
                $stmt->execute([$_POST['delete_user']]);
            }
            if (isset($_POST['delete_store'])) {
                // Remove the store's permit image too, so it doesn't linger on the server.
                $pf = $pdo->prepare('SELECT permit_image FROM stores WHERE store_id = ?');
                $pf->execute([$_POST['delete_store']]);
                $pfRow = $pf->fetch();
                $stmt = $pdo->prepare('DELETE FROM stores WHERE store_id = ?');
                $stmt->execute([$_POST['delete_store']]);
                if ($pfRow && $pfRow['permit_image']) {
                    @unlink(__DIR__ . '/uploads/permits/' . basename($pfRow['permit_image']));
                    @unlink(__DIR__ . '/uploads/permits/' . basename($pfRow['permit_image']) . '.sig');
                    @unlink(__DIR__ . '/uploads/permits/' . basename($pfRow['permit_image']) . '.txt');
                }
            }

            // --- Super-admin: edit account info ---
            if (isset($_POST['edit_user'])) {
                $id = (int)$_POST['edit_user'];
                $name = trim($_POST['edit_user_name'] ?? '');
                $username = strtolower(trim($_POST['edit_user_username'] ?? ''));
                $email = trim($_POST['edit_user_email'] ?? '');
                if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $actionError = 'Please provide a valid name and email.';
                } elseif (!preg_match('/^[a-z0-9_]{3,30}$/', $username)) {
                    $actionError = 'Username must be 3–30 characters: lowercase letters, numbers, or underscore.';
                } else {
                    $chk = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
                    $chk->execute([$email, $id]);
                    $chkU = $pdo->prepare('SELECT id FROM users WHERE username = ? AND id != ?');
                    $chkU->execute([$username, $id]);
                    if ($chk->fetch()) {
                        $actionError = 'That email is already used by another account.';
                    } elseif ($chkU->fetch()) {
                        $actionError = 'That username is already used by another account.';
                    } else {
                        $stmt = $pdo->prepare('UPDATE users SET name = ?, username = ?, email = ? WHERE id = ?');
                        $stmt->execute([$name, $username, $email, $id]);
                        $_SESSION['systemadmin_notice'] = 'Client account updated.';
                    }
                }
            }

            if (isset($_POST['edit_store'])) {
                $storeId = $_POST['edit_store'];
                $name = trim($_POST['edit_store_name'] ?? '');
                $address = trim($_POST['edit_store_address'] ?? '');
                $contact = trim($_POST['edit_store_contact'] ?? '');
                $email = trim($_POST['edit_store_email'] ?? '');
                $description = trim($_POST['edit_store_description'] ?? '');
                if (!$name) {
                    $actionError = 'Store name is required.';
                } else {
                    $stmt = $pdo->prepare('UPDATE stores SET name = ?, address = ?, contact = ?, email = ?, description = ? WHERE store_id = ?');
                    $stmt->execute([$name, $address, $contact, $email, $description, $storeId]);
                    $_SESSION['systemadmin_notice'] = 'Store account updated.';
                }
            }

            // --- Super-admin: approve / reject a store's business permit ---

            // --- Super-admin: approve / reject a store's business permit WITH EMAIL NOTIFICATIONS ---
            if (isset($_POST['set_permit_status'])) {
                $storeId = trim($_POST['set_permit_status'] ?? '');
                $status = trim($_POST['permit_status'] ?? '');
                $rejectionReason = trim($_POST['rejection_reason'] ?? '');
                
                if (!$storeId || !in_array($status, ['approved', 'rejected', 'pending'], true)) {
                    $_SESSION['systemadmin_action_error'] = 'Invalid store ID or status.';
                } else {
                    try {
                        $pdo->beginTransaction();
                        
                        // 1. Get store info for email notification
                        $storeStmt = $pdo->prepare('SELECT name, email, store_id, permit_status FROM stores WHERE store_id = ?');
                        $storeStmt->execute([$storeId]);
                        $storeRow = $storeStmt->fetch(PDO::FETCH_ASSOC);
                        
                        if (!$storeRow) {
                            throw new Exception('Store not found');
                        }
                        
                        $storeName = $storeRow['name'];
                        $storeEmail = $storeRow['email'];
                        $storeIdDisplay = $storeRow['store_id'];
                        
                        // 2. Update permit status
                        $updateStmt = $pdo->prepare(
                            'UPDATE stores SET permit_status = ?, permit_reviewed_by = ?, permit_reviewed_at = NOW() WHERE store_id = ?'
                        );
                        $updateStmt->execute([$status, $_SESSION['systemadmin_user'] ?? 'admin', $storeId]);
                        
                        // 3. Log to audit trail
                        $auditStmt = $pdo->prepare(
                            'INSERT INTO permit_audit_log (store_id, old_status, new_status, changed_by, change_reason) 
                             VALUES (?, ?, ?, ?, ?)'
                        );
                        $auditStmt->execute([
                            $storeId,
                            $storeRow['permit_status'] ?: 'pending',
                            $status,
                            $_SESSION['systemadmin_user'] ?? 'admin',
                            ($status === 'rejected' && $rejectionReason) ? $rejectionReason : null
                        ]);
                        
                        // 4. Send email notification
                        if ($status === 'approved' && filter_var($storeEmail, FILTER_VALIDATE_EMAIL)) {
                            require_once __DIR__ . '/mailer.php';
                            $emailSent = sendStoreApprovalEmail($storeName, $storeEmail, $storeIdDisplay);
                            
                            $notifStmt = $pdo->prepare('INSERT INTO store_notifications (store_id, notification_type) VALUES (?, ?)');
                            $notifStmt->execute([$storeId, 'approved']);
                            
                            $_SESSION['systemadmin_notice'] = 
                                '✅ Store APPROVED! Email sent to ' . $storeEmail . '. Store is now live.';
                        } 
                        elseif ($status === 'rejected' && filter_var($storeEmail, FILTER_VALIDATE_EMAIL)) {
                            require_once __DIR__ . '/mailer.php';
                            $emailSent = sendStoreRejectionEmail($storeName, $storeEmail, $storeIdDisplay, $rejectionReason);
                            
                            $notifStmt = $pdo->prepare('INSERT INTO store_notifications (store_id, notification_type) VALUES (?, ?)');
                            $notifStmt->execute([$storeId, 'rejected']);
                            
                            $_SESSION['systemadmin_notice'] = 
                                '❌ Store REJECTED. Rejection email sent to ' . $storeEmail . '.';
                        }
                        else {
                            $_SESSION['systemadmin_notice'] = '⏳ Permit status updated to ' . $status . '.';
                        }
                        
                        $pdo->commit();
                        
                    } catch (Throwable $e) {
                        if ($pdo->inTransaction()) {
                            $pdo->rollBack();
                        }
                        $msg = $e->getMessage();
                        if (stripos($msg, 'permit_audit_log') !== false || stripos($msg, 'store_notifications') !== false) {
                            $msg = 'Approve/Reject needs migration_v6_store_notifications.sql — import it in phpMyAdmin first. (' . $msg . ')';
                        }
                        $_SESSION['systemadmin_action_error'] = 'Error: ' . $msg;
                        error_log('Permit update error: ' . $e->getMessage());
                    }
                }
            }

            // --- Super-admin: reset a client's or store's password directly ---
            if (isset($_POST['reset_user_password'])) {
                $id = (int)$_POST['reset_user_password'];
                $newPass = (string)($_POST['new_password'] ?? '');
                if (strlen($newPass) < 8) {
                    $actionError = 'New password must be at least 8 characters.';
                } else {
                    $hash = password_hash($newPass, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare('UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?');
                    $stmt->execute([$hash, $id]);
                    $_SESSION['systemadmin_notice'] = 'Client password reset.';
                }
            }

            if (isset($_POST['reset_store_password'])) {
                $storeId = $_POST['reset_store_password'];
                $newPass = (string)($_POST['new_password'] ?? '');
                if (strlen($newPass) < 6) {
                    $actionError = 'New password must be at least 6 characters.';
                } else {
                    $hash = password_hash($newPass, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare('UPDATE stores SET password = ?, reset_token = NULL, reset_expires = NULL WHERE store_id = ?');
                    $stmt->execute([$hash, $storeId]);
                    $_SESSION['systemadmin_notice'] = 'Store password reset.';
                }
            }

            // Re-run the auto-check on every pending store.
            if (isset($_POST['recheck_pending'])) {
                $rows = $pdo->query("SELECT store_id, permit_image FROM stores WHERE permit_status = 'pending' AND permit_image IS NOT NULL")->fetchAll();
                $approvedCount = 0;
                $upd = $pdo->prepare('UPDATE stores SET permit_status = ?, permit_ai_notes = ?, permit_reviewed_by = ?,
                                             permit_reviewed_at = CASE WHEN ? = \'approved\' THEN NOW() ELSE permit_reviewed_at END
                                      WHERE store_id = ?');
                foreach ($rows as $r) {
                    $path = PERMIT_DIR . '/' . basename($r['permit_image']);
                    if (!is_file($path)) continue;
                    $c = permitAutoCheck($pdo, $path, $r['store_id']);
                    $upd->execute([$c['status'], $c['notes'], $c['by'], $c['status'], $r['store_id']]);
                    if ($c['status'] === 'approved') $approvedCount++;
                }
                $_SESSION['systemadmin_notice'] = 'Re-checked ' . count($rows) . ' pending store(s); ' . $approvedCount . ' auto-approved.';
            }

            if ($actionError) $_SESSION['systemadmin_action_error'] = $actionError;
        } catch (Throwable $e) { /* surfaced below as $dbError */ }
        header('Location: systemadmin.php');
        exit;
    }
}

$loggedIn = !empty($_SESSION['systemadmin']);
$loginError = $_SESSION['systemadmin_error'] ?? '';
unset($_SESSION['systemadmin_error']);
$notice = $_SESSION['systemadmin_notice'] ?? '';
unset($_SESSION['systemadmin_notice']);
$actionError = $_SESSION['systemadmin_action_error'] ?? '';
unset($_SESSION['systemadmin_action_error']);

$users = []; $stores = []; $inventoryByStore = []; $builds = []; $buildsByClient = []; $rejectReasons = []; $analytics = null; $dbError = '';
if ($loggedIn) {
    try {
        $pdo = getPDO();
        $users  = $pdo->query('SELECT id, name, username, email, created_at FROM users ORDER BY created_at DESC')->fetchAll();
        $stores = $pdo->query('SELECT store_id, name, email, contact, address, latitude, longitude, description, permit_image, permit_status, permit_ai_notes, permit_reviewed_by, permit_reviewed_at, created_at FROM stores ORDER BY (permit_status = \'pending\') DESC, created_at DESC')->fetchAll();

        // --- Inventory, grouped by store (view-only: editing stays with the store owner) ---
        $invRows = $pdo->query('SELECT ii.*, s.name AS store_name
                                 FROM inventory_items ii
                                 JOIN stores s ON s.store_id = ii.store_id
                                 ORDER BY s.name, ii.category, ii.name')->fetchAll();
        foreach ($invRows as $row) {
            $inventoryByStore[$row['store_id']]['store_name'] = $row['store_name'];
            $inventoryByStore[$row['store_id']]['items'][] = $row;
        }

        // --- Saved client builds (view-only) ---
        $builds = $pdo->query("SELECT b.id, b.user_id, b.name, b.total_price, b.created_at,
                                       u.name AS user_name, u.email AS user_email,
                                       s.name AS store_name
                                FROM builds b
                                LEFT JOIN users u ON u.id = b.user_id
                                LEFT JOIN stores s ON s.store_id = b.store_id
                                ORDER BY b.created_at DESC")->fetchAll();
        if ($builds) {
            $ids = array_column($builds, 'id');
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $biStmt = $pdo->prepare("SELECT build_id, slot, item_name, item_price
                                      FROM build_items WHERE build_id IN ($placeholders)
                                      ORDER BY FIELD(slot,'cpu','motherboard','ram','ram2','ram3','ram4','ram5','ram6','ram7','ram8','ram9','ram10','ram11','ram12','ram13','ram14','ram15','ram16','ram17','ram18','ram19','ram20','ram21','ram22','ram23','ram24','storage','storage2','storage3','storage4','storage5','storage6','storage7','storage8','storage9','nvme2','nvme3','nvme4','nvme5','nvme6','nvme7','gpu','psu','case')");
            $biStmt->execute($ids);
            $itemsByBuild = [];
            foreach ($biStmt->fetchAll() as $bi) {
                $itemsByBuild[$bi['build_id']][] = $bi;
            }
            foreach ($builds as &$b) {
                $b['items'] = $itemsByBuild[$b['id']] ?? [];
            }
            unset($b);
        }

        // Latest rejection reason per store (from the v6 audit log; optional)
        $rejectReasons = [];
        try {
            foreach ($pdo->query("SELECT store_id, change_reason FROM permit_audit_log
                                  WHERE new_status = 'rejected' AND change_reason IS NOT NULL AND change_reason <> ''
                                  ORDER BY changed_at ASC, id ASC") as $r) {
                $rejectReasons[$r['store_id']] = $r['change_reason'];
            }
        } catch (Throwable $e) { /* audit table not imported yet */ }

        // Group builds by client so the admin sees one row per client.
        $buildsByClient = [];
        foreach ($builds as $b) {
            $key = $b['user_id'] !== null ? 'u' . $b['user_id'] : 'guest';
            if (!isset($buildsByClient[$key])) {
                $buildsByClient[$key] = [
                    'name'   => $b['user_name'] ?? 'Guest',
                    'email'  => $b['user_email'] ?? '',
                    'builds' => [],
                ];
            }
            $buildsByClient[$key]['builds'][] = $b;
        }
        uasort($buildsByClient, function ($a, $c) { return strcasecmp($a['name'], $c['name']); });

    } catch (Throwable $e) {
        // Show the real reason (this page is admin-only). The most common one
        // is a missing column because a migration hasn't been imported yet.
        $msg = $e->getMessage();
        if (stripos($msg, 'Unknown column') !== false) {
            $dbError = 'The database is missing a column (' . $msg . '). Import the latest migration file in phpMyAdmin — your data is still there.';
        } else {
            $dbError = 'Database error: ' . $msg;
        }
    }

    // --- Site usage analytics (visits + who's online right now) ---
    // Kept in its own try/catch: this only works after migration_v3 has
    // been imported, and shouldn't block the Accounts/Inventory/Builds
    // tabs above if it hasn't been run yet.
    $analyticsError = '';
    try {
        $pdo = getPDO();
        // Housekeeping: drop heartbeats that have clearly gone stale so the
        // "online now" table doesn't grow forever.
        $pdo->exec('DELETE FROM active_sessions WHERE last_seen < (NOW() - INTERVAL 1 DAY)');

        $totalVisits  = (int)$pdo->query('SELECT COUNT(*) c FROM site_visits')->fetch()['c'];
        $uniqueVisits = (int)$pdo->query('SELECT COUNT(DISTINCT session_id) c FROM site_visits')->fetch()['c'];
        $visitsToday  = (int)$pdo->query('SELECT COUNT(*) c FROM site_visits WHERE DATE(visited_at) = CURDATE()')->fetch()['c'];
        $onlineNow    = $pdo->query("SELECT session_id, page, user_type, user_ref, last_seen
                                      FROM active_sessions
                                      WHERE last_seen >= (NOW() - INTERVAL 5 MINUTE)
                                      ORDER BY last_seen DESC")->fetchAll();
        $analytics = [
            'total_visits'  => $totalVisits,
            'unique_visits' => $uniqueVisits,
            'visits_today'  => $visitsToday,
            'online_now'    => $onlineNow,
        ];
    } catch (Throwable $e) {
        $analyticsError = 'Usage analytics aren\'t available yet — import migration_v3_infinityfree.sql first.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CompuMatch — System Admin</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ===== CompuMatch shared brand logo ===== */
    .cm-logo{display:inline-flex!important;align-items:center;gap:10px;font-weight:700;font-size:1.25rem;color:#fff!important;text-decoration:none;line-height:1;background:none!important;-webkit-background-clip:initial!important;background-clip:initial!important;}
    .cm-logo-badge{width:34px;height:34px;border-radius:50%;flex-shrink:0;background:radial-gradient(circle at 35% 30%,#12233e,#060a12)!important;border:2px solid #00D4FF;box-shadow:0 0 10px rgba(0,212,255,.55),inset 0 0 6px rgba(0,212,255,.35);display:flex;align-items:center;justify-content:center;color:#00D4FF!important;font-size:.95rem;}
    .cm-logo-badge i{color:#00D4FF!important;background:none!important;-webkit-background-clip:initial!important;margin:0!important;padding:0!important;font-size:.95rem!important;line-height:1!important;}
    .cm-logo,.cm-logo *{box-sizing:border-box;}
    .cm-logo-accent{color:#00D4FF!important;}

    :root {
        --primary: #6C63FF; --primary-dark: #5a52e0; --accent: #00D4FF;
        --success: #00E676; --warning: #FF5252;
        --bg-dark: #0f0f14; --bg-card: #1a1a24; --bg-card-hover: #22222e;
        --border: #2d2d3a; --text-muted: #9e9e9e; --text-light: #e0e0e0;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; background: var(--bg-dark); color: var(--text-light); }
    .wrap { max-width: 1100px; margin: 0 auto; padding: 40px 24px 80px; }
    .brand { display: flex; align-items: center; gap: 10px; font-size: 1.4rem; font-weight: 700; margin-bottom: 6px; color: var(--primary); }
    .sub { color: var(--text-muted); margin-bottom: 32px; }

    /* Login screen */
    .login-box { max-width: 380px; margin: 80px auto; background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 32px; }
    .login-box h2 { margin-bottom: 20px; }
    .login-box input { width: 100%; padding: 12px 14px; margin-bottom: 14px; background: var(--bg-dark); border: 1px solid var(--border); border-radius: 8px; color: #fff; font-size: 0.95rem; }
    .login-box button, .btn { width: 100%; padding: 12px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; border-radius: 8px; color: #fff; font-weight: 600; cursor: pointer; font-size: 0.95rem; }
    .err { background: rgba(255,82,82,0.15); border: 1px solid var(--warning); color: var(--warning); padding: 10px 14px; border-radius: 8px; margin-bottom: 14px; font-size: 0.85rem; }

    /* Dashboard */
    .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .logout-form button {
        width: auto;
        padding: 8px 18px;
        background: transparent;
        border: 1px solid var(--primary);
        color: var(--primary);
        font-weight: 600;
        cursor: pointer;
        border-radius: 8px;
        transition: background 0.2s, color 0.2s;
    }
    .logout-form button:hover { background: var(--primary); color: #fff; }
    .stats { display: flex; gap: 16px; margin-bottom: 32px; }
    .stat-card { flex: 1; background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 20px; }
    .stat-card .num { font-size: 2rem; font-weight: 700; color: var(--accent); }
    .stat-card .lbl { color: var(--text-muted); font-size: 0.85rem; margin-top: 4px; }

    .panel { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; margin-bottom: 28px; overflow: hidden; }
    .panel h3 { padding: 18px 20px; border-bottom: 1px solid var(--border); font-size: 1.05rem; }
    table { width: 100%; border-collapse: collapse; }
    th, td { text-align: left; padding: 12px 20px; font-size: 0.9rem; border-bottom: 1px solid var(--border); }
    th { color: var(--text-muted); font-weight: 600; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: var(--bg-card-hover); }
    .empty { padding: 28px 20px; color: var(--text-muted); text-align: center; }
    .del-btn { background: none; border: 1px solid var(--warning); color: var(--warning); padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; width: auto; }
    .del-btn:hover { background: rgba(255,82,82,0.15); }
    .row-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .edit-btn { background: none; border: 1px solid var(--accent); color: var(--accent); padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; width: auto; }
    .edit-btn:hover { background: rgba(0,212,255,0.15); }
    .key-btn { background: none; border: 1px solid var(--primary); color: var(--primary); padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; width: auto; }
    .key-btn:hover { background: rgba(108,99,255,0.15); }
    .edit-panel { display: none; background: var(--bg-dark); }
    .edit-panel.open { display: table-row; }
    .edit-panel td { padding: 16px 20px; }
    .edit-panel form { display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end; }
    .edit-panel .field { display: flex; flex-direction: column; gap: 4px; flex: 1 1 160px; }
    .edit-panel label { font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.03em; }
    .edit-panel input, .edit-panel textarea { padding: 9px 12px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 6px; color: #fff; font-size: 0.85rem; font-family: inherit; }
    .edit-panel textarea { flex: 1 1 100%; resize: vertical; min-height: 40px; }
    .edit-panel .save-btn { width: auto; padding: 9px 18px; margin: 0; }
    .notice { background: rgba(0,230,118,0.15); border: 1px solid var(--success); color: var(--success); padding: 10px 14px; border-radius: 8px; margin-bottom: 14px; font-size: 0.85rem; }

    /* Tabs */
    .tabs { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; border-bottom: 1px solid var(--border); }
    .tab-btn { width: auto; background: none; border: none; color: var(--text-muted); font-weight: 600; font-size: 0.9rem; padding: 10px 16px; cursor: pointer; border-bottom: 2px solid transparent; display: flex; align-items: center; gap: 8px; }
    .tab-btn:hover { color: var(--text-light); }
    .tab-btn.active { color: var(--accent); border-bottom-color: var(--accent); }

    .view-only-tag { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-muted); font-weight: 600; border: 1px solid var(--border); border-radius: 20px; padding: 2px 10px; margin-left: 10px; vertical-align: middle; }
    .sub-panel { border-bottom: 1px solid var(--border); }
    .sub-panel:last-child { border-bottom: none; }
    .sub-panel-head { padding: 14px 20px; font-weight: 700; background: var(--bg-card-hover); display: flex; align-items: center; gap: 10px; }
    .map-link { color: var(--accent); text-decoration: none; font-size: 0.8rem; white-space: nowrap; }
    .map-link:hover { text-decoration: underline; }
    .muted { color: var(--text-muted); }
    .permit-badge { display: inline-block; font-size: 0.72rem; font-weight: 600; padding: 2px 9px; border-radius: 20px; margin-right: 6px; border: 1px solid; }
    .permit-pending  { color: #FFD740; border-color: rgba(255,215,64,0.5); }
    .permit-approved { color: #00E676; border-color: rgba(0,230,118,0.5); }
    .permit-rejected { color: #FF5252; border-color: rgba(255,82,82,0.5); }
    .sample-help { color: var(--text-muted); font-size: 0.85rem; line-height: 1.6; margin: 0; padding: 16px 20px 0; max-width: 75ch; }
    .sample-grid { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 14px; }
    .sample-card { width: 140px; display: flex; flex-direction: column; gap: 6px; }
    .sample-card img { width: 140px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); display: block; }
    .sample-card form { margin: 0; }
    .basis-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 12px; padding: 14px 20px 0; }
    .basis-card { border: 1px solid var(--border); border-radius: 10px; padding: 12px 14px; }
    .basis-card h4 { font-size: 0.9rem; margin: 0 0 6px; color: var(--accent); }
    .basis-card p { font-size: 0.78rem; color: var(--text-muted); margin: 0 0 6px; line-height: 1.5; }
    .basis-card ul { margin: 0; padding-left: 18px; font-size: 0.8rem; line-height: 1.6; }
    .sample-tools { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; padding: 14px 20px 18px; }
    .sample-tools form { display: flex; gap: 8px; align-items: center; margin: 0; }
    .sample-tools input[type=file] { color: var(--text-muted); font-size: 0.8rem; }
    .sample-tools .save-btn { width: auto; padding: 9px 16px; }
    .permit-meta { font-size: 0.7rem; color: var(--text-muted); margin-top: 4px; max-width: 220px; }
    .permit-actions { display: flex; gap: 6px; margin-top: 6px; }
    .permit-actions form { margin: 0; }
    .permit-btn { background: none; border: 1px solid var(--border); color: var(--text-muted); padding: 3px 9px; border-radius: 6px; cursor: pointer; font-size: 0.72rem; width: auto; }
    .permit-btn-approved:hover { border-color: #00E676; color: #00E676; }
    .permit-btn-rejected:hover { border-color: #FF5252; color: #FF5252; }
    .tag { font-size: 0.72rem; font-weight: 600; color: var(--text-muted); border: 1px solid var(--border); border-radius: 20px; padding: 2px 10px; }
    .low-stock { color: var(--warning); font-weight: 700; }
    .build-meta { padding: 10px 20px; color: var(--text-muted); font-size: 0.82rem; }

    /* Search bars */
    .panel-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 14px 20px; border-bottom: 1px solid var(--border); }
    .panel-head h3 { padding: 0; border: none; }
    .search-box { position: relative; flex: 0 1 300px; min-width: 200px; }
    .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.85rem; pointer-events: none; }
    .search-box input { width: 100%; padding: 9px 12px 9px 34px; background: var(--bg-dark); border: 1px solid var(--border); border-radius: 8px; color: #fff; font-size: 0.88rem; font-family: inherit; }
    .search-box input:focus { outline: none; border-color: var(--accent); }
    .no-results { display: none; }

    /* Clients / Stores switch inside the Accounts tab */
    .acct-switch { display: inline-flex; gap: 4px; padding: 4px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 999px; margin-bottom: 18px; }
    .acct-btn { width: auto; display: flex; align-items: center; gap: 8px; background: none; border: none; color: var(--text-muted); font-weight: 600; font-size: 0.88rem; padding: 8px 18px; border-radius: 999px; cursor: pointer; font-family: inherit; }
    .acct-btn:hover { color: var(--text-light); }
    .acct-btn.active { background: rgba(0,212,255,0.12); color: var(--accent); }
    .acct-count { font-size: 0.72rem; background: var(--bg-dark); border: 1px solid var(--border); border-radius: 20px; padding: 1px 8px; color: var(--text-muted); }
    .acct-btn.active .acct-count { color: var(--accent); border-color: rgba(0,212,255,0.4); }

    /* Store permit cards (Pending / Approved / Rejected) */
    .permit-card { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; flex-wrap: wrap; padding: 18px 20px; border-bottom: 1px solid var(--border); }
    .permit-card:last-child { border-bottom: none; }
    .permit-info { flex: 1; min-width: 240px; }
    .permit-info h4 { margin: 0 0 8px; font-size: 1.05rem; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .permit-info p { margin: 4px 0; color: var(--text-muted); font-size: 0.88rem; }
    .permit-info code { background: var(--bg-dark); padding: 2px 6px; border-radius: 4px; color: var(--text-light); }
    .permit-reason { background: rgba(255,82,82,0.08); border-left: 3px solid var(--warning); padding: 8px 10px !important; border-radius: 4px; color: var(--text-light) !important; margin-top: 8px !important; max-width: 70ch; }
    .permit-ai { font-size: 0.8rem !important; max-width: 70ch; }
    .permit-buttons { display: flex; flex-direction: column; gap: 8px; }
    .pbtn { width: auto; padding: 9px 16px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.88rem; font-family: inherit; }
    .pbtn-approve { background: var(--success); color: #000; }
    .pbtn-reject { background: var(--warning); color: #fff; }

    /* Tab count badge */
    .tab-count { background: #FFD740; color: #000; font-size: 0.7rem; font-weight: 700; border-radius: 20px; padding: 1px 8px; }

    /* Collapsible list (inventory + builds) */
    .acc-item { border-bottom: 1px solid var(--border); }
    .acc-item:last-child { border-bottom: none; }
    .acc-head { width: 100%; display: flex; align-items: center; gap: 10px; padding: 14px 20px; background: none; border: none; color: var(--text-light); font-size: 0.95rem; font-weight: 700; cursor: pointer; text-align: left; font-family: inherit; }
    .acc-head:hover { background: var(--bg-card-hover); }
    .acc-head .chev { color: var(--text-muted); transition: transform 0.2s; width: 14px; }
    .acc-item.open .acc-head .chev { transform: rotate(90deg); color: var(--accent); }
    .acc-head .acc-meta { margin-left: auto; display: flex; gap: 8px; align-items: center; font-weight: 600; }
    .acc-head .sub-email { color: var(--text-muted); font-weight: 400; font-size: 0.82rem; }
    .acc-body { display: none; background: var(--bg-dark); border-top: 1px solid var(--border); }
    .acc-item.open .acc-body { display: block; }
    .acc-body tr:hover td { background: var(--bg-card); }
    .tag-warn { color: var(--warning); border-color: rgba(255,82,82,0.5); }
    .build-block { border-bottom: 1px solid var(--border); }
    .build-block:last-child { border-bottom: none; }
    .build-title { padding: 12px 20px 0; font-weight: 700; display: flex; gap: 10px; align-items: center; }
</style>
<style id="cm-bg">
    /* ===== CompuMatch shared page background (same look as the login page):
       a slowly drifting grid with a purple glow top-left and a cyan glow
       bottom-right. Sits behind everything; page content is untouched. ===== */
    body { background-color: #0b0b10 !important; }
    body::before, body::after {
        content: "";
        position: fixed;
        inset: 0;
        z-index: -1;
        pointer-events: none;
    }
    body::before {
        background-image:
            linear-gradient(rgba(108,99,255,0.07) 1px, transparent 1px),
            linear-gradient(90deg, rgba(108,99,255,0.07) 1px, transparent 1px);
        background-size: 50px 50px;
        animation: cmBgGrid 20s linear infinite;
    }
    body::after {
        background:
            radial-gradient(circle at 6% 8%, rgba(108,99,255,0.24), transparent 36%),
            radial-gradient(circle at 94% 94%, rgba(0,212,255,0.17), transparent 30%);
        animation: cmBgGlow 8s ease-in-out infinite alternate;
    }
    @keyframes cmBgGrid { from { background-position: 0 0; } to { background-position: 50px 50px; } }
    @keyframes cmBgGlow { from { transform: translateY(0) scale(1); } to { transform: translateY(-30px) scale(1.05); } }
    @media (prefers-reduced-motion: reduce) { body::before, body::after { animation: none; } }

    /* Change admin password */
    .topbar-actions { display: flex; gap: 10px; align-items: center; }
    .pw-btn { width: auto; padding: 8px 14px; background: transparent; border: 1px solid var(--border); color: var(--text-muted); font-weight: 600; cursor: pointer; border-radius: 8px; font-family: inherit; }
    .pw-btn:hover { color: var(--accent); border-color: var(--accent); }
    .mobile-only-btn { display: none; }
    .pwm { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 10000; align-items: center; justify-content: center; padding: 20px; }
    .pwm.open { display: flex; }
    .pwm-box { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 24px; width: 100%; max-width: 400px; display: flex; flex-direction: column; gap: 12px; }
    .pwm-box h3 { margin-bottom: 4px; }
    .pwm-box label { display: flex; flex-direction: column; gap: 6px; font-size: 0.8rem; color: var(--text-muted); }
    .pwm-box input { padding: 10px 12px; background: var(--bg-dark); border: 1px solid var(--border); border-radius: 8px; color: #fff; font-size: 0.95rem; }
    .pwm-hint { font-size: 0.78rem; color: var(--text-muted); }
    .pwm-actions { display: flex; gap: 10px; justify-content: flex-end; }
    .pwm-actions .save-btn { width: auto; padding: 10px 18px; }
    .pwm-cancel { width: auto; padding: 10px 16px; background: transparent; border: 1px solid var(--border); color: var(--text-light); border-radius: 8px; cursor: pointer; font-family: inherit; }

    /* ===== Phones ===== */
    .nav-toggle, .mobile-logout { display: none; }
    .table-scroll { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .acc-body { overflow-x: auto; }
    @media (max-width: 768px) {
        .wrap { padding: 20px 16px 60px; display: flex; flex-direction: column; }
        .wrap > * { order: 2; }
        .wrap > .topbar { order: 0; }
        .wrap > .tabs { order: 1; }
        .topbar { margin-bottom: 16px; gap: 12px; }
        .topbar .sub, .topbar .topbar-actions { display: none; }
        body.nav-open .mobile-only-btn { display: flex; }
        .topbar .cm-logo { font-size: 1.1rem; flex-wrap: wrap; }
        .nav-toggle {
            display: inline-flex; align-items: center; justify-content: center;
            width: 44px; height: 44px; flex-shrink: 0; border-radius: 12px;
            border: 1px solid var(--border); background: var(--bg-card);
            color: var(--text-light); font-size: 1.2rem; cursor: pointer;
        }
        body.nav-open .nav-toggle { border-color: var(--accent); color: var(--accent); }
        .tabs { display: none; }
        body.nav-open .tabs {
            display: flex; flex-direction: column; gap: 4px; padding: 10px;
            background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px;
            margin-bottom: 20px;
        }
        body.nav-open .tab-btn { width: 100%; border: none; border-radius: 10px; padding: 12px 14px; font-size: 1rem; }
        body.nav-open .tab-btn.active { background: rgba(0,212,255,0.10); }
        body.nav-open .mobile-logout { display: block; margin-top: 6px; }
        .mobile-logout button { width: 100%; padding: 12px; border-radius: 10px; border: 1px solid var(--primary); background: transparent; color: var(--primary); font-weight: 600; font-size: 0.95rem; cursor: pointer; font-family: inherit; }

        .stats { gap: 10px; margin-bottom: 20px; }
        .stat-card { padding: 14px; }
        .stat-card .num { font-size: 1.6rem; }
        .stat-card .lbl { font-size: 0.78rem; }

        /* Clients/Stores and Pending/Approved/Rejected switches fit the screen */
        .acct-switch { display: flex; width: 100%; flex-wrap: wrap; border-radius: 16px; }
        .acct-btn { flex: 1 1 0; justify-content: center; padding: 8px 10px; font-size: 0.82rem; gap: 6px; min-width: 0; }
        .acct-count { padding: 1px 6px; }

        .panel-head { padding: 14px 16px; }
        .search-box { flex: 1 1 100%; min-width: 0; }
        .table-scroll table { min-width: 640px; }
        #client-list { min-width: 780px; }
        #store-list { min-width: 1040px; }
        .table-scroll .row-actions { flex-wrap: nowrap; }
        .table-scroll td { white-space: nowrap; }
        .table-scroll td .permit-meta { white-space: normal; min-width: 180px; }
        .table-scroll::before {
            content: "← Swipe the table sideways to see more →";
            display: block; padding: 8px 16px; font-size: 0.75rem; color: var(--text-muted);
            border-bottom: 1px solid var(--border); position: sticky; left: 0;
        }
        th, td { padding: 10px 14px; }
        .acc-head { padding: 12px 16px; flex-wrap: wrap; }
        .acc-head .acc-meta { margin-left: 0; width: 100%; padding-left: 24px; }
        .acc-body table { min-width: 520px; }
        .permit-card { padding: 16px; }
        .permit-buttons { flex-direction: row; width: 100%; }
        .permit-buttons .pbtn { flex: 1; }
    }
</style>
</head>
<body>

<?php if (!$loggedIn): ?>
    <div class="login-box">
        <div class="cm-logo"><span class="cm-logo-badge"><i class="fas fa-microchip"></i></span>Compu<span class="cm-logo-accent">Match</span></div>
        <h2>System Admin Login</h2>
        <?php if ($loginError): ?><div class="err"><?php echo htmlspecialchars($loginError); ?></div><?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" autocomplete="off" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login" value="1">Log In</button>
        </form>
    </div>
<?php else: ?>
    <div class="wrap">
        <div class="topbar">
            <div>
                <div class="cm-logo"><span class="cm-logo-badge"><i class="fas fa-microchip"></i></span>Compu<span class="cm-logo-accent">Match</span><span style="color:#9e9e9e;font-weight:600;font-size:0.9rem;margin-left:6px;">— System Admin</span></div>
                <div class="sub">All client and store accounts registered on CompuMatch.</div>
            </div>
            <div class="topbar-actions">
                <a class="pw-btn" href="monitoring.php" style="text-decoration:none"><i class="fas fa-chart-line"></i> Activity Monitor</a>
                <button type="button" class="pw-btn" onclick="openAdminPw()"><i class="fas fa-key"></i> Change Password</button>
                <form class="logout-form" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                    <button type="submit" name="logout" value="1">Log Out</button>
                </form>
            </div>
            <button type="button" class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false" aria-controls="adminTabs" onclick="toggleMobileNav(event)">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <?php if ($dbError): ?><div class="err"><?php echo htmlspecialchars($dbError); ?></div><?php endif; ?>
        <?php if ($notice): ?><div class="notice"><?php echo htmlspecialchars($notice); ?></div><?php endif; ?>
        <?php if ($actionError): ?><div class="err"><?php echo htmlspecialchars($actionError); ?></div><?php endif; ?>

        <div class="stats">
            <div class="stat-card"><div class="num"><?php echo count($users); ?></div><div class="lbl">Client accounts</div></div>
            <div class="stat-card"><div class="num"><?php echo count($stores); ?></div><div class="lbl">Store accounts</div></div>
            <div class="stat-card"><div class="num"><?php echo $analytics ? count($analytics['online_now']) : '—'; ?></div><div class="lbl">Online right now</div></div>
        </div>

        <div class="tabs" id="adminTabs">
            <button type="button" class="tab-btn active" data-tab="accounts" onclick="switchTab('accounts')"><i class="fas fa-users"></i> Accounts</button>
            <?php $pendingCount = count(array_filter($stores, function ($s) { return ($s['permit_status'] ?? '') === 'pending'; })); ?>
            <button type="button" class="tab-btn" data-tab="pending" onclick="switchTab('pending')"><i class="fas fa-file-shield"></i> Store Permits<?php if ($pendingCount): ?> <span class="tab-count"><?php echo $pendingCount; ?></span><?php endif; ?></button>
            <button type="button" class="tab-btn" data-tab="inventory" onclick="switchTab('inventory')"><i class="fas fa-boxes-stacked"></i> Inventory</button>
            <button type="button" class="tab-btn" data-tab="builds" onclick="switchTab('builds')"><i class="fas fa-desktop"></i> Client Builds</button>
            <button type="button" class="tab-btn" onclick="location.href='monitoring.php'"><i class="fas fa-shield-halved"></i> Activity Monitor</button>
            <button type="button" class="tab-btn" data-tab="analytics" onclick="switchTab('analytics')"><i class="fas fa-chart-line"></i> Analytics</button>
            <button type="button" class="tab-btn mobile-only-btn" onclick="openAdminPw()"><i class="fas fa-key"></i> Change Password</button>
            <form class="mobile-logout" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                <button type="submit" name="logout" value="1"><i class="fas fa-right-from-bracket"></i> Log Out</button>
            </form>
        </div>

        <div class="tab-panel" id="tab-accounts">
        <div class="acct-switch" role="tablist">
            <button type="button" class="acct-btn active" data-acct="clients" onclick="switchAccounts('clients')"><i class="fas fa-user"></i> Clients <span class="acct-count"><?php echo count($users); ?></span></button>
            <button type="button" class="acct-btn" data-acct="stores" onclick="switchAccounts('stores')"><i class="fas fa-store"></i> Stores <span class="acct-count"><?php echo count($stores); ?></span></button>
        </div>
        <div class="panel acct-panel" id="acct-clients">
            <div class="panel-head">
                <h3><i class="fas fa-users"></i> Client Accounts</h3>
                <?php if ($users): ?>
                <div class="search-box"><i class="fas fa-search"></i><input type="search" placeholder="Search name, username or email…" oninput="filterItems(this, 'client-list')" autocomplete="off"></div>
                <?php endif; ?>
            </div>
            <?php if (!$users): ?>
                <div class="empty">No client accounts yet.</div>
            <?php else: ?>
                <div class="table-scroll">
                <table id="client-list">
                    <thead><tr><th>Name</th><th>Username</th><th>Email</th><th>Joined</th><th></th></tr></thead>
                    <?php foreach ($users as $u): ?>
                    <tbody class="filter-item" data-search="<?php echo htmlspecialchars(mb_strtolower($u['name'] . ' ' . ($u['username'] ?? '') . ' ' . $u['email'])); ?>">
                        <tr>
                            <td><?php echo htmlspecialchars($u['name']); ?></td>
                            <td><?php echo htmlspecialchars($u['username'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><?php echo htmlspecialchars(formatPHTime($pdo, $u['created_at'])); ?></td>
                            <td>
                                <div class="row-actions">
                                    <button type="button" class="edit-btn" onclick="togglePanel('user-edit-<?php echo (int)$u['id']; ?>')"><i class="fas fa-pen"></i> Edit</button>
                                    <button type="button" class="key-btn" onclick="togglePanel('user-key-<?php echo (int)$u['id']; ?>')"><i class="fas fa-key"></i> Reset Password</button>
                                    <form method="post" onsubmit="return confirm('Delete this account?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                        <input type="hidden" name="delete_user" value="<?php echo (int)$u['id']; ?>">
                                        <button type="submit" class="del-btn"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr class="edit-panel" id="panel-user-edit-<?php echo (int)$u['id']; ?>">
                            <td colspan="5">
                                <form method="post">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                    <input type="hidden" name="edit_user" value="<?php echo (int)$u['id']; ?>">
                                    <div class="field"><label>Name</label><input type="text" name="edit_user_name" value="<?php echo htmlspecialchars($u['name']); ?>" required></div>
                                    <div class="field"><label>Username</label><input type="text" name="edit_user_username" value="<?php echo htmlspecialchars($u['username'] ?? ''); ?>" pattern="[a-z0-9_]{3,30}" required></div>
                                    <div class="field"><label>Email</label><input type="email" name="edit_user_email" value="<?php echo htmlspecialchars($u['email']); ?>" required></div>
                                    <button type="submit" class="btn save-btn">Save</button>
                                </form>
                            </td>
                        </tr>
                        <tr class="edit-panel" id="panel-user-key-<?php echo (int)$u['id']; ?>">
                            <td colspan="5">
                                <form method="post" onsubmit="return confirm('Set a new password for this account?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                    <input type="hidden" name="reset_user_password" value="<?php echo (int)$u['id']; ?>">
                                    <div class="field"><label>New Password (min. 8 chars)</label><input type="password" name="new_password" minlength="8" required></div>
                                    <button type="submit" class="btn save-btn">Set Password</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                    <?php endforeach; ?>
                </table>
                </div>
                <div class="empty no-results" data-for="client-list">No client matches your search.</div>
            <?php endif; ?>
        </div>

        <div class="panel acct-panel" id="acct-stores" style="display:none;">
            <div class="panel-head">
                <h3><i class="fas fa-store"></i> Store Accounts</h3>
                <?php if ($stores): ?>
                <div class="search-box"><i class="fas fa-search"></i><input type="search" placeholder="Search store name, ID or email…" oninput="filterItems(this, 'store-list')" autocomplete="off"></div>
                <?php endif; ?>
            </div>
            <?php if (!$stores): ?>
                <div class="empty">No store accounts yet.</div>
            <?php else: ?>
                <div class="table-scroll">
                <table id="store-list">
                    <thead><tr><th>Store ID</th><th>Name</th><th>Email</th><th>Contact</th><th>Location</th><th>Permit</th><th>Joined</th><th></th></tr></thead>
                    <?php foreach ($stores as $s): ?>
                    <tbody class="filter-item" data-search="<?php echo htmlspecialchars(mb_strtolower($s['store_id'] . ' ' . $s['name'] . ' ' . $s['email'])); ?>">
                        <tr>
                            <td><?php echo htmlspecialchars($s['store_id']); ?></td>
                            <td><?php echo htmlspecialchars($s['name']); ?></td>
                            <td><?php echo htmlspecialchars($s['email']); ?></td>
                            <td><?php echo htmlspecialchars($s['contact']); ?></td>
                            <td>
                                <?php if ($s['latitude'] !== null && $s['longitude'] !== null): ?>
                                    <a class="map-link" target="_blank" rel="noopener"
                                       href="https://www.google.com/maps?q=<?php echo urlencode($s['latitude'] . ',' . $s['longitude']); ?>">
                                        <i class="fas fa-map-location-dot"></i> View map
                                    </a>
                                <?php else: ?>
                                    <span class="muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php $ps = $s['permit_status'] ?: 'pending'; ?>
                                <span class="permit-badge permit-<?php echo htmlspecialchars($ps); ?>"><?php echo htmlspecialchars(ucfirst($ps)); ?></span>
                                <?php if ($s['permit_image']): ?>
                                    <a class="map-link" target="_blank" rel="noopener" href="view_permit.php?store=<?php echo urlencode($s['store_id']); ?>">
                                        <i class="fas fa-file-image"></i> View
                                    </a>

                                <?php endif; ?>
                                <?php if (!empty($s['permit_reviewed_by']) && $s['permit_reviewed_by'] !== 'system'): ?>
                                    <div class="permit-meta">by <?php echo htmlspecialchars($s['permit_reviewed_by']); ?><?php if ($s['permit_reviewed_at']): ?>, <?php echo htmlspecialchars(formatPHTime($pdo, $s['permit_reviewed_at'])); ?><?php endif; ?></div>
                                <?php endif; ?>
                                <?php if (!empty($s['permit_ai_notes'])): ?>
                                    <div class="permit-meta" title="<?php echo htmlspecialchars($s['permit_ai_notes']); ?>"><i class="fas fa-robot"></i> <?php echo htmlspecialchars(mb_strimwidth($s['permit_ai_notes'], 0, 80, '…')); ?></div>
                                <?php endif; ?>
                                <div class="permit-actions">
                                    <?php foreach (['approved' => 'Approve', 'rejected' => 'Reject'] as $val => $lbl): if ($ps === $val) continue; ?>
                                        <form method="post" onsubmit="return confirm('<?php echo $lbl; ?> this store\'s permit?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                            <input type="hidden" name="set_permit_status" value="<?php echo htmlspecialchars($s['store_id']); ?>">
                                            <input type="hidden" name="permit_status" value="<?php echo $val; ?>">
                                            <button type="submit" class="permit-btn permit-btn-<?php echo $val; ?>"><?php echo $lbl; ?></button>
                                        </form>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars(formatPHTime($pdo, $s['created_at'])); ?></td>
                            <td>
                                <div class="row-actions">
                                    <button type="button" class="edit-btn" onclick="togglePanel('store-edit-<?php echo htmlspecialchars($s['store_id']); ?>')"><i class="fas fa-pen"></i> Edit</button>
                                    <button type="button" class="key-btn" onclick="togglePanel('store-key-<?php echo htmlspecialchars($s['store_id']); ?>')"><i class="fas fa-key"></i> Reset Password</button>
                                    <form method="post" onsubmit="return confirm('Delete this store?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                        <input type="hidden" name="delete_store" value="<?php echo htmlspecialchars($s['store_id']); ?>">
                                        <button type="submit" class="del-btn"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr class="edit-panel" id="panel-store-edit-<?php echo htmlspecialchars($s['store_id']); ?>">
                            <td colspan="8">
                                <form method="post">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                    <input type="hidden" name="edit_store" value="<?php echo htmlspecialchars($s['store_id']); ?>">
                                    <div class="field"><label>Name</label><input type="text" name="edit_store_name" value="<?php echo htmlspecialchars($s['name']); ?>" required></div>
                                    <div class="field"><label>Email</label><input type="email" name="edit_store_email" value="<?php echo htmlspecialchars($s['email']); ?>"></div>
                                    <div class="field"><label>Contact</label><input type="text" name="edit_store_contact" value="<?php echo htmlspecialchars($s['contact']); ?>"></div>
                                    <div class="field"><label>Address</label><input type="text" name="edit_store_address" value="<?php echo htmlspecialchars($s['address']); ?>"></div>
                                    <div class="field"><label>Description</label><textarea name="edit_store_description"><?php echo htmlspecialchars($s['description']); ?></textarea></div>
                                    <button type="submit" class="btn save-btn">Save</button>
                                </form>
                            </td>
                        </tr>
                        <tr class="edit-panel" id="panel-store-key-<?php echo htmlspecialchars($s['store_id']); ?>">
                            <td colspan="8">
                                <form method="post" onsubmit="return confirm('Set a new password for this store?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                    <input type="hidden" name="reset_store_password" value="<?php echo htmlspecialchars($s['store_id']); ?>">
                                    <div class="field"><label>New Password (min. 6 chars)</label><input type="password" name="new_password" minlength="6" required></div>
                                    <button type="submit" class="btn save-btn">Set Password</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                    <?php endforeach; ?>
                </table>
                </div>
                <div class="empty no-results" data-for="store-list">No store matches your search.</div>
            <?php endif; ?>
        </div>
        </div><!-- /#tab-accounts -->

        <div class="tab-panel" id="tab-pending" style="display:none;">
        <?php
        $permitGroups = ['pending' => [], 'approved' => [], 'rejected' => []];
        foreach ($stores as $st) {
            $k = $st['permit_status'] ?: 'pending';
            if (isset($permitGroups[$k])) $permitGroups[$k][] = $st;
        }
        $permitLabels = [
            'pending'  => ['Pending',  'fa-hourglass-half', 'All stores have been reviewed!'],
            'approved' => ['Approved', 'fa-circle-check',   'No approved stores yet.'],
            'rejected' => ['Rejected', 'fa-circle-xmark',   'No rejected stores.'],
        ];
        ?>
        <div class="acct-switch" role="tablist">
            <?php foreach ($permitLabels as $key => $lbl): ?>
            <button type="button" class="acct-btn permit-switch-btn<?php echo $key === 'pending' ? ' active' : ''; ?>" data-permit="<?php echo $key; ?>" onclick="switchPermits('<?php echo $key; ?>')"><i class="fas <?php echo $lbl[1]; ?>"></i> <?php echo $lbl[0]; ?> <span class="acct-count"><?php echo count($permitGroups[$key]); ?></span></button>
            <?php endforeach; ?>
        </div>

        <?php foreach ($permitLabels as $key => $lbl): $list = $permitGroups[$key]; ?>
        <div class="panel permit-panel" id="permit-<?php echo $key; ?>"<?php echo $key === 'pending' ? '' : ' style="display:none;"'; ?>>
            <div class="panel-head">
                <h3><i class="fas <?php echo $lbl[1]; ?>"></i> <?php echo $lbl[0]; ?> Stores</h3>
                <?php if ($list): ?>
                <div class="search-box"><i class="fas fa-search"></i><input type="search" placeholder="Search store name, ID or email…" oninput="filterItems(this, 'permit-list-<?php echo $key; ?>')" autocomplete="off"></div>
                <?php endif; ?>
            </div>
            <?php if (!$list): ?>
                <div class="empty"><?php echo $key === 'pending' ? '✅ ' : ''; ?><?php echo $lbl[2]; ?></div>
            <?php else: ?>
            <div id="permit-list-<?php echo $key; ?>">
            <?php foreach ($list as $st): $sid = $st['store_id']; ?>
                <div class="permit-card filter-item" data-search="<?php echo htmlspecialchars(mb_strtolower($st['name'] . ' ' . $sid . ' ' . $st['email'])); ?>">
                    <div class="permit-info">
                        <h4><?php echo htmlspecialchars($st['name']); ?> <span class="permit-badge permit-<?php echo $key; ?>"><?php echo $lbl[0]; ?></span></h4>
                        <p><strong>Store ID:</strong> <code><?php echo htmlspecialchars($sid); ?></code></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($st['email'] ?? ''); ?></p>
                        <p><strong>Applied:</strong> <?php echo htmlspecialchars(formatPHTime($pdo, $st['created_at'])); ?></p>
                        <?php if ($key !== 'pending' && !empty($st['permit_reviewed_at'])): ?>
                            <p><strong><?php echo $key === 'approved' ? 'Approved' : 'Rejected'; ?>:</strong> <?php echo htmlspecialchars(formatPHTime($pdo, $st['permit_reviewed_at'])); ?><?php if (!empty($st['permit_reviewed_by'])): ?> by <?php echo htmlspecialchars($st['permit_reviewed_by']); ?><?php endif; ?></p>
                        <?php endif; ?>
                        <?php if ($key === 'rejected' && !empty($rejectReasons[$sid])): ?>
                            <p class="permit-reason"><strong>Reason:</strong> <?php echo nl2br(htmlspecialchars($rejectReasons[$sid])); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($st['permit_ai_notes'])): ?>
                            <p class="permit-ai" title="<?php echo htmlspecialchars($st['permit_ai_notes']); ?>"><i class="fas fa-robot"></i> <?php echo htmlspecialchars(mb_strimwidth($st['permit_ai_notes'], 0, 140, '…')); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($st['permit_image'])): ?>
                            <p><a class="map-link" target="_blank" rel="noopener" href="view_permit.php?store=<?php echo urlencode($sid); ?>"><i class="fas fa-file-image"></i> View permit</a></p>
                        <?php endif; ?>
                    </div>
                    <div class="permit-buttons">
                        <?php if ($key !== 'approved'): ?>
                        <button type="button" class="pbtn pbtn-approve" onclick="openApprovalModal('<?php echo htmlspecialchars($sid, ENT_QUOTES); ?>')">✅ Approve</button>
                        <?php endif; ?>
                        <?php if ($key !== 'rejected'): ?>
                        <button type="button" class="pbtn pbtn-reject" onclick="openRejectionModal('<?php echo htmlspecialchars($sid, ENT_QUOTES); ?>')">❌ Reject</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <div class="empty no-results" data-for="permit-list-<?php echo $key; ?>">No store matches your search.</div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <div class="panel">
            <h3><i class="fas fa-file-shield"></i> Permit Auto-Check</h3>
            <p class="sample-help">
                A new store is approved automatically when the words read from its uploaded document show a
                <strong>DTI Certificate of Business Name Registration</strong> or a <strong>Business Permit</strong>
                (from any city or municipality) that has not expired. Anything else — permit plates, unclear photos,
                expired or reused permits — stays pending for you to review.
            </p>
            <div class="basis-grid">
                <div class="basis-card">
                    <h4><i class="fas fa-certificate"></i> DTI Certificate</h4>
                    <p>Approved when <strong>3 or more</strong> of these are found (or 2 plus a "valid from … to …" date):</p>
                    <ul><?php foreach ((defined('PERMIT_DTI_PHRASES') ? PERMIT_DTI_PHRASES : []) as $ph): ?><li><?php echo htmlspecialchars(function_exists('permitPhraseLabel') ? permitPhraseLabel($ph) : ucwords(strtolower($ph))); ?></li><?php endforeach; ?></ul>
                </div>
                <div class="basis-card">
                    <h4><i class="fas fa-file-contract"></i> Business Permit</h4>
                    <p>Approved when <strong>3 or more</strong> of these are found <strong>and</strong> it has a permit number or a "Valid Until" date:</p>
                    <ul><?php foreach ((defined('PERMIT_BP_PHRASES') ? PERMIT_BP_PHRASES : []) as $ph): ?><li><?php echo htmlspecialchars(function_exists('permitPhraseLabel') ? permitPhraseLabel($ph) : ucwords(strtolower($ph))); ?></li><?php endforeach; ?></ul>
                </div>
                <div class="basis-card">
                    <h4><i class="fas fa-ban"></i> Always held for review</h4>
                    <ul>
                        <li>Expired (validity date already passed)</li>
                        <li>Same photo as another store's permit</li>
                        <li>Same DTI Business Name No. as another store</li>
                        <li>No readable text (blurry, dark, or a permit plate)</li>
                    </ul>
                </div>
            </div>
            <div class="sample-tools">
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                    <input type="hidden" name="recheck_pending" value="1">
                    <button type="submit" class="edit-btn"><i class="fas fa-rotate"></i> Re-check pending stores</button>
                </form>
            </div>
        </div>

        </div><!-- /#tab-pending -->

        <div class="tab-panel" id="tab-inventory" style="display:none;">
        <div class="panel">
            <div class="panel-head">
                <h3><i class="fas fa-boxes-stacked"></i> Inventory by Store <span class="view-only-tag">view only</span></h3>
                <?php if ($inventoryByStore): ?>
                <div class="search-box"><i class="fas fa-search"></i><input type="search" placeholder="Search store…" oninput="filterItems(this, 'inv-list')" autocomplete="off"></div>
                <?php endif; ?>
            </div>
            <?php if (!$inventoryByStore): ?>
                <div class="empty">No inventory items found.</div>
            <?php else: ?>
            <div id="inv-list">
            <?php foreach ($inventoryByStore as $storeId => $data):
                $lowCount = count(array_filter($data['items'], function ($it) { return (int)$it['stock'] <= (int)$it['low_stock']; })); ?>
                <div class="acc-item filter-item" data-search="<?php echo htmlspecialchars(mb_strtolower($data['store_name'] . ' ' . $storeId)); ?>">
                    <button type="button" class="acc-head" onclick="toggleAcc(this)">
                        <i class="fas fa-chevron-right chev"></i>
                        <?php echo htmlspecialchars($data['store_name']); ?>
                        <span class="tag"><?php echo htmlspecialchars($storeId); ?></span>
                        <span class="acc-meta">
                            <?php if ($lowCount): ?><span class="tag tag-warn"><?php echo $lowCount; ?> low stock</span><?php endif; ?>
                            <span class="tag"><?php echo count($data['items']); ?> item<?php echo count($data['items']) === 1 ? '' : 's'; ?></span>
                        </span>
                    </button>
                    <div class="acc-body">
                    <table>
                        <thead><tr><th>Item</th><th>Category</th><th>Brand</th><th>Price</th><th>Stock</th><th>Sold</th></tr></thead>
                        <tbody>
                        <?php foreach ($data['items'] as $it): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($it['name']); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($it['category'])); ?></td>
                                <td><?php echo htmlspecialchars($it['brand'] ?? ''); ?></td>
                                <td>₱<?php echo number_format((float)$it['price'], 2); ?></td>
                                <td<?php echo ((int)$it['stock'] <= (int)$it['low_stock']) ? ' class="low-stock"' : ''; ?>><?php echo (int)$it['stock']; ?></td>
                                <td><?php echo (int)$it['sold']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <div class="empty no-results" data-for="inv-list">No store matches your search.</div>
            <?php endif; ?>
        </div>
        </div><!-- /#tab-inventory -->

        <div class="tab-panel" id="tab-builds" style="display:none;">
        <div class="panel">
            <div class="panel-head">
                <h3><i class="fas fa-desktop"></i> Client Saved Builds <span class="view-only-tag">view only</span></h3>
                <?php if ($buildsByClient): ?>
                <div class="search-box"><i class="fas fa-search"></i><input type="search" placeholder="Search client name or email…" oninput="filterItems(this, 'builds-list')" autocomplete="off"></div>
                <?php endif; ?>
            </div>
            <?php if (!$buildsByClient): ?>
                <div class="empty">No saved builds yet.</div>
            <?php else: ?>
            <div id="builds-list">
            <?php foreach ($buildsByClient as $client): $n = count($client['builds']); ?>
                <div class="acc-item filter-item" data-search="<?php echo htmlspecialchars(mb_strtolower($client['name'] . ' ' . $client['email'])); ?>">
                    <button type="button" class="acc-head" onclick="toggleAcc(this)">
                        <i class="fas fa-chevron-right chev"></i>
                        <?php echo htmlspecialchars($client['name']); ?>
                        <?php if ($client['email']): ?><span class="sub-email"><?php echo htmlspecialchars($client['email']); ?></span><?php endif; ?>
                        <span class="acc-meta"><span class="tag"><?php echo $n; ?> build<?php echo $n === 1 ? '' : 's'; ?></span></span>
                    </button>
                    <div class="acc-body">
                    <?php foreach ($client['builds'] as $b): ?>
                        <div class="build-block">
                            <div class="build-title">
                                <?php echo htmlspecialchars($b['name']); ?>
                                <span class="tag">₱<?php echo number_format((float)$b['total_price'], 2); ?></span>
                            </div>
                            <div class="build-meta">
                                <?php if ($b['store_name']): ?>From <?php echo htmlspecialchars($b['store_name']); ?> · <?php endif; ?>
                                <?php echo htmlspecialchars(formatPHTime($pdo, $b['created_at'])); ?>
                            </div>
                            <?php if ($b['items']): ?>
                            <table>
                                <thead><tr><th>Slot</th><th>Item</th><th>Price</th></tr></thead>
                                <tbody>
                                <?php foreach ($b['items'] as $bi): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars((in_array($bi['slot'], ['storage2','storage3','storage4','storage5','storage6','storage7','storage8','storage9'], true) ? 'Secondary Storage' : (strpos($bi['slot'], 'nvme') === 0 ? 'Storage (M.2)' : (preg_match('/^ram\d+$/', $bi['slot']) ? 'Ram' : ucfirst($bi['slot']))))); ?></td>
                                        <td><?php echo htmlspecialchars($bi['item_name'] ?? '—'); ?></td>
                                        <td><?php echo $bi['item_price'] !== null ? '₱' . number_format((float)$bi['item_price'], 2) : '—'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <div class="empty no-results" data-for="builds-list">No client matches your search.</div>
            <?php endif; ?>
        </div>
        </div><!-- /#tab-builds -->

        <div class="tab-panel" id="tab-analytics" style="display:none;">
            <?php if ($analyticsError): ?>
                <div class="err"><?php echo htmlspecialchars($analyticsError); ?></div>
            <?php else: ?>
                <div class="stats">
                    <div class="stat-card"><div class="num"><?php echo count($analytics['online_now']); ?></div><div class="lbl">Online right now</div></div>
                    <div class="stat-card"><div class="num"><?php echo number_format($analytics['visits_today']); ?></div><div class="lbl">Visits today</div></div>
                    <div class="stat-card"><div class="num"><?php echo number_format($analytics['unique_visits']); ?></div><div class="lbl">Unique visitors (all-time)</div></div>
                </div>
                <div class="panel">
                    <h3><i class="fas fa-signal"></i> Who's Online (last 5 minutes)</h3>
                    <?php if (!$analytics['online_now']): ?>
                        <div class="empty">Nobody's browsing the site right now.</div>
                    <?php else: ?>
                        <table>
                            <thead><tr><th>Type</th><th>Who</th><th>Page</th><th>Last active</th></tr></thead>
                            <tbody>
                            <?php foreach ($analytics['online_now'] as $o): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(ucfirst($o['user_type'])); ?></td>
                                    <td><?php echo htmlspecialchars($o['user_ref'] ?? 'Anonymous'); ?></td>
                                    <td><?php echo htmlspecialchars($o['page']); ?></td>
                                    <td><?php echo htmlspecialchars(formatPHTime($pdo, $o['last_seen'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div><!-- /#tab-analytics -->
    </div>
<?php endif; ?>

<script>
function togglePanel(id) {
    const el = document.getElementById('panel-' + id);
    if (!el) return;
    el.classList.toggle('open');
}

// Live search: hide .filter-item rows whose data-search doesn't contain the text
function filterItems(input, listId) {
    const q = input.value.trim().toLowerCase();
    const list = document.getElementById(listId);
    if (!list) return;
    let shown = 0;
    list.querySelectorAll('.filter-item').forEach(el => {
        const match = !q || (el.dataset.search || '').includes(q);
        el.style.display = match ? '' : 'none';
        if (match) shown++;
    });
    const none = document.querySelector('.no-results[data-for="' + listId + '"]');
    if (none) none.style.display = shown ? 'none' : 'block';
    if (list.tagName === 'TABLE') list.querySelector('thead').style.display = shown ? '' : 'none';
}

// Open/close a store (inventory) or client (builds) dropdown
function toggleAcc(btn) {
    btn.closest('.acc-item').classList.toggle('open');
}

// Accounts tab: show either Clients or Stores (remembered between page loads)
function switchAccounts(which) {
    document.querySelectorAll('.acct-panel').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.acct-btn[data-acct]').forEach(el => el.classList.toggle('active', el.dataset.acct === which));
    const panel = document.getElementById('acct-' + which);
    if (panel) panel.style.display = 'block';
    try { localStorage.setItem('cm_admin_accounts_view', which); } catch (e) {}
}
(function () {
    let saved = 'clients';
    try { saved = localStorage.getItem('cm_admin_accounts_view') || 'clients'; } catch (e) {}
    switchAccounts(saved === 'stores' ? 'stores' : 'clients');
})();

// Store Permits tab: Pending / Approved / Rejected
function switchPermits(which) {
    document.querySelectorAll('.permit-panel').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.permit-switch-btn').forEach(el => el.classList.toggle('active', el.dataset.permit === which));
    const panel = document.getElementById('permit-' + which);
    if (panel) panel.style.display = 'block';
    try { localStorage.setItem('cm_admin_permit_view', which); } catch (e) {}
}
(function () {
    let saved = 'pending';
    try { saved = localStorage.getItem('cm_admin_permit_view') || 'pending'; } catch (e) {}
    switchPermits(['pending', 'approved', 'rejected'].includes(saved) ? saved : 'pending');
})();

// Phone menu (☰): the tabs + Log Out open as a dropdown under the header.
function toggleMobileNav(e) {
    if (e) e.stopPropagation();
    const open = !document.body.classList.contains('nav-open');
    document.body.classList.toggle('nav-open', open);
    const btn = document.getElementById('navToggle');
    if (btn) {
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        btn.innerHTML = open ? '<i class="fas fa-xmark"></i>' : '<i class="fas fa-bars"></i>';
    }
}
function closeMobileNav() {
    if (document.body.classList.contains('nav-open')) toggleMobileNav();
}
document.addEventListener('click', e => {
    if (!document.body.classList.contains('nav-open')) return;
    const tabs = document.getElementById('adminTabs');
    if (tabs && !tabs.contains(e.target)) closeMobileNav();
});

function switchTab(name) {
    closeMobileNav();
    document.querySelectorAll('.tab-panel').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    const panel = document.getElementById('tab-' + name);
    const btn = document.querySelector('.tab-btn[data-tab="' + name + '"]');
    if (panel) panel.style.display = 'block';
    if (btn) btn.classList.add('active');
    if (history.replaceState) history.replaceState(null, '', '#' + name);
}

// Reopen whichever tab the admin was last on (e.g. after saving a form)
(function () {
    const hash = (location.hash || '').replace('#', '');
    const valid = ['accounts', 'pending', 'inventory', 'builds', 'analytics'];
    switchTab(valid.includes(hash) ? hash : 'accounts');
})();
</script>


<?php if ($loggedIn): ?>
<!-- ============ CHANGE ADMIN PASSWORD ============ -->
<div id="admin-pw-modal" class="pwm" onclick="if(event.target===this)closeAdminPw()">
    <form method="post" class="pwm-box" autocomplete="off">
        <h3><i class="fas fa-key"></i> Change Admin Password</h3>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
        <input type="hidden" name="change_admin_password" value="1">
        <label>Current password<input type="password" name="current_password" required autocomplete="current-password"></label>
        <label>New password<input type="password" name="new_password" required minlength="10" autocomplete="new-password"></label>
        <label>Confirm new password<input type="password" name="confirm_password" required minlength="10" autocomplete="new-password"></label>
        <p class="pwm-hint">At least 10 characters, with letters and numbers.</p>
        <div class="pwm-actions">
            <button type="button" class="pwm-cancel" onclick="closeAdminPw()">Cancel</button>
            <button type="submit" class="btn save-btn">Save Password</button>
        </div>
    </form>
</div>
<script>
function openAdminPw() { if (typeof closeMobileNav === 'function') closeMobileNav(); document.getElementById('admin-pw-modal').classList.add('open'); }
function closeAdminPw() { document.getElementById('admin-pw-modal').classList.remove('open'); }
</script>
<?php endif; ?>

<!-- ============ STORE APPROVAL MODAL FORM ============ -->
<div id="permit-modal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.7); z-index:10000; padding:20px; overflow:auto;">
    <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:30px; margin:50px auto; max-width:500px; box-shadow:0 10px 40px rgba(0,0,0,0.5);">
        
        <h3 id="modal-title" style="margin-top:0; color:var(--text-light); font-size:1.3rem;">Approve Store Permit</h3>
        
        <form method="POST" id="permit-form" style="margin:0;">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
            <input type="hidden" name="set_permit_status" id="modal-store-id" value="">
            
            <!-- Store Info -->
            <div style="background:var(--bg-dark); padding:15px; border-radius:6px; margin-bottom:20px;">
                <p style="margin:0; color:var(--text-muted); font-size:0.9rem;">
                    <strong>Store ID:</strong> <code id="modal-store-id-display" style="background:var(--bg-card); padding:2px 6px; border-radius:3px; font-family:monospace;"></code>
                </p>
            </div>
            
            <!-- Decision -->
            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:8px; font-weight:600; color:var(--text-light); font-size:0.9rem;">Your Decision:</label>
                
                <label style="display:block; margin-bottom:12px; padding:12px; border:2px solid var(--border); border-radius:6px; cursor:pointer; background:var(--bg-dark);">
                    <input type="radio" name="permit_status" value="approved" checked onchange="toggleRejectionSection()"> 
                    <strong style="color:var(--success);">✅ Approve</strong> - Store goes live on CompuMatch
                </label>
                
                <label style="display:block; padding:12px; border:2px solid var(--border); border-radius:6px; cursor:pointer; background:var(--bg-dark);">
                    <input type="radio" name="permit_status" value="rejected" onchange="toggleRejectionSection()"> 
                    <strong style="color:var(--warning);">❌ Reject</strong> - Ask store to resubmit
                </label>
            </div>
            
            <!-- Rejection Reason -->
            <div id="rejection-section" style="display:none; margin-bottom:20px;">
                <label style="display:block; margin-bottom:8px; font-weight:600; color:var(--text-light); font-size:0.9rem;">Reason for Rejection:</label>
                <textarea name="rejection_reason" 
                          style="width:100%; padding:10px; border:1px solid var(--border); border-radius:6px; font-size:0.85rem; background:var(--bg-dark); color:var(--text-light); font-family:Arial,sans-serif; resize:vertical; box-sizing:border-box;" 
                          rows="4" 
                          placeholder="E.g., Permit is expired. Please submit current permit."></textarea>
                <p style="margin:5px 0 0 0; font-size:0.8rem; color:var(--text-muted);">This will be sent to the store owner.</p>
            </div>
            
            <!-- Buttons -->
            <div style="display:flex; gap:10px; margin-top:25px;">
                <button type="button" 
                        onclick="closePermitModal()" 
                        style="flex:1; padding:10px; background:var(--bg-dark); border:1px solid var(--border); border-radius:6px; cursor:pointer; font-weight:600; color:var(--text-light);">
                    Cancel
                </button>
                <button type="submit" 
                        style="flex:1; padding:10px; background:var(--primary); border:none; border-radius:6px; cursor:pointer; font-weight:600; color:white;">
                    Send Decision
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openApprovalModal(storeId) {
    document.getElementById('modal-store-id').value = storeId;
    document.getElementById('modal-store-id-display').textContent = storeId;
    document.getElementById('modal-title').textContent = 'Approve Store Permit';
    document.querySelector('input[name="permit_status"][value="approved"]').checked = true;
    toggleRejectionSection();
    document.getElementById('permit-modal').style.display = 'block';
}

function openRejectionModal(storeId) {
    document.getElementById('modal-store-id').value = storeId;
    document.getElementById('modal-store-id-display').textContent = storeId;
    document.getElementById('modal-title').textContent = 'Reject Store Permit';
    document.querySelector('input[name="permit_status"][value="rejected"]').checked = true;
    toggleRejectionSection();
    document.getElementById('permit-modal').style.display = 'block';
}

function closePermitModal() {
    document.getElementById('permit-modal').style.display = 'none';
}

function toggleRejectionSection() {
    const isRejected = document.querySelector('input[name="permit_status"]:checked').value === 'rejected';
    document.getElementById('rejection-section').style.display = isRejected ? 'block' : 'none';
}

// Close when clicking outside
document.getElementById('permit-modal').addEventListener('click', function(e) {
    if (e.target === this) closePermitModal();
});

// Close on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('permit-modal').style.display === 'block') {
        closePermitModal();
    }
});
</script>
<!-- ============ END MODAL FORM ============ -->

</html>
