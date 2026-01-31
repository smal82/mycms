<?php
session_start();

require_once 'core/bootstrap.php';

// Aggiungi queste 3 righe per l'auto-login
require_once BASE_PATH . '/core/auto-login.php';
$db = new Database();
checkRememberMe($db);

// ===== POOR MAN'S CRON - OTTIMIZZATO =====
// Esegue ogni 1 minuto (60 secondi) per bilanciare carico/efficacia
try {
    // $db è già istanziato sopra, non serve ricrearlo
    $prefix = DB_PREFIX;
    
    $stmt = $db->pdo->prepare("SELECT setting_value FROM {$prefix}settings WHERE setting_key = 'cron_last_run'");
    $stmt->execute();
    $lastRun = $stmt->fetchColumn();
    
    if (!$lastRun || (time() - (int)$lastRun) >= 60) {
        $db->pdo->prepare("
            INSERT INTO {$prefix}settings (setting_key, setting_value) 
            VALUES ('cron_last_run', ?) ON DUPLICATE KEY UPDATE setting_value = ?
        ")->execute([time(), time()]);
        
        if (function_exists('fastcgi_finish_request')) {
            register_shutdown_function(function() {
                fastcgi_finish_request();
                include __DIR__ . '/cron-handler.php';
            });
        } else {
            @include __DIR__ . '/cron-handler.php';
        }
    }
} catch (Exception $e) {
    error_log('Cron setup: ' . $e->getMessage());
}

$cms = new CMS();
$cms->run();
?>
