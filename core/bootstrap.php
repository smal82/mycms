<?php
/**
 * FILE: /core/bootstrap.php
 * Configurazione e autoload del CMS
 */

// === CONFIGURAZIONE DATABASE ===
// Compila questi dati durante l'installazione
define('DB_HOST', 'localhost');
define('DB_NAME', 'bsalvati_smal');
define('DB_USER', 'bsalvati_bsalvati');
define('DB_PASS', '&WCVl^v;..sS');
define('DB_PREFIX', 'cmsmio_');
define('DB_CHARSET', 'utf8mb4');

// Verifica se DB è configurato
if (empty(DB_NAME) && !defined('INSTALLING') && basename($_SERVER['PHP_SELF']) !== 'install.php') {
    header('Location: /install.php');
    exit;
}

// Autoload delle classi core
spl_autoload_register(function($class) {
    $file = __DIR__ . '/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Autoload widget
spl_autoload_register(function($class) {
    if (strpos($class, 'Widget_') === 0) {
        // Prova prima nella cartella widgets (dashboard)
        $file = __DIR__ . '/widgets/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
        
        // Poi prova nella cartella theme-widgets
        $file = '/Themes/widgets/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});


// Configurazione base
define('BASE_PATH', dirname(__DIR__));
define('THEME_PATH', BASE_PATH . '/themes');
define('PLUGIN_PATH', BASE_PATH . '/plugins');
define('CONTENT_PATH', BASE_PATH . '/content');
define('ADMIN_PATH', BASE_PATH . '/admin');

// Carica sistema hook cron
require_once __DIR__ . '/cron-hooks.php';

// Carica sistema hook generico
require_once __DIR__ . '/hooks.php';

// Carica funzioni helper
require_once __DIR__ . '/functions.php';
?>
