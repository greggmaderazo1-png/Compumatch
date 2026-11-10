<?php
require_once __DIR__ . '/db.php';

$token = trim($_GET['token'] ?? '');
$success = false;
$message = 'Invalid verification link.';

if ($token) {
    try {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT id FROM users WHERE verify_token = ?');
        $stmt->execute([$token]);
        $u = $stmt->fetch();
        if ($u) {
            $upd = $pdo->prepare('UPDATE users SET email_verified = 1, verify_token = NULL WHERE id = ?');
            $upd->execute([$u['id']]);
            $success = true;
            $message = 'Your email has been verified! You can now log in.';
        } else {
            $message = 'This verification link is invalid or was already used.';
        }
    } catch (Throwable $e) {
        $message = 'Could not connect to the database.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CompuMatch — Email Verification</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ===== CompuMatch shared brand logo ===== */
    .cm-logo{display:inline-flex!important;align-items:center;gap:10px;font-weight:700;font-size:1.25rem;color:#fff!important;text-decoration:none;line-height:1;background:none!important;-webkit-background-clip:initial!important;background-clip:initial!important;}
    .cm-logo-badge{width:34px;height:34px;border-radius:50%;flex-shrink:0;background:radial-gradient(circle at 35% 30%,#12233e,#060a12)!important;border:2px solid #00D4FF;box-shadow:0 0 10px rgba(0,212,255,.55),inset 0 0 6px rgba(0,212,255,.35);display:flex;align-items:center;justify-content:center;color:#00D4FF!important;font-size:.95rem;}
    .cm-logo-badge i{color:#00D4FF!important;background:none!important;-webkit-background-clip:initial!important;margin:0!important;padding:0!important;font-size:.95rem!important;line-height:1!important;}
    .cm-logo,.cm-logo *{box-sizing:border-box;}
    .cm-logo-accent{color:#00D4FF!important;}

    :root { --primary: #6C63FF; --primary-dark: #5a52e0; --bg-dark: #0f0f14; --bg-card: #1a1a24; --border: #2d2d3a; --success: #00E676; --warning: #FF5252; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; background: var(--bg-dark); color: #fff; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    .card { max-width: 420px; text-align: center; background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 40px 32px; }
    .icon { font-size: 3rem; margin-bottom: 16px; color: <?php echo $success ? 'var(--success)' : 'var(--warning)'; ?>; }
    h2 { margin-bottom: 10px; }
    p { color: #b7b7c2; margin-bottom: 24px; }
    a.btn { display: inline-block; padding: 12px 28px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 8px; color: #fff; text-decoration: none; font-weight: 600; }
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
</style>
</head>
<body>
    <div class="card">
        <div class="cm-logo" style="justify-content:center;margin-bottom:20px;"><span class="cm-logo-badge"><i class="fas fa-microchip"></i></span>Compu<span class="cm-logo-accent">Match</span></div>
        <div class="icon"><i class="fas <?php echo $success ? 'fa-circle-check' : 'fa-circle-exclamation'; ?>"></i></div>
        <h2><?php echo $success ? 'Verified!' : 'Verification Failed'; ?></h2>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a class="btn" href="login.php">Go to Login</a>
    </div>
</body>
</html>
