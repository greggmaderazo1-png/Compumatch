<?php
// Live stats from MySQL (falls back to static numbers if DB is off / db.php missing)
$stats = ['stores' => 2, 'parts' => 41, 'categories' => 7];
$dbFile = __DIR__ . '/db.php';
if (file_exists($dbFile)) {
    require_once $dbFile;
    try {
        $pdo = getPDO();
        $stats['stores']     = (int)$pdo->query('SELECT COUNT(*) c FROM stores')->fetch()['c'];
        $stats['parts']      = (int)$pdo->query('SELECT COUNT(*) c FROM inventory_items')->fetch()['c'];
        $stats['categories'] = (int)$pdo->query('SELECT COUNT(DISTINCT category) c FROM inventory_items')->fetch()['c'];
    } catch (Throwable $e) { /* keep fallback numbers */ }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CompuMatch — Build a PC that just works</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    /* ===== CompuMatch shared brand logo ===== */
    .cm-logo{display:inline-flex!important;align-items:center;gap:10px;font-weight:700;font-size:1.25rem;color:#fff!important;text-decoration:none;line-height:1;background:none!important;-webkit-background-clip:initial!important;background-clip:initial!important;}
    .cm-logo-badge{width:34px;height:34px;border-radius:50%;flex-shrink:0;background:radial-gradient(circle at 35% 30%,#12233e,#060a12)!important;border:2px solid #00D4FF;box-shadow:0 0 10px rgba(0,212,255,.55),inset 0 0 6px rgba(0,212,255,.35);display:flex;align-items:center;justify-content:center;color:#00D4FF!important;font-size:.95rem;}
    .cm-logo-badge i{color:#00D4FF!important;background:none!important;-webkit-background-clip:initial!important;margin:0!important;padding:0!important;font-size:.95rem!important;line-height:1!important;}
    .cm-logo,.cm-logo *{box-sizing:border-box;}
    .cm-logo-accent{color:#00D4FF!important;}

:root{
  --primary:#6C63FF; --accent:#00D4FF; --success:#00E676; --warning:#FF5252;
  --bg:#0f0f14; --bg-deep:#0a0b10; --card:#1a1a24; --card-2:#22222e;
  --border:#2d2d3a; --text:#e8e8ef; --muted:#9a9ab0;
  --grad:linear-gradient(120deg,var(--primary),var(--accent));
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{
  font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);
  line-height:1.6;overflow-x:hidden;-webkit-font-smoothing:antialiased;
}
a{color:inherit;text-decoration:none}
h1,h2,h3,.display{font-family:'Poppins',sans-serif;line-height:1.1}
.wrap{max-width:1160px;margin:0 auto;padding:0 24px}
.mono{font-family:'JetBrains Mono',monospace}
.eyebrow{
  font-family:'JetBrains Mono',monospace;font-size:.74rem;letter-spacing:.28em;
  text-transform:uppercase;color:var(--accent);
}

/* ---------- nav ---------- */
.nav{position:sticky;top:0;z-index:50;backdrop-filter:blur(12px);
  background:rgba(15,15,20,.72);border-bottom:1px solid var(--border)}
.nav-inner{display:flex;align-items:center;justify-content:space-between;height:68px}
.brand{display:flex;align-items:center;gap:10px;font-family:'Poppins';font-weight:700;font-size:1.2rem}
.brand .chip{width:30px;height:30px;border-radius:8px;background:var(--grad);
  display:grid;place-items:center;color:#0a0b10;box-shadow:0 0 18px rgba(0,212,255,.45)}
.brand b{color:var(--accent)}
.nav-links{display:flex;align-items:center;gap:28px}
.nav-links a.link{color:var(--muted);font-weight:500;font-size:.95rem}
.nav-links a.link:hover{color:var(--text)}
.btn{display:inline-flex;align-items:center;gap:9px;border-radius:11px;font-weight:600;
  padding:11px 20px;font-size:.95rem;border:1px solid transparent;cursor:pointer;transition:.18s}
.btn-ghost{border-color:var(--border);color:var(--text);background:transparent}
.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.btn-grad{background:var(--grad);color:#08090d;box-shadow:0 8px 26px rgba(108,99,255,.35)}
.btn-grad:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(0,212,255,.45)}
.nav-toggle{display:none;background:none;border:0;color:var(--text);font-size:1.4rem}

/* ---------- hero ---------- */
.hero{position:relative;padding:88px 0 72px;overflow:hidden}
.hero-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:56px;align-items:center}
.hero h1{font-size:clamp(2.5rem,5.4vw,4rem);font-weight:800;letter-spacing:-.02em;margin:18px 0}
.hero h1 .hl{background:var(--grad);-webkit-background-clip:text;background-clip:text;color:transparent}
.hero p.lead{color:var(--muted);font-size:1.16rem;max-width:30em;margin-bottom:30px}
.hero-cta{display:flex;gap:14px;flex-wrap:wrap}
.hero-note{margin-top:20px;color:var(--muted);font-size:.86rem;display:flex;align-items:center;gap:8px}
.hero-note i{color:var(--success)}

/* faint PCB traces in the hero background */
.traces{position:absolute;inset:0;width:100%;height:100%;z-index:0;opacity:.5;pointer-events:none}
.traces path{fill:none;stroke:url(#tg);stroke-width:1.4}
.traces .pulse{stroke:var(--accent);stroke-width:1.6;stroke-dasharray:6 240;filter:drop-shadow(0 0 5px var(--accent));animation:flow 6s linear infinite}
@keyframes flow{to{stroke-dashoffset:-246}}
.traces circle{fill:var(--accent);filter:drop-shadow(0 0 5px var(--accent))}
.hero .wrap{position:relative;z-index:1}

/* ---------- build card (signature) ---------- */
.buildcard{background:linear-gradient(180deg,var(--card),#15151d);border:1px solid var(--border);
  border-radius:20px;padding:20px;box-shadow:0 30px 60px rgba(0,0,0,.5);position:relative}
.buildcard::before{content:"";position:absolute;inset:0;border-radius:20px;padding:1px;
  background:linear-gradient(160deg,rgba(0,212,255,.5),transparent 40%);-webkit-mask:
  linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);-webkit-mask-composite:xor;mask-composite:exclude;pointer-events:none}
.bc-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.bc-head .t{font-family:'Poppins';font-weight:600}
.bc-badge{font-family:'JetBrains Mono';font-size:.72rem;color:var(--success);
  border:1px solid rgba(0,230,118,.4);background:rgba(0,230,118,.08);padding:4px 10px;border-radius:20px}
.part{display:flex;align-items:center;gap:13px;padding:11px 12px;border-radius:12px;
  background:var(--bg-deep);border:1px solid var(--border);margin-bottom:9px;position:relative}
.part .ic{width:36px;height:36px;border-radius:9px;display:grid;place-items:center;
  background:rgba(108,99,255,.14);color:var(--accent);font-size:1rem;flex-shrink:0}
.part .meta{flex:1;min-width:0}
.part .meta .k{font-family:'JetBrains Mono';font-size:.64rem;letter-spacing:.16em;
  text-transform:uppercase;color:var(--muted)}
.part .meta .v{font-size:.92rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.part .chk{color:var(--success);font-size:1.05rem}
.bc-foot{display:flex;align-items:center;justify-content:space-between;margin-top:14px;padding-top:14px;border-top:1px dashed var(--border)}
.bc-foot .total .k{font-family:'JetBrains Mono';font-size:.66rem;letter-spacing:.16em;text-transform:uppercase;color:var(--muted)}
.bc-foot .total .v{font-family:'Poppins';font-weight:700;font-size:1.35rem}
.bc-3d{display:inline-flex;align-items:center;gap:8px;font-size:.86rem;font-weight:600;color:#08090d;
  background:var(--grad);padding:9px 15px;border-radius:10px}

/* ---------- stats ---------- */
.stats{border-top:1px solid var(--border);border-bottom:1px solid var(--border);
  background:var(--bg-deep)}
.stats-row{display:flex;flex-wrap:wrap;gap:12px;justify-content:space-around;padding:26px 0;text-align:center}
.stat .num{font-family:'Poppins';font-weight:800;font-size:2rem;
  background:var(--grad);-webkit-background-clip:text;background-clip:text;color:transparent}
.stat .lbl{color:var(--muted);font-size:.82rem;font-family:'JetBrains Mono';letter-spacing:.1em;text-transform:uppercase}

/* ---------- sections ---------- */
.section{padding:84px 0}
.section-head{max-width:36em;margin-bottom:44px}
.section-head h2{font-size:clamp(1.8rem,3.6vw,2.6rem);font-weight:700;margin:12px 0}
.section-head p{color:var(--muted);font-size:1.05rem}

/* features */
.feat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
.feat{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:26px;
  transition:.2s;position:relative;overflow:hidden}
.feat:hover{transform:translateY(-4px);border-color:rgba(0,212,255,.5);background:var(--card-2)}
.feat .ic{width:46px;height:46px;border-radius:12px;display:grid;place-items:center;font-size:1.25rem;
  background:rgba(0,212,255,.12);color:var(--accent);margin-bottom:16px}
.feat h3{font-size:1.18rem;font-weight:600;margin-bottom:8px}
.feat p{color:var(--muted);font-size:.95rem}

/* how it works */
.steps{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;counter-reset:s}
.step{position:relative;padding:26px 20px;border-radius:16px;background:var(--card);border:1px solid var(--border)}
.step .n{counter-increment:s;font-family:'JetBrains Mono';font-weight:600;font-size:.8rem;
  color:var(--accent);border:1px solid rgba(0,212,255,.4);border-radius:8px;padding:3px 9px;display:inline-block;margin-bottom:14px}
.step .n::before{content:"0" counter(s)}
.step h3{font-size:1.05rem;font-weight:600;margin-bottom:6px}
.step p{color:var(--muted);font-size:.9rem}
.step:not(:last-child)::after{content:"\f061";font-family:"Font Awesome 6 Free";font-weight:900;
  position:absolute;right:-13px;top:50%;transform:translateY(-50%);color:var(--border);z-index:2;font-size:.8rem}

/* compatibility band */
.band{background:linear-gradient(120deg,rgba(108,99,255,.12),rgba(0,212,255,.08));
  border:1px solid var(--border);border-radius:22px;padding:46px;display:grid;
  grid-template-columns:1.1fr .9fr;gap:40px;align-items:center}
.band h2{font-size:clamp(1.7rem,3.4vw,2.3rem);font-weight:700;margin-bottom:14px}
.band p{color:var(--muted);margin-bottom:10px}
.compat-demo{display:flex;flex-direction:column;gap:10px}
.cd-row{display:flex;align-items:center;gap:12px;padding:13px 16px;border-radius:12px;
  background:var(--bg-deep);border:1px solid var(--border);font-size:.92rem}
.cd-row.ok{border-color:rgba(0,230,118,.4)}
.cd-row.no{border-color:rgba(255,82,82,.4);opacity:.85}
.cd-row .tag{margin-left:auto;font-family:'JetBrains Mono';font-size:.72rem;padding:3px 9px;border-radius:20px}
.cd-row.ok .tag{color:var(--success);background:rgba(0,230,118,.1)}
.cd-row.no .tag{color:var(--warning);background:rgba(255,82,82,.1)}
.cd-row i:first-child{color:var(--accent)}
.cd-row.no i:first-child{color:var(--muted)}

/* final cta */
.final{text-align:center;padding:90px 0}
.final h2{font-size:clamp(2rem,4.4vw,3rem);font-weight:800;margin-bottom:16px}
.final h2 .hl{background:var(--grad);-webkit-background-clip:text;background-clip:text;color:transparent}
.final p{color:var(--muted);font-size:1.1rem;margin-bottom:30px}
.final .hero-cta{justify-content:center}

/* footer */
footer{border-top:1px solid var(--border);background:var(--bg-deep)}
.foot-row{display:flex;align-items:center;justify-content:space-between;padding:28px 0;flex-wrap:wrap;gap:14px}
.foot-row .muted{color:var(--muted);font-size:.86rem}
.foot-links{display:flex;gap:22px;font-size:.9rem;color:var(--muted)}
.foot-links a:hover{color:var(--accent)}

/* reveal */
.reveal{opacity:0;transform:translateY(22px);transition:opacity .6s ease,transform .6s ease}
.reveal.in{opacity:1;transform:none}

@media (max-width:900px){
  .hero-grid,.band{grid-template-columns:1fr;gap:36px}
  .feat-grid{grid-template-columns:repeat(2,1fr)}
  .steps{grid-template-columns:repeat(2,1fr)}
  .step:not(:last-child)::after{display:none}
  .nav-links{position:fixed;inset:68px 0 auto 0;flex-direction:column;background:var(--bg);
    border-bottom:1px solid var(--border);padding:18px 24px;gap:16px;align-items:stretch;
    transform:translateY(-130%);transition:.25s;z-index:40}
  .nav-links.open{transform:none}
  .nav-toggle{display:block}
}
@media (max-width:560px){
  .feat-grid,.steps{grid-template-columns:1fr}
  .band{padding:30px}
}
@media (prefers-reduced-motion:reduce){
  *{animation:none!important;scroll-behavior:auto}
  .reveal{opacity:1;transform:none}
}
</style>
</head>
<body>

<nav class="nav">
  <div class="wrap nav-inner">
    <a class="cm-logo" href="landing.php"><span class="cm-logo-badge"><i class="fas fa-microchip"></i></span>Compu<span class="cm-logo-accent">Match</span></a>
    <div class="nav-links" id="navLinks">
      <a class="link" href="#features">Features</a>
      <a class="link" href="#how">How it works</a>
      <a class="link" href="#compatible">Compatibility</a>
      <a class="link" href="login.php">Log in</a>
      <a class="btn btn-grad" href="signup.php">Get started</a>
    </div>
    <button class="nav-toggle" id="navToggle" aria-label="Menu"><i class="fas fa-bars"></i></button>
  </div>
</nav>

<!-- ============ HERO ============ -->
<header class="hero">
  <svg class="traces" preserveAspectRatio="none" viewBox="0 0 1200 620">
    <defs><linearGradient id="tg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="#6C63FF" stop-opacity=".5"/><stop offset="1" stop-color="#00D4FF" stop-opacity=".5"/>
    </linearGradient></defs>
    <path d="M0,90 H260 V210 H470"/><circle cx="470" cy="210" r="4"/>
    <path d="M1200,150 H940 V300 H720"/><circle cx="720" cy="300" r="4"/>
    <path d="M0,420 H180 V520 H430"/><circle cx="430" cy="520" r="4"/>
    <path d="M1200,470 H880 V360 H690"/>
    <path class="pulse" d="M0,90 H260 V210 H470"/>
    <path class="pulse" d="M1200,150 H940 V300 H720" style="animation-delay:-2s"/>
    <path class="pulse" d="M0,420 H180 V520 H430" style="animation-delay:-4s"/>
  </svg>

  <div class="wrap">
    <div class="hero-grid">
      <div class="reveal in">
        <span class="eyebrow">PC parts store + smart builder</span>
        <h1>Build a PC that <span class="hl">just works.</span></h1>
        <p class="lead">Pick parts from real shops. CompuMatch blocks whatever won't fit — socket, RAM, form factor — then shows your rig in 3D before you commit.</p>
        <div class="hero-cta">
          <a class="btn btn-grad" href="signup.php"><i class="fas fa-screwdriver-wrench"></i> Start building</a>
          <a class="btn btn-ghost" href="index.php"><i class="fas fa-store"></i> Browse parts</a>
        </div>
        <div class="hero-note"><i class="fas fa-circle-check"></i> No more “wrong socket” surprises — compatibility is checked as you go.</div>
      </div>

      <!-- signature: a live-looking compatible build -->
      <div class="buildcard reveal in">
        <div class="bc-head">
          <span class="t">Your Build</span>
          <span class="bc-badge"><i class="fas fa-check"></i> All compatible</span>
        </div>
        <div class="part"><span class="ic"><i class="fas fa-microchip"></i></span>
          <div class="meta"><div class="k">CPU · LGA1700</div><div class="v">Intel Core i5-13400</div></div><i class="fas fa-circle-check chk"></i></div>
        <div class="part"><span class="ic"><i class="fas fa-server"></i></span>
          <div class="meta"><div class="k">Motherboard · auto-picked</div><div class="v">MSI PRO B760M-A</div></div><i class="fas fa-circle-check chk"></i></div>
        <div class="part"><span class="ic"><i class="fas fa-memory"></i></span>
          <div class="meta"><div class="k">Memory · DDR5</div><div class="v">Corsair Vengeance 32GB</div></div><i class="fas fa-circle-check chk"></i></div>
        <div class="part"><span class="ic"><i class="fas fa-tv"></i></span>
          <div class="meta"><div class="k">Graphics</div><div class="v">NVIDIA RTX 4060</div></div><i class="fas fa-circle-check chk"></i></div>
        <div class="bc-foot">
          <div class="total"><div class="k">Estimated total</div><div class="v">₱51,400</div></div>
          <span class="bc-3d"><i class="fas fa-cube"></i> View in 3D</span>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- ============ STATS ============ -->
<section class="stats">
  <div class="wrap stats-row">
    <div class="stat"><div class="num"><?php echo $stats['stores']; ?></div><div class="lbl">Partner stores</div></div>
    <div class="stat"><div class="num"><?php echo $stats['parts']; ?>+</div><div class="lbl">Parts in stock</div></div>
    <div class="stat"><div class="num"><?php echo $stats['categories']; ?></div><div class="lbl">Component types</div></div>
    <div class="stat"><div class="num">3D</div><div class="lbl">Build preview</div></div>
  </div>
</section>

<!-- ============ FEATURES ============ -->
<section class="section" id="features">
  <div class="wrap">
    <div class="section-head reveal">
      <span class="eyebrow">What you get</span>
      <h2>Everything to spec a build, in one place.</h2>
      <p>From browsing parts to seeing your finished rig spin in 3D — without a compatibility spreadsheet.</p>
    </div>
    <div class="feat-grid">
      <div class="feat reveal"><div class="ic"><i class="fas fa-store"></i></div><h3>Real store inventory</h3><p>Browse parts from actual shops with live stock and prices, organised by category.</p></div>
      <div class="feat reveal"><div class="ic"><i class="fas fa-list-check"></i></div><h3>Guided custom builder</h3><p>Go step by step — CPU, motherboard, RAM, storage, GPU, PSU, case — at your own pace.</p></div>
      <div class="feat reveal"><div class="ic"><i class="fas fa-shield-halved"></i></div><h3>Real-time compatibility</h3><p>Only matching sockets, RAM types, and case sizes appear. Wrong parts never show up.</p></div>
      <div class="feat reveal"><div class="ic"><i class="fas fa-wand-magic-sparkles"></i></div><h3>Auto-recommend</h3><p>Pick a CPU and we fill in a compatible Intel or AMD board and memory for you.</p></div>
      <div class="feat reveal"><div class="ic"><i class="fas fa-cube"></i></div><h3>3D build simulator</h3><p>Watch every part drop into a case, then orbit and inspect your rig before buying.</p></div>
      <div class="feat reveal"><div class="ic"><i class="fas fa-floppy-disk"></i></div><h3>Save your builds</h3><p>Keep a primary and secondary drive, then save the whole build to revisit anytime.</p></div>
    </div>
  </div>
</section>

<!-- ============ HOW IT WORKS ============ -->
<section class="section" id="how" style="padding-top:0">
  <div class="wrap">
    <div class="section-head reveal">
      <span class="eyebrow">How it works</span>
      <h2>Four steps to a working rig.</h2>
    </div>
    <div class="steps">
      <div class="step reveal"><span class="n"></span><h3>Pick a store</h3><p>Choose a shop and your intended use — gaming, office, server, and more.</p></div>
      <div class="step reveal"><span class="n"></span><h3>Choose a CPU</h3><p>Start with the processor. We instantly know the socket and platform.</p></div>
      <div class="step reveal"><span class="n"></span><h3>We filter the rest</h3><p>Only compatible motherboards, RAM, and cases are shown from then on.</p></div>
      <div class="step reveal"><span class="n"></span><h3>Preview &amp; save</h3><p>See it built in 3D, check the total, and save your build to your account.</p></div>
    </div>
  </div>
</section>

<!-- ============ COMPATIBILITY BAND ============ -->
<section class="section" id="compatible" style="padding-top:0">
  <div class="wrap">
    <div class="band reveal">
      <div>
        <span class="eyebrow">The difference</span>
        <h2>You literally can't pick the wrong part.</h2>
        <p>Choose an Intel LGA1700 CPU and AM5 boards disappear. Choose a DDR5 board and DDR4 sticks vanish. Add a micro-ATX board and oversized cases drop off the list.</p>
        <p>It's the compatibility check that usually trips people up — handled for you, every step.</p>
      </div>
      <div class="compat-demo">
        <div class="cd-row ok"><i class="fas fa-microchip"></i> Intel Core i5-13400 <span class="mono" style="color:var(--muted)">LGA1700</span><span class="tag">CPU</span></div>
        <div class="cd-row ok"><i class="fas fa-server"></i> MSI PRO B760M-A <span class="mono" style="color:var(--muted)">LGA1700</span><span class="tag"><i class="fas fa-check"></i> fits</span></div>
        <div class="cd-row no"><i class="fas fa-server"></i> ASUS B650 (AM5) <span class="mono" style="color:var(--muted)">AM5</span><span class="tag"><i class="fas fa-xmark"></i> hidden</span></div>
        <div class="cd-row ok"><i class="fas fa-memory"></i> Corsair 32GB <span class="mono" style="color:var(--muted)">DDR5</span><span class="tag"><i class="fas fa-check"></i> fits</span></div>
        <div class="cd-row no"><i class="fas fa-memory"></i> Kingston 16GB <span class="mono" style="color:var(--muted)">DDR4</span><span class="tag"><i class="fas fa-xmark"></i> hidden</span></div>
      </div>
    </div>
  </div>
</section>

<!-- ============ FINAL CTA ============ -->
<section class="final">
  <div class="wrap reveal">
    <h2>Ready to build <span class="hl">your</span> PC?</h2>
    <p>Create an account and spec your first compatible rig in minutes.</p>
    <div class="hero-cta">
      <a class="btn btn-grad" href="signup.php"><i class="fas fa-user-plus"></i> Create free account</a>
      <a class="btn btn-ghost" href="index.php"><i class="fas fa-store"></i> Just browse parts</a>
    </div>
  </div>
</section>

<footer>
  <div class="wrap foot-row">
    <a class="cm-logo" href="landing.php"><span class="cm-logo-badge"><i class="fas fa-microchip"></i></span>Compu<span class="cm-logo-accent">Match</span></a>
    <div class="foot-links">
      <a href="#features">Features</a><a href="#how">How it works</a>
      <a href="login.php">Log in</a><a href="signup.php">Sign up</a>
    </div>
    <span class="muted">© <?php echo date('Y'); ?> CompuMatch · Calbayog City</span>
  </div>
</footer>

<script>
// mobile nav
const tg = document.getElementById('navToggle'), nl = document.getElementById('navLinks');
tg.addEventListener('click', () => nl.classList.toggle('open'));
nl.querySelectorAll('a').forEach(a => a.addEventListener('click', () => nl.classList.remove('open')));

// scroll reveal
const io = new IntersectionObserver((es) => {
  es.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
}, { threshold: .14 });
document.querySelectorAll('.reveal').forEach(el => io.observe(el));
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
                body: JSON.stringify({ action: action, page: 'landing', store_id: currentStoreId() })
            }).catch(function () {});
        } catch (e) {}
    }
    ping('track_visit');
    setInterval(function () { ping('heartbeat'); }, 120000); // every 2 min (keeps hosting load low)
})();
</script>
</body>
</html>
