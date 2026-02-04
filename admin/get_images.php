<?php
header('Content-Type: application/json');

$uploadsDir = '../uploads/';
$images = [];

if (is_dir($uploadsDir)) {
    $files = scandir($uploadsDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
            $images[] = $file;
        }
    }
    
    // Ordina per data di modifica (più recenti prima)
    usort($images, function($a, $b) use ($uploadsDir) {
        return filemtime($uploadsDir . $b) - filemtime($uploadsDir . $a);
    });
}

echo json_encode([
    'success' => true,
    'images' => $images
]);
