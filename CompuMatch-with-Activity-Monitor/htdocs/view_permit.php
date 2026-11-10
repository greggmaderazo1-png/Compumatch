<?php
// =============================================================
// Streams a store's business permit image — System Admin only.
// Permits live in uploads/permits/, which blocks direct access via
// .htaccess, so this is the only way to see them.
// =============================================================
require_once __DIR__ . '/db.php';
startSecureSession();

if (empty($_SESSION['systemadmin'])) {
    http_response_code(403);
    exit('Forbidden');
}

$storeId = strtolower(trim($_GET['store'] ?? ''));
if (!preg_match('/^[a-z0-9]+$/', $storeId)) {
    http_response_code(400);
    exit('Bad request');
}

try {
    $stmt = getPDO()->prepare('SELECT permit_image FROM stores WHERE store_id = ?');
    $stmt->execute([$storeId]);
    $row = $stmt->fetch();
} catch (Throwable $e) {
    http_response_code(503);
    exit('Database unavailable');
}

// basename() guards against path tricks even though the name came from our own DB.
$file = $row && $row['permit_image'] ? __DIR__ . '/uploads/permits/' . basename($row['permit_image']) : '';
if (!$file || !is_file($file)) {
    http_response_code(404);
    exit('No permit on file');
}

permitStream($file);

function permitStream($file) {
    $types = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    header('Content-Type: ' . ($types[$ext] ?? 'application/octet-stream'));
    header('Content-Length: ' . filesize($file));
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: private, no-store');
    readfile($file);
    exit;
}
