<?php require_once __DIR__ . '/mailer.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CompuMatch — Forgot Password</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ===== CompuMatch shared brand logo ===== */
    .cm-logo{display:inline-flex!important;align-items:center;gap:10px;font-weight:700;font-size:1.25rem;color:#fff!important;text-decoration:none;line-height:1;background:none!important;-webkit-background-clip:initial!important;background-clip:initial!important;}
    .cm-logo-badge{width:34px;height:34px;border-radius:50%;flex-shrink:0;background:radial-gradient(circle at 35% 30%,#12233e,#060a12)!important;border:2px solid #00D4FF;box-shadow:0 0 10px rgba(0,212,255,.55),inset 0 0 6px rgba(0,212,255,.35);display:flex;align-items:center;justify-content:center;color:#00D4FF!important;font-size:.95rem;}
    .cm-logo-badge i{color:#00D4FF!important;background:none!important;-webkit-background-clip:initial!important;margin:0!important;padding:0!important;font-size:.95rem!important;line-height:1!important;}
    .cm-logo,.cm-logo *{box-sizing:border-box;}
    .cm-logo-accent{color:#00D4FF!important;}

    :root { --primary: #6C63FF; --primary-dark: #5a52e0; --bg-dark: #0f0f14; --bg-card: #1a1a24; --border: #2d2d3a; --success: #00E676; --warning: #FF5252; --text-muted: #9e9e9e; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DM Sans', sans-serif; background: var(--bg-dark); color: #fff; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
    .card { width: 100%; max-width: 420px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 36px 32px; }
    .brand { display: flex; align-items: center; gap: 8px; color: var(--primary); font-weight: 700; font-size: 1.2rem; margin-bottom: 20px; }
    h2 { margin-bottom: 8px; }
    p.sub { color: var(--text-muted); margin-bottom: 24px; font-size: 0.9rem; }
    .toggle { display: flex; background: var(--bg-dark); border: 1px solid var(--border); border-radius: 10px; padding: 4px; margin-bottom: 20px; }
    .toggle button { flex: 1; padding: 10px; background: none; border: none; color: var(--text-muted); border-radius: 8px; cursor: pointer; font-weight: 600; }
    .toggle button.active { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff; }
    label { display: block; font-size: 0.85rem; margin-bottom: 6px; font-weight: 600; }
    input { width: 100%; padding: 12px 14px; margin-bottom: 16px; background: var(--bg-dark); border: 1px solid var(--border); border-radius: 8px; color: #fff; font-size: 0.95rem; }
    button.submit { width: 100%; padding: 12px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; border-radius: 8px; color: #fff; font-weight: 600; cursor: pointer; font-size: 0.95rem; }
    .msg { padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 0.85rem; display: none; }
    .msg.ok { background: rgba(0,230,118,0.12); border: 1px solid var(--success); color: var(--success); }
    .msg.err { background: rgba(255,82,82,0.12); border: 1px solid var(--warning); color: var(--warning); }
    .msg a { color: inherit; text-decoration: underline; word-break: break-all; }
    .back { display: block; text-align: center; margin-top: 16px; color: var(--text-muted); font-size: 0.85rem; }
    .back a { color: var(--primary); text-decoration: none; }
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
    <div class="cm-logo"><span class="cm-logo-badge"><i class="fas fa-microchip"></i></span>Compu<span class="cm-logo-accent">Match</span></div>
    <h2>Forgot Password?</h2>
    <p class="sub">Enter your account details and we'll send you a reset link.</p>

    <div class="toggle">
        <button type="button" id="tabUser" class="active" onclick="setType('user')"><i class="fas fa-user"></i> Client</button>
        <button type="button" id="tabStore" onclick="setType('store')"><i class="fas fa-store"></i> Store</button>
    </div>

    <div class="msg" id="msg"></div>

    <div id="fieldEmail">
        <label>Email Address</label>
        <input type="email" id="email" placeholder="you@example.com">
    </div>
    <div id="fieldStoreId" style="display:none;">
        <label>Store ID</label>
        <input type="text" id="storeId" placeholder="e.g. pchub">
    </div>

    <button class="submit" onclick="submitRequest()">Send Reset Link</button>
    <span class="back">Remembered it? <a href="login.php">Back to Login</a></span>
</div>

<script>
let currentType = 'user';
function setType(type) {
    currentType = type;
    document.getElementById('tabUser').classList.toggle('active', type === 'user');
    document.getElementById('tabStore').classList.toggle('active', type === 'store');
    document.getElementById('fieldEmail').style.display = type === 'user' ? 'block' : 'none';
    document.getElementById('fieldStoreId').style.display = type === 'store' ? 'block' : 'none';
}

// Opened from the store login ("?type=store&store_id=pchub") → start on the Store tab.
(function () {
    const q = new URLSearchParams(window.location.search);
    if (q.get('type') === 'store') {
        setType('store');
        const id = q.get('store_id');
        if (id) document.getElementById('storeId').value = id;
    }
})();

async function submitRequest() {
    const msg = document.getElementById('msg');
    msg.style.display = 'none';

    const body = { action: 'request_password_reset', type: currentType };
    if (currentType === 'user') {
        body.email = document.getElementById('email').value.trim();
        if (!body.email) { showMsg('Please enter your email.', false); return; }
    } else {
        body.store_id = document.getElementById('storeId').value.trim().toLowerCase();
        if (!body.store_id) { showMsg('Please enter your Store ID.', false); return; }
    }

    try {
        const res = await fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        });
        const data = await res.json();
        if (!data.ok) { showMsg(data.error || 'Something went wrong.', false); return; }

        let text = data.message || 'If that account exists, a reset link has been sent.';
        showMsg(text, true);
    } catch (e) {
        showMsg('Could not reach the server. Please try again later.', false);
    }
}

function showMsg(html, ok) {
    const msg = document.getElementById('msg');
    msg.innerHTML = html;
    msg.className = 'msg ' + (ok ? 'ok' : 'err');
    msg.style.display = 'block';
}
</script>
</body>
</html>
