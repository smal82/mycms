<?php
/**
 * FILE: /install-ajax.php
 * Gestisce le richieste AJAX dell'installer
 */

define('INSTALLING', true);

// Previeni qualsiasi output prima del JSON
ob_start();
session_start();

$action = $_GET['action'] ?? '';

// Pulisci qualsiasi output precedente
ob_clean();
header('Content-Type: application/json');

try {
    switch ($action) {
        case 'test_db':
            testDatabase();
            break;
        
        case 'save_config':
            saveConfig();
            break;
        
        case 'create_tables':
            createTables();
            break;
        
        case 'insert_data':
            insertData();
            break;
        
        default:
            echo json_encode(['success' => false, 'error' => 'Azione non valida']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Errore: ' . $e->getMessage()]);
}

exit;

function testDatabase() {
    $host = $_POST['db_host'] ?? '';
    $name = $_POST['db_name'] ?? '';
    $user = $_POST['db_user'] ?? '';
    $pass = $_POST['db_pass'] ?? '';
    $prefix = $_POST['db_prefix'] ?? '';
    
    if (empty($host) || empty($name) || empty($user)) {
        echo json_encode([
            'success' => false,
            'error' => 'Compila tutti i campi obbligatori'
        ]);
        return;
    }
    
    try {
        $dsn = "mysql:host=$host;dbname=$name;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Test scrittura
        $pdo->query("SELECT 1");
        
        echo json_encode([
            'success' => true,
            'db_config' => [
                'host' => $host,
                'name' => $name,
                'user' => $user,
                'pass' => $pass,
                'prefix' => $prefix
            ]
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Connessione fallita: ' . $e->getMessage()
        ]);
    }
}

function saveConfig() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['db'])) {
        echo json_encode(['success' => false, 'error' => 'Dati mancanti']);
        return;
    }
    
    $db = $data['db'];
    
    // Leggi il file bootstrap.php
    $bootstrapPath = __DIR__ . '/core/bootstrap.php';
    
    if (!file_exists($bootstrapPath)) {
        echo json_encode(['success' => false, 'error' => 'File bootstrap.php non trovato']);
        return;
    }
    
    $bootstrapContent = file_get_contents($bootstrapPath);
    
    // Sostituisci i valori del database
    $bootstrapContent = preg_replace(
        "/define\('DB_HOST',\s*'[^']*'\);/",
        "define('DB_HOST', '" . addslashes($db['host']) . "');",
        $bootstrapContent
    );
    
    $bootstrapContent = preg_replace(
        "/define\('DB_NAME',\s*'[^']*'\);/",
        "define('DB_NAME', '" . addslashes($db['name']) . "');",
        $bootstrapContent
    );
    
    $bootstrapContent = preg_replace(
        "/define\('DB_USER',\s*'[^']*'\);/",
        "define('DB_USER', '" . addslashes($db['user']) . "');",
        $bootstrapContent
    );
    
    $bootstrapContent = preg_replace(
        "/define\('DB_PASS',\s*'[^']*'\);/",
        "define('DB_PASS', '" . addslashes($db['pass']) . "');",
        $bootstrapContent
    );
    
    $bootstrapContent = preg_replace(
        "/define\('DB_PREFIX',\s*'[^']*'\);/",
        "define('DB_PREFIX', '" . addslashes($db['prefix']) . "');",
        $bootstrapContent
    );
    
    if (file_put_contents($bootstrapPath, $bootstrapContent)) {
        $_SESSION['install_data'] = $data;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Impossibile scrivere bootstrap.php. Verifica i permessi della cartella /core/']);
    }
}

