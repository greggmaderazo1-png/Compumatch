<?php
// =============================================================
// CompuMatch — Business permit AUTO-CHECK
//
// Accepted documents: DTI Certificate of Business Name Registration, or a
// paper Business Permit (a.k.a. Mayor's Permit) from any city/municipality.
// They are recognized by the WORDS on them (read in the browser by OCR),
// not by their design. Expired documents and permits already used by
// another store stay "pending" for manual review. Metal permit plates are
// not auto-approved.
//
// ⚠️ What this can and can't do:
//   ✔ Recognizes DTI certificates and business permits from any LGU
//   ✔ Catches random images (selfies, memes, other documents)
//   ✔ Catches expired documents and permits reused by two stores
//   ✘ Cannot prove a permit is REAL — a good edited fake can still pass,
//     and the OCR text comes from the browser. Only the issuing office can
//     truly confirm it, so System Admin can still reject any store.
// =============================================================

if (!defined('PERMIT_DIR')) define('PERMIT_DIR', __DIR__ . '/uploads/permits');
define('PERMIT_SAMPLE_DIR', PERMIT_DIR . '/samples');

// Anything THIS similar (0–1) to ANOTHER STORE'S permit photo is almost
// certainly the same picture re-used — held for manual review.
define('PERMIT_NEAR_DUPLICATE_SCORE', 0.97);

// Creates a folder and locks it with .htaccess so images can't be opened
// directly in a browser (only through view_permit.php, admin-only).
function permitEnsureDir($dir) {
    if (!is_dir($dir) && !@mkdir($dir, 0755, true)) return false;
    if (!is_file(PERMIT_DIR . '/.htaccess')) {
        @file_put_contents(PERMIT_DIR . '/.htaccess',
            "<IfModule mod_authz_core.c>\n    Require all denied\n</IfModule>\n" .
            "<IfModule !mod_authz_core.c>\n    Order allow,deny\n    Deny from all\n</IfModule>\n" .
            "Options -Indexes -ExecCGI\n");
    }
    return true;
}

// All sample permit files (full paths).
function permitListSamples() {
    if (!is_dir(PERMIT_SAMPLE_DIR)) return [];
    $out = [];
    foreach (scandir(PERMIT_SAMPLE_DIR) as $f) {
        if (preg_match('/^[A-Za-z0-9_\-]+\.(jpg|jpeg|png|webp)$/i', $f)) $out[] = PERMIT_SAMPLE_DIR . '/' . $f;
    }
    sort($out);
    return $out;
}

function permitLoadImage($path) {
    $info = @getimagesize($path);
    if (!$info) return null;
    $im = false;
    switch ($info[2]) {
        case IMAGETYPE_JPEG: $im = @imagecreatefromjpeg($path); break;
        case IMAGETYPE_PNG:  $im = @imagecreatefrompng($path); break;
        case IMAGETYPE_WEBP: $im = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false; break;
    }
    return $im ?: null;
}

function permitResize($im, $w, $h) {
    $out = imagecreatetruecolor($w, $h);
    imagefill($out, 0, 0, imagecolorallocate($out, 255, 255, 255)); // transparent PNG areas -> white
    imagecopyresampled($out, $im, 0, 0, 0, 0, $w, $h, imagesx($im), imagesy($im));
    return $out;
}

function permitResizeMax($im, $max) {
    $w = imagesx($im); $h = imagesy($im);
    $scale = min(1, $max / max($w, $h));
    return permitResize($im, max(1, (int)round($w * $scale)), max(1, (int)round($h * $scale)));
}

function permitRgb($c) { return [($c >> 16) & 255, ($c >> 8) & 255, $c & 255]; }

