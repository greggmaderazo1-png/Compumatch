<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CompuMatch – Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    /* ===== CompuMatch shared brand logo ===== */
    .cm-logo{display:inline-flex!important;align-items:center;gap:10px;font-weight:700;font-size:1.25rem;color:#fff!important;text-decoration:none;line-height:1;background:none!important;-webkit-background-clip:initial!important;background-clip:initial!important;}
    .cm-logo-badge{width:34px;height:34px;border-radius:50%;flex-shrink:0;background:radial-gradient(circle at 35% 30%,#12233e,#060a12)!important;border:2px solid #00D4FF;box-shadow:0 0 10px rgba(0,212,255,.55),inset 0 0 6px rgba(0,212,255,.35);display:flex;align-items:center;justify-content:center;color:#00D4FF!important;font-size:.95rem;}
    .cm-logo-badge i{color:#00D4FF!important;background:none!important;-webkit-background-clip:initial!important;margin:0!important;padding:0!important;font-size:.95rem!important;line-height:1!important;}
    .cm-logo,.cm-logo *{box-sizing:border-box;}
    .cm-logo-accent{color:#00D4FF!important;}

        :root {
            --primary: #6C63FF;
            --primary-dark: #5a52e0;
            --accent: #00D4FF;
            --success: #00E676;
            --warning: #FF5252;
            --bg-dark: #0f0f14;
            --bg-card: #1a1a24;
            --border: #2d2d3a;
            --border-focus: #6C63FF;
            --text-muted: #9e9e9e;
            --text-light: #e0e0e0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg-dark);
            color: #fff;
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #0b0b10;
        }

        /* animated grid bg */
        .grid-bg {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(108,99,255,0.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(108,99,255,0.07) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            from { background-position: 0 0; }
            to   { background-position: 50px 50px; }
        }

        /* glowing orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.25;
            animation: float 8s ease-in-out infinite alternate;
        }
        .orb-1 { width: 380px; height: 380px; background: var(--primary); top: -60px; left: -80px; }
        .orb-2 { width: 280px; height: 280px; background: var(--accent); bottom: -60px; right: -40px; animation-delay: 3s; }

        @keyframes float {
            from { transform: translateY(0) scale(1); }
            to   { transform: translateY(-30px) scale(1.05); }
        }

        .left-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 40px;
            max-width: 480px;
        }

        .brand-logo {
            font-family: 'Poppins', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .brand-logo i { color: var(--primary); background: none; -webkit-background-clip: unset; }

        .brand-tagline {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 56px;
            letter-spacing: 0.3px;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            text-align: left;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 16px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 16px 20px;
            animation: slideInLeft 0.6s ease both;
        }

        .feature-item:nth-child(1) { animation-delay: 0.1s; }
        .feature-item:nth-child(2) { animation-delay: 0.2s; }
        .feature-item:nth-child(3) { animation-delay: 0.3s; }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .feature-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(108,99,255,0.15);
            border: 1px solid rgba(108,99,255,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .feature-text h4 {
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .feature-text p {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        /* ── RIGHT PANEL ── */
        .right-panel {
            width: 480px;
            flex-shrink: 0;
            background: var(--bg-card);
            border-left: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            animation: fadeInRight 0.5s ease;
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .form-wrapper {
            width: 100%;
            max-width: 360px;
        }

        .form-header {
            margin-bottom: 36px;
        }

        .form-header h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.9rem;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .form-header p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .form-header p a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .form-header p a:hover { text-decoration: underline; }

        /* account-type toggle */
        .account-toggle {
            display: flex;
            gap: 6px;
            background: var(--bg-dark);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 5px;
            margin-bottom: 28px;
        }

        .account-toggle button {
            flex: 1;
            padding: 11px 8px;
            background: transparent;
            border: none;
            border-radius: 10px;
            color: var(--text-muted);
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .account-toggle button i { font-size: 0.95rem; }

        .account-toggle button.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            box-shadow: 0 4px 14px rgba(108,99,255,0.35);
        }

        .account-toggle button:not(.active):hover {
            color: var(--text-light);
            background: rgba(108,99,255,0.06);
        }

        /* helper to hide mode-specific blocks */
        .is-hidden { display: none !important; }

        /* store register hint */
        .store-hint {
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 18px;
        }

        .store-hint a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .store-hint a:hover { text-decoration: underline; }

        .demo-creds {
            background: rgba(108,99,255,0.08);
            border: 1px solid rgba(108,99,255,0.2);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .demo-creds b { color: var(--text-light); }

        /* input group */
        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-light);
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }


        .input-wrap input {
            width: 100%;
            padding: 13px 46px 13px 44px;
            background: var(--bg-dark);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            transition: border-color 0.3s, box-shadow 0.3s;
            outline: none;
        }

        .input-wrap input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(108,99,255,0.12);
        }

        .input-wrap input:focus + i,
        .input-wrap input:focus ~ i {
            color: var(--primary);
        }

        /* fix icon color on focus — target sibling */
        .input-wrap input:focus ~ .icon-left { color: var(--primary); }

        .icon-left {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.9rem;
            transition: color 0.3s;
            pointer-events: none;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .toggle-password:hover { color: var(--text-light); }

        /* The eye icon must never swallow the click meant for its button. */
        .toggle-password i { pointer-events: none; }

        /* Edge/IE draw their own "reveal password" eye inside the field,
           which overlapped ours. Hide it so only one eye shows. */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear { display: none; }

        /* options row */
        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            font-size: 0.85rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--text-muted);
        }

        .checkbox-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .forgot-link:hover { opacity: 0.7; }

        /* submit button */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 12px;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
            opacity: 0;
            transition: opacity 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(108,99,255,0.35);
        }

        .btn-submit:hover::after { opacity: 1; }

        .btn-submit:active { transform: translateY(0); }

        /* divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* social buttons */
        .social-buttons {
            display: flex;
            gap: 12px;
        }

        .btn-social {
            flex: 1;
            padding: 11px;
            background: var(--bg-dark);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            color: var(--text-light);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-social:hover {
            border-color: var(--primary);
            background: rgba(108,99,255,0.05);
        }

        /* error message */
        .error-msg {
            display: none;
            background: rgba(255,82,82,0.1);
            border: 1px solid rgba(255,82,82,0.3);
            border-radius: 10px;
            padding: 10px 14px;
            color: var(--warning);
            font-size: 0.85rem;
            margin-bottom: 16px;
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%,100% { transform: translateX(0); }
            25%      { transform: translateX(-6px); }
            75%      { transform: translateX(6px); }
        }

        /* success overlay */
        .success-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            z-index: 999;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        .success-box {
            background: var(--bg-card);
            border: 1px solid rgba(0,230,118,0.3);
            border-radius: 24px;
            padding: 40px;
            text-align: center;
            animation: popIn 0.4s cubic-bezier(0.34,1.56,0.64,1);
        }

        @keyframes popIn {
            from { opacity: 0; transform: scale(0.7); }
            to   { opacity: 1; transform: scale(1); }
        }

        .success-icon {
            width: 72px;
            height: 72px;
            background: rgba(0,230,118,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: var(--success);
        }

        .success-box h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.4rem;
            margin-bottom: 8px;
        }

        .success-box p { color: var(--text-muted); font-size: 0.9rem; }

        /* responsive */
        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; border: none; }
        }

        @media (max-width: 480px) {
            .right-panel { padding: 32px 24px; }
        }
    </style>
</head>
<body>

<!-- LEFT PANEL -->
<div class="left-panel">
    <div class="grid-bg"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="left-content">
        <div class="brand-logo">
            <a href="landing.php" class="cm-logo" title="Back to home"><span class="cm-logo-badge"><i class="fas fa-microchip"></i></span>Compu<span class="cm-logo-accent">Match</span></a>
        </div>
        <p class="brand-tagline">Smart PC Build Recommendation System</p>

        <div class="feature-list">
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
                <div class="feature-text">
                    <h4>Smart Compatibility Check</h4>
                    <p>Automatic CPU, motherboard & RAM verification</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                <div class="feature-text">
                    <h4>Budget Optimization</h4>
                    <p>Best price-to-performance for your budget</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-save"></i></div>
                <div class="feature-text">
                    <h4>Save & Compare Builds</h4>
                    <p>Keep track of all your PC configurations</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RIGHT PANEL -->
<div class="right-panel">
    <div class="form-wrapper">
        <div class="form-header">
            <h2 id="formTitle">Welcome back 👋</h2>
            <p id="formSubtitle">Don't have an account? <a href="signup.php">Sign up for free</a></p>
        </div>

        <!-- Account type toggle -->
        <div class="account-toggle">
            <button type="button" id="clientTab" class="active" onclick="setAccountType('client')">
                <i class="fas fa-user"></i> Client
            </button>
            <button type="button" id="storeTab" onclick="setAccountType('store')">
                <i class="fas fa-store"></i> Computer Store
            </button>
        </div>

        <div class="error-msg" id="errorMsg">
            <i class="fas fa-exclamation-circle"></i> Invalid username or password. Please try again.
        </div>

        <!-- Username (client mode) -->
        <div class="input-group" id="emailGroup">
            <label for="username">Username</label>
            <div class="input-wrap">
                <input type="text" id="username" placeholder="e.g. juandelacruz" autocomplete="username" autocapitalize="none" spellcheck="false">
                <span class="icon-left"><i class="fas fa-user"></i></span>
            </div>
        </div>

        <!-- Store ID (store mode) -->
        <div class="input-group is-hidden" id="storeIdGroup">
            <label for="storeId">Store ID</label>
            <div class="input-wrap">
                <input type="text" id="storeId" placeholder="e.g. pchub" autocomplete="off">
                <span class="icon-left"><i class="fas fa-store"></i></span>
            </div>
        </div>

        <!-- Password -->
        <div class="input-group">
            <label for="password">Password</label>
            <div class="input-wrap">
                <input type="password" id="password" placeholder="Enter your password" autocomplete="current-password">
                <span class="icon-left"><i class="fas fa-lock"></i></span>
                <button class="toggle-password" type="button" onclick="togglePassword('password', this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <!-- Options (client mode) -->
        <div class="options-row" id="optionsRow">
            <label class="checkbox-label">
                <input type="checkbox" id="rememberMe"> Remember me
            </label>
            <a href="forgot_password.php" class="forgot-link">Forgot password?</a>
        </div>

        <!-- Options (store mode): forgot password for Computer Store accounts -->
        <div class="options-row is-hidden" id="storeOptionsRow" style="justify-content:flex-end;">
            <a href="forgot_password.php?type=store" class="forgot-link" onclick="goStoreForgot(event)">Forgot password?</a>
        </div>

        <!-- Submit -->
        <button class="btn-submit" id="submitBtn" onclick="handleLogin()">
            Sign In &nbsp;<i class="fas fa-arrow-right"></i>
        </button>

        <!-- Client-only extras (social login removed) -->
        <div id="clientExtras">        </div>

        <!-- Store-only: register link -->
        <div class="store-hint is-hidden" id="storeExtras">
            New store? <a href="signup.php">Register your store here</a>
        </div>

        <div style="text-align:center; margin-top: 18px;">
            <a href="systemadmin.php" style="font-size: 0.8rem; color: var(--text-muted); text-decoration: none;">
                <i class="fas fa-user-shield"></i> System Admin
            </a>
        </div>
    </div>
</div>

<!-- SUCCESS OVERLAY -->
<div class="success-overlay" id="successOverlay">
    <div class="success-box">
        <div class="success-icon"><i class="fas fa-check"></i></div>
        <h3 id="successTitle">Login Successful!</h3>
        <p id="successText">Redirecting to your dashboard...</p>
    </div>
</div>

<script>
    let currentAccountType = 'client';

    // ── Demo store data (kept in sync with admin.html) ──
    // Seeds localStorage so store login works even before the admin portal
    // has been opened. If stores already exist, this leaves them untouched.
    function seedDemoStores() {
        const existing = JSON.parse(localStorage.getItem('compumatch_stores') || '{}');
        if (Object.keys(existing).length > 0) return;

        const stores = {
            'pchub': {
                id: 'pchub',
                name: 'PC Hub Calbayog',
                address: 'Calbayog City, Samar',
                contact: '09123456789',
                email: 'pchub@example.com',
                password: 'store123',
                description: 'Your trusted computer shop in Calbayog City',
                inventory: [
                    { id: 'cpu1', category: 'cpu', name: 'Intel Core i5-13400', brand: 'Intel', price: 13500, stock: 10, lowStock: 5, specs: '10 cores, up to 4.6GHz', sold: 0 },
                    { id: 'cpu2', category: 'cpu', name: 'AMD Ryzen 5 7600X', brand: 'AMD', price: 14500, stock: 8, lowStock: 5, specs: '6 cores, up to 5.3GHz', sold: 0 },
                    { id: 'gpu1', category: 'gpu', name: 'NVIDIA RTX 4060', brand: 'NVIDIA', price: 21500, stock: 5, lowStock: 3, specs: '8GB GDDR6', sold: 0 },
                    { id: 'gpu2', category: 'gpu', name: 'AMD RX 6700 XT', brand: 'AMD', price: 24500, stock: 3, lowStock: 3, specs: '12GB GDDR6', sold: 0 },
                    { id: 'mb1', category: 'motherboard', name: 'MSI PRO B760M-A', brand: 'MSI', price: 7500, stock: 7, lowStock: 3, specs: 'LGA1700, DDR5', sold: 0 },
                    { id: 'ram1', category: 'ram', name: 'Corsair Vengeance 32GB DDR5', brand: 'Corsair', price: 8900, stock: 12, lowStock: 5, specs: '32GB, 6000MHz', sold: 0 },
                    { id: 'storage1', category: 'storage', name: 'Samsung 980 Pro 1TB NVMe', brand: 'Samsung', price: 6800, stock: 15, lowStock: 5, specs: 'NVMe PCIe 4.0', sold: 0 },
                    { id: 'psu1', category: 'psu', name: 'Corsair RM750e', brand: 'Corsair', price: 7500, stock: 6, lowStock: 3, specs: '750W Gold', sold: 0 }
                ],
                orders: []
            },
            'techhaven': {
                id: 'techhaven',
                name: 'Tech Haven',
                address: 'Calbayog City, Samar',
                contact: '09876543210',
                email: 'techhaven@example.com',
                password: 'store123',
                description: 'Latest tech and gaming PC parts',
                inventory: [
                    { id: 'cpu3', category: 'cpu', name: 'Intel Core i7-13700K', brand: 'Intel', price: 22500, stock: 4, lowStock: 3, specs: '16 cores, up to 5.4GHz', sold: 0 },
                    { id: 'gpu3', category: 'gpu', name: 'NVIDIA RTX 4070', brand: 'NVIDIA', price: 38500, stock: 3, lowStock: 2, specs: '12GB GDDR6X', sold: 0 },
                    { id: 'ram2', category: 'ram', name: 'G.Skill Trident 32GB DDR5', brand: 'G.Skill', price: 8900, stock: 8, lowStock: 3, specs: '32GB, 6000MHz RGB', sold: 0 },
                    { id: 'storage2', category: 'storage', name: 'WD Black 1TB NVMe', brand: 'WD', price: 5500, stock: 10, lowStock: 3, specs: 'NVMe PCIe 4.0', sold: 0 },
                    { id: 'psu2', category: 'psu', name: 'Seasonic Focus 750W', brand: 'Seasonic', price: 6800, stock: 5, lowStock: 2, specs: '750W Gold', sold: 0 }
                ],
                orders: []
            }
        };
        localStorage.setItem('compumatch_stores', JSON.stringify(stores));
    }

    // ── Switch between Client and Computer Store login ──
    function setAccountType(type) {
        currentAccountType = type;
        const isStore = type === 'store';

        document.getElementById('clientTab').classList.toggle('active', !isStore);
        document.getElementById('storeTab').classList.toggle('active', isStore);

        // Swap visible fields
        document.getElementById('emailGroup').classList.toggle('is-hidden', isStore);
        document.getElementById('storeIdGroup').classList.toggle('is-hidden', !isStore);

        // Swap supporting UI
        document.getElementById('optionsRow').classList.toggle('is-hidden', isStore);
        document.getElementById('storeOptionsRow').classList.toggle('is-hidden', !isStore);
        document.getElementById('clientExtras').classList.toggle('is-hidden', isStore);
        document.getElementById('storeExtras').classList.toggle('is-hidden', !isStore);

        // Update copy
        document.getElementById('formTitle').textContent = isStore ? 'Store Admin Login 🛒' : 'Welcome back 👋';
        document.getElementById('formSubtitle').innerHTML = isStore
            ? 'Manage your inventory and orders in the Admin Portal'
            : "Don't have an account? <a href=\"signup.php\">Sign up for free</a>";
        document.getElementById('submitBtn').innerHTML = isStore
            ? 'Login to Admin Portal &nbsp;<i class="fas fa-arrow-right"></i>'
            : 'Sign In &nbsp;<i class="fas fa-arrow-right"></i>';

        // Reset error
        document.getElementById('errorMsg').style.display = 'none';
    }

    // Store "Forgot password?" — opens the reset page on the Store tab and
    // carries over the Store ID already typed, so they don't retype it.
    function goStoreForgot(e) {
        e.preventDefault();
        const id = document.getElementById('storeId').value.trim().toLowerCase();
        window.location.href = 'forgot_password.php?type=store' + (id ? '&store_id=' + encodeURIComponent(id) : '');
    }

    function showError(message) {
        const errorMsg = document.getElementById('errorMsg');
        errorMsg.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + message;
        errorMsg.style.display = 'flex';
        errorMsg.style.alignItems = 'center';
        errorMsg.style.gap = '8px';
    }

    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    function handleLogin() {
        document.getElementById('errorMsg').style.display = 'none';
        if (currentAccountType === 'store') {
            handleStoreLogin();
        } else {
            handleClientLogin();
        }
    }

    // Clean up passwords that older versions saved in this browser.
    try {
        const cur = JSON.parse(localStorage.getItem('compumatch_current_store') || 'null');
        if (cur && cur.password) { delete cur.password; localStorage.setItem('compumatch_current_store', JSON.stringify(cur)); }
        const us = JSON.parse(localStorage.getItem('compumatch_users') || '{}');
        let changed = false;
        Object.values(us).forEach(u => { if (u && u.password) { delete u.password; changed = true; } });
        if (changed) localStorage.setItem('compumatch_users', JSON.stringify(us));
        const st = JSON.parse(localStorage.getItem('compumatch_stores') || '{}');
        changed = false;
        Object.values(st).forEach(x => { if (x && x.password) { delete x.password; changed = true; } });
        if (changed) localStorage.setItem('compumatch_stores', JSON.stringify(st));
    } catch (e) {}

    // ── CLIENT login → customer site (by USERNAME) ──
    async function handleClientLogin() {
        const username = document.getElementById('username').value.trim().toLowerCase();
        const password = document.getElementById('password').value;

        if (!username || !password) {
            showError('Please fill in all fields.');
            return;
        }

        let account = null;

        // Verify against the real database first (works from any device).
        try {
            const res = await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'login_user', username, password })
            });
            const data = await res.json();
            if (data.ok) {
                account = data.user;
            } else {
                showError(data.error || 'Invalid username or password.');
                return;
            }
        } catch (e) {
            // Passwords are only checked by the server (never stored in the browser).
            showError('Could not reach the server. Please check your connection and try again.');
            return;
        }

        // The rest of the site still identifies the account by its email
        // behind the scenes (saved builds, profile settings), so keep it.
        const email = account.email || '';
        localStorage.setItem('compumatch_logged_in', 'true');
        localStorage.setItem('compumatch_user_email', email);
        localStorage.setItem('compumatch_user_username', account.username || username);

        // Restore the saved display name for this account
        const savedName = account.name;
        if (savedName) localStorage.setItem('compumatch_user_name', savedName);
        else localStorage.removeItem('compumatch_user_name');

        document.getElementById('successTitle').textContent = 'Login Successful!';
        document.getElementById('successText').textContent = 'Redirecting to your dashboard...';
        document.getElementById('successOverlay').style.display = 'flex';
        setTimeout(() => { window.location.href = 'index.php'; }, 1500);
    }

    // ── STORE login → admin portal ──
    async function handleStoreLogin() {
        const storeId = document.getElementById('storeId').value.trim().toLowerCase();
        const password = document.getElementById('password').value;

        if (!storeId || !password) {
            showError('Please enter your Store ID and password.');
            return;
        }

        let store = null;

        try {
            const res = await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'login_store', store_id: storeId, password })
            });
            const data = await res.json();
            if (data.ok) {
                store = data.store;
            } else {
                showError(data.error || 'Invalid Store ID or password.');
                return;
            }
        } catch (e) {
            showError('Could not reach the server. Please check your connection and try again.');
            return;
        }

        // The real login is the server session set by login_store. This
        // only remembers WHICH store is open (no password) for the portal.
        localStorage.setItem('compumatch_current_store', JSON.stringify({ id: store.id, name: store.name }));

        document.getElementById('successTitle').textContent = 'Welcome, ' + store.name + '!';
        document.getElementById('successText').textContent = 'Opening your Admin Portal...';
        document.getElementById('successOverlay').style.display = 'flex';
        setTimeout(() => { window.location.href = 'admin.php'; }, 1500);
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Allow Enter key to submit
    document.addEventListener('keydown', e => {
        if (e.key === 'Enter') handleLogin();
    });

    // Seed demo stores on load so store login always works
    seedDemoStores();
</script>
<script>
// Lightweight usage ping for System Admin's Analytics tab. Fails silently
// if the API/DB is unreachable so it never blocks the page.
(function () {
    function currentStoreId() {
        try {
            var cur = JSON.parse(localStorage.getItem('compumatch_current_store') || 'null');
            return cur && cur.id ? cur.id : null;
        } catch (e) { return null; }
    }
    function ping(action) {
        try {
            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: action, page: 'login', store_id: currentStoreId() })
            }).catch(function () {});
        } catch (e) {}
    }
    ping('track_visit');
    setInterval(function () { ping('heartbeat'); }, 120000); // every 2 min (keeps hosting load low)
})();
</script>
</body>
</html>