function createTables() {
    // Ricarica bootstrap.php con i nuovi dati
    require_once __DIR__ . '/core/bootstrap.php';
    
    if (empty(DB_NAME)) {
        echo json_encode(['success' => false, 'error' => 'Configurazione database non trovata']);
        return;
    }
    
    $prefix = DB_PREFIX;
    
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Tabella utenti
        $pdo->exec("CREATE TABLE IF NOT EXISTS {$prefix}users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('amministratore', 'gestore', 'registrato') DEFAULT 'registrato',
            is_active TINYINT(1) DEFAULT 1,
            activation_token VARCHAR(255),
            reset_token VARCHAR(255),
            reset_token_expiry DATETIME,
            last_login DATETIME,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_email (email),
            INDEX idx_role (role)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Tabella pagine
        $pdo->exec("CREATE TABLE IF NOT EXISTS {$prefix}pages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            content LONGTEXT,
            featured_image VARCHAR(255),
            author_id INT,
            status ENUM('bozza', 'pubblicato') DEFAULT 'bozza',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (author_id) REFERENCES {$prefix}users(id) ON DELETE SET NULL,
            INDEX idx_slug (slug),
            INDEX idx_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Tabella categorie
        $pdo->exec("CREATE TABLE IF NOT EXISTS {$prefix}categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_slug (slug)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Tabella post
        $pdo->exec("CREATE TABLE IF NOT EXISTS {$prefix}posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            content LONGTEXT,
            excerpt TEXT,
            featured_image VARCHAR(255),
            author_id INT,
            status ENUM('bozza', 'pubblicato') DEFAULT 'bozza',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (author_id) REFERENCES {$prefix}users(id) ON DELETE SET NULL,
            INDEX idx_slug (slug),
            INDEX idx_status (status),
            INDEX idx_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Tabella relazione post-categorie
        $pdo->exec("CREATE TABLE IF NOT EXISTS {$prefix}post_categories (
            post_id INT NOT NULL,
            category_id INT NOT NULL,
            PRIMARY KEY (post_id, category_id),
            FOREIGN KEY (post_id) REFERENCES {$prefix}posts(id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES {$prefix}categories(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Tabella upload
        $pdo->exec("CREATE TABLE IF NOT EXISTS {$prefix}uploads (
            id INT AUTO_INCREMENT PRIMARY KEY,
            filename VARCHAR(255) NOT NULL,
            original_name VARCHAR(255) NOT NULL,
            mime_type VARCHAR(100) NOT NULL,
            size INT NOT NULL,
            uploaded_by INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (uploaded_by) REFERENCES {$prefix}users(id) ON DELETE SET NULL,
            INDEX idx_uploaded_by (uploaded_by)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Tabella vecchia contents (manteniamo per retrocompatibilità)
        $pdo->exec("CREATE TABLE IF NOT EXISTS {$prefix}contents (
            id INT AUTO_INCREMENT PRIMARY KEY,
            type VARCHAR(50) NOT NULL,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            content TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_type (type),
            INDEX idx_slug (slug)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Tabella impostazioni
        $pdo->exec("CREATE TABLE IF NOT EXISTS {$prefix}settings (
            setting_key VARCHAR(100) PRIMARY KEY,
            setting_value TEXT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Tabella plugin attivi
        $pdo->exec("CREATE TABLE IF NOT EXISTS {$prefix}active_plugins (
            name VARCHAR(100) PRIMARY KEY
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Tabella menu
        $pdo->exec("CREATE TABLE IF NOT EXISTS {$prefix}menus (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            location VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Tabella voci menu
        $pdo->exec("CREATE TABLE IF NOT EXISTS {$prefix}menu_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            menu_id INT NOT NULL,
            parent_id INT DEFAULT NULL,
            title VARCHAR(255) NOT NULL,
            url VARCHAR(255),
            target VARCHAR(20) DEFAULT '_self',
            sort_order INT DEFAULT 0,
            FOREIGN KEY (menu_id) REFERENCES {$prefix}menus(id) ON DELETE CASCADE,
            INDEX idx_menu (menu_id),
            INDEX idx_parent (parent_id),
            INDEX idx_order (sort_order)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Tabella widget dashboard
        $pdo->exec("CREATE TABLE IF NOT EXISTS {$prefix}dashboard_widgets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            widget_type VARCHAR(100) NOT NULL,
            position INT DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            config TEXT,
            INDEX idx_position (position)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Tabella aree widget tema
        $pdo->exec("CREATE TABLE IF NOT EXISTS {$prefix}theme_widget_areas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            area_name VARCHAR(100) NOT NULL,
            widget_type VARCHAR(100) NOT NULL,
            position INT DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            config TEXT,
            INDEX idx_area (area_name),
            INDEX idx_position (position)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Tabella task programmati (NUOVA)
        $pdo->exec("CREATE TABLE IF NOT EXISTS {$prefix}scheduled_tasks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            task_type VARCHAR(50) NOT NULL,
            task_data TEXT,
            scheduled_at DATETIME NOT NULL,
            executed_at DATETIME DEFAULT NULL,
            status ENUM('pending','running','completed','failed') DEFAULT 'pending',
            error_message TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_status_scheduled (status, scheduled_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        echo json_encode(['success' => true]);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Errore creazione tabelle: ' . $e->getMessage()]);
    }
}

function insertData() {
    // Ricarica bootstrap.php con i nuovi dati
    require_once __DIR__ . '/core/bootstrap.php';
    
    if (empty(DB_NAME)) {
        echo json_encode(['success' => false, 'error' => 'Configurazione database non trovata']);
        return;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        $data = $_SESSION['install_data'] ?? null;
    }
    
    if (!$data) {
        echo json_encode(['success' => false, 'error' => 'Dati installazione non trovati']);
        return;
    }
    
    $prefix = DB_PREFIX;
    
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Impostazioni sito
        $pdo->prepare("INSERT INTO {$prefix}settings (setting_key, setting_value) VALUES (?, ?)")
            ->execute(['site_title', $data['site_title']]);
        $pdo->prepare("INSERT INTO {$prefix}settings (setting_key, setting_value) VALUES (?, ?)")
            ->execute(['site_description', $data['site_description']]);
        $pdo->prepare("INSERT INTO {$prefix}settings (setting_key, setting_value) VALUES (?, ?)")
            ->execute(['active_theme', 'aurora']);
        
        // Utente admin
        $hashedPassword = password_hash($data['admin_password'], PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO {$prefix}users (name, email, password, role, is_active) VALUES (?, ?, ?, ?, ?)")
            ->execute([$data['admin_name'], $data['admin_email'], $hashedPassword, 'amministratore', 1]);
        
        $adminId = $pdo->lastInsertId();
        
        // Categorie
        $pdo->exec("INSERT INTO {$prefix}categories (name, slug, description) VALUES 
            ('Generale', 'generale', 'Categoria generale'),
            ('Novità', 'novita', 'Ultime novità'),
            ('Guide', 'guide', 'Guide e tutorial')");
        
        // Pagine
        $pdo->prepare("INSERT INTO {$prefix}pages (title, slug, content, status, author_id) VALUES (?, ?, ?, ?, ?)")
            ->execute(['Chi siamo', 'chi-siamo', '<h2>Chi siamo</h2><p>Questa è la pagina chi siamo del sito.</p>', 'pubblicato', $adminId]);
        
        // Post
        $pdo->prepare("INSERT INTO {$prefix}posts (title, slug, content, excerpt, status, author_id) VALUES (?, ?, ?, ?, ?, ?)")
            ->execute(['Benvenuto nel nostro blog', 'benvenuto-blog', '<h2>Benvenuto!</h2><p>Questo è il primo post del nostro blog. Siamo felici di averti qui!</p>', 'Il primo articolo del nostro blog', 'pubblicato', $adminId]);
        
        $pdo->prepare("INSERT INTO {$prefix}posts (title, slug, content, excerpt, status, author_id) VALUES (?, ?, ?, ?, ?, ?)")
            ->execute(['Come iniziare', 'come-iniziare', '<h2>Guida Rapida</h2><p>Ecco una guida per iniziare.</p>', 'Una guida rapida', 'pubblicato', $adminId]);
        
        $pdo->prepare("INSERT INTO {$prefix}posts (title, slug, content, excerpt, status, author_id) VALUES (?, ?, ?, ?, ?, ?)")
            ->execute(['Novità in arrivo', 'novita-in-arrivo', '<h2>Prossimamente</h2><p>Tante novità in arrivo!</p>', 'Scopri le novità', 'pubblicato', $adminId]);
        
        // Assegna categorie
        $pdo->exec("INSERT INTO {$prefix}post_categories (post_id, category_id) VALUES (1, 1), (2, 3), (3, 2)");
        
        // Menu
        $pdo->exec("INSERT INTO {$prefix}menus (name, location) VALUES ('Menu Principale', 'primary')");
        $pdo->exec("INSERT INTO {$prefix}menu_items (menu_id, title, url, sort_order) VALUES (1, 'Home', '/', 0)");
        $pdo->exec("INSERT INTO {$prefix}menu_items (menu_id, title, url, sort_order) VALUES (1, 'Chi siamo', '/page/chi-siamo', 1)");
        $pdo->exec("INSERT INTO {$prefix}menu_items (menu_id, title, url, sort_order) VALUES (1, 'Blog', '/blog', 2)");
        
        // Widget dashboard
        $pdo->exec("INSERT INTO {$prefix}dashboard_widgets (widget_type, position) VALUES ('stats', 0), ('recent_contents', 1), ('quick_info', 2)");
        
        // Crea cartella uploads
        if (!is_dir('uploads')) {
            @mkdir('uploads', 0755, true);
        }
        
        // Invia email di riepilogo
        try {
            sendInstallationEmail($data, $adminId);
        } catch (Exception $e) {
            // L'email non è critica, continua comunque
            error_log("Errore invio email: " . $e->getMessage());
        }
        
        echo json_encode(['success' => true]);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Errore inserimento dati: ' . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Errore generale: ' . $e->getMessage()]);
    }
}

function sendInstallationEmail(array $data, int $adminId)
{
    // Destinatario
    $to = $data['admin_email'];

    // Oggetto
    $subject = 'Installazione completata con successo';

    // Recupera dominio corrente
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $domain   = $protocol . $_SERVER['HTTP_HOST'];

    // Header email
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: CMS Installer <no-reply@" . $_SERVER['HTTP_HOST'] . ">\r\n";

    // Corpo HTML
    $message = '
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Installazione completata</title>
</head>
<body style="margin:0;padding:0;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);font-family:Segoe UI,Arial,sans-serif;">
    <div style="max-width:700px;margin:40px auto;background:#ffffff;border-radius:20px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        
        <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;padding:40px;text-align:center;">
            <h1 style="margin:0;font-size:32px;">Installazione completata</h1>
            <p style="opacity:0.9;margin-top:10px;">Il tuo CMS è pronto all\'uso</p>
        </div>

        <div style="padding:40px;color:#333;">
            <p style="font-size:16px;line-height:1.6;">
                Ciao <strong>' . htmlspecialchars($data['admin_name']) . '</strong>,<br><br>
                l\'installazione del CMS è stata completata con successo.  
                Qui sotto trovi il riepilogo della configurazione iniziale.
            </p>

            <div style="background:#f8f9fa;border:2px solid #e0e0e0;border-radius:12px;padding:20px;margin:30px 0;">
                <h3 style="margin-top:0;color:#667eea;">Informazioni sito</h3>
                <p><strong>Titolo:</strong> ' . htmlspecialchars($data['site_title']) . '</p>
                <p><strong>Descrizione:</strong> ' . nl2br(htmlspecialchars($data['site_description'])) . '</p>
                <p><strong>URL:</strong> <a href="' . $domain . '" style="color:#667eea;">' . $domain . '</a></p>
            </div>

            <div style="background:#f8f9fa;border:2px solid #e0e0e0;border-radius:12px;padding:20px;margin-bottom:30px;">
                <h3 style="margin-top:0;color:#667eea;">Account amministratore</h3>
                <p><strong>Nome:</strong> ' . htmlspecialchars($data['admin_name']) . '</p>
                <p><strong>Email:</strong> ' . htmlspecialchars($data['admin_email']) . '</p>
                <p><strong>ID utente:</strong> ' . (int)$adminId . '</p>
            </div>

            <div style="text-align:center;margin-top:40px;">
                <a href="' . $domain . '/admin/" style="display:inline-block;padding:15px 40px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;text-decoration:none;border-radius:50px;font-weight:600;">
                    Accedi al pannello di amministrazione
                </a>
            </div>

            <p style="margin-top:40px;font-size:13px;color:#777;text-align:center;">
                Questa email è stata generata automaticamente al termine dell\'installazione.
            </p>
        </div>
    </div>
</body>
</html>';

    // Invio
    if (!mail($to, $subject, $message, $headers)) {
        throw new Exception('Invio email fallito');
    }
}

?>