// Turns an image into a small "fingerprint":
//   gray   — 16x16 brightness layout (where the logo, title, text, QR code are)
//   chroma — 8x8 color layout
//   hue    — which strong colors appear (logos, seals), ignoring paper and text
//   sat    — how much of the image is strongly colored
// Colors are white-balanced first, so a photo taken under purple/yellow
// lighting still matches a sample taken under normal light.
function permitFeatures($im) {
    $base = permitResize($im, 64, 64);
    $px = [];
    $sum = [0, 0, 0];
    for ($y = 0; $y < 64; $y++) for ($x = 0; $x < 64; $x++) {
        $p = permitRgb(imagecolorat($base, $x, $y));
        $px[] = $p;
        $sum[0] += $p[0]; $sum[1] += $p[1]; $sum[2] += $p[2];
    }
    imagedestroy($base);

    // Gray-world white balance: make the average color neutral gray.
    $avg = ($sum[0] + $sum[1] + $sum[2]) / 3;
    $f = [];
    for ($c = 0; $c < 3; $c++) $f[$c] = $sum[$c] > 4096 ? $avg / $sum[$c] : 1;
    foreach ($px as $i => $p) {
        $px[$i] = [min(255, $p[0] * $f[0]), min(255, $p[1] * $f[1]), min(255, $p[2] * $f[2])];
    }

    // Average colors over an n x n grid of cells.
    $cells = function ($n) use ($px) {
        $size = intdiv(64, $n);
        $out = [];
        for ($cy = 0; $cy < $n; $cy++) for ($cx = 0; $cx < $n; $cx++) {
            $acc = [0, 0, 0];
            for ($y = $cy * $size; $y < ($cy + 1) * $size; $y++) {
                for ($x = $cx * $size; $x < ($cx + 1) * $size; $x++) {
                    $p = $px[$y * 64 + $x];
                    $acc[0] += $p[0]; $acc[1] += $p[1]; $acc[2] += $p[2];
                }
            }
            $k = $size * $size;
            $out[] = [$acc[0] / $k, $acc[1] / $k, $acc[2] / $k];
        }
        return $out;
    };

    $gray = [];
    foreach ($cells(16) as [$r, $g, $b]) $gray[] = 0.299 * $r + 0.587 * $g + 0.114 * $b;

    $chroma = [];
    foreach ($cells(8) as [$r, $g, $b]) {
        $chroma[] = $r - $g;
        $chroma[] = ($r + $g) / 2 - $b;
    }

    $hue = array_fill(0, 12, 0);
    $satCount = 0;
    foreach ($px as [$r, $g, $b]) {
        $mx = max($r, $g, $b); $mn = min($r, $g, $b);
        if ($mx <= 0) continue;
        $s = ($mx - $mn) / $mx;
        if ($s > 0.35 && $mx / 255 > 0.2) {
            $d = $mx - $mn;
            if ($mx == $r)      $h = fmod(($g - $b) / $d, 6);
            elseif ($mx == $g)  $h = ($b - $r) / $d + 2;
            else                $h = ($r - $g) / $d + 4;
            $h /= 6;
            if ($h < 0) $h += 1;
            $hue[((int)floor($h * 12)) % 12]++;
            $satCount++;
        }
    }
    $tot = array_sum($hue) ?: 1;
    foreach ($hue as $i => $v) $hue[$i] = $v / $tot;

    return ['v' => 2, 'gray' => $gray, 'chroma' => $chroma, 'hue' => $hue, 'sat' => $satCount / 4096];
}

// The image resized to max 256px, in 4 orientations (0°, 90°, 180°, 270°)
// in case the photo was taken sideways. Caller must imagedestroy() them.
function permitLoadRotations($path) {
    $im = permitLoadImage($path);
    if (!$im) return null;
    $med = permitResizeMax($im, 256);
    imagedestroy($im);
    $out = [$med];
    foreach ([90, 180, 270] as $angle) $out[] = imagerotate($med, $angle, 0);
    return $out;
}

function permitFreeRotations($rots) {
    foreach ((array)$rots as $r) if ($r) imagedestroy($r);
}

// Fingerprints of an image at all 4 orientations.
function permitFeaturesAllRotations($path) {
    $rots = permitLoadRotations($path);
    if (!$rots) return null;
    $out = [];
    foreach ($rots as $r) $out[] = permitFeatures($r);
    permitFreeRotations($rots);
    return $out;
}

