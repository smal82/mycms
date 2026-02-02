<?php
// Nel tuo controller principale

if ($_GET['action'] === 'upload_ga_credentials' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['ga_json_file']) || $_FILES['ga_json_file']['error'] !== UPLOAD_ERR_OK) {
        die('Errore upload file');
    }
    
    $jsonContent = file_get_contents($_FILES['ga_json_file']['tmp_name']);
    
    // Valida che sia un JSON valido
    $credentials = json_decode($jsonContent, true);
    if (!$credentials || !isset($credentials['type']) || $credentials['type'] !== 'service_account') {
        die('File JSON non valido. Assicurati di caricare il file Service Account corretto.');
    }
    
    // Salva nel database (criptato per sicurezza - opzionale ma consigliato)
    $db->setSetting('ga_service_account_json', base64_encode($jsonContent));
    
    // Salva anche Property ID se presente nel measurement ID
    $measurementId = $db->getSetting('google_analytics');
    if ($measurementId) {
        // Estrai property ID dal measurement ID usando le API
        // Lo faremo dopo nella classe GoogleAnalyticsAPI
    }
    
    header('Location: index.php?action=customizer&saved=1');
    exit;
}
