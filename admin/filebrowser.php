<?php
session_start();
require_once '../core/bootstrap.php';

// Verifica login
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'data' => ['messages' => ['Non autenticato']]]);
    exit;
}

$uploadDir = '../uploads/';
$files = [];
$folders = [];

if (is_dir($uploadDir)) {
    $items = array_diff(scandir($uploadDir), ['.', '..', '.htaccess']);
    
    foreach ($items as $item) {
        $filepath = $uploadDir . $item;
        
        if (is_file($filepath)) {
            $mime = mime_content_type($filepath);
            
            // Solo immagini
            if (strpos($mime, 'image/') === 0) {
                $files[] = [
                    'file' => $item,
                    'name' => $item,
                    'type' => $mime,
                    'thumb' => '../uploads/' . $item,
                    'changed' => filemtime($filepath),
                    'size' => filesize($filepath),
                    'isImage' => true
                ];
            }
        }
    }
    
    // Ordina per data modifica (più recenti prima)
    usort($files, function($a, $b) {
        return $b['changed'] - $a['changed'];
    });
}

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'data' => [
        'sources' => [
            [
                'name' => 'default',
                'path' => '',
                'baseurl' => '/uploads/',
                'files' => $files,
                'folders' => $folders
            ]
        ],
        'code' => 220
    ]
]);
