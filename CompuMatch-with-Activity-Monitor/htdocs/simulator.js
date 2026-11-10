/* =========================================================================
   CompuMatch — 3D PC Build Simulator
   Self-contained Three.js scene. Exposes a handful of global functions used
   by the buttons in #simulator-page. Safe to call initSimulator() repeatedly.
   ========================================================================= */
(function () {
    'use strict';

    // ---- Part definitions (order = recommended install order) ----------------
    var PARTS = [
        { key: 'case',        icon: 'fa-server',       label: 'PC Case',     defName: 'Mid-Tower ATX Case' },
        { key: 'psu',         icon: 'fa-plug',         label: 'Power Supply', defName: '650W 80+ Bronze PSU' },
        { key: 'motherboard', icon: 'fa-network-wired',label: 'Motherboard', defName: 'ATX Motherboard' },
        { key: 'cpu',         icon: 'fa-microchip',    label: 'CPU + Cooler', defName: 'Processor' },
        { key: 'ram',         icon: 'fa-memory',       label: 'RAM',         defName: 'DDR Memory' },
        { key: 'gpu',         icon: 'fa-display',      label: 'Graphics Card', defName: 'GPU' },
        { key: 'storage',     icon: 'fa-hard-drive',   label: 'Storage',     defName: 'SSD' }
    ];

    var ACCENT = 0x22d3ee;   // cyan RGB glow
    var ACCENT2 = 0x53e7ff;  // brighter cyan

    // ---- Module state --------------------------------------------------------
    var initialized = false;
    var scene, camera, renderer, raycaster, pointer;
    var pcGroup;                 // holds all the part groups
    var partMeshes = {};         // key -> THREE.Group
    var accentLights = [];       // RGB ambience lights (recolored per case)
    var themeAccent = ACCENT;    // current RGB glow color (follows selected case)
    var DEFAULT_CASE_STYLE = { body: 0xe7eaee, fanBody: 0xe7eaee, accent: ACCENT, sideFans: 3, topFans: 3, mesh: false };
    var installed = {};          // key -> bool
    var partInfo = {};           // key -> {name, price}
    var animations = [];         // active drop-in tweens
    var autoRotate = false;
    var holder, canvas;
    var rafId = null;

    // Orbit camera state (spherical around target)
    var orbit = { target: { x: 0, y: 0.2, z: 0 }, radius: 9, theta: 0.9, phi: 1.15, min: 4, max: 18 };
    var dragging = false, lastX = 0, lastY = 0, pinchDist = 0;

    // =====================================================================
    // PUBLIC ENTRY
    // =====================================================================
    // The 3D engine normally comes from three_min.js on our own server. If
    // that file didn't load (missing upload, blocked, stale cache), fetch the
    // same version (r128) from a public CDN once, then carry on.
    var THREE_CDN = 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js';
    var threeLoading = false, threeFailed = false, afterThree = [];
    function ensureThree(cb) {
        if (typeof THREE !== 'undefined') { cb(); return; }
        afterThree.push(cb);
        if (threeLoading || threeFailed) return;
        threeLoading = true;
        var l = document.getElementById('simLoading');
        if (l) l.textContent = 'Loading simulator…';
        var s = document.createElement('script');
        s.src = THREE_CDN;
        s.async = true;
        s.onload = function () {
            threeLoading = false;
            var q = afterThree.splice(0);
            if (typeof THREE !== 'undefined') q.forEach(function (f) { try { f(); } catch (e) { console.error(e); } });
            else s.onerror();
        };
        s.onerror = function () {
            threeLoading = false; threeFailed = true; afterThree = [];
            var l2 = document.getElementById('simLoading');
            if (l2) l2.textContent = 'The simulator failed to load. Check your internet connection, then refresh the page.';
        };
        document.head.appendChild(s);
    }

    // Things to run once the scene exists (e.g. "View in 3D Simulator").
    var afterInit = [];
    function whenReady(fn) {
        if (initialized) fn(); else afterInit.push(fn);
    }

    window.initSimulator = function () {
        renderPartsList();
        ensureThree(function () {
            if (initialized) { resize(); return; }
            buildScene();
            initialized = true;
            loadDataFromCustomBuild(true); // silently pick up names if any exist
            animate();
            var q = afterInit.splice(0);
            q.forEach(function (f) { setTimeout(f, 60); });
        });
    };

    // =====================================================================
    // SCENE SETUP
    // =====================================================================
    function buildScene() {
        holder = document.getElementById('simCanvasHolder');
        var loading = document.getElementById('simLoading');
        if (loading) loading.style.display = 'none';

        scene = new THREE.Scene();
        scene.background = new THREE.Color(0x0a0b10);
        scene.fog = new THREE.Fog(0x0a0b10, 16, 34);

        camera = new THREE.PerspectiveCamera(42, 1, 0.1, 100);
        renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        renderer.outputEncoding = THREE.sRGBEncoding;
        renderer.toneMapping = THREE.ACESFilmicToneMapping;
        renderer.toneMappingExposure = 1.0;
        renderer.shadowMap.enabled = true;
        renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        holder.appendChild(renderer.domElement);
        canvas = renderer.domElement;
        canvas.style.display = 'block';
        canvas.style.cursor = 'grab';

        // Studio lighting tuned for a dark backdrop (white case pops)
        scene.add(new THREE.HemisphereLight(0x9fb2d0, 0x05060a, 0.35));
        var key = new THREE.DirectionalLight(0xffffff, 1.35);
        key.position.set(7, 12, 9);
        key.castShadow = true;
        key.shadow.mapSize.set(2048, 2048);
        key.shadow.camera.near = 1; key.shadow.camera.far = 50;
        key.shadow.camera.left = -9; key.shadow.camera.right = 9;
        key.shadow.camera.top = 9; key.shadow.camera.bottom = -9;
        key.shadow.bias = -0.0004; key.shadow.radius = 5;
        scene.add(key);
        var fill = new THREE.DirectionalLight(0x8fb4ff, 0.5);
        fill.position.set(-9, 5, -3); scene.add(fill);
        var rim = new THREE.DirectionalLight(0xffffff, 0.7);
        rim.position.set(-2, 4, -10); scene.add(rim);
        // cyan accent fills (RGB ambience) — kept for live recolor when case changes
        var c1 = new THREE.PointLight(ACCENT, 0.9, 16); c1.position.set(-2.5, 1.5, 3.5); scene.add(c1);
        var c2 = new THREE.PointLight(ACCENT, 0.7, 16); c2.position.set(2.5, -0.5, 2.5); scene.add(c2);
        accentLights = [c1, c2];

        // Environment reflections (PMREM from a simple lit room) — wrapped for safety
        try {
            var pmrem = new THREE.PMREMGenerator(renderer);
            scene.environment = pmrem.fromScene(makeEnvScene(), 0.04).texture;
        } catch (e) { /* environment optional */ }

        // Perspective grid floor (BuildCores-style)
        var grid = new THREE.GridHelper(80, 80, 0x2b3550, 0x161b2a);
        grid.position.y = -2.8;
        if (grid.material) { grid.material.transparent = true; grid.material.opacity = 0.55; }
        scene.add(grid);

        // Soft contact shadow on the floor
        var ground = new THREE.Mesh(
            new THREE.PlaneGeometry(60, 60),
            new THREE.ShadowMaterial({ opacity: 0.35 })
        );
        ground.rotation.x = -Math.PI / 2;
        ground.position.y = -2.79;
        ground.receiveShadow = true;
        scene.add(ground);

        pcGroup = new THREE.Group();
        scene.add(pcGroup);

        buildAllParts();

        // Enable shadows on solid meshes (skip glass / glowing / halo parts)
        pcGroup.traverse(function (o) {
            if (o.isMesh && !o.userData.noShadow) { o.castShadow = true; o.receiveShadow = true; }
        });

        setupInput();
        resize();
        window.addEventListener('resize', resize);
    }

    // A tiny "room" whose bright panels become soft reflections via PMREM
    function makeEnvScene() {
        var s = new THREE.Scene();
        s.background = new THREE.Color(0x95a3b3);
        function panel(w, h, x, y, z, rx, ry, lum) {
            var p = new THREE.Mesh(new THREE.PlaneGeometry(w, h), new THREE.MeshBasicMaterial());
            p.material.color.setScalar(lum);
            p.position.set(x, y, z); p.rotation.set(rx, ry, 0); s.add(p);
        }
        panel(40, 40, 0, 20, 0, Math.PI / 2, 0, 0.65);   // ceiling (brightest)
        panel(40, 40, -20, 0, 0, 0, Math.PI / 2, 0.35);  // left wall
        panel(40, 40, 20, 0, 0, 0, -Math.PI / 2, 0.35);  // right wall
        panel(40, 40, 0, 0, -20, 0, 0, 0.28);            // back wall
        panel(40, 40, 0, 0, 20, 0, Math.PI, 0.3);        // front wall
        return s;
    }

    // ---- Material helpers ----------------------------------------------------
    function mat(color, opts) {
        opts = opts || {};
        return new THREE.MeshStandardMaterial({
            color: color,
            metalness: opts.metalness != null ? opts.metalness : 0.4,
            roughness: opts.roughness != null ? opts.roughness : 0.55,
            emissive: opts.emissive != null ? opts.emissive : 0x000000,
            emissiveIntensity: opts.emissiveIntensity != null ? opts.emissiveIntensity : 1,
            envMapIntensity: 0.5,
            transparent: false,
            opacity: 1
        });
    }
    // Clean white chassis material (matte, not mirror)
    function whiteMat(rough) {
        return new THREE.MeshStandardMaterial({
            color: 0xe7eaee, metalness: 0.05,
            roughness: rough != null ? rough : 0.6,
            envMapIntensity: 0.4, transparent: false, opacity: 1
        });
    }
    // Tempered-glass panel: clearly see-through, lightly tinted, soft sheen
    function glassMat() {
        var m;
        try {
            m = new THREE.MeshPhysicalMaterial({
                color: 0xbfd0db, metalness: 0, roughness: 0.15,
                clearcoat: 0.3, clearcoatRoughness: 0.2, reflectivity: 0.15,
                transparent: true, opacity: 0.09, envMapIntensity: 0.35,
                depthWrite: false
            });
        } catch (e) {
            m = new THREE.MeshStandardMaterial({ color: 0xbfd0db, metalness: 0.1, roughness: 0.2, transparent: true, opacity: 0.09, depthWrite: false });
        }
        return m;
    }
    // Emissive cyan (RGB) material — unaffected by environment, strong glow
    function glowMat(color, intensity) {
        return new THREE.MeshStandardMaterial({
            color: 0x06141a, emissive: color, emissiveIntensity: intensity != null ? intensity : 2.4,
            metalness: 0, roughness: 0.4, envMapIntensity: 0, transparent: false, opacity: 1
        });
    }
    function box(w, h, d, m) { return new THREE.Mesh(new THREE.BoxGeometry(w, h, d), m); }

    // Generic neutral material with adjustable darkness
    function grayMat(c, rough) {
        return new THREE.MeshStandardMaterial({
            color: c, metalness: 0.4, roughness: rough != null ? rough : 0.5,
            envMapIntensity: 0.5, transparent: false, opacity: 1
        });
    }

    // Matte chassis panel of any colour
    function panelMat(c) {
        return new THREE.MeshStandardMaterial({
            color: c, metalness: 0.05, roughness: 0.6,
            envMapIntensity: 0.4, transparent: false, opacity: 1
        });
    }

    // A realistic-ish case fan. Faces +Z by default. housingColor lets case fans
    // match the chassis colour. Returns a Group; rotor child is tagged userData.spin.
    function makeFan(r, glow, housingColor) {
        var G = new THREE.Group();
        var housing = box(2 * r + 0.14, 2 * r + 0.14, 0.16, panelMat(housingColor != null ? housingColor : 0xe7eaee));
        G.add(housing);
        // dark circular opening behind the blades (gives contrast)
        var opening = new THREE.Mesh(new THREE.CylinderGeometry(r * 0.94, r * 0.94, 0.05, 36), grayMat(0x1b1e24, 0.7));
        opening.rotation.x = Math.PI / 2; opening.position.z = 0.04; G.add(opening);

        var rotor = new THREE.Group();
        rotor.position.z = 0.09; rotor.userData.spin = true; G.add(rotor);
        var hub = new THREE.Mesh(new THREE.CylinderGeometry(0.24 * r, 0.24 * r, 0.1, 20), grayMat(0x2a2e36, 0.5));
        hub.rotation.x = Math.PI / 2; rotor.add(hub);
        var blades = 7;
        for (var i = 0; i < blades; i++) {
            var pivot = new THREE.Group();
            pivot.rotation.z = (i / blades) * Math.PI * 2;
            var blade = box(r * 0.86, r * 0.52, 0.03, grayMat(0xe2e6ea, 0.55));
            blade.position.set(r * 0.5, 0, 0);
            blade.rotation.x = 0.55;   // pitch
            blade.rotation.z = 0.32;   // sweep
            pivot.add(blade); rotor.add(pivot);
        }
        // cyan rim ring (recolorable)
        var ring = new THREE.Mesh(new THREE.TorusGeometry(r - 0.04, 0.05, 16, 48), glowMat(glow, 2.6));
        ring.position.z = 0.12; ring.userData.noFade = true; ring.userData.noShadow = true; ring.userData.glow = true; G.add(ring);
        // additive glow halo (fake bloom)
        var halo = new THREE.Mesh(new THREE.TorusGeometry(r - 0.04, 0.15, 12, 48),
            new THREE.MeshBasicMaterial({ color: glow, transparent: true, opacity: 0.33, blending: THREE.AdditiveBlending, depthWrite: false }));
        halo.position.z = 0.12; halo.userData.noFade = true; halo.userData.noShadow = true; halo.userData.glow = true; G.add(halo);
        return G;
    }

    // Build a group, tag it, hide it (installed later)
    function makePart(key) {
        var g = new THREE.Group();
        g.userData.partKey = key;
        g.visible = false;
        pcGroup.add(g);
        partMeshes[key] = g;
        return g;
    }

    // =====================================================================
    // GEOMETRY FOR EACH PART
    // =====================================================================
    function buildAllParts() {
        buildCase();
        buildPSU();
        buildMotherboard();
        buildCPU();
        buildRAM();
        buildGPU();
        buildStorage();
    }

    // Case dimensions shared by part placement
    var HX = 2.1, HY = 2.7, HZ = 2.2;

    function buildCase(style) {
        style = style || DEFAULT_CASE_STYLE;
        var g = makePart('case');
        var W = HX * 2, H = HY * 2, D = HZ * 2, t = 0.12;
        var bodyMat = function () { return panelMat(style.body); };
        var accent = style.accent;

        // Solid body panels (tray/back, left, bottom)
        var back = box(W, H, t, bodyMat()); back.position.set(0, 0, -HZ); g.add(back);
        var left = box(t, H, D, bodyMat()); left.position.set(-HX, 0, 0); g.add(left);
        var bottom = box(W, t, D, bodyMat()); bottom.position.set(0, -HY, 0); g.add(bottom);

        // Open-top rails + corner posts (so top fans show)
        var tr1 = box(W, t, t, bodyMat()); tr1.position.set(0, HY, -HZ); g.add(tr1);
        var tr2 = box(W, t, t, bodyMat()); tr2.position.set(0, HY, HZ); g.add(tr2);
        var tr3 = box(t, t, D, bodyMat()); tr3.position.set(-HX, HY, 0); g.add(tr3);
        var tr4 = box(t, t, D, bodyMat()); tr4.position.set(HX, HY, 0); g.add(tr4);
        [[-HX, -HZ], [HX, -HZ], [-HX, HZ], [HX, HZ]].forEach(function (p) {
            var e = box(t, H, t, bodyMat()); e.position.set(p[0], 0, p[1]); g.add(e);
        });

        // Right (+X) panel is always glass so we can see inside.
        var gr = box(t, H - 0.2, D - 0.2, glassMat());
        gr.position.set(HX, 0, 0); gr.userData.noFade = true; gr.userData.noShadow = true; g.add(gr);

        // Front (+Z): mesh grille for airflow cases, tempered glass otherwise.
        if (style.mesh) {
            var meshFront = new THREE.Mesh(new THREE.BoxGeometry(W - 0.2, H - 0.2, t),
                new THREE.MeshStandardMaterial({ color: 0x14161b, metalness: 0.5, roughness: 0.7, transparent: true, opacity: 0.32 }));
            meshFront.position.set(0, 0, HZ); meshFront.userData.noFade = true; meshFront.userData.noShadow = true; g.add(meshFront);
        } else {
            var gf = box(W - 0.2, H - 0.2, t, glassMat());
            gf.position.set(0, 0, HZ); gf.userData.noFade = true; gf.userData.noShadow = true; g.add(gf);
        }

        // Top-front RGB strip (accent colour)
        var strip = box(W * 0.5, 0.1, 0.12, glowMat(accent, 1.9));
        strip.position.set(-0.15, HY - 0.16, HZ - 0.22);
        strip.userData.noFade = true; strip.userData.noShadow = true; strip.userData.glow = true; g.add(strip);

        // Side intake fans on the +X wall, rings facing the viewer (count from case)
        var sideN = Math.max(1, Math.min(3, style.sideFans));
        spread(sideN, 1.35).forEach(function (y) {
            var f = makeFan(0.6, accent, style.fanBody);
            f.position.set(HX - 0.16, y, 0.05); f.rotation.y = Math.PI / 2; g.add(f);
        });
        // Top exhaust fans facing up (count from case)
        var topN = Math.max(1, Math.min(3, style.topFans));
        spread(topN, 1.15).forEach(function (z) {
            var f = makeFan(0.54, accent, style.fanBody);
            f.position.set(-0.1, HY - 0.16, z); f.rotation.x = -Math.PI / 2; g.add(f);
        });

        // ---- Front I/O panel: power button, USB ports, audio jack ------------
        var ioMat = grayMat(0x1b1e24, 0.5);
        var ioPlate = box(0.62, 0.16, 0.05, ioMat);
        ioPlate.position.set(HX - 0.5, HY - 0.03, HZ - 0.02); g.add(ioPlate);
        var pwrBtn = new THREE.Mesh(new THREE.CylinderGeometry(0.05, 0.05, 0.04, 20), glowMat(accent, 2.2));
        pwrBtn.rotation.x = Math.PI / 2; pwrBtn.position.set(HX - 0.7, HY - 0.03, HZ + 0.01);
        pwrBtn.userData.noFade = true; pwrBtn.userData.noShadow = true; pwrBtn.userData.glow = true; g.add(pwrBtn);
        [-0.1, 0.08].forEach(function (dx) {
            var usb = box(0.16, 0.06, 0.02, grayMat(0x0e0f12, 0.4));
            usb.position.set(HX - 0.5 + dx, HY - 0.03, HZ + 0.01); g.add(usb);
        });
        var jack = new THREE.Mesh(new THREE.CylinderGeometry(0.035, 0.035, 0.03, 16), grayMat(0x3a3d44, 0.3));
        jack.rotation.x = Math.PI / 2; jack.position.set(HX - 0.3, HY - 0.03, HZ + 0.01); g.add(jack);

        // ---- Rubber feet ------------------------------------------------------
        [[-HX + 0.3, -HZ + 0.3], [HX - 0.3, -HZ + 0.3], [-HX + 0.3, HZ - 0.3], [HX - 0.3, HZ - 0.3]].forEach(function (p) {
            var foot = new THREE.Mesh(new THREE.CylinderGeometry(0.14, 0.16, 0.12, 16), grayMat(0x0d0e11, 0.8));
            foot.position.set(p[0], -HY - 0.06, p[1]); foot.userData.noShadow = true; g.add(foot);
        });

        // ---- Rear I/O: stacked PCIe slot covers + exhaust fan ------------------
        for (var slot = 0; slot < 5; slot++) {
            var sc = box(0.42, 0.045, 0.02, grayMat(0x2a2d33, 0.5));
            sc.position.set(HX - 0.45, 1.0 - slot * 0.11, -HZ - 0.005); g.add(sc);
        }
        var rearFan = makeFan(0.42, accent, style.fanBody);
        rearFan.position.set(-1.0, 0.55, -HZ + 0.03); g.add(rearFan);

        // ---- Glass panel corner screws (realism detail) ------------------------
        [[-H / 2 + 0.35, -D / 2 + 0.35], [-H / 2 + 0.35, D / 2 - 0.35], [H / 2 - 0.35, -D / 2 + 0.35], [H / 2 - 0.35, D / 2 - 0.35]].forEach(function (p) {
            var screw = new THREE.Mesh(new THREE.CylinderGeometry(0.035, 0.035, 0.03, 12), grayMat(0x555a63, 0.3));
            screw.rotation.z = Math.PI / 2; screw.position.set(HX + 0.06, p[0], p[1]);
            screw.userData.noShadow = true; g.add(screw);
        });

        // ---- Perforated mesh dots for airflow-style front panels ---------------
        if (style.mesh) {
            var dotMat = grayMat(0x0a0b0d, 0.8);
            for (var row = 0; row < 10; row++) {
                for (var col = 0; col < 7; col++) {
                    var dot = new THREE.Mesh(new THREE.CircleGeometry(0.06, 8), dotMat);
                    dot.position.set(-1.5 + col * 0.5, -2.2 + row * 0.5, HZ + 0.005);
                    dot.userData.noShadow = true; g.add(dot);
                }
            }
        }
    }

    // Evenly spaced positions centred on 0, e.g. spread(3, 1.35) -> [-1.35,0,1.35]
    function spread(n, ext) {
        if (n <= 1) return [0];
        var out = [];
        for (var i = 0; i < n; i++) out.push(-ext + (2 * ext) * (i / (n - 1)));
        return out;
    }

    // Derive a case look from the selected case component
    function deriveCaseStyle(comp) {
        var s = ((comp && comp.name || '') + ' ' + (comp && comp.specs || '') + ' ' +
                 (comp && (comp.brandName || comp.brand) || '')).toLowerCase();
        var st = { body: 0xe7eaee, fanBody: 0xe7eaee, accent: ACCENT, sideFans: 2, topFans: 2, mesh: false };

        // Body colour: explicit colour words win, else brand defaults
        if (/\b(black|noir|dark|midnight|shadow|stealth)\b/.test(s)) { st.body = 0x202227; st.fanBody = 0x202227; }
        else if (/\b(white|snow|pure|frost|arctic|wh\b)\b/.test(s)) { st.body = 0xe7eaee; st.fanBody = 0xe7eaee; }
        else if (/corsair|cooler master|deepcool|montech|nzxt h510 b/.test(s)) { st.body = 0x202227; st.fanBody = 0x202227; }

        // Accent (RGB) colour
        if (/corsair/.test(s)) st.accent = 0xffc83d;        // amber
        else if (/cooler master/.test(s)) st.accent = 0x9b6bff; // purple
        else if (/lian li/.test(s)) st.accent = 0x3aa0ff;   // blue
        else if (/nzxt/.test(s)) st.accent = 0x22d3ee;      // cyan
        if (/\bpurple\b/.test(s)) st.accent = 0x9b6bff;
        if (/\bred\b/.test(s)) st.accent = 0xff4d6d;
        if (/\bgreen\b/.test(s)) st.accent = 0x49e06b;
        if (/\bblue\b/.test(s)) st.accent = 0x3aa0ff;
        if (/\bpink\b/.test(s)) st.accent = 0xff7eb6;

        // Fan count from specs ("3 fans")
        var m = s.match(/(\d+)\s*fan/);
        var fans = m ? parseInt(m[1], 10) : 2;
        st.sideFans = Math.max(1, Math.min(3, fans));
        st.topFans = fans >= 3 ? 3 : (fans >= 2 ? 2 : 1);

        // mATX / mini cases get fewer fans
        if (/\bmatx\b|micro|mini|itx|m-atx/.test(s)) { st.sideFans = Math.min(st.sideFans, 2); st.topFans = 1; }

        // Mesh / airflow front
        st.mesh = /flow|airflow|mesh|\bair\b/.test(s);
        return st;
    }

    function disposeGroup(g) {
        g.traverse(function (o) {
            if (o.isMesh) {
                if (o.geometry) o.geometry.dispose();
                if (o.material) { (Array.isArray(o.material) ? o.material : [o.material]).forEach(function (m) { m.dispose && m.dispose(); }); }
            }
        });
    }

    // Replace any part's geometry with a new style (re-drops in on next install)
    function rebuildPart(key, style) {
        var old = partMeshes[key];
        if (old) { pcGroup.remove(old); disposeGroup(old); }
        installed[key] = false;
        switch (key) {
            case 'case': buildCase(style); break;
            case 'psu': buildPSU(style); break;
            case 'motherboard': buildMotherboard(style); break;
            case 'cpu': buildCPU(style); break;
            case 'ram': buildRAM(style); break;
            case 'gpu': buildGPU(style); break;
            case 'storage': buildStorage(style); break;
            default: return;
        }
        partMeshes[key].traverse(function (o) {
            if (o.isMesh && !o.userData.noShadow) { o.castShadow = true; o.receiveShadow = true; }
        });
    }

    // Recolour every glowing element + ambience lights to the theme accent
    function recolorGlow(accent) {
        themeAccent = accent;
        pcGroup.traverse(function (o) {
            if (o.isMesh && o.userData && o.userData.glow && o.material) {
                if (o.material.emissive) o.material.emissive.setHex(accent);
                else if (o.material.color) o.material.color.setHex(accent);
            }
        });
        accentLights.forEach(function (l) { if (l.color) l.color.setHex(accent); });
    }

    // Derive a per-component look from the selected item's name/brand/specs
    function derivePartStyle(key, comp) {
        if (key === 'case') return deriveCaseStyle(comp);
        var s = ((comp && comp.name || '') + ' ' + (comp && comp.specs || '') + ' ' +
                 (comp && (comp.brandName || comp.brand) || '')).toLowerCase();
        var white = /\b(white|snow|pure|frost|arctic)\b/.test(s);
        function brandAccent() {
            if (/nvidia|geforce|rtx|gtx/.test(s)) return 0x76b900;
            if (/radeon|\brx\b|\bamd\b|ryzen/.test(s)) return 0xed1c24;
            if (/intel|\bcore\b|arc/.test(s)) return 0x0071c5;
            if (/msi/.test(s)) return 0xff2d2d;
            if (/asus|rog/.test(s)) return 0xff2a6d;
            if (/gigabyte|aorus/.test(s)) return 0xff7a00;
            if (/corsair/.test(s)) return 0xffc83d;
            if (/seasonic/.test(s)) return 0x18a0e0;
            return 0x9aa0a8;
        }
        if (key === 'gpu') {
            var high = /\b(4070|4080|4090|5070|5080|5090|3080|3090|6800|6900|7700|7800|7900)\b/.test(s);
            return { fans: high ? 3 : 2, big: high, color: white ? 0xe7eaee : 0x1d1f25, brandAccent: brandAccent() };
        }
        if (key === 'ram') {
            return { sticks: (/\b4x|\(4|64\s*gb/.test(s)) ? 4 : 2, rgb: /\brgb\b/.test(s), color: white ? 0xd9dde2 : 0x282c34 };
        }
        if (key === 'motherboard') {
            var form = /\bitx\b|mini-?itx/.test(s) ? 'itx'
                     : (/m-?atx|micro|\b[bhxza]\d{3}m\b|matx/.test(s) ? 'matx' : 'atx');
            return { form: form, color: white ? 0xd9dde2 : 0x14141b, brandAccent: brandAccent() };
        }
        if (key === 'cpu') {
            var big = /(i7|i9|ryzen 7|ryzen 9|threadripper)/.test(s) || /\d{3,4}k\b/.test(s);
            var tint = /amd|ryzen/.test(s) ? 0xed1c24 : (/intel|core/.test(s) ? 0x0071c5 : 0x9aa0a8);
            return { big: big, tint: tint };
        }
        if (key === 'storage') {
            var type = /hdd|hard drive|7200|5400/.test(s) ? 'hdd' : (/(2\.5|sata ssd)/.test(s) ? 'ssd' : 'm2');
            return { type: type };
        }
        if (key === 'psu') {
            return { watt: parseInt((s.match(/(\d{3,4})\s*w/) || [])[1] || '650', 10), accent: brandAccent() };
        }
        return {};
    }

    function buildPSU(st) {
        st = st || {};
        var g = makePart('psu');
        var big = (st.watt || 650) >= 850;
        // white PSU shroud across the bottom
        var shroud = box(HX * 2 * 0.9, 0.8, HZ * 2 * 0.8, whiteMat(0.45));
        shroud.position.set(-0.05, -1.95, -0.1); g.add(shroud);
        // visible PSU body (slightly larger for high-wattage units)
        var psu = box(big ? 3.0 : 2.7, big ? 0.7 : 0.6, 1.7, mat(0x23232b, { metalness: 0.6, roughness: 0.4 }));
        psu.position.set(-0.1, -2.38, -0.2); g.add(psu);
        // accent line on the shroud front (syncs with theme glow)
        var line = box(2.4, 0.06, 0.06, glowMat(ACCENT, 1.3));
        line.position.set(-0.1, -1.95, HZ * 0.8 - 0.12);
        line.userData.noFade = true; line.userData.noShadow = true; line.userData.glow = true; g.add(line);
    }

    function buildMotherboard(st) {
        st = st || {};
        var form = st.form || 'atx';
        var BH = form === 'itx' ? 2.4 : (form === 'matx' ? 2.95 : 3.7);
        var BW = form === 'itx' ? 2.4 : 2.95;
        var topY = 2.45, cy = topY - BH / 2;
        var z = -HZ + 0.12, zf = z + 0.07;
        var g = makePart('motherboard');
        var pcb = box(BW, BH, 0.1, mat(st.color != null ? st.color : 0x101218, { metalness: 0.3, roughness: 0.78 }));
        pcb.position.set(-0.2, cy, z); g.add(pcb);

        var dark = mat(0x1a1d24, { metalness: 0.6, roughness: 0.5 });
        var metalHs = mat(0x34383f, { metalness: 0.85, roughness: 0.3 });
        var brand = st.brandAccent != null ? st.brandAccent : 0x3a3d44;

        // CPU socket
        var sock = box(0.62, 0.62, 0.06, metalHs); sock.position.set(-0.2, cy + BH * 0.16, zf); g.add(sock);

        // finned VRM heatsinks (left + top of socket)
        function finned(w, h, x, y) {
            var blk = box(w, h, 0.18, metalHs); blk.position.set(x, y, zf + 0.03); g.add(blk);
            var rows = 4;
            for (var k = 0; k < rows; k++) {
                var fin = box(w, 0.03, 0.24, metalHs);
                fin.position.set(x, y - h / 2 + 0.08 + k * (h / rows), zf + 0.05); g.add(fin);
            }
        }
        finned(0.42, Math.min(1.1, BH * 0.32), -0.2 - BW / 2 + 0.4, topY - 0.78);
        finned(0.95, 0.34, -0.45, topY - 0.3);

        // rear I/O cover (brand-tinted)
        var io = box(1.45, 0.5, 0.22, mat(brand, { metalness: 0.6, roughness: 0.4 }));
        io.position.set(-0.2 - BW / 2 + 0.78, topY - 0.28, zf); g.add(io);

        // DIMM slot bases (under where the RAM sits)
        var nSlots = form === 'itx' ? 2 : 4;
        for (var i = 0; i < nSlots; i++) {
            var ds = box(0.16, 0.3, 0.66, dark);
            ds.position.set(0.55 + i * 0.26, 0.2, -1.55); g.add(ds);
        }

        // M.2 heatsink covers with cyan strips
        for (var m = 0; m < (form === 'itx' ? 1 : 2); m++) {
            var my = cy - 0.2 - m * 0.5;
            var cover = box(1.5, 0.34, 0.1, dark); cover.position.set(-0.35, my, zf); g.add(cover);
            var strip = box(1.3, 0.05, 0.12, glowMat(ACCENT, 1.2));
            strip.position.set(-0.35, my, zf + 0.03);
            strip.userData.noFade = true; strip.userData.noShadow = true; strip.userData.glow = true; g.add(strip);
        }

        // chipset heatsink (brand-tinted)
        var chip = box(0.78, 0.78, 0.14, mat(brand, { metalness: 0.7, roughness: 0.4 }));
        chip.position.set(0.6, cy - BH * 0.3, zf); g.add(chip);

        // reinforced PCIe x16 slot with glow
        var pcie = box(Math.min(2.0, BW * 0.7), 0.1, 0.16, metalHs);
        pcie.position.set(-0.4, cy - BH * 0.1, zf); g.add(pcie);
        var pglow = box(Math.min(1.85, BW * 0.64), 0.05, 0.1, glowMat(ACCENT, 1.0));
        pglow.position.set(-0.4, cy - BH * 0.1, zf + 0.04);
        pglow.userData.noFade = true; pglow.userData.noShadow = true; pglow.userData.glow = true; g.add(pglow);

        // 24-pin ATX power connector on the right edge
        var atx = box(0.18, 0.72, 0.22, dark);
        atx.position.set(-0.2 + BW / 2 - 0.12, cy + 0.25, zf); g.add(atx);

        // Capacitors scattered around the CPU socket (small dark cylinders)
        var capMat = grayMat(0x18191d, 0.4);
        [[-0.55, 0.42], [-0.55, 0.02], [0.16, 0.5], [0.02, -0.1]].forEach(function (p) {
            var cap = new THREE.Mesh(new THREE.CylinderGeometry(0.045, 0.045, 0.16, 12), capMat);
            cap.position.set(-0.2 + p[0], cy + p[1], zf + 0.08); g.add(cap);
        });

        // RAM slot retention clips (small tabs at each end of the DIMM slots)
        for (var rc = 0; rc < nSlots; rc++) {
            var clip = box(0.14, 0.06, 0.05, dark);
            clip.position.set(0.55 + rc * 0.26, 0.36, -1.55); g.add(clip);
        }

        // SATA port row (bottom-right edge, angled connectors)
        for (var sp = 0; sp < 4; sp++) {
            var sata = box(0.05, 0.22, 0.14, mat(0x14161b, { metalness: 0.5, roughness: 0.5 }));
            sata.position.set(-0.2 + BW / 2 - 0.08, cy - BH / 2 + 0.25, zf - 0.3 + sp * 0.18); g.add(sata);
        }
    }

    function buildCPU(st) {
        st = st || {};
        var g = makePart('cpu');
        var w = st.big ? 1.25 : 1.05, h = st.big ? 2.0 : 1.65, d = st.big ? 1.15 : 0.95;
        var baseY = 0.95, z = -1.45;
        // mounting base
        var base = box(w * 0.7, 0.5, d * 0.7, grayMat(0x23262d, 0.5));
        base.position.set(-0.2, baseY - h / 2 + 0.25, z); g.add(base);
        // aluminium fin stack
        var fins = Math.round(h / 0.13);
        var finMat = grayMat(0xc7ccd3, 0.4);
        for (var i = 0; i < fins; i++) {
            var fin = box(w, 0.04, d, finMat);
            fin.position.set(-0.2, baseY - h / 2 + 0.5 + i * (h * 0.86 / fins), z); g.add(fin);
        }
        // top plate
        var cap = box(w, 0.1, d, grayMat(0x3b4048, 0.4));
        cap.position.set(-0.2, baseY + h / 2 + 0.04, z); g.add(cap);
        // heatpipe tips poking through the top
        [-w * 0.28, 0, w * 0.28].forEach(function (hx) {
            var hp = new THREE.Mesh(new THREE.CylinderGeometry(0.06, 0.06, 0.18, 12), grayMat(0xb08d57, 0.3));
            hp.position.set(-0.2 + hx, baseY + h / 2 + 0.12, z); g.add(hp);
        });
        // brand badge
        var badge = box(0.34, 0.34, 0.03, mat(st.tint != null ? st.tint : 0x0071c5, { metalness: 0.3, roughness: 0.5 }));
        badge.position.set(-0.2, baseY + 0.35, z + d / 2 + 0.02); g.add(badge);
        // front fan with RGB ring
        var f = makeFan(st.big ? 0.56 : 0.5, ACCENT);
        f.position.set(-0.2, baseY, z + d / 2 + 0.36); g.add(f);
    }

    function buildRAM(st) {
        st = st || {};
        var n = st.sticks || 4;
        var rgb = st.rgb != null ? st.rgb : true;
        var color = st.color != null ? st.color : 0x282c34;
        var g = makePart('ram');
        for (var i = 0; i < n; i++) {
            var x = 0.55 + i * 0.26;
            // green/black PCB
            var pcb = box(0.05, 1.45, 0.6, grayMat(0x0e1014, 0.6));
            pcb.position.set(x, 0.92, -1.55); g.add(pcb);
            // metal heatspreader
            var hs = box(0.12, 1.3, 0.6, grayMat(color, 0.45));
            hs.position.set(x, 1.0, -1.55); g.add(hs);
            if (rgb) {
                var bar = box(0.13, 0.14, 0.6, glowMat(ACCENT, 2.6));
                bar.position.set(x, 1.72, -1.55);
                bar.userData.noFade = true; bar.userData.noShadow = true; bar.userData.glow = true; g.add(bar);
            } else {
                var top = box(0.12, 0.1, 0.6, grayMat(0x4a4f57, 0.5));
                top.position.set(x, 1.7, -1.55); g.add(top);
            }
        }
    }

    function buildGPU(st) {
        st = st || {};
        var len = st.big ? 3.05 : 2.7;
        var ht = st.big ? 0.62 : 0.5;
        var color = st.color != null ? st.color : 0x202229;
        var fans = st.fans || 2;
        var z = -1.0;
        var g = makePart('gpu');
        // main shroud
        var shroud = box(len, ht, 1.55, grayMat(color, 0.5));
        shroud.position.set(-0.2, -0.55, z); g.add(shroud);
        // brand-coloured top edge strip
        var accent = box(len + 0.02, 0.05, 1.0, mat(st.brandAccent != null ? st.brandAccent : 0xffffff, { metalness: 0.4, roughness: 0.4 }));
        accent.position.set(-0.2, -0.55 + ht / 2, z); g.add(accent);
        // backplate toward the board
        var back = box(len, ht * 0.92, 0.08, grayMat(0x15161b, 0.5));
        back.position.set(-0.2, -0.55, z - 0.8); g.add(back);
        // PCB lip into the PCIe slot
        var bp = box(len, 0.14, 0.3, grayMat(0x16171c, 0.45));
        bp.position.set(-0.2, -0.4, -HZ + 0.25); g.add(bp);
        // I/O bracket (display outputs) at the slot end
        var bracket = box(0.08, ht + 0.2, 1.4, grayMat(0x3a3d44, 0.4));
        bracket.position.set(-0.2 - len / 2 - 0.02, -0.55, z); g.add(bracket);
        // power connector block on the top edge
        var pwr = box(0.32, 0.16, 0.3, grayMat(0x0f1014, 0.5));
        pwr.position.set(-0.2 + len / 2 - 0.35, -0.55 + ht / 2 + 0.08, z + 0.3); g.add(pwr);
        // fan row recessed into the front face
        spread(fans, len * 0.3).forEach(function (x) {
            var f = makeFan(st.big ? 0.46 : 0.44, ACCENT);
            f.position.set(-0.2 + x, -0.55, z + 0.78); g.add(f);
        });

        // backplate ridge lines (brushed-metal look)
        for (var rline = 0; rline < 4; rline++) {
            var ridge = box(len * 0.85, 0.02, 0.01, grayMat(0x22242a, 0.35));
            ridge.position.set(-0.2, -0.55 - ht * 0.3 + rline * (ht * 0.5 / 4), z - 0.84); g.add(ridge);
        }

        // anti-sag support bracket down to the case floor (common on big cards)
        if (st.big) {
            var brace = box(0.1, 0.9, 0.1, grayMat(0x2a2d33, 0.4));
            brace.position.set(-0.2 + len / 2 - 0.3, -1.0, z + 0.3); g.add(brace);
            var braceFoot = box(0.3, 0.06, 0.3, grayMat(0x1b1d22, 0.5));
            braceFoot.position.set(-0.2 + len / 2 - 0.3, -1.45, z + 0.3); g.add(braceFoot);
        }
    }

    function buildStorage(st) {
        st = st || {};
        var type = st.type || 'm2';
        var g = makePart('storage');
        if (type === 'hdd') {
            var hdd = box(1.2, 0.5, 0.9, grayMat(0x3a3d44, 0.45));
            hdd.position.set(1.2, -1.6, -0.5); g.add(hdd);
            var hl = box(0.7, 0.06, 0.06, glowMat(ACCENT, 1.2));
            hl.position.set(1.2, -1.34, -0.06); hl.userData.noFade = true; hl.userData.noShadow = true; hl.userData.glow = true; g.add(hl);
        } else if (type === 'ssd') {
            var ssd = box(1.0, 0.16, 0.7, whiteMat(0.4));
            ssd.position.set(1.25, -1.5, -0.5); g.add(ssd);
            var dot = box(0.16, 0.13, 0.16, glowMat(ACCENT, 1.4));
            dot.position.set(1.58, -1.5, -0.72); dot.userData.noFade = true; dot.userData.noShadow = true; dot.userData.glow = true; g.add(dot);
        } else { // m.2 stick on the board
            var m2 = box(0.9, 0.1, 0.34, grayMat(0x2a2e36, 0.4));
            m2.position.set(-0.1, -0.55, -HZ + 0.2); g.add(m2);
            var led = box(0.12, 0.11, 0.12, glowMat(ACCENT, 1.6));
            led.position.set(0.3, -0.55, -HZ + 0.22); led.userData.noFade = true; led.userData.noShadow = true; led.userData.glow = true; g.add(led);
        }
    }

    // =====================================================================
    // INSTALL / REMOVE + ANIMATION
    // =====================================================================
    function setOpacity(group, o) {
        var fading = o < 0.999;
        group.traverse(function (c) {
            if (c.isMesh && !c.userData.noFade) {
                c.material.opacity = o;
                // only transparent mid-animation; opaque when fully shown (correct occlusion)
                c.material.transparent = fading;
            }
        });
    }

    function installPart(key, silent) {
        var g = partMeshes[key];
        if (!g || installed[key]) return;
        installed[key] = true;
        g.visible = true;
        // drop-in animation
        var targetY = g.position.y;
        g.position.y = targetY + 4;
        setOpacity(g, 0);
        animations.push({ group: g, fromY: targetY + 4, toY: targetY, t: 0, dur: silent ? 0.001 : 0.5 });
        updateUI();
    }

    function removePart(key) {
        var g = partMeshes[key];
        if (!g || !installed[key]) return;
        installed[key] = false;
        g.visible = false;
        updateUI();
    }

    function tickAnimations(dt) {
        for (var i = animations.length - 1; i >= 0; i--) {
            var a = animations[i];
            a.t += dt;
            var k = Math.min(a.t / a.dur, 1);
            var e = 1 - Math.pow(1 - k, 3); // easeOutCubic
            a.group.position.y = a.fromY + (a.toY - a.fromY) * e;
            setOpacity(a.group, e);
            if (k >= 1) { a.group.position.y = a.toY; setOpacity(a.group, 1); animations.splice(i, 1); }
        }
    }

    // =====================================================================
    // RENDER LOOP
    // =====================================================================
    var clock = (typeof THREE !== 'undefined') ? new THREE.Clock() : null;
    function animate() {
        rafId = requestAnimationFrame(animate);
        var dt = clock ? clock.getDelta() : 0.016;
        tickAnimations(dt);
        if (autoRotate) orbit.theta += dt * 0.4;

        // spin fan rotors (tagged userData.spin) of installed parts
        Object.keys(installed).forEach(function (key) {
            if (installed[key] && partMeshes[key]) {
                partMeshes[key].traverse(function (o) {
                    if (o.userData && o.userData.spin) o.rotation.z += dt * 6;
                });
            }
        });

        updateCamera();
        renderer.render(scene, camera);
    }

    function updateCamera() {
        var s = orbit;
        s.phi = Math.max(0.2, Math.min(Math.PI - 0.2, s.phi));
        s.radius = Math.max(s.min, Math.min(s.max, s.radius));
        camera.position.x = s.target.x + s.radius * Math.sin(s.phi) * Math.sin(s.theta);
        camera.position.y = s.target.y + s.radius * Math.cos(s.phi);
        camera.position.z = s.target.z + s.radius * Math.sin(s.phi) * Math.cos(s.theta);
        camera.lookAt(s.target.x, s.target.y, s.target.z);
    }

    function resize() {
        if (!renderer || !holder) return;
        var w = holder.clientWidth, h = holder.clientHeight;
        if (w === 0 || h === 0) return;
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
    }

    // =====================================================================
    // INPUT (orbit + pick)
    // =====================================================================
    function setupInput() {
        raycaster = new THREE.Raycaster();
        pointer = new THREE.Vector2();
        var moved = false;

        canvas.addEventListener('pointerdown', function (e) {
            dragging = true; moved = false; lastX = e.clientX; lastY = e.clientY;
            canvas.style.cursor = 'grabbing';
        });
        window.addEventListener('pointerup', function (e) {
            if (dragging && !moved) pick(e);
            dragging = false; canvas.style.cursor = 'grab';
        });
        window.addEventListener('pointermove', function (e) {
            if (!dragging) return;
            var dx = e.clientX - lastX, dy = e.clientY - lastY;
            if (Math.abs(dx) + Math.abs(dy) > 3) moved = true;
            orbit.theta -= dx * 0.008;
            orbit.phi -= dy * 0.008;
            lastX = e.clientX; lastY = e.clientY;
        });
        canvas.addEventListener('wheel', function (e) {
            e.preventDefault();
            orbit.radius += e.deltaY * 0.01;
        }, { passive: false });

        // touch pinch
        canvas.addEventListener('touchmove', function (e) {
            if (e.touches.length === 2) {
                var d = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY);
                if (pinchDist) orbit.radius += (pinchDist - d) * 0.02;
                pinchDist = d;
            }
        }, { passive: true });
        canvas.addEventListener('touchend', function () { pinchDist = 0; });
    }

    function pick(e) {
        var rect = canvas.getBoundingClientRect();
        pointer.x = ((e.clientX - rect.left) / rect.width) * 2 - 1;
        pointer.y = -((e.clientY - rect.top) / rect.height) * 2 + 1;
        raycaster.setFromCamera(pointer, camera);
        var hits = raycaster.intersectObjects(pcGroup.children, true);
        for (var i = 0; i < hits.length; i++) {
            var o = hits[i].object;
            while (o && !o.userData.partKey) o = o.parent;
            if (o && o.userData.partKey && installed[o.userData.partKey]) {
                showPartInfo(o.userData.partKey);
                return;
            }
        }
    }

    function showPartInfo(key) {
        var def = PARTS.find(function (p) { return p.key === key; });
        var info = partInfo[key];
        var el = document.getElementById('simPartInfo');
        if (!el) return;
        var name = (info && info.name) || (def && def.defName) || key;
        var price = info && info.price ? ' • ₱' + Number(info.price).toLocaleString() : '';
        el.innerHTML = '<i class="fas ' + (def ? def.icon : 'fa-cube') + '"></i> <strong>' + def.label + ':</strong> ' + escapeSim(name) + price;
    }

    // =====================================================================
    // UI PANEL
    // =====================================================================
    function renderPartsList() {
        var list = document.getElementById('simPartsList');
        if (!list) return;
        list.innerHTML = PARTS.map(function (p) {
            var on = installed[p.key];
            var info = partInfo[p.key];
            var name = (info && info.name) || p.defName;
            return '' +
                '<div class="sim-part-row ' + (on ? 'installed' : '') + '">' +
                  '<div class="sim-part-ic"><i class="fas ' + p.icon + '"></i></div>' +
                  '<div class="sim-part-meta">' +
                    '<div class="sim-part-name">' + p.label + '</div>' +
                    '<div class="sim-part-sub">' + escapeSim(name) + '</div>' +
                  '</div>' +
                  '<button class="sim-part-btn ' + (on ? 'remove' : 'install') + '" onclick="simToggle(\'' + p.key + '\')">' +
                    (on ? '✓ Installed' : 'Install') +
                  '</button>' +
                '</div>';
        }).join('');
    }

    function updateUI() {
        renderPartsList();
        var count = PARTS.filter(function (p) { return installed[p.key]; }).length;
        var cEl = document.getElementById('simProgressCount');
        var fEl = document.getElementById('simProgressFill');
        if (cEl) cEl.textContent = count + ' / ' + PARTS.length;
        if (fEl) fEl.style.width = (count / PARTS.length * 100) + '%';

        var total = PARTS.reduce(function (s, p) {
            return s + (installed[p.key] && partInfo[p.key] ? (partInfo[p.key].price || 0) : 0);
        }, 0);
        var tEl = document.getElementById('simTotalPrice');
        if (tEl) tEl.innerHTML = total > 0 ? 'Installed parts total: <strong>₱' + total.toLocaleString() + '</strong>' : '';

        if (count === PARTS.length) {
            var pi = document.getElementById('simPartInfo');
            if (pi) pi.innerHTML = '🎉 <strong>Build complete!</strong> Drag to admire your rig.';
        }
    }

    // =====================================================================
    // DATA: pull selected parts from the Custom Build, if present
    // =====================================================================
    function loadDataFromCustomBuild(silent) {
        var src = (typeof customBuildComponents !== 'undefined') ? customBuildComponents : null;
        if (!src) return false;
        var found = false;
        // map sim keys -> customBuild keys (same names, plus cpu cooler shares cpu)
        PARTS.forEach(function (p) {
            var c = src[p.key];
            if (c) {
                partInfo[p.key] = {
                    name: (c.brandName && String(c.name || '').toLowerCase().indexOf(String(c.brandName).toLowerCase()) === -1 ? c.brandName + ' ' : '') + (c.name || ''),
                    price: c.price || 0
                };
                found = true;
            }
        });
        if (found && !silent) updateUI(); else renderPartsList();
        return found;
    }

    // Sync the 3D build to the user's Custom Build selection:
    // install exactly the parts they picked, remove the ones they didn't.
    // Returns the number of selected parts (or -1 if no custom build state exists).
    function applyCustomBuild() {
        var src = (typeof customBuildComponents !== 'undefined') ? customBuildComponents : null;
        if (!src) return -1;
        var selected = [];
        PARTS.forEach(function (p) {
            var c = src[p.key];
            if (c) {
                partInfo[p.key] = {
                    name: (c.brandName && String(c.name || '').toLowerCase().indexOf(String(c.brandName).toLowerCase()) === -1 ? c.brandName + ' ' : '') + (c.name || ''),
                    price: c.price || 0
                };
                selected.push(p.key);
            } else {
                delete partInfo[p.key];
            }
        });

        // Rebuild each part's 3D model to match the selected item
        var caseAccent = src.case ? deriveCaseStyle(src.case).accent : ACCENT;
        PARTS.forEach(function (p) {
            var c = src[p.key];
            rebuildPart(p.key, c ? derivePartStyle(p.key, c) : null);
        });
        recolorGlow(caseAccent);

        // Remove anything not in the selection
        PARTS.forEach(function (p) {
            if (selected.indexOf(p.key) === -1) removePart(p.key);
        });
        // Install selected parts with a staggered drop-in
        var delay = 0;
        selected.forEach(function (key) {
            if (!installed[key]) {
                setTimeout(function () { installPart(key); }, delay);
                delay += 260;
            }
        });
        updateUI();
        return selected.length;
    }

    function escapeSim(s) {
        return String(s == null ? '' : s).replace(/[&<>"]/g, function (m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[m];
        });
    }

    // =====================================================================
    // GLOBAL BUTTON HANDLERS
    // =====================================================================
    window.simToggle = function (key) {
        if (installed[key]) removePart(key); else installPart(key);
    };

    window.simBuildAll = function () {
        var delay = 0;
        PARTS.forEach(function (p) {
            if (!installed[p.key]) {
                setTimeout(function () { installPart(p.key); }, delay);
                delay += 280;
            }
        });
    };

    window.simResetBuild = function () {
        PARTS.forEach(function (p) { removePart(p.key); });
        var pi = document.getElementById('simPartInfo');
        if (pi) pi.textContent = 'Click a part to inspect it.';
    };

    window.simLoadCustomBuild = function () {
        if (!initialized) { whenReady(window.simLoadCustomBuild); if (window.initSimulator) window.initSimulator(); return; }
        var n = applyCustomBuild();
        var pi = document.getElementById('simPartInfo');
        if (!pi) return;
        if (n === -1 || n === 0) {
            pi.innerHTML = 'No parts selected yet — go to <strong>Custom Build</strong> and pick your components first.';
        } else {
            pi.innerHTML = '🧩 Installed <strong>' + n + '</strong> part' + (n > 1 ? 's' : '') + ' from your Custom Build.';
        }
    };

    // Called from the Custom Build page: jump to the simulator and drop in
    // exactly the parts the user selected.
    window.viewBuildIn3D = function () {
        if (typeof showPage === 'function') showPage('simulator'); // this also calls initSimulator()
        if (typeof window.initSimulator === 'function') window.initSimulator();
        // Defer so the canvas has its size after the page becomes visible
        setTimeout(function () { window.simLoadCustomBuild(); }, 60);
    };

    window.simResetView = function () {
        orbit.target.x = 0; orbit.target.y = 0.2; orbit.target.z = 0;
        orbit.radius = 9; orbit.theta = 0.9; orbit.phi = 1.15;
    };
    window.simTopView = function () { orbit.theta = 0.0; orbit.phi = 0.25; orbit.radius = 11; };
    window.simToggleAutoRotate = function () {
        autoRotate = !autoRotate;
        var b = document.getElementById('simAutoRotateBtn');
        if (b) b.classList.toggle('active', autoRotate);
    };
})();
