<?php require_once __DIR__ . '/db.php'; $BOOT = bootstrapStores(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CompuMatch - Store Admin Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            --warning-light: #FF8A65;
            --bg-dark: #0f0f14;
            --bg-card: #1a1a24;
            --bg-card-hover: #22222e;
            --border: #2d2d3a;
            --text-muted: #9e9e9e;
            --text-light: #e0e0e0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-dark);
            color: var(--text-light);
        }

        .navbar {
            background: rgba(15, 15, 20, 0.95);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid var(--border);
            padding: 16px 0;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Poppins', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .logo i {
            background: none;
            color: var(--primary);
            margin-right: 8px;
        }

        .store-badge {
            background: rgba(108, 99, 255, 0.2);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            cursor: pointer;
            transition: color 0.3s;
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--primary);
        }

        .btn-logout {
            background: rgba(255, 82, 82, 0.15);
            border: 1px solid var(--warning);
            color: var(--warning);
            padding: 8px 20px;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: var(--warning);
            color: white;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .dashboard-title h1 {
            font-size: 1.8rem;
            margin-bottom: 8px;
        }

        .dashboard-title p {
            color: var(--text-muted);
        }

        .stats-cards {
            display: flex;
            gap: 20px;
        }

        .stat-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 16px 24px;
            text-align: center;
            border: 1px solid var(--border);
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 32px;
            border-bottom: 1px solid var(--border);
            flex-wrap: wrap;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 12px 24px;
            color: var(--text-muted);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .tab-btn:hover {
            color: var(--primary);
        }

        .tab-btn.active {
            color: var(--primary);
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            color: white;
            padding: 10px 24px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(108, 99, 255, 0.3);
        }

        .btn-secondary {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-light);
            padding: 8px 20px;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .inv-search { position: relative; }
        .inv-search i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.85rem; pointer-events: none; }
        .inv-search input { width: 260px; max-width: 100%; padding: 8px 16px 8px 36px; background: transparent; border: 1px solid var(--border); border-radius: 30px; color: var(--text-light); font-size: 0.9rem; font-family: inherit; transition: border-color 0.3s; }
        .inv-search input:focus { outline: none; border-color: var(--primary); }
        .inv-search input::placeholder { color: var(--text-muted); }

        .btn-secondary:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-danger {
            background: rgba(255, 82, 82, 0.15);
            border: 1px solid var(--warning);
            color: var(--warning);
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s;
        }

        .btn-danger:hover {
            background: var(--warning);
            color: white;
        }

        .btn-success {
            background: rgba(0, 230, 118, 0.15);
            border: 1px solid var(--success);
            color: var(--success);
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s;
        }

        .btn-success:hover {
            background: var(--success);
            color: white;
        }

        .inventory-table-container {
            background: var(--bg-card);
            border-radius: 20px;
            overflow-x: auto;
            border: 1px solid var(--border);
        }

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
        }

        .inventory-table th,
        .inventory-table td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .inventory-table th {
            background: rgba(108, 99, 255, 0.1);
            font-weight: 600;
            color: var(--primary);
        }

        .inventory-table tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .stock-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .stock-in {
            background: rgba(0, 230, 118, 0.15);
            color: var(--success);
        }

        .stock-low {
            background: rgba(255, 138, 101, 0.15);
            color: var(--warning-light);
        }

        .stock-out {
            background: rgba(255, 82, 82, 0.15);
            color: var(--warning);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--bg-card);
            border-radius: 24px;
            padding: 32px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            border: 1px solid var(--border);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .modal-header h3 {
            font-size: 1.4rem;
        }

        .close-modal {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.5rem;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            background: var(--bg-dark);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text-light);
            font-family: 'Inter', sans-serif;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .modal-buttons {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
        }

        .orders-grid {
            display: grid;
            gap: 20px;
        }

        .order-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 20px;
            border: 1px solid var(--border);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .order-id {
            font-weight: 600;
            color: var(--primary);
        }

        .order-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        .status-pending {
            background: rgba(255, 138, 101, 0.15);
            color: var(--warning-light);
        }

        .status-confirmed {
            background: rgba(0, 212, 255, 0.15);
            color: var(--accent);
        }

        .status-ready {
            background: rgba(108, 99, 255, 0.15);
            color: var(--primary);
        }

        .status-completed {
            background: rgba(0, 230, 118, 0.15);
            color: var(--success);
        }

        .order-items {
            margin: 16px 0;
            padding: 12px;
            background: var(--bg-dark);
            border-radius: 12px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 0.9rem;
        }

        .order-total {
            text-align: right;
            font-weight: 600;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--border);
        }

        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: var(--bg-card);
            border-left: 4px solid var(--success);
            padding: 12px 20px;
            border-radius: 12px;
            z-index: 1100;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .empty-state {
            text-align: center;
            padding: 60px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .login-screen {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--bg-dark), #0a0a0f);
        }

        .login-card {
            background: var(--bg-card);
            border-radius: 32px;
            padding: 48px;
            max-width: 450px;
            width: 90%;
            border: 1px solid var(--border);
            text-align: center;
        }

        .login-card .logo {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .login-card h2 {
            margin-bottom: 8px;
        }

        .login-card p {
            color: var(--text-muted);
            margin-bottom: 32px;
        }

        .login-card input {
            width: 100%;
            padding: 14px;
            margin-bottom: 16px;
            background: var(--bg-dark);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: white;
        }

        .register-link {
            margin-top: 20px;
            color: var(--text-muted);
        }

        .register-link a {
            color: var(--primary);
            text-decoration: none;
            cursor: pointer;
        }

        /* Phone menu: CompuMatch + store name + ☰; links open as a dropdown */
        .nav-toggle { display: none; }
        @media (max-width: 768px) {
            .nav-container { flex-wrap: wrap; gap: 10px; padding: 0 16px; }
            .logo { font-size: 1.3rem; }
            .store-badge { max-width: 40vw; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin-right: auto; }
            .nav-toggle {
                display: inline-flex; align-items: center; justify-content: center;
                width: 44px; height: 44px; border-radius: 12px;
                border: 1px solid var(--border); background: var(--bg-card);
                color: var(--text-light); font-size: 1.2rem; cursor: pointer;
            }
            .navbar.nav-open .nav-toggle { border-color: var(--accent); color: var(--accent); }
            .nav-links { display: none; }
            .navbar.nav-open .nav-links {
                display: flex; flex-direction: column; align-items: stretch;
                width: 100%; gap: 4px; margin-top: 6px; padding: 10px;
                background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px;
            }
            .navbar.nav-open .nav-links a { padding: 12px 14px; border-radius: 10px; font-size: 1rem; }
            .navbar.nav-open .nav-links a.active { background: rgba(108,99,255,0.15); }
            .navbar.nav-open .nav-links a:hover { background: var(--bg-dark); }
            .navbar.nav-open .btn-logout { width: 100%; margin-top: 6px; padding: 12px; }
            .container { padding: 24px 16px; }
            .stats-cards { flex-wrap: wrap; gap: 10px; }
            .stats-cards .stat-card { flex: 1 1 calc(50% - 10px); padding: 12px; }
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .stats-cards {
                width: 100%;
                justify-content: space-between;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
            .action-buttons {
                flex-direction: column;
            }
        }
        /* Inventory: clickable item (photo + name) -> details pop-up */
        .inv-item { display: flex; align-items: flex-start; gap: 12px; cursor: pointer; border-radius: 10px; padding: 4px; margin: -4px; }
        .inv-item:hover .inv-name { color: var(--accent); text-decoration: underline; }
        .inv-item:hover .inv-thumb { box-shadow: 0 0 0 2px var(--accent); }
        .inv-item:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }
        .inv-thumb { width: 48px; height: 48px; flex-shrink: 0; object-fit: contain; background: #fff; border-radius: 8px; border: 1px solid var(--border); transition: box-shadow 0.15s; }
        .inv-thumb-empty { display: inline-flex; align-items: center; justify-content: center; background: var(--bg-dark); color: var(--text-muted); }
        .inv-text { min-width: 0; display: flex; flex-direction: column; gap: 2px; }
        .inv-specs { color: var(--text-muted); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .idm { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 2000; align-items: flex-start; justify-content: center; padding: 40px 16px; overflow-y: auto; }
        .idm.open { display: flex; }
        .idm-box { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; width: 100%; max-width: 880px; box-shadow: 0 20px 60px rgba(0,0,0,0.6); }
        .idm-head { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--border); }
        .idm-x { width: 36px; height: 36px; border-radius: 10px; border: 1px solid var(--border); background: transparent; color: var(--text-muted); cursor: pointer; }
        .idm-x:hover { color: var(--warning); border-color: var(--warning); }
        .idm-body { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1.1fr); gap: 22px; padding: 22px; }
        .idm-photo { background: #fff; border-radius: 14px; min-height: 300px; display: flex; align-items: center; justify-content: center; padding: 16px; }
        .idm-photo img { max-width: 100%; max-height: 400px; object-fit: contain; display: block; cursor: zoom-in; }
        .idm-nophoto { color: #999; display: flex; flex-direction: column; align-items: center; gap: 8px; font-size: 2.2rem; }
        .idm-nophoto span { font-size: 0.9rem; }
        .idm-info h2 { font-size: 1.3rem; line-height: 1.3; margin-bottom: 12px; }
        .idm-facts { display: grid; grid-template-columns: max-content 1fr; gap: 6px 16px; font-size: 0.9rem; margin-bottom: 16px; }
        .idm-facts dt { color: var(--text-muted); }
        .idm-facts dd { margin: 0; font-weight: 500; }
        .idm-info h4 { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-muted); margin-bottom: 6px; }
        .idm-specs { font-size: 0.9rem; line-height: 1.6; white-space: pre-wrap; margin-bottom: 18px; }
        .idm-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        @media (max-width: 760px) {
            .idm-body { grid-template-columns: 1fr; padding: 16px; }
            .idm-photo { min-height: 200px; }
            .idm-photo img { max-height: 260px; }
        }
        /* Category pills above the inventory table (same look as Browse Parts) */
        .bp-switch { display: inline-flex; flex-wrap: wrap; gap: 4px; padding: 4px; margin: 0 0 18px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 999px; }
        .bp-btn { display: inline-flex; align-items: center; gap: 8px; background: none; border: none; border-radius: 999px; padding: 8px 16px; color: var(--text-muted); font-weight: 600; font-size: 0.88rem; font-family: inherit; cursor: pointer; transition: background 0.2s, color 0.2s; width: auto; }
        .bp-btn:hover { color: var(--text-light); }
        .bp-btn.selected { background: rgba(0,212,255,0.12); color: var(--accent); box-shadow: inset 0 0 0 1px rgba(0,212,255,0.5); }
        .bp-btn:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }
        .bp-count { font-size: 0.72rem; background: var(--bg-dark); border: 1px solid var(--border); border-radius: 20px; padding: 1px 8px; color: var(--text-muted); }
        .bp-btn.selected .bp-count { color: var(--accent); border-color: rgba(0,212,255,0.4); }
        @media (max-width: 700px) {
            .bp-switch { display: flex; width: 100%; border-radius: 16px; }
            .bp-btn { padding: 8px 12px; font-size: 0.82rem; }
        }
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

<!-- Login Screen (legacy — no longer used; login happens on login.html) -->
<div id="loginScreen" class="login-screen" style="display:none;">
    <div class="login-card">
        <div class="logo">
            <span class="cm-logo"><span class="cm-logo-badge"><i class="fas fa-microchip"></i></span>Compu<span class="cm-logo-accent">Match</span></span>
        </div>
        <h2>Store Admin Portal</h2>
        <p>Manage your computer store inventory</p>
        
        <input type="text" id="loginStoreName" placeholder="Store ID (e.g., pchub)" autocomplete="off">
        <input type="password" id="loginPassword" placeholder="Password">
        
        <button class="btn-primary" onclick="handleStoreLogin()" style="width: 100%;">Login as Store</button>

        <div class="register-link">
            <a href="forgot_password.php">Forgot password?</a>
        </div>
        
        <div class="register-link">
            New store? <a href="#" onclick="showStoreRegistration()">Register Your Store</a>
        </div>
    </div>
</div>

<!-- Store Registration Modal -->
<div id="registerStoreModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-store"></i> Register Your Computer Store</h3>
            <button class="close-modal" onclick="closeStoreRegistration()">&times;</button>
        </div>
        <form id="storeRegistrationForm" onsubmit="registerNewStore(event)">
            <div class="form-group">
                <label>Store Name <span style="color: var(--warning);">*</span></label>
                <input type="text" id="newStoreName" required placeholder="e.g., PC Hub Calbayog">
                <small style="color: var(--text-muted);">This will be visible to customers</small>
            </div>
            <div class="form-group">
                <label>Store ID (Username) <span style="color: var(--warning);">*</span></label>
                <input type="text" id="newStoreId" required placeholder="e.g., pchub">
                <small style="color: var(--text-muted);">Use lowercase letters and numbers only (no spaces)</small>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Store Address</label>
                    <input type="text" id="newStoreAddress" placeholder="Full address">
                </div>
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" id="newStoreContact" placeholder="Contact number">
                </div>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" id="newStoreEmail" placeholder="store@example.com">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Password <span style="color: var(--warning);">*</span></label>
                    <input type="password" id="newStorePassword" required minlength="6">
                </div>
                <div class="form-group">
                    <label>Confirm Password <span style="color: var(--warning);">*</span></label>
                    <input type="password" id="newStoreConfirmPassword" required>
                </div>
            </div>
            <div class="form-group">
                <label>Store Description (Optional)</label>
                <textarea id="newStoreDescription" rows="2" placeholder="Tell customers about your store..."></textarea>
            </div>
            <div class="modal-buttons">
                <button type="button" class="btn-secondary" onclick="closeStoreRegistration()">Cancel</button>
                <button type="submit" class="btn-primary">Register Store</button>
            </div>
        </form>
    </div>
</div>

<!-- Main Dashboard -->
<div id="dashboardScreen" style="display: none;">
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <span class="cm-logo"><span class="cm-logo-badge"><i class="fas fa-microchip"></i></span>Compu<span class="cm-logo-accent">Match</span></span>
            </div>
            <div class="store-badge" id="storeNameDisplay">Store Name</div>
            <button type="button" class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false" aria-controls="navLinks" onclick="toggleMobileNav(event)">
                <i class="fas fa-bars"></i>
            </button>
            <div class="nav-links" id="navLinks">
                <a href="#" class="active" data-tab="inventory" onclick="showTab('inventory'); return false;">Inventory</a>
                <a href="#" data-tab="analytics" onclick="showTab('analytics'); return false;">Overview</a>
                <button class="btn-logout" onclick="logout()">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="dashboard-header">
            <div class="dashboard-title">
                <h1>Store Dashboard</h1>
                <p>Manage your inventory and prices for customer builds</p>
            </div>
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-number" id="statTotalItems">0</div>
                    <div class="stat-label">Total Items</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="statInStock">0</div>
                    <div class="stat-label">In Stock</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="statLowStock">0</div>
                    <div class="stat-label">Low Stock</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="statOutStock">0</div>
                    <div class="stat-label">Out of Stock</div>
                </div>
            </div>
        </div>

        <div class="tabs">
            <button class="tab-btn active" onclick="showTab('inventory')">📦 Inventory Management</button>
            <button class="tab-btn" onclick="showTab('analytics')">📊 Inventory Overview</button>
        </div>

        <!-- Inventory Tab -->
        <div id="inventoryTab" class="tab-content active">
            <div style="margin-bottom: 20px; display: flex; gap: 12px; justify-content: space-between; flex-wrap: wrap;">
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <div class="inv-search">
                        <i class="fas fa-search"></i>
                        <input type="search" id="inventorySearch" placeholder="Search item, brand or specs…" oninput="filterInventory()" autocomplete="off">
                    </div>
                    <input type="hidden" id="categoryFilter" value="all">
                    <select id="stockFilter" class="btn-secondary" style="padding: 8px 16px;" onchange="filterInventory()">
                        <option value="all">All Stock</option>
                        <option value="in">In Stock</option>
                        <option value="low">Low Stock</option>
                        <option value="out">Out of Stock</option>
                    </select>
                </div>
                <button class="btn-primary" onclick="openAddItemModal()">
                    <i class="fas fa-plus"></i> Add New Item
                </button>
            </div>

            <div class="bp-switch" id="invCategoryPills" role="tablist">
                <button type="button" class="bp-btn selected" data-cat="all" onclick="setInvCategory(this)"><i class="fas fa-layer-group"></i> All <span class="bp-count">0</span></button>
                <button type="button" class="bp-btn" data-cat="cpu" onclick="setInvCategory(this)"><i class="fas fa-microchip"></i> CPU <span class="bp-count">0</span></button>
                <button type="button" class="bp-btn" data-cat="gpu" onclick="setInvCategory(this)"><i class="fas fa-display"></i> GPU <span class="bp-count">0</span></button>
                <button type="button" class="bp-btn" data-cat="motherboard" onclick="setInvCategory(this)"><i class="fas fa-chess-board"></i> Motherboard <span class="bp-count">0</span></button>
                <button type="button" class="bp-btn" data-cat="ram" onclick="setInvCategory(this)"><i class="fas fa-memory"></i> RAM <span class="bp-count">0</span></button>
                <button type="button" class="bp-btn" data-cat="storage" onclick="setInvCategory(this)"><i class="fas fa-hard-drive"></i> Storage <span class="bp-count">0</span></button>
                <button type="button" class="bp-btn" data-cat="psu" onclick="setInvCategory(this)"><i class="fas fa-plug"></i> Power Supply <span class="bp-count">0</span></button>
                <button type="button" class="bp-btn" data-cat="case" onclick="setInvCategory(this)"><i class="fas fa-box"></i> Case <span class="bp-count">0</span></button>
            </div>

            <div class="inventory-table-container">
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Item Name</th>
                            <th>Brand</th>
                            <th>Price (₱)</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="inventoryTableBody"></tbody>
                </table>
            </div>
        </div>

        <!-- Inventory Overview Tab -->
        <div id="analyticsTab" class="tab-content">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                <div class="stat-card" style="text-align: center; padding: 32px;">
                    <i class="fas fa-coins" style="font-size: 2.5rem; color: var(--primary);"></i>
                    <div class="stat-number" id="analyticsInventoryValue">₱0</div>
                    <div class="stat-label">Total Inventory Value</div>
                </div>
                <div class="stat-card" style="text-align: center; padding: 32px;">
                    <i class="fas fa-boxes-stacked" style="font-size: 2.5rem; color: var(--primary);"></i>
                    <div class="stat-number" id="analyticsTotalUnits">0</div>
                    <div class="stat-label">Total Units in Stock</div>
                </div>
                <div class="stat-card" style="text-align: center; padding: 32px;">
                    <i class="fas fa-tag" style="font-size: 2.5rem; color: var(--primary);"></i>
                    <div class="stat-number" id="analyticsTopCategory">-</div>
                    <div class="stat-label">Most Stocked Category</div>
                </div>
            </div>
            
            <div class="inventory-table-container" style="margin-top: 24px;">
                <h3 style="padding: 20px 20px 0;">Inventory by Category</h3>
                <table class="inventory-table">
                    <thead>
                        <tr><th>Category</th><th>Items</th><th>Units in Stock</th><th>Stock Value</th></tr>
                    </thead>
                    <tbody id="categoryBreakdownBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Item Modal -->
<div id="itemModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add New Item</h3>
            <button class="close-modal" onclick="closeItemModal()">&times;</button>
        </div>
        <form id="itemForm" onsubmit="saveItem(event)">
            <input type="hidden" id="itemId">
            <div class="form-group">
                <label>Category</label>
                <select id="itemCategory" required onchange="toggleCompatFields()">
                    <option value="">Select Category</option>
                    <option value="cpu">CPU / Processor</option>
                    <option value="gpu">Graphics Card (GPU)</option>
                    <option value="motherboard">Motherboard</option>
                    <option value="ram">RAM / Memory</option>
                    <option value="storage">Storage (SSD/HDD)</option>
                    <option value="psu">Power Supply (PSU)</option>
                    <option value="case">PC Case</option>
                </select>
            </div>
            <div class="form-group">
                <label>Item Name</label>
                <input type="text" id="itemName" required placeholder="e.g., Intel Core i7-13700K">
            </div>
            <!-- Compatibility fields, typed in by the store owner. Shown by category:
                 CPU: socket · Motherboard: socket + RAM type · RAM: RAM type. -->
            <div class="form-row" id="compatRow" style="display:none;">
                <div class="form-group" id="socketGroup" style="display:none;">
                    <label>CPU Socket Type</label>
                    <input type="text" id="itemSocket" autocomplete="off" autocapitalize="characters" spellcheck="false" maxlength="40"
                           oninput="checkCompatField(this, 'CPU Socket Type')">
                    <small id="itemSocketHint" style="display:block; margin-top:6px; color: var(--text-muted);"></small>
                </div>
                <div class="form-group" id="ramTypeGroup" style="display:none;">
                    <label>RAM Socket Type</label>
                    <input type="text" id="itemRamType" autocomplete="off" autocapitalize="characters" spellcheck="false" maxlength="40"
                           oninput="checkCompatField(this, 'RAM Socket Type')">
                    <small id="itemRamTypeHint" style="display:block; margin-top:6px; color: var(--text-muted);"></small>
                </div>
                <div class="form-group" id="ramSlotsGroup" style="display:none;">
                    <label>RAM Slots</label>
                    <input type="number" id="itemRamSlots" min="1" max="24" step="1" placeholder="e.g. 2 or 4">
                    <small style="display:block; margin-top:6px; color: var(--text-muted);">How many memory (DIMM) slots the board has. Decides how many RAM sticks a customer can add.</small>
                </div>
                <div class="form-group" id="m2Group" style="display:none;">
                    <label>M.2 Slots</label>
                    <input type="number" id="itemM2Slots" min="1" max="8" step="1" placeholder="e.g. 1, 2 or 4">
                    <small style="display:block; margin-top:6px; color: var(--text-muted);">How many M.2 (NVMe) slots the board has. Decides how many extra NVMe drives a customer can add to the main storage.</small>
                </div>
                <div class="form-group" id="sataGroup" style="display:none;">
                    <label>SATA Ports</label>
                    <input type="number" id="itemSataPorts" min="1" max="12" step="1" placeholder="e.g. 4 or 6">
                    <small style="display:block; margin-top:6px; color: var(--text-muted);">How many SATA ports the board has (see the product specs). Decides how many secondary drives a customer can add.</small>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Brand</label>
                    <input type="text" id="itemBrand" required placeholder="e.g., Intel, AMD, NVIDIA">
                </div>
                <div class="form-group">
                    <label>Price (₱)</label>
                    <input type="number" id="itemPrice" required min="0" step="100">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" id="itemStock" required min="0" value="0">
                </div>
                <div class="form-group">
                    <label>Low Stock Alert (threshold)</label>
                    <input type="number" id="itemLowStock" value="5" min="0">
                </div>
            </div>
            <div class="form-group">
                <label>Specifications (optional)</label>
                <textarea id="itemSpecs" rows="2" placeholder="e.g., 16 cores, 5.4GHz, LGA1700"></textarea>
            </div>
            <div class="form-group">
                <label>Item Photo (optional)</label>
                <div style="display:flex; align-items:center; gap:14px;">
                    <div id="itemImagePreview" style="width:84px; height:84px; flex-shrink:0; border-radius:12px; border:1px solid var(--border); background:var(--bg-dark); display:flex; align-items:center; justify-content:center; overflow:hidden; color:var(--text-muted); font-size:1.5rem;"><i class="fas fa-image"></i></div>
                    <div style="flex:1; min-width:0;">
                        <input type="file" id="itemImage" accept="image/jpeg,image/png,image/webp" onchange="onItemImageChosen(this.files[0])">
                        <small style="display:block; margin-top:6px; color: var(--text-muted);">JPG, PNG, or WEBP. Large photos are resized automatically.</small>
                        <button type="button" class="btn-danger" id="itemImageRemove" style="display:none; margin-top:8px;" onclick="removeItemImage()"><i class="fas fa-xmark"></i> Remove photo</button>
                    </div>
                </div>
            </div>
            <div class="modal-buttons">
                <button type="button" class="btn-secondary" onclick="closeItemModal()">Cancel</button>
                <button type="submit" class="btn-primary">Save Item</button>
            </div>
        </form>
    </div>
</div>

<script>
// Always refresh admin inventory from MySQL (PHP) on load so the dashboard
// reflects live DB data instead of a stale cached copy.
window.__BOOTSTRAP__ = <?php echo $BOOT ? json_encode($BOOT) : "null"; ?>;
try {
  if (window.__BOOTSTRAP__) {
    localStorage.setItem("compumatch_stores", JSON.stringify(window.__BOOTSTRAP__));
  }
} catch (e) {}
</script>
<script>
    let storesData = JSON.parse(localStorage.getItem('compumatch_stores') || '{}');
    let currentStore = null;
    let currentInventory = [];
    let inventoryFilter = { category: 'all', stock: 'all', search: '' };

    function initializeDemoStores() {
        if (Object.keys(storesData).length === 0) {
            storesData = {
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
                        { id: 'psu1', category: 'psu', name: 'Corsair RM750e', brand: 'Corsair', price: 7500, stock: 6, lowStock: 3, specs: '750W Gold', sold: 0 },
                        { id: 'case1', category: 'case', name: 'NZXT H5 Flow', brand: 'NZXT', price: 5500, stock: 6, lowStock: 3, specs: 'ATX Mid-Tower, 2 fans', sold: 0 },
                        { id: 'case2', category: 'case', name: 'Cooler Master Q300L', brand: 'Cooler Master', price: 2800, stock: 9, lowStock: 4, specs: 'mATX, 1 fan', sold: 0 }
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
                        { id: 'psu2', category: 'psu', name: 'Seasonic Focus 750W', brand: 'Seasonic', price: 6800, stock: 5, lowStock: 2, specs: '750W Gold', sold: 0 },
                        { id: 'case3', category: 'case', name: 'Lian Li Lancool 216', brand: 'Lian Li', price: 6500, stock: 4, lowStock: 2, specs: 'ATX Mid-Tower, 3 fans', sold: 0 },
                        { id: 'case4', category: 'case', name: 'Corsair 4000D Airflow', brand: 'Corsair', price: 5200, stock: 5, lowStock: 2, specs: 'ATX Mid-Tower, 2 fans', sold: 0 }
                    ],
                    orders: []
                }
            };
            localStorage.setItem('compumatch_stores', JSON.stringify(storesData));
        }
    }

    function showToast(message, isError = false) {
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.style.borderLeftColor = isError ? '#FF5252' : '#00E676';
        toast.innerHTML = `<i class="fas ${isError ? 'fa-exclamation-triangle' : 'fa-check-circle'}"></i> ${message}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    function showStoreRegistration() {
        document.getElementById('registerStoreModal').style.display = 'flex';
    }

    function closeStoreRegistration() {
        document.getElementById('registerStoreModal').style.display = 'none';
        document.getElementById('storeRegistrationForm').reset();
    }

    async function registerNewStore(event) {
        event.preventDefault();
        
        const storeId = document.getElementById('newStoreId').value.trim().toLowerCase().replace(/\s/g, '');
        const storeName = document.getElementById('newStoreName').value.trim();
        const address = document.getElementById('newStoreAddress').value;
        const contact = document.getElementById('newStoreContact').value;
        const email = document.getElementById('newStoreEmail').value;
        const password = document.getElementById('newStorePassword').value;
        const confirmPassword = document.getElementById('newStoreConfirmPassword').value;
        const description = document.getElementById('newStoreDescription').value;
        
        if (!storeId || !storeName || !password) {
            showToast('Please fill in all required fields (*)', true);
            return;
        }
        
        if (!/^[a-z0-9]+$/.test(storeId)) {
            showToast('Store ID must contain only lowercase letters and numbers (no spaces)', true);
            return;
        }
        
        if (password !== confirmPassword) {
            showToast('Passwords do not match', true);
            return;
        }
        
        if (password.length < 6) {
            showToast('Password must be at least 6 characters', true);
            return;
        }
        
        if (storesData[storeId]) {
            showToast('Store ID already exists. Please choose another one.', true);
            return;
        }

        // Save to the real database first so this store account works from any device.
        try {
            const res = await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'register_store', store_id: storeId, name: storeName,
                    address, contact, email, password, description
                })
            });
            const data = await res.json();
            if (!data.ok) { showToast(data.error || 'Could not register store.', true); return; }
        } catch (e) {
            // Backend unreachable — keep working offline via localStorage only.
        }
        
        storesData[storeId] = {
            id: storeId,
            name: storeName,
            address: address || '',
            contact: contact || '',
            email: email || '',
            description: description || '',
            inventory: [],
            orders: [],
            createdAt: new Date().toISOString()
        };
        
        localStorage.setItem('compumatch_stores', JSON.stringify(storesData));
        
        closeStoreRegistration();
        showToast(`Store "${storeName}" registered successfully! You can now login.`);
        
        document.getElementById('loginStoreName').value = storeId;
        document.getElementById('loginPassword').value = '';
    }

    async function handleStoreLogin() {
        const storeId = document.getElementById('loginStoreName').value.trim().toLowerCase();
        const password = document.getElementById('loginPassword').value;
        
        if (!storeId || !password) {
            showToast('Please enter store ID and password', true);
            return;
        }
        
        let store = storesData[storeId];

        // Verify against the real database first (works even if this browser
        // never had the store cached locally, e.g. a different device).
        try {
            const res = await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'login_store', store_id: storeId, password })
            });
            const data = await res.json();
            if (!data.ok) { showToast(data.error || 'Invalid store ID or password', true); return; }
            if (!store) {
                // Not cached locally yet — build a minimal local record so the
                // dashboard has something to render (inventory syncs separately).
                store = { id: data.store.id, name: data.store.name, inventory: [], orders: [] };
                storesData[storeId] = store;
            }
            // Always refresh inventory from the database so changes made on
            // other devices/browsers show up here too.
            try {
                const invRes = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'list_inventory', store_id: storeId })
                });
                const invData = await invRes.json();
                if (invData.ok) {
                    store.inventory = invData.items;
                    storesData[storeId].inventory = invData.items;
                }
            } catch (e2) { /* keep whatever inventory we already have locally */ }
        } catch (e) {
            showToast('Could not reach the server. Please try again.', true);
            return;
        }
        
        currentStore = store;
        currentInventory = [...store.inventory];
        
        localStorage.setItem('compumatch_current_store', JSON.stringify({ id: store.id, name: store.name }));
        
        document.getElementById('loginScreen').style.display = 'none';
        document.getElementById('dashboardScreen').style.display = 'block';
        document.getElementById('storeNameDisplay').innerHTML = `<i class="fas fa-store"></i> ${store.name}`;
        
        refreshDashboard();
        showToast(`Welcome back, ${store.name}!`);
    }

    async function logout() {
        currentStore = null;
        localStorage.removeItem('compumatch_current_store');
        try {
            await fetch('api.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'logout_store' }) });
        } catch (e) {}
        window.location.href = 'landing.php';
    }

    function refreshDashboard() {
        updateStats();
        renderInventoryTable();
        renderAnalytics();
    }

    function updateStats() {
        document.getElementById('statTotalItems').textContent = currentInventory.length;
        const inStock = currentInventory.filter(i => i.stock > 0).length;
        const lowStock = currentInventory.filter(i => i.stock > 0 && i.stock <= i.lowStock).length;
        const outStock = currentInventory.filter(i => i.stock === 0).length;
        document.getElementById('statInStock').textContent = inStock;
        document.getElementById('statLowStock').textContent = lowStock;
        document.getElementById('statOutStock').textContent = outStock;
    }

    function renderInventoryTable() {
        let filtered = [...currentInventory];
        
        if (inventoryFilter.stock === 'in') {
            filtered = filtered.filter(i => i.stock > 0);
        } else if (inventoryFilter.stock === 'low') {
            filtered = filtered.filter(i => i.stock > 0 && i.stock <= i.lowStock);
        } else if (inventoryFilter.stock === 'out') {
            filtered = filtered.filter(i => i.stock === 0);
        }
        
        if (inventoryFilter.search) {
            const q = inventoryFilter.search;
            filtered = filtered.filter(i =>
                [i.name, i.brand, i.specs, i.socket, i.ram_type, i.category]
                    .some(v => String(v || '').toLowerCase().includes(q))
            );
        }
        
        // Counts on the category pills follow the stock filter and search
        const catCounts = { all: filtered.length };
        filtered.forEach(i => { catCounts[i.category] = (catCounts[i.category] || 0) + 1; });
        document.querySelectorAll('#invCategoryPills .bp-btn').forEach(b => {
            const el = b.querySelector('.bp-count');
            if (el) el.textContent = catCounts[b.dataset.cat] || 0;
        });
        if (inventoryFilter.category !== 'all') {
            filtered = filtered.filter(i => i.category === inventoryFilter.category);
        }

        const tbody = document.getElementById('inventoryTableBody');
        
        if (filtered.length === 0) {
            const msg = inventoryFilter.search ? `No items match "${escapeHtml(inventoryFilter.search)}"` : 'No items found';
            tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; padding: 40px;">${msg}</td></tr>`;
            return;
        }
        
        const categoryNames = { cpu: 'CPU', gpu: 'GPU', motherboard: 'Motherboard', ram: 'RAM', storage: 'Storage', psu: 'PSU', case: 'PC Case' };
        
        tbody.innerHTML = filtered.map(item => {
            let stockClass = 'stock-in';
            let stockText = `${item.stock} in stock`;
            if (item.stock === 0) {
                stockClass = 'stock-out';
                stockText = 'Out of Stock';
            } else if (item.stock <= item.lowStock) {
                stockClass = 'stock-low';
                stockText = `Low Stock (${item.stock})`;
            }
            
            return `
                <tr>
                    <td>${categoryNames[item.category] || item.category}</td>
                    <td><div class="inv-item" role="button" tabindex="0" title="View details" data-id="${escapeHtml(item.id)}" onclick="openItemDetails(this.dataset.id)" onkeydown="if(event.key==='Enter'){openItemDetails(this.dataset.id)}">${item.image ? `<img class="inv-thumb" src="${escapeHtml(item.image)}" alt="" loading="lazy">` : `<span class="inv-thumb inv-thumb-empty"><i class="fas fa-microchip"></i></span>`}<div class="inv-text"><strong class="inv-name">${escapeHtml(item.name)}</strong><small class="inv-specs">${escapeHtml(item.specs || '')}</small></div></div>${[item.socket, item.ram_type, item.ram_slots ? item.ram_slots + ' RAM slots' : null, item.m2_slots ? item.m2_slots + ' M.2' : null, item.sata_ports ? item.sata_ports + ' SATA' : null].filter(Boolean).map(t => ` <span class="stock-badge" style="background: rgba(0,212,255,0.12); color: var(--accent); margin-top: 4px;">${escapeHtml(t)}</span>`).join('')}</td>
                    <td>${item.brand}</td>
                    <td><strong>₱${item.price.toLocaleString()}</strong></td>
                    <td>${item.stock}</td>
                    <td><span class="stock-badge ${stockClass}">${stockText}</span></td>
                    <td class="action-buttons">
                        <button class="btn-success" onclick="editItem('${item.id}')"><i class="fas fa-edit"></i> Edit</button>
                        <button class="btn-danger" onclick="deleteItem('${item.id}')"><i class="fas fa-trash"></i> Delete</button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Item details pop-up: big photo + everything saved for the item.
    function openItemDetails(itemId) {
        const item = currentInventory.find(i => String(i.id) === String(itemId));
        if (!item) return;
        const catNames = { cpu: 'CPU / Processor', gpu: 'Graphics Card', motherboard: 'Motherboard', ram: 'Memory (RAM)', storage: 'Storage', psu: 'Power Supply', case: 'PC Case' };
        const facts = [];
        const add = (k, v) => { if (v !== null && v !== undefined && v !== '' && v !== 0) facts.push([k, v]); };
        add('Category', catNames[item.category] || item.category);
        add('Brand', item.brand);
        add('Price', '₱' + Number(item.price || 0).toLocaleString());
        add('Stock', item.stock + (item.stock > 0 && item.stock <= item.lowStock ? ' (low — alert at ' + item.lowStock + ')' : item.stock === 0 ? ' (out of stock)' : ''));
        add('Sold', item.sold);
        add('Socket', item.socket);
        add('Memory type', item.ram_type);
        add('Form factor', item.form_factor);
        add('Storage type', item.storage_type);
        add('RAM slots', item.ram_slots);
        add('M.2 slots', item.m2_slots);
        add('SATA ports', item.sata_ports);

        let modal = document.getElementById('itemDetailModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'itemDetailModal';
            modal.className = 'idm';
            modal.addEventListener('click', e => { if (e.target === modal) closeItemDetails(); });
            document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) closeItemDetails(); });
            document.body.appendChild(modal);
        }
        modal.innerHTML = `
            <div class="idm-box" role="dialog" aria-modal="true" aria-label="${escapeHtml(item.name)}">
                <div class="idm-head">
                    <h3>${escapeHtml(catNames[item.category] || 'Item details')}</h3>
                    <button type="button" class="idm-x" onclick="closeItemDetails()" title="Close"><i class="fas fa-xmark"></i></button>
                </div>
                <div class="idm-body">
                    <div class="idm-photo">${item.image
                        ? `<a href="${escapeHtml(item.image)}" target="_blank" rel="noopener" title="Open full-size photo"><img src="${escapeHtml(item.image)}" alt=""></a>`
                        : `<div class="idm-nophoto"><i class="fas fa-image"></i><span>No photo yet</span></div>`}</div>
                    <div class="idm-info">
                        <h2>${escapeHtml(item.name)}</h2>
                        <dl class="idm-facts">${facts.map(([k, v]) => `<dt>${escapeHtml(k)}</dt><dd>${escapeHtml(String(v))}</dd>`).join('')}</dl>
                        ${item.specs ? `<h4>Description / specs</h4><p class="idm-specs">${escapeHtml(item.specs)}</p>` : '<p class="idm-specs" style="color:var(--text-muted)">No description yet.</p>'}
                        <div class="idm-actions">
                            <button type="button" class="btn-success" data-id="${escapeHtml(item.id)}" onclick="closeItemDetails(); editItem(this.dataset.id)"><i class="fas fa-edit"></i> Edit</button>
                            <button type="button" class="btn-secondary" onclick="closeItemDetails()">Close</button>
                        </div>
                    </div>
                </div>
            </div>`;
        modal.classList.add('open');
    }
    function closeItemDetails() {
        const m = document.getElementById('itemDetailModal');
        if (m) m.classList.remove('open');
    }

    function setInvCategory(btn) {
        document.querySelectorAll('#invCategoryPills .bp-btn').forEach(b => b.classList.toggle('selected', b === btn));
        document.getElementById('categoryFilter').value = btn.dataset.cat;
        filterInventory();
    }

    function filterInventory() {
        inventoryFilter.category = document.getElementById('categoryFilter').value;
        inventoryFilter.stock = document.getElementById('stockFilter').value;
        inventoryFilter.search = (document.getElementById('inventorySearch').value || '').trim().toLowerCase();
        renderInventoryTable();
    }

    // Which compatibility fields each category needs.
    const COMPAT_FIELDS = {
        cpu:         { socket: true,  ramType: false },
        motherboard: { socket: true,  ramType: true, sata: true },
        ram:         { socket: false, ramType: true  }
    };

    const SOCKET_LABELS = {
        LGA1700: 'LGA1700 (Intel 12th–14th Gen)',
        LGA1200: 'LGA1200 (Intel 10th–11th Gen)',
        LGA1151: 'LGA1151 (Intel 6th–9th Gen)',
        AM5:     'AM5 (Ryzen 7000 / 8000 / 9000)',
        AM4:     'AM4 (Ryzen 1000–5000)'
    };
    const ALL_SOCKETS   = ['LGA1700', 'LGA1200', 'LGA1151', 'AM5', 'AM4'];
    const ALL_RAM_TYPES = ['DDR5', 'DDR4'];

    // "am5" / "lga 1700" / "1700" / "ddr-5"  ->  "AM5" / "LGA1700" / "LGA1700" / "DDR5"
    function normalizeCompat(v) {
        v = String(v || '').toUpperCase().replace(/[\s\-_.]/g, '');
        if (/^(1700|1200|1151)$/.test(v)) v = 'LGA' + v;
        return v;
    }

    // Marks a type-in box invalid (the browser shows the message on Save)
    // unless it holds one of the accepted types.
    function checkCompatField(el, label) {
        if (el._freeText) { el.setCustomValidity(''); return; }   // any text accepted (required is still enforced)
        const allowed = el._allowed || [];
        const v = normalizeCompat(el.value);
        el.setCustomValidity(!el.required || allowed.includes(v) ? '' : label + ' must be one of: ' + allowed.join(', ') + '.');
    }

    // Sets up a type-in box (no dropdown): the accepted types are listed under it.
    function fillCompatField(el, label, values, labels) {
        el._allowed = values;
        el.placeholder = 'e.g. ' + values[values.length - 1];
        document.getElementById(el.id + 'Hint').textContent = 'Type one of: ' + values.join(', ');
        checkCompatField(el, label);
    }

    function clearCompatField(el) {
        el.value = '';
        el._allowed = [];
        el.setCustomValidity('');
    }

    // Shows the CPU Socket / RAM Type boxes based on the chosen category only.
    // Hidden boxes are cleared and not required, so they never block saving.
    function toggleCompatFields() {
        const need = COMPAT_FIELDS[document.getElementById('itemCategory').value] || { socket: false, ramType: false };
        const socketEl = document.getElementById('itemSocket');
        const ramEl = document.getElementById('itemRamType');

        document.getElementById('compatRow').style.display = (need.socket || need.ramType || need.sata) ? 'grid' : 'none';
        const sataEl = document.getElementById('itemSataPorts');
        document.getElementById('sataGroup').style.display = need.sata ? 'block' : 'none';
        sataEl.required = !!need.sata;
        if (!need.sata) sataEl.value = '';
        const ramSlotsEl = document.getElementById('itemRamSlots');
        document.getElementById('ramSlotsGroup').style.display = need.sata ? 'block' : 'none';
        ramSlotsEl.required = !!need.sata;
        if (!need.sata) ramSlotsEl.value = '';
        const m2El = document.getElementById('itemM2Slots');
        document.getElementById('m2Group').style.display = need.sata ? 'block' : 'none';
        m2El.required = !!need.sata;
        if (!need.sata) m2El.value = '';
        document.getElementById('socketGroup').style.display = need.socket ? 'block' : 'none';
        document.getElementById('ramTypeGroup').style.display = need.ramType ? 'block' : 'none';
        socketEl.required = need.socket;
        ramEl.required = need.ramType;

        if (need.socket) {
            // Any socket is accepted; these are only examples.
            socketEl._freeText = true;
            socketEl._allowed = [];
            socketEl.placeholder = 'e.g. AM5, LGA1700, LGA1851';
            document.getElementById('itemSocketHint').textContent = 'Type the socket exactly as written in the product specs.';
            checkCompatField(socketEl, 'CPU Socket Type');
        } else clearCompatField(socketEl);
        if (need.ramType) {
            // Any RAM type is accepted; these are only examples.
            ramEl._freeText = true;
            ramEl._allowed = [];
            ramEl.placeholder = 'e.g. DDR4, DDR5';
            document.getElementById('itemRamTypeHint').textContent = 'Type the RAM type exactly as written in the product specs.';
            checkCompatField(ramEl, 'RAM Socket Type');
        } else clearCompatField(ramEl);
    }

    // ---------- Item photo ----------
    let itemImageBlob = null;      // new photo waiting to be uploaded on Save
    let itemImageRemoved = false;  // the saved photo should be removed on Save
    const ITEM_IMAGE_PLACEHOLDER = '<i class="fas fa-image"></i>';

    function showItemImagePreview(src) {
        const box = document.getElementById('itemImagePreview');
        box.innerHTML = src ? '<img alt="Item photo" style="width:100%; height:100%; object-fit:cover;">' : ITEM_IMAGE_PLACEHOLDER;
        if (src) box.querySelector('img').src = src;
        document.getElementById('itemImageRemove').style.display = src ? 'inline-block' : 'none';
    }

    function resetItemImage(savedPath) {
        itemImageBlob = null;
        itemImageRemoved = false;
        document.getElementById('itemImage').value = '';
        showItemImagePreview(savedPath || '');
    }

    // Shrinks the chosen photo to at most 900px (JPEG) so uploads are quick
    // and stay well under the host's limits.
    function onItemImageChosen(file) {
        if (!file) return;
        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            showToast('Photo must be a JPG, PNG, or WEBP image.', true);
            document.getElementById('itemImage').value = '';
            return;
        }
        const img = new Image();
        img.onload = () => {
            const scale = Math.min(1, 900 / Math.max(img.width, img.height));
            const canvas = document.createElement('canvas');
            canvas.width = Math.max(1, Math.round(img.width * scale));
            canvas.height = Math.max(1, Math.round(img.height * scale));
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = '#fff';                       // transparent PNG areas -> white
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            canvas.toBlob(b => {
                if (!b) { showToast('Could not read that photo. Try another one.', true); return; }
                itemImageBlob = b;
                itemImageRemoved = false;
                showItemImagePreview(URL.createObjectURL(b));
            }, 'image/jpeg', 0.85);
        };
        img.onerror = () => showToast('Could not read that photo. Try another one.', true);
        img.src = URL.createObjectURL(file);
    }

    function removeItemImage() {
        itemImageBlob = null;
        itemImageRemoved = true;
        document.getElementById('itemImage').value = '';
        showItemImagePreview('');
    }

    // Sends the new photo (or the removal) for an item that is already saved.
    // Returns the item's image path afterwards; `current` if nothing changed.
    async function syncItemImage(itemId, current) {
        if (!itemImageBlob && !itemImageRemoved) return current || null;
        const form = new FormData();
        form.append('action', 'upload_item_image');
        form.append('store_id', currentStore.id);
        form.append('password', '');   // authorised by the store's login session
        form.append('item_id', itemId);
        if (itemImageBlob) form.append('image', itemImageBlob, 'item.jpg');
        else form.append('remove', '1');
        try {
            const res = await fetch('api.php', { method: 'POST', body: form });
            const data = await res.json();
            if (!data.ok) { showToast(data.error || 'The item was saved, but the photo could not be uploaded.', true); return current || null; }
            return data.image || null;
        } catch (e) {
            showToast('The item was saved, but the photo could not be uploaded.', true);
            return current || null;
        }
    }

    function openAddItemModal() {
        document.getElementById('modalTitle').textContent = 'Add New Item';
        document.getElementById('itemForm').reset();
        document.getElementById('itemId').value = '';
        resetItemImage('');
        ['itemSocket', 'itemRamType'].forEach(id => clearCompatField(document.getElementById(id)));
        toggleCompatFields();
        document.getElementById('itemModal').style.display = 'flex';
    }

    function closeItemModal() {
        document.getElementById('itemModal').style.display = 'none';
    }

    function editItem(itemId) {
        const item = currentInventory.find(i => i.id === itemId);
        if (!item) return;
        
        document.getElementById('modalTitle').textContent = 'Edit Item';
        document.getElementById('itemId').value = item.id;
        document.getElementById('itemCategory').value = item.category;
        document.getElementById('itemName').value = item.name;
        document.getElementById('itemBrand').value = item.brand;
        // start clean so nothing carries over from the previously opened item
        ['itemSocket', 'itemRamType'].forEach(id => clearCompatField(document.getElementById(id)));
        toggleCompatFields();
        document.getElementById('itemSocket').value = item.socket || '';
        document.getElementById('itemRamType').value = item.ram_type || '';
        document.getElementById('itemSataPorts').value = item.sata_ports || '';
        document.getElementById('itemM2Slots').value = item.m2_slots || '';
        document.getElementById('itemRamSlots').value = item.ram_slots || '';
        checkCompatField(document.getElementById('itemSocket'), 'CPU Socket Type');
        checkCompatField(document.getElementById('itemRamType'), 'RAM Socket Type');
        document.getElementById('itemPrice').value = item.price;
        document.getElementById('itemStock').value = item.stock;
        document.getElementById('itemLowStock').value = item.lowStock;
        document.getElementById('itemSpecs').value = item.specs || '';
        resetItemImage(item.image || '');
        document.getElementById('itemModal').style.display = 'flex';
    }

    async function saveItem(event) {
        event.preventDefault();
        
        const itemId = document.getElementById('itemId').value;
        const category = document.getElementById('itemCategory').value;
        const name = document.getElementById('itemName').value;
        const brand = document.getElementById('itemBrand').value;
        const price = parseFloat(document.getElementById('itemPrice').value);
        const stock = parseInt(document.getElementById('itemStock').value);
        const lowStock = parseInt(document.getElementById('itemLowStock').value);
        const specs = document.getElementById('itemSpecs').value;
        const need = COMPAT_FIELDS[category] || { socket: false, ramType: false };
        const socket = need.socket ? (document.getElementById('itemSocket').value.toUpperCase().replace(/\s+/g, '') || null) : null;
        const ram_type = need.ramType ? (document.getElementById('itemRamType').value.toUpperCase().replace(/\s+/g, '') || null) : null;
        const sata_ports = need.sata ? (parseInt(document.getElementById('itemSataPorts').value, 10) || null) : null;
        const m2_slots = need.sata ? (parseInt(document.getElementById('itemM2Slots').value, 10) || null) : null;
        const ram_slots = need.sata ? (parseInt(document.getElementById('itemRamSlots').value, 10) || null) : null;

        let finalId = itemId;
        let savedOnline = false;
        const existing = itemId ? currentInventory.find(i => i.id === itemId) : null;
        let image = existing ? (existing.image || null) : null;

        // Save to the real database first so other devices see this change too.
        try {
            const res = await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'save_item', store_id: currentStore.id, password: '',
                    item_id: itemId || null, category, name, brand, price, stock,
                    low_stock: lowStock, specs, socket, ram_type, sata_ports, m2_slots, ram_slots
                })
            });
            const data = await res.json();
            if (!data.ok) { showToast(data.error || 'Could not save item to the database.', true); return; }
            finalId = data.id; // server-generated id for new items
            savedOnline = true;
        } catch (e) {
            // Backend unreachable — fall back to a locally-generated id (offline mode).
            if (!finalId) finalId = `${category}_${Date.now()}_${Math.random().toString(36).substr(2, 6)}`;
        }
        
        // The photo goes up after the item itself, since a new item has no id until then.
        if (savedOnline) image = await syncItemImage(finalId, image);

        if (itemId) {
            const index = currentInventory.findIndex(i => i.id === itemId);
            if (index !== -1) {
                currentInventory[index] = { ...currentInventory[index], category, name, brand, price, stock, lowStock, specs, socket, ram_type, sata_ports, m2_slots, ram_slots, image };
            }
        } else {
            currentInventory.push({
                id: finalId, category, name, brand, price, stock, lowStock, specs, socket, ram_type, sata_ports, m2_slots, ram_slots, image, sold: 0
            });
        }
        
        const storeKey = currentStore.id;
        storesData[storeKey].inventory = currentInventory;
        localStorage.setItem('compumatch_stores', JSON.stringify(storesData));
        
        closeItemModal();
        refreshDashboard();
        showToast(`Item ${itemId ? 'updated' : 'added'} successfully!`);
    }

    async function deleteItem(itemId) {
        if (confirm('Are you sure you want to delete this item?')) {
            try {
                const res = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete_item', store_id: currentStore.id, password: '', item_id: itemId })
                });
                const data = await res.json();
                if (!data.ok) { showToast(data.error || 'Could not delete item from the database.', true); return; }
            } catch (e) {
                // Backend unreachable — still remove locally so offline mode keeps working.
            }
            currentInventory = currentInventory.filter(i => i.id !== itemId);
            const storeKey = currentStore.id;
            storesData[storeKey].inventory = currentInventory;
            localStorage.setItem('compumatch_stores', JSON.stringify(storesData));
            refreshDashboard();
            showToast('Item deleted successfully');
        }
    }

    function renderAnalytics() {
        const categoryNames = { cpu: 'CPU', gpu: 'GPU', motherboard: 'Motherboard', ram: 'RAM', storage: 'Storage', psu: 'PSU', case: 'PC Case' };

        const totalValue = currentInventory.reduce((sum, i) => sum + (i.price * i.stock), 0);
        const totalUnits = currentInventory.reduce((sum, i) => sum + i.stock, 0);

        document.getElementById('analyticsInventoryValue').textContent = `₱${totalValue.toLocaleString()}`;
        document.getElementById('analyticsTotalUnits').textContent = totalUnits.toLocaleString();

        // Build a per-category summary
        const byCategory = {};
        currentInventory.forEach(i => {
            if (!byCategory[i.category]) {
                byCategory[i.category] = { items: 0, units: 0, value: 0 };
            }
            byCategory[i.category].items += 1;
            byCategory[i.category].units += i.stock;
            byCategory[i.category].value += i.price * i.stock;
        });

        // Most stocked category (by units in stock)
        let topCategory = 'None';
        let topUnits = -1;
        for (const [cat, data] of Object.entries(byCategory)) {
            if (data.units > topUnits) {
                topUnits = data.units;
                topCategory = categoryNames[cat] || cat.toUpperCase();
            }
        }
        document.getElementById('analyticsTopCategory').textContent = topUnits > 0 ? topCategory : '-';

        const body = document.getElementById('categoryBreakdownBody');
        const rows = Object.entries(byCategory).sort((a, b) => b[1].value - a[1].value);
        if (rows.length === 0) {
            body.innerHTML = `<tr><td colspan="4" style="text-align: center;">No inventory yet</td></tr>`;
        } else {
            body.innerHTML = rows.map(([cat, data]) => `
                <tr>
                    <td>${categoryNames[cat] || cat.toUpperCase()}</td>
                    <td>${data.items}</td>
                    <td>${data.units} units</td>
                    <td>₱${data.value.toLocaleString()}</td>
                </tr>
            `).join('');
        }
    }

    // Phone menu (☰): open / close the nav links dropdown.
    function toggleMobileNav(e) {
        if (e) e.stopPropagation();
        const nav = document.querySelector('.navbar');
        if (!nav) return;
        const open = !nav.classList.contains('nav-open');
        nav.classList.toggle('nav-open', open);
        const btn = document.getElementById('navToggle');
        if (btn) {
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            btn.innerHTML = open ? '<i class="fas fa-xmark"></i>' : '<i class="fas fa-bars"></i>';
        }
    }
    function closeMobileNav() {
        const nav = document.querySelector('.navbar');
        if (nav && nav.classList.contains('nav-open')) toggleMobileNav();
    }
    document.addEventListener('click', e => {
        const nav = document.querySelector('.navbar.nav-open');
        if (nav && !nav.contains(e.target)) closeMobileNav();
    });

    function showTab(tabName) {
        closeMobileNav();
        document.querySelectorAll('.nav-links a[data-tab]').forEach(a => a.classList.toggle('active', a.dataset.tab === tabName));
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        
        document.getElementById(`${tabName}Tab`).classList.add('active');
        
        const btns = document.querySelectorAll('.tab-btn');
        const btnIndex = tabName === 'inventory' ? 0 : 1;
        if (btns[btnIndex]) btns[btnIndex].classList.add('active');
        
        if (tabName === 'analytics') renderAnalytics();
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"']/g, function(m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m];
        });
    }

    initializeDemoStores();

    // The Admin Portal no longer has its own login. A store must sign in from
    // login.html (choosing "Computer Store"). If there's no valid store session,
    // send them straight to the login page.
    // The store is logged in only if the SERVER says so (login session from
    // login.php). Nothing secret is kept in the browser.
    (async function openStoreDashboard() {
        let saved = null;
        try { saved = JSON.parse(localStorage.getItem('compumatch_current_store') || 'null'); } catch (e) {}
        let who = null;
        try {
            const res = await fetch('api.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'whoami_store' }) });
            who = await res.json();
        } catch (e) {}
        if (!who || !who.ok || !who.logged_in || (saved && saved.id && saved.id !== who.store.id)) {
            localStorage.removeItem('compumatch_current_store');
            window.location.replace('login.php');
            return;
        }
        const sid = who.store.id;
        if (!storesData[sid]) storesData[sid] = { id: sid, name: who.store.name, inventory: [], orders: [] };
        currentStore = storesData[sid];
        currentStore.name = who.store.name;
        delete currentStore.password;
        localStorage.setItem('compumatch_current_store', JSON.stringify({ id: sid, name: who.store.name }));
        // Fresh inventory from the database
        try {
            const r = await fetch('api.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'list_inventory', store_id: sid }) });
            const d = await r.json();
            if (d.ok) currentStore.inventory = d.items;
        } catch (e) {}
        currentInventory = [...(currentStore.inventory || [])];
        document.getElementById('loginScreen').style.display = 'none';
        document.getElementById('dashboardScreen').style.display = 'block';
        document.getElementById('storeNameDisplay').innerHTML = `<i class="fas fa-store"></i> ${escapeHtml(who.store.name)}`;
        refreshDashboard();
    })();
    
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    };
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
                body: JSON.stringify({ action: action, page: 'admin', store_id: currentStoreId() })
            }).catch(function () {});
        } catch (e) {}
    }
    ping('track_visit');
    setInterval(function () { ping('heartbeat'); }, 120000); // every 2 min (keeps hosting load low)
})();
</script>
</body>
</html>