// Fingerprints of the whole image PLUS smaller windows inside it that have
// the same shape as the sample. This way a photo that still shows some wall
// or table around the permit (loosely cropped) can still match.
function permitWindowFeatures($img, $aspect) {
    $W = imagesx($img); $H = imagesy($img);
    $out = [permitFeatures($img)];
    if ($aspect <= 0) return $out;
    foreach ([0.85, 0.7, 0.55] as $scale) {
        if ($W / $H > $aspect) { $wh = $H * $scale; $ww = $wh * $aspect; }
        else                   { $ww = $W * $scale; $wh = $ww / $aspect; }
        $ww = min($ww, $W); $wh = min($wh, $H);
        if ($ww < 24 || $wh < 24) continue;
        foreach ([0, 0.5, 1] as $fy) foreach ([0, 0.5, 1] as $fx) {
            $x = (int)round(($W - $ww) * $fx);
            $y = (int)round(($H - $wh) * $fy);
            $crop = imagecreatetruecolor((int)$ww, (int)$wh);
            imagecopy($crop, $img, 0, 0, $x, $y, (int)$ww, (int)$wh);
            $out[] = permitFeatures($crop);
            imagedestroy($crop);
        }
    }
    return $out;
}

// Upright fingerprint of a stored image (plus its width/height ratio),
// cached in a ".sig" file next to it so samples and old permits aren't
// re-processed on every registration.
function permitCachedFeatures($path) {
    $cache = $path . '.sig';
    if (is_file($cache) && filemtime($cache) >= filemtime($path)) {
        $f = json_decode((string)@file_get_contents($cache), true);
        if (is_array($f) && ($f['v'] ?? 0) === 2 && isset($f['aspect'])) return $f; // older cache -> recompute
    }
    $rots = permitLoadRotations($path);
    if (!$rots) return null;
    $f = permitFeatures($rots[0]);
    $f['aspect'] = imagesx($rots[0]) / max(1, imagesy($rots[0]));
    permitFreeRotations($rots);
    @file_put_contents($cache, json_encode($f));
    return $f;
}

function permitCorr($a, $b) {
    $n = count($a);
    if (!$n || $n !== count($b)) return 0;
    $ma = array_sum($a) / $n; $mb = array_sum($b) / $n;
    $num = 0; $da = 0; $db = 0;
    for ($i = 0; $i < $n; $i++) {
        $x = $a[$i] - $ma; $y = $b[$i] - $mb;
        $num += $x * $y; $da += $x * $x; $db += $y * $y;
    }
    return ($da > 0 && $db > 0) ? $num / sqrt($da * $db) : 0;
}

// 0 = nothing alike, 1 = identical. Layout (where things are on the page)
// matters most; color only helps a little, since lighting varies a lot.
function permitScore($a, $b) {
    $layout = max(0, permitCorr($a['gray'], $b['gray']));
    $color  = max(0, permitCorr($a['chroma'], $b['chroma']));
    if ($a['sat'] < 0.005 && $b['sat'] < 0.005) {
        $hue = 1; // both have basically no strong colors
    } else {
        $hue = 0;
        for ($i = 0; $i < 12; $i++) $hue += min($a['hue'][$i], $b['hue'][$i]);
    }
    return 0.7 * $layout + 0.2 * $color + 0.1 * $hue;
}

// Best score of an uploaded image (all rotations) vs one reference fingerprint.
function permitBestScore($uploadRotations, $refFeatures) {
    $best = 0;
    foreach ($uploadRotations as $f) $best = max($best, permitScore($f, $refFeatures));
    return $best;
}

// ─────────────────────────────────────────────────────────────
// TEXT CHECK — the store's browser reads the words on the permit (OCR with
// Tesseract.js) and sends them along. Different copies of the same kind of
// document (e.g. two DTI certificates printed/scanned differently) don't
// always LOOK alike pixel-for-pixel, but they always SAY the same key
// phrases, so this recognizes them even when the image check can't.
//
// ⚠️ The text comes from the store's browser, so a tech-savvy person could
// fake it. That's why the permit image is still saved for admin review and
// every auto-approval can still be rejected in System Admin.
// ─────────────────────────────────────────────────────────────

