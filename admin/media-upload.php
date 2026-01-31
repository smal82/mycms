<?php
/**
 * FILE: /admin/media-upload.php
 * Upload file dalla galleria media (con redirect)
 */

session_start();
require_once '../core/bootstrap.php';

// Verifica login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?action=login');
    exit;
}

$db = new Database();

// Configurazione - PERCORSO ASSOLUTO
$uploadDir = __DIR__ . '/../uploads/';
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    
    // Validazione
    if ($file['error'] !== UPLOAD_ERR_OK) {
        header('Location: index.php?action=media&error=upload_error');
        exit;
    }
    
    // Ottieni il tipo MIME reale
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        header('Location: index.php?action=media&error=invalid_type');
        exit;
    }
    
    // Genera nome file unico
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Debug - rimuovi dopo il test
    error_log("Upload - Percorso destinazione: " . $filepath);
    error_log("Upload - File temporaneo: " . $file['tmp_name']);
    error_log("Upload - Directory esiste: " . (is_dir($uploadDir) ? 'SI' : 'NO'));
    error_log("Upload - Directory scrivibile: " . (is_writable($uploadDir) ? 'SI' : 'NO'));
    
    // Verifica permessi
    if (!is_writable($uploadDir)) {
        error_log("ERRORE: Directory uploads non scrivibile!");
        header('Location: index.php?action=media&error=permission_denied');
        exit;
    }
    
    // Sposta file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Verifica che il file sia stato effettivamente creato
        if (!file_exists($filepath)) {
            error_log("ERRORE: File non trovato dopo move_uploaded_file!");
            header('Location: index.php?action=media&error=save_failed');
            exit;
        }
        
        error_log("SUCCESS: File salvato correttamente in " . $filepath);
        
        // Salva nel database
        try {
            $db->saveUpload([
                'filename' => $filename,
                'original_name' => $file['name'],
                'mime_type' => $mimeType,
                'size' => $file['size'],
                'uploaded_by' => $_SESSION['user_id']
            ]);
            
            header('Location: index.php?action=media&uploaded=1');
            exit;
        } catch (Exception $e) {
            // Elimina il file se fallisce il salvataggio nel DB
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            error_log('Errore salvataggio upload: ' . $e->getMessage());
            header('Location: index.php?action=media&error=db_error');
            exit;
        }
    } else {
        $uploadError = error_get_last();
        error_log("ERRORE move_uploaded_file: " . print_r($uploadError, true));
        header('Location: index.php?action=media&error=save_failed');
        exit;
    }
} else {
    header('Location: index.php?action=media');
    exit;
}
