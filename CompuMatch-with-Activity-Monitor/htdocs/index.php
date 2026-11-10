<?php
require_once __DIR__ . '/db.php';
$BOOT = bootstrapStores();
// Customer-facing page: only list stores whose business permit is approved.
if (is_array($BOOT)) {
    $BOOT = array_filter($BOOT, function ($s) { return !empty($s['verified']); });
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>CompuMatch - Smart PC Build Recommendation System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="style.css?v=<?php echo (int)@filemtime(__DIR__ . '/style.css'); ?>">
    <style>
    /* ===== CompuMatch shared brand logo ===== */
    .cm-logo{display:inline-flex!important;align-items:center;gap:10px;font-weight:700;font-size:1.25rem;color:#fff!important;text-decoration:none;line-height:1;background:none!important;-webkit-background-clip:initial!important;background-clip:initial!important;}
    .cm-logo-badge{width:34px;height:34px;border-radius:50%;flex-shrink:0;background:radial-gradient(circle at 35% 30%,#12233e,#060a12)!important;border:2px solid #00D4FF;box-shadow:0 0 10px rgba(0,212,255,.55),inset 0 0 6px rgba(0,212,255,.35);display:flex;align-items:center;justify-content:center;color:#00D4FF!important;font-size:.95rem;}
    .cm-logo-badge i{color:#00D4FF!important;background:none!important;-webkit-background-clip:initial!important;margin:0!important;padding:0!important;font-size:.95rem!important;line-height:1!important;}
    .cm-logo,.cm-logo *{box-sizing:border-box;}
    .cm-logo-accent{color:#00D4FF!important;}

        /* Store Selection Styles */
        .store-selection-section {
            background: linear-gradient(135deg, var(--bg-card), var(--bg-dark));
            border-radius: 24px;
            padding: 32px;
            margin-bottom: 32px;
            border: 1px solid var(--border);
            text-align: center;
        }

        .store-selection-title {
            font-size: 1.4rem;
            margin-bottom: 8px;
        }

        .store-selection-sub {
            color: var(--text-muted);
            margin-bottom: 24px;
            font-size: 0.9rem;
        }

        .store-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-bottom: 24px;
        }

        .store-card {
            background: var(--bg-dark);
            border: 2px solid var(--border);
            border-radius: 20px;
            padding: 20px 28px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            min-width: 180px;
        }

        .store-card i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 12px;
            display: block;
        }

        .store-card h4 {
            margin-bottom: 4px;
            font-size: 1.1rem;
        }

        .store-card p {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        .store-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
        }

        .store-card.selected {
            border-color: var(--primary);
            background: rgba(108, 99, 255, 0.1);
            box-shadow: 0 0 20px rgba(108, 99, 255, 0.2);
        }

        .store-card.selected i {
            color: var(--accent);
        }

        .warning-message {
            background: rgba(255, 82, 82, 0.15);
            border: 1px solid var(--warning);
            border-radius: 12px;
            padding: 12px 20px;
            margin-top: 16px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--warning);
        }

        .no-items-message {
            text-align: center;
            padding: 60px;
            background: var(--bg-card);
            border-radius: 20px;
        }

        .no-items-message i {
            font-size: 4rem;
            color: var(--text-muted);
            margin-bottom: 16px;
            opacity: 0.5;
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

<!-- Navigation -->
<nav class="navbar">
    <div class="nav-container">
        <div class="logo" onclick="window.location.href='landing.php'" style="cursor:pointer;" title="Back to home">
            <span class="cm-logo"><span class="cm-logo-badge"><i class="fas fa-microchip"></i></span>Compu<span class="cm-logo-accent">Match</span></span>
        </div>
        <button type="button" class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false" aria-controls="navLinks" onclick="toggleMobileNav(event)">
            <i class="fas fa-bars"></i>
        </button>
        <div class="nav-links" id="navLinks">
            <a href="#" data-page="home" onclick="showPage('home'); return false;">Home</a>
            <a href="#" data-page="build" onclick="showPage('build'); return false;">Build PC</a>
            <a href="#" data-page="custom" onclick="showPage('custom'); return false;">Custom Build</a>
            <a href="#" data-page="browse" onclick="showPage('browse'); return false;">Browse Parts</a>
            <a href="#" data-page="simulator" onclick="showPage('simulator'); return false;">Simulator</a>
            <a href="#" data-page="mybuilds" onclick="showPage('mybuilds'); return false;">My Builds</a>
            <button class="btn-primary" onclick="showPage('build')">Get Started</button>
            <div class="profile-menu" id="profileArea"></div>
        </div>
    </div>
</nav>

<main id="app">
    <!-- HOME PAGE -->
    <div id="home-page">
        <div class="hero">
            <div class="container">
                <h1>Build Your Perfect PC.<br>No Compatibility Nightmares.</h1>
                <p>Get budget-optimized, compatible PC builds tailored to your needs - whether for gaming, school, or creative work.</p>
                <div class="hero-buttons">
                    <button class="btn-primary" onclick="showPage('build')">Get Recommendations →</button>
                    <button class="btn-secondary" onclick="showPage('custom')">Build Your Own PC</button>
                    <button class="btn-outline" onclick="showPage('browse')">Browse Components</button>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="section">
                <h2 class="section-title">How It Works</h2>
                <div class="steps-grid">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h3>Choose Your Store</h3>
                        <p>Select from our partner computer stores</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h3>Set Your Preferences</h3>
                        <p>Choose budget, purpose, and performance level</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3>Get Recommendations</h3>
                        <p>Compatible build suggestions mixed from all partner stores</p>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">Partner Stores</h2>
                <div class="store-grid" id="homeStoreGrid"></div>
            </div>

            <div class="section">
                <h2 class="section-title">Features</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <i class="fas fa-check-circle"></i>
                        <h3>Smart Compatibility Check</h3>
                        <p>Automatic CPU, motherboard, RAM verification</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-chart-line"></i>
                        <h3>Budget Optimization</h3>
                        <p>Best price-to-performance ratio</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>Real-time Warnings</h3>
                        <p>Alerts for bottlenecks and issues</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-store"></i>
                        <h3>Multi-Store Support</h3>
                        <p>Compare prices from different computer stores</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-save"></i>
                        <h3>Save Builds</h3>
                        <p>Save and compare multiple configurations</p>
                    </div>
                </div>
            </div>
        </div>
        <div id="aboutSection" class="container" style="padding: 60px 0;">
            <div class="about-content">
                <h2 style="text-align: center; margin-bottom: 30px;">About CompuMatch</h2>
                <p style="text-align: center; max-width: 800px; margin: 0 auto; color: var(--text-muted);">
                    CompuMatch is a PC building assistant that helps you find compatible components for your dream PC.
                    Whether you want AI-powered recommendations or prefer to build manually, we've got you covered!
                </p>
            </div>
        </div>
    </div>

    <!-- BUILD PREFERENCES PAGE (Recommended) -->
    <div id="build-page" class="hidden">
        <div class="container" style="padding: 60px 0;">
            <h1 style="text-align: center; margin-bottom: 40px;">Configure Your PC Build</h1>
            <p style="text-align: center; max-width: 700px; margin: 0 auto 30px; color: var(--text-muted);">
                <i class="fas fa-shuffle"></i> We'll mix and match the best available parts across all partner stores for you.
            </p>
            
            <div id="buildPreferencesForm">
                <div class="form-container">
                    <div class="form-group">
                        <label>💰 Budget Range (₱)</label>
                        <div class="budget-input-group">
                            <!-- dual-thumb range slider -->
                            <div class="range-slider" id="rangeSlider">
                                <div class="range-track"></div>
                                <div class="range-fill" id="rangeFill"></div>
                                <input type="range" id="budgetMinSlider" min="15000" max="200000" step="5000" value="50000" aria-label="Minimum budget">
                                <input type="range" id="budgetMaxSlider" min="15000" max="200000" step="5000" value="100000" aria-label="Maximum budget">
                            </div>
                            <div class="budget-value-controls">
                                <div class="budget-range-inputs">
                                    <div class="budget-display-wrapper">
                                        <span>₱</span>
                                        <input type="number" id="budgetMinInput" class="budget-number-input" value="50000" min="15000" max="200000" step="5000">
                                    </div>
                                    <span class="range-dash">–</span>
                                    <div class="budget-display-wrapper">
                                        <span>₱</span>
                                        <input type="number" id="budgetMaxInput" class="budget-number-input" value="100000" min="15000" max="200000" step="5000">
                                    </div>
                                </div>
                                <div class="budget-presets">
                                    <button type="button" class="preset-btn" data-min="15000" data-max="50000">≤₱50K</button>
                                    <button type="button" class="preset-btn" data-min="50000" data-max="75000">₱50–75K</button>
                                    <button type="button" class="preset-btn" data-min="50000" data-max="100000">₱50–100K</button>
                                    <button type="button" class="preset-btn" data-min="75000" data-max="100000">₱75–100K</button>
                                    <button type="button" class="preset-btn" data-min="100000" data-max="150000">₱100–150K</button>
                                    <button type="button" class="preset-btn" data-min="150000" data-max="200000">₱150K+</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>🎯 Intended Use</label>
                        <div class="radio-group" id="useGroup">
                            <div class="radio-card" data-value="office">
                                <i class="fas fa-book"></i>
                                <h4>Office/<wbr>School</h4>
                                <p>Document processing, web browsing</p>
                            </div>
                            <div class="radio-card selected" data-value="gaming">
                                <i class="fas fa-gamepad"></i>
                                <h4>Gaming</h4>
                                <p>AAA titles, high FPS</p>
                            </div>
                            <div class="radio-card" data-value="creative">
                                <i class="fas fa-video"></i>
                                <h4>Creative/<wbr>Media</h4>
                                <p>Video editing, 3D rendering</p>
                            </div>
                            <div class="radio-card" data-value="business">
                                <i class="fas fa-briefcase"></i>
                                <h4>Business</h4>
                                <p>Productivity, multitasking</p>
                            </div>
                            <div class="radio-card" data-value="networking">
                                <i class="fas fa-network-wired"></i>
                                <h4>Networking/<wbr>Server</h4>
                                <p>NAS, home server, network setup</p>
                            </div>
                            <div class="radio-card" data-value="virtualization">
                                <i class="fas fa-layer-group"></i>
                                <h4>Virtualization/<wbr>Labs</h4>
                                <p>VMs, multi-OS, CCNA/network labs</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>⚡ Performance Level</label>
                        <div class="radio-group" id="perfGroup">
                            <div class="radio-card" data-value="entry">Entry Level</div>
                            <div class="radio-card selected" data-value="balanced">Balanced</div>
                            <div class="radio-card" data-value="high">High Performance</div>
                            <div class="radio-card" data-value="enthusiast">Enthusiast</div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 16px; justify-content: center; margin-top: 32px;">
                        <button class="btn-secondary" onclick="showPage('home')">Cancel</button>
                        <button class="btn-primary" onclick="generateBuilds()">Generate Builds →</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RECOMMENDED BUILDS PAGE -->
    <div id="results-page" class="hidden">
        <div class="container" style="padding: 60px 0;">
            <h1 style="text-align: center; margin-bottom: 16px;">Recommended Builds</h1>
            <p id="resultsSubtitle" style="text-align: center; color: var(--text-muted); margin-bottom: 24px;">Based on your preferences</p>
            <div id="buildsNote" class="builds-note" style="display:none;"></div>
            <div id="buildsGrid" class="builds-grid"></div>
            <div style="text-align: center; margin-top: 40px;">
                <button class="btn-secondary" onclick="showPage('build')">← Back to Preferences</button>
                <button class="btn-primary" onclick="showPage('custom')">← Build Your Own PC Instead</button>
            </div>
        </div>
    </div>

    <!-- CUSTOM BUILD PAGE (Build Your Own PC) -->
    <div id="custom-page" class="hidden">
        <div class="container" style="padding: 60px 0;">
            <div style="text-align: center; margin-bottom: 32px;">
                <h1><i class="fas fa-wrench"></i> Build Your Own PC</h1>
                <p style="color: var(--text-muted);">Select each component manually. Only items from your chosen store will appear!</p>
            </div>
            
            <div class="cb-top-row">
            <!-- Store Selection for Custom Build -->
            <div class="store-selection-section cb-store-side">
                <h3 class="store-selection-title"><i class="fas fa-store"></i> Select Your Store</h3>
                <p class="store-selection-sub">Choose a computer store to see their available inventory</p>
                <div class="store-grid" id="customStoreGrid"></div>
                <div id="customSelectedStoreWarning" class="warning-message" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Please select a store first to see available components.</span>
                </div>
            </div>
                    <!-- Brand Filter Section -->
                    <div class="brand-filter-section" id="brandFilterSection" style="display: none;">
                        <h3 class="cb-side-title"><i class="fas fa-tags"></i> Filter by Brand <small>(optional)</small></h3>
                        <div class="brand-filter-grid" id="brandFilterGrid">
                            <div class="brand-card selected" data-brand="all">
                                <i class="fas fa-microchip"></i>
                                <span>All Brands</span>
                            </div>
                            <div class="brand-card" data-brand="intel">
                                <i class="fas fa-microchip"></i>
                                <span>Intel</span>
                            </div>
                            <div class="brand-card" data-brand="amd">
                                <i class="fas fa-microchip"></i>
                                <span>AMD</span>
                            </div>
                            <div class="brand-card" data-brand="nvidia">
                                <i class="fas fa-tv"></i>
                                <span>NVIDIA</span>
                            </div>
                            <div class="brand-card" data-brand="corsair">
                                <i class="fas fa-memory"></i>
                                <span>Corsair</span>
                            </div>
                            <div class="brand-card" data-brand="kingston">
                                <i class="fas fa-database"></i>
                                <span>Kingston</span>
                            </div>
                            <div class="brand-card" data-brand="gskill">
                                <i class="fas fa-microchip"></i>
                                <span>G.Skill</span>
                            </div>
                            <div class="brand-card" data-brand="msi">
                                <i class="fas fa-microchip"></i>
                                <span>MSI</span>
                            </div>
                            <div class="brand-card" data-brand="asus">
                                <i class="fas fa-microchip"></i>
                                <span>ASUS</span>
                            </div>
                            <div class="brand-card" data-brand="gigabyte">
                                <i class="fas fa-microchip"></i>
                                <span>Gigabyte</span>
                            </div>
                            <div class="brand-card" data-brand="seasonic">
                                <i class="fas fa-bolt"></i>
                                <span>Seasonic</span>
                            </div>
                            <div class="brand-card" data-brand="coolermaster">
                                <i class="fas fa-fan"></i>
                                <span>Cooler Master</span>
                            </div>
                            <div class="brand-card" data-brand="samsung">
                                <i class="fas fa-database"></i>
                                <span>Samsung</span>
                            </div>
                            <div class="brand-card" data-brand="seagate">
                                <i class="fas fa-hdd"></i>
                                <span>Seagate</span>
                            </div>
                            <div class="brand-card" data-brand="wd">
                                <i class="fas fa-hdd"></i>
                                <span>WD</span>
                            </div>
                            <div class="brand-card" data-brand="nzxt">
                                <i class="fas fa-server"></i>
                                <span>NZXT</span>
                            </div>
                            <div class="brand-card" data-brand="lianli">
                                <i class="fas fa-server"></i>
                                <span>Lian Li</span>
                            </div>
                        </div>
                    </div>
            </div>

            <div id="customBuildContent" style="display: none;">
                <!-- Component Selectors -->
                <div class="configurator-layout">
                    <div class="components-list" id="componentsList"></div>
                    <div class="summary-panel" id="summaryPanel"></div>
                </div>
                
                <!-- Compatibility Report -->
                <div id="compatibilityReport" style="margin-top: 30px;"></div>
                
                <div style="margin-top: 30px; text-align: center; display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                    <button class="btn-secondary" onclick="showPage('home')">← Back to Home</button>
                    <button class="btn-primary" onclick="viewBuildIn3D()">🧊 View in Simulator</button>
                    <button class="btn-primary" onclick="openSaveModal()">💾 Save This Build</button>
                    <button class="btn-outline" onclick="resetCustomBuild()">🔄 Reset All</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MY BUILDS PAGE -->
    <div id="mybuilds-page" class="hidden">
        <div class="container" style="padding: 60px 0;">
            <h1 style="margin-bottom: 32px;"><i class="fas fa-folder"></i> My Saved Builds</h1>
            <div id="savedBuildsGrid" class="builds-grid"></div>
            <div id="emptyBuildsMsg" style="text-align: center; color: var(--text-muted);">
                <i class="fas fa-box-open" style="font-size: 4rem; margin-bottom: 16px; opacity: 0.5;"></i>
                <p>No builds yet. Start your first build!</p>
                <div style="display: flex; gap: 16px; justify-content: center; margin-top: 20px;">
                    <button class="btn-primary" onclick="showPage('build')">Get Recommendations</button>
                    <button class="btn-secondary" onclick="showPage('custom')">Build Your Own PC</button>
                </div>
            </div>
            <div id="guestBuildsMsg" style="display:none; text-align: center; color: var(--text-muted);">
                <i class="fas fa-user-lock" style="font-size: 4rem; margin-bottom: 16px; opacity: 0.5;"></i>
                <p>Please log in to view your saved builds.</p>
                <div style="display: flex; gap: 16px; justify-content: center; margin-top: 20px;">
                    <a class="btn-primary" href="login.php">Log In</a>
                    <a class="btn-secondary" href="signup.php">Sign Up</a>
                </div>
            </div>
        </div>
    </div>

    <!-- BROWSE PARTS PAGE -->
    <div id="browse-page" class="hidden">
        <div class="container" style="padding: 60px 0;">
            <h1 style="margin-bottom: 16px;"><i class="fas fa-search"></i> Browse Components</h1>
            <p style="color: var(--text-muted); margin-bottom: 32px;">Explore all available PC parts from different stores</p>
            
            <div class="store-selection-section" style="margin-bottom: 24px;">
                <h3 class="store-selection-title"><i class="fas fa-store"></i> Filter by Store</h3>
                <div class="store-grid" id="browseStoreGrid"></div>
            </div>
            
            <div class="browse-toolbar">
            <div class="browse-search">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" id="browseSearch" placeholder="Search parts by name, brand, or spec…" autocomplete="off">
                <button class="browse-search-clear" id="browseSearchClear" onclick="clearBrowseSearch()" aria-label="Clear search" style="display:none;">&times;</button>
            </div>

            <div class="browse-brand-filter">
                <label for="browseBrandFilter"><i class="fas fa-tags"></i> Brand:</label>
                <select id="browseBrandFilter" class="brand-filter-select">
                    <option value="all">All Brands</option>
                    <option value="intel">Intel</option>
                    <option value="amd">AMD</option>
                    <option value="nvidia">NVIDIA</option>
                    <option value="corsair">Corsair</option>
                    <option value="kingston">Kingston</option>
                    <option value="gskill">G.Skill</option>
                    <option value="msi">MSI</option>
                    <option value="asus">ASUS</option>
                    <option value="gigabyte">Gigabyte</option>
                    <option value="seasonic">Seasonic</option>
                    <option value="coolermaster">Cooler Master</option>
                    <option value="samsung">Samsung</option>
                    <option value="seagate">Seagate</option>
                    <option value="wd">WD</option>
                    <option value="nzxt">NZXT</option>
                    <option value="lianli">Lian Li</option>
                    <option value="cougar">Cougar</option>
                    <option value="tecware">Tecware</option>
                </select>
            </div>
            </div>
            
            <div class="bp-tabs"><span class="bp-tab active"><i class="fas fa-cubes"></i> Parts</span></div>
            <div class="bp-switch radio-group" id="partCategoryFilter" role="tablist">
                <button type="button" class="radio-card bp-btn selected" data-cat="all"><i class="fas fa-layer-group"></i> All <span class="bp-count">0</span></button>
                <button type="button" class="radio-card bp-btn" data-cat="cpu"><i class="fas fa-microchip"></i> CPU <span class="bp-count">0</span></button>
                <button type="button" class="radio-card bp-btn" data-cat="gpu"><i class="fas fa-display"></i> GPU <span class="bp-count">0</span></button>
                <button type="button" class="radio-card bp-btn" data-cat="motherboard"><i class="fas fa-chess-board"></i> Motherboard <span class="bp-count">0</span></button>
                <button type="button" class="radio-card bp-btn" data-cat="ram"><i class="fas fa-memory"></i> RAM <span class="bp-count">0</span></button>
                <button type="button" class="radio-card bp-btn" data-cat="storage"><i class="fas fa-hard-drive"></i> Storage <span class="bp-count">0</span></button>
                <button type="button" class="radio-card bp-btn" data-cat="psu"><i class="fas fa-plug"></i> Power Supply <span class="bp-count">0</span></button>
                <button type="button" class="radio-card bp-btn" data-cat="case"><i class="fas fa-box"></i> Case <span class="bp-count">0</span></button>
            </div>
            <div class="form-container bp-list">
                <div id="partsGrid" class="components-list"></div>
            </div>
        </div>
    </div>
    <!-- 3D PC BUILD SIMULATOR PAGE -->
    <div id="simulator-page" class="hidden">
        <div class="container" style="padding: 48px 0;">
            <h1 style="margin-bottom: 28px;"><i class="fas fa-cube"></i> Simulator</h1>

            <div class="sim-layout">
                <!-- 3D Viewport -->
                <div class="sim-viewport-wrap">
                    <div id="simCanvasHolder" class="sim-canvas-holder">
                        <div id="simLoading" class="sim-loading">Loading simulator…</div>
                    </div>
                    <div class="sim-view-controls">
                        <button class="sim-icon-btn" onclick="simResetView()" title="Reset view"><i class="fas fa-arrows-rotate"></i></button>
                        <button class="sim-icon-btn" onclick="simTopView()" title="Top view"><i class="fas fa-arrow-down"></i></button>
                        <button class="sim-icon-btn" id="simAutoRotateBtn" onclick="simToggleAutoRotate()" title="Auto-rotate"><i class="fas fa-sync"></i></button>
                    </div>
                    <div id="simPartInfo" class="sim-part-info">Click a part to inspect it.</div>
                </div>

                <!-- Parts panel -->
                <div class="sim-panel">
                    <div class="sim-progress">
                        <div class="sim-progress-head">
                            <span>Build Progress</span>
                            <span id="simProgressCount">0 / 7</span>
                        </div>
                        <div class="sim-progress-bar"><div id="simProgressFill" class="sim-progress-fill"></div></div>
                    </div>

                    <div id="simPartsList" class="sim-parts-list"></div>

                    <div class="sim-panel-actions">
                        <button class="btn-primary" style="width:100%;" onclick="simBuildAll()">⚡ Auto-Assemble All</button>
                        <div style="display:flex; gap:10px; margin-top:10px;">
                            <button class="btn-secondary" style="flex:1;" onclick="simLoadCustomBuild()">↓ Load My Build</button>
                            <button class="btn-outline" style="flex:1;" onclick="simResetBuild()">🔄 Reset</button>
                        </div>
                    </div>

                    <div id="simTotalPrice" class="sim-total"></div>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h4><span class="cm-logo"><span class="cm-logo-badge"><i class="fas fa-microchip"></i></span>Compu<span class="cm-logo-accent">Match</span></span></h4>
                <p>Smart PC building assistant</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <a href="#" onclick="showPage('build')">Get Recommendations</a>
                <a href="#" onclick="showPage('custom')">Build Your Own PC</a>
                <a href="#" onclick="showPage('browse')">Browse Parts</a>
                <a href="#" onclick="showPage('mybuilds')">My Builds</a>
                <a href="#" onclick="scrollToAbout()">About</a>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <a href="#">compumatch@nwssu.edu.ph</a>
                <a href="#">Northwest Samar State University</a>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2026 CompuMatch - A Capstone Project | NWSSU - Calbayog City</p>
        </div>
    </div>
</footer>

<!-- SAVE MODAL -->
<div id="saveModal" class="modal">
    <div class="modal-content">
        <h3>Save Your Build</h3>
        <input type="text" id="buildNameInput" placeholder="Build name (e.g., My Gaming Rig)">
        <div class="modal-buttons">
            <button class="btn-secondary" onclick="closeSaveModal()">Cancel</button>
            <button class="btn-primary" onclick="saveCurrentBuild()">Save</button>
        </div>
    </div>
</div>

<!-- Login Required Modal (replaces the native browser confirm()) -->
<div id="loginRequiredModal" class="modal" onclick="if(event.target===this)closeLoginRequiredModal()">
    <div class="modal-content" style="text-align:center;">
        <i class="fas fa-user-lock" style="font-size:2.5rem;color:var(--primary);margin-bottom:12px;display:block;"></i>
        <h3>Log In Required</h3>
        <p style="color:var(--text-muted);margin:12px 0 20px;">You need to be logged in to save a build.</p>
        <div class="modal-buttons" style="justify-content:center;">
            <button class="btn-secondary" onclick="closeLoginRequiredModal()">Cancel</button>
            <button class="btn-primary" onclick="window.location.href='login.php'">Go to Login</button>
        </div>
    </div>
</div>

<!-- Profile Settings Modal -->
<div id="profileSettingsModal" class="modal" onclick="if(event.target===this)closeProfileSettings()">
    <div class="modal-content">
        <h3><i class="fas fa-gear"></i> Profile Settings</h3>
        <label class="settings-label">Display Name</label>
        <input type="text" id="settingsName" placeholder="Your name">
        <label class="settings-label">Email Address</label>
        <input type="email" id="settingsEmail" placeholder="you@example.com">
        <label class="settings-label">Current Password</label>
        <input type="password" id="settingsPassword" placeholder="Enter your current password to confirm">
        <p style="font-size:0.8rem; color:var(--text-muted, #9e9e9e); margin-top:-8px; margin-bottom:12px;">
            Required to confirm changes to your saved account.
        </p>
        <div class="modal-buttons">
            <button class="btn-secondary" onclick="closeProfileSettings()">Cancel</button>
            <button class="btn-primary" onclick="saveProfileSettings()">Save Changes</button>
        </div>
    </div>
</div>

<script src="three_min.js?v=<?php echo (int)@filemtime(__DIR__ . '/three_min.js'); ?>"></script>
<script>
// Data provided server-side from MySQL (PHP). Always refresh localStorage
// with this on load so store/inventory changes (e.g. deletions made in
// System Admin) show up right away instead of using a stale cached copy.
window.__BOOTSTRAP__ = <?php echo $BOOT ? json_encode($BOOT) : "null"; ?>;
try {
  if (window.__BOOTSTRAP__) {
    localStorage.setItem("compumatch_stores", JSON.stringify(window.__BOOTSTRAP__));
  }
} catch (e) {}
</script>
<script src="script.js?v=<?php echo (int)@filemtime(__DIR__ . '/script.js'); ?>"></script>
<script src="simulator.js?v=<?php echo (int)@filemtime(__DIR__ . '/simulator.js'); ?>"></script>
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
                body: JSON.stringify({ action: action, page: 'index', store_id: currentStoreId() })
            }).catch(function () {});
        } catch (e) {}
    }
    ping('track_visit');
    setInterval(function () { ping('heartbeat'); }, 120000); // every 2 min (keeps hosting load low)
})();
</script>
</body>
</html>