// Key phrases. Only words of 3+ letters (and numbers) are checked, and small
// OCR typos are tolerated (e.g. "Fegistered" still matches "REGISTERED").
const PERMIT_DTI_PHRASES = [
    'CERTIFICATE BUSINESS NAME REGISTRATION',
    'DEPARTMENT TRADE INDUSTRY',
    'BUSINESS NAME REGISTERED',
    'ACT 3883',
    'REPUBLIC ACT 863',
    'ACT 4147',
    'NOT LICENSE ENGAGE',
];
// Mayor's / Business Permit. Every LGU designs its permit differently, but
// the WORDING is mostly the same everywhere (they all follow the Local
// Government Code), so these phrases work for permits from any city or
// municipality. A permit only needs 3 of them, plus a permit number or an
// expiry date (see permitTextCheck).
const PERMIT_BP_PHRASES = [
    'BUSINESS PERMIT',
    'MAYOR PERMIT',
    'HEREBY GRANTED',
    'PERMIT NO',
    'PLATE NO',
    'VALID UNTIL',
    'OFFICE MAYOR',
    'CITY MAYOR',
    'MUNICIPAL MAYOR',
    'BUSINESS PERMITS LICENSING OFFICE',
    'BPLO',
    'REVENUE CODE',
    'ORDINANCES RULES REGULATIONS',
    'OPERATE',
    'ENGAGE BUSINESS',
    'LINE BUSINESS',
    'PROPRIETOR',
    'OWNER',
    'BUSINESS NAME',
    'EXPIRES',
];

// Human-readable version of each key phrase, shown in System Admin so the
// admin can see exactly what the auto-check looks for.
function permitPhraseLabel($p) {
    static $labels = [
        'CERTIFICATE BUSINESS NAME REGISTRATION' => 'Certificate of Business Name Registration',
        'DEPARTMENT TRADE INDUSTRY'              => 'Department of Trade and Industry',
        'BUSINESS NAME REGISTERED'               => 'business name registered',
        'ACT 3883'                               => 'Act 3883',
        'REPUBLIC ACT 863'                       => 'Republic Act No. 863',
        'ACT 4147'                               => 'Act 4147',
        'NOT LICENSE ENGAGE'                     => 'not a license to engage in business',
        'BUSINESS PERMIT'                        => 'Business Permit',
        'MAYOR PERMIT'                           => "Mayor's Permit",
        'HEREBY GRANTED'                         => 'is hereby granted',
        'PERMIT NO'                              => 'Permit No.',
        'PLATE NO'                               => 'Plate No.',
        'VALID UNTIL'                            => 'Valid Until',
        'OFFICE MAYOR'                           => 'Office of the Mayor',
        'CITY MAYOR'                             => 'City Mayor',
        'MUNICIPAL MAYOR'                        => 'Municipal Mayor',
        'BUSINESS PERMITS LICENSING OFFICE'      => 'Business Permits and Licensing Office',
        'BPLO'                                   => 'BPLO',
        'REVENUE CODE'                           => 'Revenue Code',
        'ORDINANCES RULES REGULATIONS'           => 'ordinances, rules and regulations',
        'OPERATE'                                => 'operate',
        'ENGAGE BUSINESS'                        => 'engage in business',
        'LINE BUSINESS'                          => 'Line of Business',
        'PROPRIETOR'                             => 'Proprietor',
        'OWNER'                                  => 'Owner',
        'BUSINESS NAME'                          => 'Business Name',
        'EXPIRES'                                => 'expires',
    ];
    return $labels[$p] ?? ucwords(strtolower($p));
}

function permitNormText($t) {
    $t = strtoupper((string)$t);
    $t = preg_replace('/[^A-Z0-9]+/', ' ', $t);
    return trim(preg_replace('/\s+/', ' ', $t));
}

function permitWordFound($w, $words) {
    if (isset($words[$w])) return true;
    $len = strlen($w);
    if ($len < 6) return false;
    $maxDist = $len >= 9 ? 2 : 1;
    foreach ($words as $t => $_) {
        if (abs(strlen($t) - $len) <= $maxDist && levenshtein($t, $w) <= $maxDist) return true;
    }
    return false;
}

