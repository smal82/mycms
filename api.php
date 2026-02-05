<?php
require_once 'core/bootstrap.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// DEBUG: verifica che i file esistano
$files = [
    'core/Api/ApiRouter.php',
    'core/Api/Controllers/PostController.php',
    'core/Api/Controllers/PageController.php',
];

$missing = [];
foreach ($files as $file) {
    if (!file_exists($file)) {
        $missing[] = $file;
    }
}

if (!empty($missing)) {
    echo json_encode([
        'error' => 'Setup Error',
        'message' => 'Missing files',
        'missing_files' => $missing,
        'current_dir' => __DIR__
    ], JSON_PRETTY_PRINT);
    exit;
}

// Carica il router API
require_once 'core/Api/ApiRouter.php';

$apiRouter = new ApiRouter();
$apiRouter->handleRequest();
