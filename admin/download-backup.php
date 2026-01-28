<?php
/**
 * FILE: /admin/download-backup.php
 * Download sicuro backup
 */

require_once '../core/bootstrap.php';
require_once '../core/Admin.php';

$admin = new Admin();

// Verifica autenticazione e ruolo amministratore
if (!$admin->user->isLoggedIn() || !$admin->user->hasRole(User::ROLE_ADMIN)) {
    header('HTTP/1.0 403 Forbidden');
    exit('Accesso negato');
}

// Ottieni filename
$filename = $_GET['file'] ?? '';
$filename = basename($filename); // Previeni directory traversal

// Verifica che il file esista
$filepath = BASE_PATH . '/backups/' . $filename;

if (!file_exists($filepath) || !is_file($filepath)) {
    header('HTTP/1.0 404 Not Found');
    exit('File non trovato');
}

// Verifica che sia un file .zip
if (pathinfo($filename, PATHINFO_EXTENSION) !== 'zip') {
    header('HTTP/1.0 403 Forbidden');
    exit('Tipo file non valido');
}

// Imposta headers per download
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filepath));
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Leggi e invia file
readfile($filepath);
exit;