function permitPhraseHits($norm, $phrases) {
    $words = array_flip(explode(' ', $norm));
    $hits = [];
    foreach ($phrases as $p) {
        $ok = true;
        foreach (explode(' ', $p) as $w) {
            // short words like "NO" must appear exactly; longer ones allow OCR typos
            $found = (strlen($w) < 3 && !ctype_digit($w)) ? isset($words[$w]) : permitWordFound($w, $words);
            if (!$found) { $ok = false; break; }
        }
        if ($ok) $hits[] = $p;
    }
    return $hits;
}

function permitMonth($w) {
    $i = array_search(substr($w, 0, 3), ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'], true);
    return $i === false ? null : $i + 1;
}

function permitDate($y, $m, $d) {
    return ($m && checkdate((int)$m, (int)$d, (int)$y)) ? sprintf('%04d-%02d-%02d', (int)$y, (int)$m, (int)$d) : null;
}

// Reads the expiry date in the common formats:
//   DTI:      "is valid from 20 October 2024 to 20 October 2029"
//   Permits:  "Valid Until: Dec. 31, 2026" / "valid up to 31 December 2026" /
//             "This permit expires on December 31, 2026" / "Expiry Date: 12/31/2026"
function permitValidTo($norm) {
    if (preg_match('/VALID FROM \d{1,2} [A-Z]+ \d{4} TO (\d{1,2}) ([A-Z]+) (\d{4})/', $norm, $m)) {
        return permitDate($m[3], permitMonth($m[2]), $m[1]);
    }
    $kw = '(?:VALID UNTIL|VALID UP TO|VALID TILL|VALID THRU|EXPIRES ON|EXPIRES|EXPIRY DATE|EXPIRATION DATE|EXPIRATION)';
    if (preg_match('/' . $kw . ' (?:([A-Z]+) (\d{1,2})|(\d{1,2}) ([A-Z]+)|(\d{1,2}) (\d{1,2})) (20\d\d)/', $norm, $m)) {
        if ($m[1] !== '') return permitDate($m[7], permitMonth($m[1]), $m[2]);   // Dec 31 2026
        if ($m[3] !== '') return permitDate($m[7], permitMonth($m[4]), $m[3]);   // 31 Dec 2026
        return permitDate($m[7], (int)$m[5], $m[6]);                             // 12/31/2026
    }
    return null;
}

// Mayor's/Business permits are yearly and expire on Dec 31. If no expiry
// date could be read, use the permit year instead ("Business Permit 2026").
function permitYearExpiry($norm) {
    if (!preg_match_all('/(?:PERMIT|YEAR) (20\d\d)\b/', $norm, $m)) return null;
    return max(array_map('intval', $m[1])) . '-12-31';
}

// "Business Name No. 6532842" -> '6532842'
function permitBusinessNameNo($norm) {
    return preg_match('/BUSINESS NAME NO (\d{5,})/', $norm, $m) ? $m[1] : null;
}

// Returns ['kind' => 'Business Permit'|'DTI certificate'|null, 'hits' => n,
//          'valid_to' => 'Y-m-d'|null, 'bn_no' => string|null]
function permitTextCheck($text) {
    $norm = permitNormText($text);
    $out = ['kind' => null, 'hits' => 0, 'valid_to' => null, 'bn_no' => null];
    if (strlen($norm) < 20) return $out;

    $dti = permitPhraseHits($norm, PERMIT_DTI_PHRASES);
    $bp  = permitPhraseHits($norm, PERMIT_BP_PHRASES);
    $out['valid_to'] = permitValidTo($norm);
    $out['bn_no'] = permitBusinessNameNo($norm);
    // A real permit carries an actual number, e.g. "Permit No.: 2026-0105511000-1755"
    $hasPermitNo = (bool)preg_match('/(PERMIT|PLATE) (NO|NUMBER) [A-Z]?\d/', $norm);

    // DTI first: 3+ key phrases, or 2 + a readable "valid from ... to ..." line.
    if (count($dti) >= 3 || (count($dti) >= 2 && $out['valid_to'])) {
        $out['kind'] = 'DTI certificate'; $out['hits'] = count($dti);
    // Business permit: 3+ key phrases AND a permit number or an expiry
    // date (so an article that merely talks about business permits doesn't pass).
    } elseif (count($bp) >= 3 && ($hasPermitNo || $out['valid_to'])) {
        $out['kind'] = 'Business Permit'; $out['hits'] = count($bp);
        if (!$out['valid_to']) $out['valid_to'] = permitYearExpiry($norm);
    } else {
        $out['hits'] = max(count($dti), count($bp));
    }
    return $out;
}

// The OCR text is kept next to the permit image as "<file>.txt", so
// "Re-check pending stores" can use it again later.
function permitReadText($path) {
    $f = $path . '.txt';
    return is_file($f) ? (string)@file_get_contents($f) : '';
}

// Runs the full auto-check. Returns:
//   ['status' => 'approved'|'pending', 'by' => 'auto'|null, 'notes' => '...']
// Never throws — any problem just means "pending, admin will review".
function permitAutoCheck($pdo, $path, $storeId, $text = null) {
    $pending = function ($note) { return ['status' => 'pending', 'by' => null, 'notes' => 'Auto-check: ' . $note]; };
    $rots = null;

    try {
        if ($text === null) $text = permitReadText($path);
        $tc = permitTextCheck($text);

        // A: expired certificate? (DTI certificates print their validity dates)
        if ($tc['valid_to'] && $tc['valid_to'] < date('Y-m-d')) {
            return $pending('The permit/certificate appears EXPIRED (valid until ' . $tc['valid_to'] . ') — manual review needed.');
        }

        $hash = sha1_file($path);

        // B: same permit already used by another store?
        $others = [];
        $stmt = $pdo->prepare('SELECT store_id, permit_image FROM stores WHERE permit_image IS NOT NULL AND store_id <> ?');
        $stmt->execute([$storeId]);
        foreach ($stmt->fetchAll() as $row) {
            $p = PERMIT_DIR . '/' . basename($row['permit_image']);
            if (!is_file($p)) continue;
            if (sha1_file($p) === $hash) {
                return $pending('Same image as the permit of store "' . $row['store_id'] . '" — manual review needed.');
            }
            if ($tc['bn_no']) {
                $otherTc = permitTextCheck(permitReadText($p));
                if ($otherTc['bn_no'] === $tc['bn_no']) {
                    return $pending('Same DTI Business Name No. (' . $tc['bn_no'] . ') as store "' . $row['store_id'] . '" — manual review needed.');
                }
            }
            $others[$row['store_id']] = $p;
        }

        // C: copy check — is this photo (resized/re-saved) almost identical
        //    to another store's permit?
        if (function_exists('imagecreatetruecolor') && $others) {
            $rots = permitLoadRotations($path);
            if ($rots) {
                $upload = [];
                foreach ($rots as $r) $upload[] = permitFeatures($r);
                permitFreeRotations($rots);
                $rots = null;
                foreach ($others as $otherId => $p) {
                    $f = permitCachedFeatures($p);
                    if ($f && permitBestScore($upload, $f) >= PERMIT_NEAR_DUPLICATE_SCORE) {
                        return $pending('Looks almost identical to the permit of store "' . $otherId . '" — possibly copied. Manual review needed.');
                    }
                }
            }
        }

        // D: only a DTI Certificate or a paper Business Permit is auto-approved,
        //    recognized by the words on it (works for any city or municipality).
        if ($tc['kind']) {
            return ['status' => 'approved', 'by' => 'auto',
                    'notes' => 'Auto-check: approved — recognized a ' . $tc['kind'] . ' (' . $tc['hits'] . ' key phrases'
                             . ($tc['valid_to'] ? ', valid until ' . $tc['valid_to'] : '')
                             . ($tc['bn_no'] ? ', BN No. ' . $tc['bn_no'] : '') . ').'];
        }
        if (trim((string)$text) === '') {
            return $pending('no text could be read from the photo (blurry, too dark, or the text reader did not load) — manual review needed.');
        }
        return $pending('not recognized as a DTI Certificate or Business Permit (only ' . $tc['hits'] . ' key phrase(s) found) — manual review needed.');

    } catch (Throwable $e) {
        if ($rots) permitFreeRotations($rots);
        return $pending('Error while checking (' . $e->getMessage() . ') — manual review needed.');
    }
}
