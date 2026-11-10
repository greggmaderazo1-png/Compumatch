<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CompuMatch – Create Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
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
            width: 420px;
            flex-shrink: 0;
            background: var(--bg-card);
            border-right: 1px solid var(--border);
            display: flex;
            align-items: flex-start; /* fallback: never crop content, always scrollable */
            align-items: safe center; /* nicer centering on browsers that support it */
            justify-content: center;
            padding: 48px 40px;
            overflow-y: auto;
            max-height: 100vh;
            animation: fadeInLeft 0.5s ease;
            scrollbar-width: thin;               /* Firefox */
            scrollbar-color: var(--primary) var(--bg-dark); /* Firefox: thumb, track */
        }

        /* Chrome / Edge / Safari scrollbar */
        .left-panel::-webkit-scrollbar { width: 8px; }
        .left-panel::-webkit-scrollbar-track { background: var(--bg-dark); }
        .left-panel::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 8px;
        }
        .left-panel::-webkit-scrollbar-thumb:hover { background: var(--primary-dark); }

        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* ── RIGHT PANEL ── */
        .right-panel {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #0b0b10;
        }

        .grid-bg {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,212,255,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,212,255,0.06) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            from { background-position: 0 0; }
            to   { background-position: 50px 50px; }
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.2;
            animation: float 9s ease-in-out infinite alternate;
        }

        .orb-1 { width: 400px; height: 400px; background: var(--accent); bottom: -100px; right: -80px; }
        .orb-2 { width: 260px; height: 260px; background: var(--primary); top: -60px; right: 120px; animation-delay: 4s; }

        @keyframes float {
            from { transform: translateY(0) scale(1); }
            to   { transform: translateY(-28px) scale(1.04); }
        }

        .right-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 40px;
            max-width: 500px;
        }

        .brand-logo {
            font-family: 'Poppins', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent), var(--primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .brand-logo i { color: var(--accent); background: none; -webkit-background-clip: unset; }

        .brand-tagline {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 48px;
        }

        /* stats */
        .stats-row {
            display: flex;
            gap: 24px;
            justify-content: center;
            margin-bottom: 48px;
        }

        .stat-box {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px;
            padding: 20px 28px;
            text-align: center;
            animation: popUp 0.5s ease both;
        }

        .stat-box:nth-child(1) { animation-delay: 0.1s; }
        .stat-box:nth-child(2) { animation-delay: 0.2s; }
        .stat-box:nth-child(3) { animation-delay: 0.3s; }

        @keyframes popUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .stat-num {
            font-family: 'Poppins', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--accent);
        }

        .stat-label {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* testimonial */
        .testimonial {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 20px;
            padding: 28px;
            text-align: left;
        }

        .testimonial p {
            font-size: 0.9rem;
            color: var(--text-light);
            line-height: 1.7;
            margin-bottom: 16px;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .author-info span {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .author-info small {
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        /* ── FORM ── */
        .form-wrapper {
            width: 100%;
            max-width: 360px;
        }

        .form-header {
            margin-bottom: 28px;
        }

        .form-header h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.8rem;
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
            margin-bottom: 22px;
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

        .is-hidden { display: none !important; }

        .field-hint {
            display: block;
            font-size: 0.72rem;
            color: var(--text-muted);
            margin-top: 5px;
        }

        .input-wrap textarea {
            width: 100%;
            padding: 12px 14px;
            background: var(--bg-dark);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.92rem;
            outline: none;
            resize: vertical;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .input-wrap textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(108,99,255,0.12);
        }

        .name-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .input-group {
            margin-bottom: 16px;
        }

        .input-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--text-light);
            margin-bottom: 7px;
        }

        .input-wrap {
            position: relative;
        }

        .icon-left {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.85rem;
            pointer-events: none;
            transition: color 0.3s;
        }

        .input-wrap input {
            width: 100%;
            padding: 12px 42px 12px 40px;
            background: var(--bg-dark);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.92rem;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .input-wrap input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(108,99,255,0.12);
        }

        .input-wrap input:focus ~ .icon-left { color: var(--primary); }

        /* When icon is BEFORE the input in DOM, use adjacent sibling is tricky.
           Instead we flip: icon inside wrap as first child and use :focus-within */
        .input-wrap:focus-within .icon-left { color: var(--primary); }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            font-size: 0.85rem;
            transition: color 0.2s;
        }

        .toggle-password:hover { color: var(--text-light); }

        /* The eye icon must never swallow the click meant for its button. */
        .toggle-password i { pointer-events: none; }

        /* Edge/IE draw their own "reveal password" eye inside the field,
           which overlapped ours. Hide it so only one eye shows. */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear { display: none; }

        /* password strength */
        .password-strength {
            margin-top: 8px;
            display: none;
        }

        .strength-bar {
            display: flex;
            gap: 4px;
            margin-bottom: 4px;
        }

        .strength-segment {
            height: 3px;
            flex: 1;
            border-radius: 2px;
            background: var(--border);
            transition: background 0.3s;
        }

        .strength-label {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* terms */
        .terms-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        .terms-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .terms-row a {
            color: var(--primary);
            text-decoration: none;
        }

        /* submit */
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
            margin-bottom: 20px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(108,99,255,0.35);
        }

        .btn-submit:active { transform: translateY(0); }

        /* divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
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
            font-size: 0.83rem;
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

        /* error */
        .error-msg {
            display: none;
            background: rgba(255,82,82,0.1);
            border: 1px solid rgba(255,82,82,0.3);
            border-radius: 10px;
            padding: 10px 14px;
            color: var(--warning);
            font-size: 0.83rem;
            margin-bottom: 14px;
            gap: 8px;
            align-items: center;
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%,100% { transform: translateX(0); }
            25%      { transform: translateX(-5px); }
            75%      { transform: translateX(5px); }
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
            padding: 48px 40px;
            text-align: center;
            max-width: 360px;
            animation: popIn 0.4s cubic-bezier(0.34,1.56,0.64,1);
        }

        @keyframes popIn {
            from { opacity: 0; transform: scale(0.7); }
            to   { opacity: 1; transform: scale(1); }
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: rgba(0,230,118,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.2rem;
            color: var(--success);
        }

        .success-box h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            margin-bottom: 8px;
        }

        .success-box p { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 24px; }

        .btn-goto {
            display: inline-block;
            padding: 12px 32px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 30px;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.95rem;
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-goto:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(108,99,255,0.35);
        }

        /* ── store location map ── */
        .map-box {
            height: 210px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            background: var(--bg-dark);
        }
        .map-box.has-pin { border-color: var(--accent); }
        .map-tools {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 8px;
        }
        .btn-mini {
            padding: 7px 12px;
            background: transparent;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            color: var(--text-light);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.78rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: border-color 0.2s, color 0.2s;
        }
        .btn-mini:hover { border-color: var(--accent); color: var(--accent); }
        .btn-mini:disabled { opacity: 0.5; cursor: wait; }
        .pin-readout { font-size: 0.72rem; color: var(--text-muted); text-align: right; }
        .pin-readout.set { color: var(--accent); }
        .cm-pin {
            width: 22px; height: 22px;
            border-radius: 50% 50% 50% 0;
            background: var(--accent);
            border: 3px solid #fff;
            transform: rotate(-45deg);
            box-shadow: 0 2px 8px rgba(0,0,0,0.5);
        }
        .leaflet-container { font-family: 'DM Sans', sans-serif; }

        /* ── business permit upload ── */
        .input-group label.permit-drop {
            display: flex;
            margin-bottom: 0;
            align-items: center;
            gap: 12px;
            padding: 14px;
            background: var(--bg-dark);
            border: 1.5px dashed var(--border);
            border-radius: 12px;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
        }
        .permit-drop:hover, .permit-drop.dragover {
            border-color: var(--primary);
            background: rgba(108,99,255,0.06);
        }
        .permit-drop.has-file { border-style: solid; border-color: var(--success); }
        .permit-drop input[type="file"] { display: none; }
        .permit-thumb {
            width: 56px; height: 56px;
            flex-shrink: 0;
            border-radius: 8px;
            background: rgba(255,255,255,0.04);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted);
            font-size: 1.3rem;
            overflow: hidden;
        }
        .permit-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .permit-text { flex: 1; min-width: 0; font-size: 0.82rem; color: var(--text-light); }
        .permit-text small { display: block; color: var(--text-muted); font-size: 0.72rem; margin-top: 3px; }
        .permit-name { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .permit-remove {
            background: none; border: none; color: var(--text-muted);
            cursor: pointer; padding: 6px; font-size: 0.9rem;
        }
        .permit-remove:hover { color: var(--warning); }

        /* ── permit crop step ── */
        .crop-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1000;
            background: rgba(0,0,0,0.75);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .crop-box {
            width: 100%;
            max-width: 640px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
        }
        .crop-box h4 { font-family: 'Poppins', sans-serif; font-size: 1.05rem; margin-bottom: 4px; }
        .crop-box p { color: var(--text-muted); font-size: 0.82rem; margin-bottom: 12px; }
        .crop-stage { height: min(60vh, 420px); background: #000; border-radius: 10px; overflow: hidden; }
        .crop-stage img { display: block; max-width: 100%; }
        .crop-actions { display: flex; gap: 8px; margin-top: 12px; align-items: center; }
        .crop-actions .spacer { flex: 1; }
        .btn-mini-primary { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-color: transparent; color: #fff; }
        .btn-mini-primary:hover { color: #fff; border-color: transparent; }

        .btn-submit:disabled { opacity: 0.6; cursor: wait; transform: none; box-shadow: none; }

        /* responsive */
        @media (max-width: 1000px) {
            .right-panel { display: none; }
            .left-panel { width: 100%; border: none; }
        }

        @media (max-width: 480px) {
            .left-panel { padding: 32px 20px; }
            .name-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- LEFT PANEL – FORM -->
<div class="left-panel">
    <div class="form-wrapper">
        <div class="form-header">
            <h2 id="formTitle">Create account 🚀</h2>
            <p id="formSubtitle">Already have one? <a href="login.php">Sign in here</a></p>
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
            <i class="fas fa-exclamation-circle"></i>
            <span id="errorText">Please fill in all required fields.</span>
        </div>

        <!-- ════════ CLIENT FIELDS ════════ -->
        <div id="clientFields">
            <!-- Name Row -->
            <div class="name-row">
                <div class="input-group">
                    <label>First Name</label>
                    <div class="input-wrap">
                        <span class="icon-left"><i class="fas fa-user"></i></span>
                        <input type="text" id="firstName" placeholder="Juan">
                    </div>
                </div>
                <div class="input-group">
                    <label>Last Name</label>
                    <div class="input-wrap">
                        <span class="icon-left"><i class="fas fa-user"></i></span>
                        <input type="text" id="lastName" placeholder="Dela Cruz">
                    </div>
                </div>
            </div>

            <!-- Username (used to log in) -->
            <div class="input-group">
                <label>Username</label>
                <div class="input-wrap">
                    <span class="icon-left"><i class="fas fa-at"></i></span>
                    <input type="text" id="username" placeholder="e.g. juandelacruz" autocomplete="username" autocapitalize="none" spellcheck="false" maxlength="30">
                </div>
                <small class="field-hint">You'll use this to log in. Lowercase letters, numbers, or _ (3–30 characters).</small>
            </div>

            <!-- Email -->
            <div class="input-group">
                <label>Email Address</label>
                <div class="input-wrap">
                    <span class="icon-left"><i class="fas fa-envelope"></i></span>
                    <input type="email" id="email" placeholder="you@example.com" autocomplete="email">
                </div>
                <small class="field-hint">For verifying your account and resetting your password.</small>
            </div>

            <!-- Password -->
            <div class="input-group">
                <label>Password</label>
                <div class="input-wrap">
                    <span class="icon-left"><i class="fas fa-lock"></i></span>
                    <input type="password" id="password" placeholder="Min. 8 characters" oninput="checkStrength(this.value)">
                    <button class="toggle-password" type="button" onclick="togglePassword('password', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-strength" id="strengthMeter">
                    <div class="strength-bar">
                        <div class="strength-segment" id="s1"></div>
                        <div class="strength-segment" id="s2"></div>
                        <div class="strength-segment" id="s3"></div>
                        <div class="strength-segment" id="s4"></div>
                    </div>
                    <span class="strength-label" id="strengthLabel">Weak</span>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="input-group">
                <label>Confirm Password</label>
                <div class="input-wrap">
                    <span class="icon-left"><i class="fas fa-lock"></i></span>
                    <input type="password" id="confirmPassword" placeholder="Repeat your password">
                    <button class="toggle-password" type="button" onclick="togglePassword('confirmPassword', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- ════════ STORE FIELDS ════════ -->
        <div id="storeFields" class="is-hidden">
            <!-- Store Name -->
            <div class="input-group">
                <label>Store Name <span style="color:var(--warning);">*</span></label>
                <div class="input-wrap">
                    <span class="icon-left"><i class="fas fa-store"></i></span>
                    <input type="text" id="storeName" placeholder="e.g. PC Hub Calbayog">
                </div>
                <small class="field-hint">This will be visible to customers</small>
            </div>

            <!-- Store ID -->
            <div class="input-group">
                <label>Store ID (Username) <span style="color:var(--warning);">*</span></label>
                <div class="input-wrap">
                    <span class="icon-left"><i class="fas fa-at"></i></span>
                    <input type="text" id="storeId" placeholder="e.g. pchub">
                </div>
                <small class="field-hint">Lowercase letters and numbers only, no spaces</small>
            </div>

            <!-- Address + Contact -->
            <div class="name-row">
                <div class="input-group">
                    <label>Store Address</label>
                    <div class="input-wrap">
                        <span class="icon-left"><i class="fas fa-location-dot"></i></span>
                        <input type="text" id="storeAddress" placeholder="Full address">
                    </div>
                </div>
                <div class="input-group">
                    <label>Contact Number</label>
                    <div class="input-wrap">
                        <span class="icon-left"><i class="fas fa-phone"></i></span>
                        <input type="text" id="storeContact" placeholder="09xxxxxxxxx">
                    </div>
                </div>
            </div>

            <!-- Store location pin -->
            <div class="input-group">
                <label>Store Location on Map <span style="color:var(--warning);">*</span></label>
                <div class="map-box" id="storeMap" aria-label="Map: tap to pin your store"></div>
                <div class="map-tools">
                    <button type="button" class="btn-mini" id="locateBtn" onclick="useMyLocation()">
                        <i class="fas fa-location-crosshairs"></i> Use my location
                    </button>
                    <span class="pin-readout" id="pinReadout">Tap the map to pin your store</span>
                </div>
                <small class="field-hint">Drag the pin to adjust. Customers will use this to find your shop.</small>
                <input type="hidden" id="storeLat">
                <input type="hidden" id="storeLng">
            </div>

            <!-- Store Email -->
            <div class="input-group">
                <label>Email Address</label>
                <div class="input-wrap">
                    <span class="icon-left"><i class="fas fa-envelope"></i></span>
                    <input type="email" id="storeEmail" placeholder="store@example.com" autocomplete="email">
                </div>
            </div>

            <!-- Store Password -->
            <div class="input-group">
                <label>Password <span style="color:var(--warning);">*</span></label>
                <div class="input-wrap">
                    <span class="icon-left"><i class="fas fa-lock"></i></span>
                    <input type="password" id="storePassword" placeholder="Min. 6 characters">
                    <button class="toggle-password" type="button" onclick="togglePassword('storePassword', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Confirm Store Password -->
            <div class="input-group">
                <label>Confirm Password <span style="color:var(--warning);">*</span></label>
                <div class="input-wrap">
                    <span class="icon-left"><i class="fas fa-lock"></i></span>
                    <input type="password" id="storeConfirmPassword" placeholder="Repeat your password">
                    <button class="toggle-password" type="button" onclick="togglePassword('storeConfirmPassword', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Description -->
            <div class="input-group">
                <label>Store Description (Optional)</label>
                <div class="input-wrap">
                    <textarea id="storeDescription" rows="2" placeholder="Tell customers about your store..."></textarea>
                </div>
            </div>

            <!-- Business permit -->
            <div class="input-group">
                <label>Business Permit or DTI Certificate <span style="color:var(--warning);">*</span></label>
                <label class="permit-drop" id="permitDrop" for="permitFile">
                    <input type="file" id="permitFile" accept="image/jpeg,image/png,image/webp" onchange="onPermitChosen(this.files[0])">
                    <div class="permit-thumb" id="permitThumb"><i class="fas fa-file-image"></i></div>
                    <div class="permit-text">
                        <div class="permit-name" id="permitName">Upload a clear photo of your document</div>
                        <small id="permitMeta">JPG, PNG, or WEBP · max 5 MB</small>
                    </div>
                    <button type="button" class="permit-remove is-hidden" id="permitRemove" onclick="clearPermit(event)" title="Remove">
                        <i class="fas fa-xmark"></i>
                    </button>
                </label>
                <small class="field-hint">Upload your <strong>Business Permit</strong> or <strong>DTI Certificate of Business Name Registration</strong> (the paper document — permit plates aren't accepted). Take the photo in bright light, without flash, with the whole document in view. A valid, unexpired document is approved right away; otherwise an admin will review it.</small>
            </div>
        </div>

        <!-- Terms -->
        <div class="terms-row">
            <input type="checkbox" id="terms">
            <label for="terms">
                I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
            </label>
        </div>

        <!-- Submit -->
        <button class="btn-submit" id="submitBtn" onclick="handleSignup()">
            Create Account &nbsp;<i class="fas fa-user-plus"></i>
        </button>

        <!-- Client-only extras (social sign-up removed) -->
        <div id="clientExtras">        </div>
    </div>
</div>

<!-- RIGHT PANEL – BRANDING -->
<div class="right-panel">
    <div class="grid-bg"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="right-content">
        <div class="brand-logo">
            <a href="landing.php" class="cm-logo" title="Back to home"><span class="cm-logo-badge"><i class="fas fa-microchip"></i></span>Compu<span class="cm-logo-accent">Match</span></a>
        </div>
        <p class="brand-tagline">Join thousands of builders already using CompuMatch</p>

        <div class="stats-row">
            <div class="stat-box">
                <div class="stat-num">5K+</div>
                <div class="stat-label">Builds Created</div>
            </div>
            <div class="stat-box">
                <div class="stat-num">98%</div>
                <div class="stat-label">Compatibility</div>
            </div>
            <div class="stat-box">
                <div class="stat-num">Free</div>
                <div class="stat-label">Always</div>
            </div>
        </div>

        <div class="testimonial">
            <p>"CompuMatch helped me build my first gaming PC without any compatibility issues. Saved me hours of research!"</p>
            <div class="testimonial-author">
                <div class="avatar">MR</div>
                <div class="author-info">
                    <span>Miguel Reyes</span>
                    <small>NWSSU Student, Calbayog City</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SUCCESS OVERLAY -->
<!-- PERMIT CROP STEP -->
<div class="crop-overlay" id="cropOverlay" role="dialog" aria-modal="true" aria-labelledby="cropTitle">
    <div class="crop-box">
        <h4 id="cropTitle">Crop your document</h4>
        <p>Drag the box so only the Business Permit or DTI Certificate is inside — no table, hands, or background. This helps us read it automatically.</p>
        <div class="crop-stage"><img id="cropImage" alt="Permit to crop"></div>
        <div class="crop-actions">
            <button type="button" class="btn-mini" onclick="cropRotate()"><i class="fas fa-rotate-right"></i> Rotate</button>
            <span class="spacer"></span>
            <button type="button" class="btn-mini" onclick="cropCancel()">Cancel</button>
            <button type="button" class="btn-mini btn-mini-primary" onclick="cropConfirm()"><i class="fas fa-check"></i> Use this photo</button>
        </div>
    </div>
</div>

<div class="success-overlay" id="successOverlay">
    <div class="success-box">
        <div class="success-icon"><i class="fas fa-check"></i></div>
        <h3 id="successTitle">Account Created!</h3>
        <p id="successText">Welcome to CompuMatch! Your account has been successfully created.</p>
        <button class="btn-goto" id="successBtn" onclick="window.location.href='login.php'">
            Go to Sign In &nbsp;<i class="fas fa-arrow-right"></i>
        </button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5.1.1/dist/tesseract.min.js"></script>
<script>
    let currentAccountType = 'client';

    // ── Switch between Client and Computer Store sign-up ──
    function setAccountType(type) {
        currentAccountType = type;
        const isStore = type === 'store';

        document.getElementById('clientTab').classList.toggle('active', !isStore);
        document.getElementById('storeTab').classList.toggle('active', isStore);

        document.getElementById('clientFields').classList.toggle('is-hidden', isStore);
        document.getElementById('storeFields').classList.toggle('is-hidden', !isStore);
        document.getElementById('clientExtras').classList.toggle('is-hidden', isStore);

        document.getElementById('formTitle').textContent = isStore ? 'Register your store 🏪' : 'Create account 🚀';
        document.getElementById('formSubtitle').innerHTML = isStore
            ? 'List your shop on CompuMatch. Already registered? <a href="login.php">Sign in here</a>'
            : 'Already have one? <a href="login.php">Sign in here</a>';
        document.getElementById('submitBtn').innerHTML = isStore
            ? 'Register Store &nbsp;<i class="fas fa-store"></i>'
            : 'Create Account &nbsp;<i class="fas fa-user-plus"></i>';

        document.getElementById('errorMsg').style.display = 'none';
        if (isStore) initStoreMap();
    }

    // ══════════ STORE LOCATION MAP (Leaflet + OpenStreetMap, no API key) ══════════
    const DEFAULT_CENTER = [12.0668, 124.5946]; // Calbayog City
    let storeMap = null;
    let storeMarker = null;

    function initStoreMap() {
        if (typeof L === 'undefined') {
            document.getElementById('storeMap').innerHTML =
                '<p style="padding:16px;font-size:0.8rem;color:var(--text-muted);">Map could not load. Check your internet connection and refresh.</p>';
            return;
        }
        if (storeMap) {
            // The map was created while hidden — Leaflet needs a resize nudge.
            setTimeout(() => storeMap.invalidateSize(), 50);
            return;
        }
        storeMap = L.map('storeMap', { zoomControl: true }).setView(DEFAULT_CENTER, 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(storeMap);
        storeMap.on('click', e => setPin(e.latlng.lat, e.latlng.lng, true));
        setTimeout(() => storeMap.invalidateSize(), 50);
    }

    function setPin(lat, lng, fillAddress) {
        const icon = L.divIcon({ className: '', html: '<div class="cm-pin"></div>', iconSize: [22, 22], iconAnchor: [11, 22] });
        if (!storeMarker) {
            storeMarker = L.marker([lat, lng], { draggable: true, icon }).addTo(storeMap);
            storeMarker.on('dragend', () => {
                const p = storeMarker.getLatLng();
                setPin(p.lat, p.lng, false);
            });
        } else {
            storeMarker.setLatLng([lat, lng]);
        }
        document.getElementById('storeLat').value = lat.toFixed(7);
        document.getElementById('storeLng').value = lng.toFixed(7);
        const readout = document.getElementById('pinReadout');
        readout.textContent = lat.toFixed(5) + ', ' + lng.toFixed(5);
        readout.classList.add('set');
        document.getElementById('storeMap').classList.add('has-pin');
        if (fillAddress) reverseGeocode(lat, lng);
    }

    // Pre-fills the address box from the pin — only if the owner left it empty,
    // so it never overwrites something they typed themselves.
    async function reverseGeocode(lat, lng) {
        const addr = document.getElementById('storeAddress');
        if (addr.value.trim()) return;
        try {
            const r = await fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&zoom=18&lat=' + lat + '&lon=' + lng,
                                  { headers: { 'Accept-Language': 'en' } });
            const d = await r.json();
            if (d && d.display_name && !addr.value.trim()) addr.value = d.display_name;
        } catch (e) { /* optional nicety — ignore failures */ }
    }

    function useMyLocation() {
        if (!navigator.geolocation) { showError('Your browser cannot share its location. Tap the map instead.'); return; }
        const btn = document.getElementById('locateBtn');
        btn.disabled = true;
        navigator.geolocation.getCurrentPosition(pos => {
            btn.disabled = false;
            const { latitude, longitude } = pos.coords;
            storeMap.setView([latitude, longitude], 17);
            setPin(latitude, longitude, true);
        }, () => {
            btn.disabled = false;
            showError('Location access was blocked. Tap the map to pin your store instead.');
        }, { enableHighAccuracy: true, timeout: 10000 });
    }

    // ══════════ BUSINESS PERMIT UPLOAD ══════════
    const PERMIT_MAX_BYTES = 5 * 1024 * 1024;
    let permitBlob = null;   // the (possibly compressed) file that gets uploaded
    let permitName = '';

    function onPermitChosen(file) {
        if (!file) return;
        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            showError('Business permit must be a JPG, PNG, or WEBP image.');
            clearPermit();
            return;
        }
        permitName = file.name;
        // Crop step first (so only the permit is compared, not the background).
        // If the crop library didn't load, fall back to a plain resize.
        if (!openCropper(file)) {
            shrinkImage(file).then(b => finalizePermit(b), () => finalizePermit(file));
        }
    }

    function finalizePermit(blob) {
        if (blob.size > PERMIT_MAX_BYTES) {
            showError('That image is still over 5 MB. Try a smaller photo.');
            clearPermit();
            return;
        }
        permitBlob = blob;
        document.getElementById('permitThumb').innerHTML = '<img alt="Permit preview" src="' + URL.createObjectURL(permitBlob) + '">';
        document.getElementById('permitName').textContent = permitName || 'permit.jpg';
        document.getElementById('permitMeta').textContent = (permitBlob.size / 1024 / 1024).toFixed(2) + ' MB · ready to upload';
        document.getElementById('permitDrop').classList.add('has-file');
        document.getElementById('permitRemove').classList.remove('is-hidden');
        document.getElementById('errorMsg').style.display = 'none';
        startPermitOcr(blob);
    }

    // ── Read the words on the permit (OCR, runs in the browser) ──
    // Lets the server recognize a DTI certificate / Mayor's Permit by what it
    // SAYS, even when the photo doesn't look exactly like the sample.
    let permitOcrPromise = null;
    let permitOcrToken = null;

    function startPermitOcr(blob) {
        const meta = document.getElementById('permitMeta');
        const sizeText = (blob.size / 1024 / 1024).toFixed(2) + ' MB';
        if (typeof Tesseract === 'undefined') {           // library didn't load — image check only
            permitOcrPromise = Promise.resolve('');
            return;
        }
        const token = {};
        permitOcrToken = token;
        meta.textContent = sizeText + ' · reading text…';
        permitOcrPromise = Tesseract.recognize(blob, 'eng')
            .then(r => (r && r.data && r.data.text) || '')
            .catch(() => '')
            .then(text => {
                if (permitOcrToken === token) meta.textContent = sizeText + ' · ready to upload';
                return text;
            });
    }

    // ── Crop step (Cropper.js) ──
    let cropper = null;

    function openCropper(file) {
        if (typeof Cropper === 'undefined') return false;
        const overlay = document.getElementById('cropOverlay');
        const img = document.getElementById('cropImage');
        if (cropper) { cropper.destroy(); cropper = null; }
        img.onload = () => {
            cropper = new Cropper(img, {
                viewMode: 1,
                autoCropArea: 0.9,
                background: false,
                responsive: true
            });
        };
        img.src = URL.createObjectURL(file);
        overlay.style.display = 'flex';
        return true;
    }

    function closeCropper() {
        document.getElementById('cropOverlay').style.display = 'none';
        if (cropper) { cropper.destroy(); cropper = null; }
    }

    function cropRotate() {
        if (cropper) cropper.rotate(90);
    }

    function cropCancel() {
        closeCropper();
        if (!permitBlob) clearPermit();
        else document.getElementById('permitFile').value = '';
    }

    function cropConfirm() {
        if (!cropper) return;
        // Max 2000px keeps the text readable while staying under upload limits.
        const canvas = cropper.getCroppedCanvas({ maxWidth: 2000, maxHeight: 2000, fillColor: '#fff' });
        closeCropper();
        if (!canvas) { showError('Could not crop that image. Please try another photo.'); clearPermit(); return; }
        canvas.toBlob(b => {
            if (b) finalizePermit(b);
            else { showError('Could not process that image. Please try another photo.'); clearPermit(); }
        }, 'image/jpeg', 0.9);
        document.getElementById('permitFile').value = ''; // lets the same file be picked again
    }

    // Phone photos are often 4–10 MB. Resize to max 2000px on the long side
    // (still very readable for a permit) so uploads are fast and stay under
    // the free host's limits. Small images are sent untouched.
    function shrinkImage(file) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => {
                const MAX = 2000;
                const scale = Math.min(1, MAX / Math.max(img.width, img.height));
                if (scale === 1 && file.size <= 1.5 * 1024 * 1024) { resolve(file); return; }
                const canvas = document.createElement('canvas');
                canvas.width = Math.round(img.width * scale);
                canvas.height = Math.round(img.height * scale);
                canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);
                canvas.toBlob(b => b ? resolve(b) : reject(), 'image/jpeg', 0.85);
            };
            img.onerror = reject;
            img.src = URL.createObjectURL(file);
        });
    }

    function clearPermit(e) {
        if (e) { e.preventDefault(); e.stopPropagation(); }
        permitOcrPromise = null;
        permitOcrToken = null;
        permitBlob = null;
        permitName = '';
        document.getElementById('permitFile').value = '';
        document.getElementById('permitThumb').innerHTML = '<i class="fas fa-file-image"></i>';
        document.getElementById('permitName').textContent = 'Upload a clear photo of your document';
        document.getElementById('permitMeta').textContent = 'JPG, PNG, or WEBP · max 5 MB';
        document.getElementById('permitDrop').classList.remove('has-file');
        document.getElementById('permitRemove').classList.add('is-hidden');
    }

    // Drag-and-drop onto the permit box (desktop)
    (function () {
        const drop = document.getElementById('permitDrop');
        ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('dragover'); }));
        ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('dragover'); }));
        drop.addEventListener('drop', e => { if (e.dataTransfer.files[0]) onPermitChosen(e.dataTransfer.files[0]); });
    })();

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

    function checkStrength(val) {
        const meter = document.getElementById('strengthMeter');
        meter.style.display = val.length ? 'block' : 'none';

        const segs = [document.getElementById('s1'), document.getElementById('s2'),
                      document.getElementById('s3'), document.getElementById('s4')];
        const label = document.getElementById('strengthLabel');

        segs.forEach(s => s.style.background = 'var(--border)');

        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const colors = ['#FF5252', '#FF8A65', '#FFD740', '#00E676'];
        const labels = ['Weak', 'Fair', 'Good', 'Strong'];

        for (let i = 0; i < score; i++) {
            segs[i].style.background = colors[score - 1];
        }

        label.textContent = score > 0 ? labels[score - 1] : '';
        label.style.color = score > 0 ? colors[score - 1] : 'var(--text-muted)';
    }

    function showError(msg) {
        const el = document.getElementById('errorMsg');
        document.getElementById('errorText').textContent = msg;
        el.style.display = 'flex';
        // re-trigger shake
        el.style.animation = 'none';
        el.offsetHeight;
        el.style.animation = 'shake 0.4s ease';
    }

    function handleSignup() {
        if (document.getElementById('submitBtn').disabled) return; // upload already in progress
        document.getElementById('errorMsg').style.display = 'none';
        if (currentAccountType === 'store') {
            handleStoreSignup();
        } else {
            handleClientSignup();
        }
    }

    // ── CLIENT sign-up ──
    async function handleClientSignup() {
        const firstName = document.getElementById('firstName').value.trim();
        const lastName  = document.getElementById('lastName').value.trim();
        const username  = document.getElementById('username').value.trim().toLowerCase();
        const email     = document.getElementById('email').value.trim();
        const password  = document.getElementById('password').value;
        const confirm   = document.getElementById('confirmPassword').value;
        const terms     = document.getElementById('terms').checked;

        if (!firstName || !lastName) { showError('Please enter your full name.'); return; }
        if (!/^[a-z0-9_]{3,30}$/.test(username)) { showError('Username must be 3–30 characters: lowercase letters, numbers, or _ only.'); return; }
        if (!email || !validateEmail(email)) { showError('Please enter a valid email address.'); return; }
        if (password.length < 8) { showError('Password must be at least 8 characters.'); return; }
        if (password !== confirm) { showError('Passwords do not match.'); return; }
        if (!terms) { showError('Please accept the Terms of Service to continue.'); return; }

        const fullName = `${firstName} ${lastName}`.trim();

        // Save to the real database first so the account exists for every device.
        try {
            const res = await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'register_user', name: fullName, username, email, password })
            });
            const data = await res.json();
            if (!data.ok) { showError(data.error || 'Could not create your account.'); return; }
        } catch (e) {
            // Backend unreachable — keep working offline via localStorage only.
        }

        // Remember this account locally too, so this browser can log in right away
        const users = JSON.parse(localStorage.getItem('compumatch_users') || '{}');
        users[email] = { name: fullName, username: username, email: email };  // no password kept in the browser
        localStorage.setItem('compumatch_users', JSON.stringify(users));

        document.getElementById('successTitle').textContent = 'Account Created!';
        document.getElementById('successText').textContent = 'Welcome to CompuMatch! Log in with your username "' + username + '".';
        document.getElementById('successBtn').onclick = () => window.location.href = 'login.php';
        document.getElementById('successOverlay').style.display = 'flex';
    }

    // ── STORE sign-up → saves into compumatch_stores (shared with Admin Portal) ──
    async function handleStoreSignup() {
        const name     = document.getElementById('storeName').value.trim();
        const idRaw    = document.getElementById('storeId').value.trim().toLowerCase();
        const address  = document.getElementById('storeAddress').value.trim();
        const contact  = document.getElementById('storeContact').value.trim();
        const email    = document.getElementById('storeEmail').value.trim();
        const password = document.getElementById('storePassword').value;
        const confirm  = document.getElementById('storeConfirmPassword').value;
        const desc     = document.getElementById('storeDescription').value.trim();
        const lat      = document.getElementById('storeLat').value;
        const lng      = document.getElementById('storeLng').value;
        const terms    = document.getElementById('terms').checked;

        if (!name) { showError('Please enter your store name.'); return; }
        if (!idRaw) { showError('Please choose a Store ID.'); return; }
        if (!/^[a-z0-9]+$/.test(idRaw)) { showError('Store ID can only contain lowercase letters and numbers (no spaces).'); return; }
        if (!lat || !lng) { showError('Please pin your store location on the map.'); return; }
        if (email && !validateEmail(email)) { showError('Please enter a valid email address.'); return; }
        if (password.length < 6) { showError('Password must be at least 6 characters.'); return; }
        if (password !== confirm) { showError('Passwords do not match.'); return; }
        if (!permitBlob) { showError('Please upload a photo of your Business Permit or DTI Certificate.'); return; }
        if (!terms) { showError('Please accept the Terms of Service to continue.'); return; }

        // multipart/form-data (not JSON) because it carries the permit image.
        const form = new FormData();
        form.append('action', 'register_store');
        form.append('store_id', idRaw);
        form.append('name', name);
        form.append('address', address);
        form.append('contact', contact);
        form.append('email', email);
        form.append('password', password);
        form.append('description', desc);
        form.append('latitude', lat);
        form.append('longitude', lng);
        const ext = permitBlob.type === 'image/png' ? 'png' : permitBlob.type === 'image/webp' ? 'webp' : 'jpg';
        form.append('permit', permitBlob, 'permit.' + ext);

        const btn = document.getElementById('submitBtn');
        const btnHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = 'Uploading… &nbsp;<i class="fas fa-spinner fa-spin"></i>';

        // Wait for the permit text (max 25 s), then send it with the form.
        let permitText = '';
        if (permitOcrPromise) {
            btn.innerHTML = 'Reading permit… &nbsp;<i class="fas fa-spinner fa-spin"></i>';
            permitText = await Promise.race([permitOcrPromise, new Promise(r => setTimeout(() => r(''), 25000))]);
            btn.innerHTML = 'Uploading… &nbsp;<i class="fas fa-spinner fa-spin"></i>';
        }
        form.append('permit_text', (permitText || '').slice(0, 20000));

        let res = null;
        let permitStatus = 'pending';
        try {
            res = await fetch('api.php', { method: 'POST', body: form }); // no Content-Type: the browser sets the multipart boundary
        } catch (e) {
            res = null; // backend unreachable — fall through to offline localStorage mode
        }
        if (res) {
            let data = null;
            try { data = await res.json(); } catch (e) { /* non-JSON, e.g. host error page */ }
            if (!data) {
                btn.disabled = false; btn.innerHTML = btnHtml;
                showError('The server returned an unexpected response. Please try again.');
                return;
            }
            if (!data.ok) {
                btn.disabled = false; btn.innerHTML = btnHtml;
                showError(data.error || 'Could not register your store.');
                return;
            }
            permitStatus = data.permit_status || 'pending';
        }
        btn.disabled = false; btn.innerHTML = btnHtml;

        const stores = JSON.parse(localStorage.getItem('compumatch_stores') || '{}');
        if (!res && stores[idRaw]) { showError('That Store ID is already taken. Please choose another.'); return; }

        stores[idRaw] = {
            id: idRaw,
            name: name,
            address: address || '',
            latitude: Number(lat),
            longitude: Number(lng),
            contact: contact || '',
            email: email || '',
            description: desc || '',
            inventory: [],
            orders: [],
            createdAt: new Date().toISOString()
        };
        localStorage.setItem('compumatch_stores', JSON.stringify(stores));

        document.getElementById('successTitle').textContent = 'Store Registered!';
        document.getElementById('successText').textContent = permitStatus === 'approved'
            ? '"' + name + '" is verified and now visible to customers. Sign in with Store ID "' + idRaw + '" to add your inventory.'
            : '"' + name + '" has been submitted and is waiting for business permit approval. ' +
              'You can sign in with Store ID "' + idRaw + '" once the admin approves it.';
        document.getElementById('successBtn').onclick = () => window.location.href = 'login.php';
        document.getElementById('successOverlay').style.display = 'flex';
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Enter' && document.getElementById('cropOverlay').style.display !== 'flex') handleSignup();
    });
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
                body: JSON.stringify({ action: action, page: 'signup', store_id: currentStoreId() })
            }).catch(function () {});
        } catch (e) {}
    }
    ping('track_visit');
    setInterval(function () { ping('heartbeat'); }, 120000); // every 2 min (keeps hosting load low)
})();
</script>
</body>
</html>
