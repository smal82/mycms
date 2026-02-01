<?php
/**
 * Plugin: RSS Feed Aggregator
 * Descrizione: Aggrega feed RSS multipli e pubblica automaticamente news
 * Versione: 1.0.0
 * Autore: smal
 */

class RSSAggregatorPlugin {
    private $cms;
    private $db;
    private $prefix;
    
    public function __construct($cms) {
        $this->cms = $cms;
        $this->db = new Database();
        $this->prefix = DB_PREFIX;
        
        // Inizializza il plugin
        $this->init();
    }
    
    /**
     * Inizializzazione del plugin
     */
    private function init() {
        // Crea tabelle se non esistono
        $this->createTables();
        
        // Registra il task handler per il cron
        $this->registerCronHandler();
        
        // Registra le pagine admin (per i callback)
    add_hook('admin_plugin_pages', [$this, 'registerAdminPages'], 10);
    
    // Registra menu custom separato (per il menu visibile)
    add_hook('admin_custom_menus', [$this, 'registerCustomMenu'], 10);
    
    // **NUOVO**: Registra hook per aggiungere link feed RSS nell'head
    add_hook('mycms_head', [$this, 'addRSSFeedLink'], 10);

        
        // Avvia lo scheduler
        if (file_exists(__DIR__ . '/includes/RSSScheduler.php')) {
            require_once __DIR__ . '/includes/RSSScheduler.php';
            $scheduler = new RSSScheduler($this->db);
            $scheduler->checkAndSchedule();
        }
    }
    
    /**
     * Attivazione plugin
     */
    public function activate() {
        $this->createTables();
        $this->createMenuItem();
    }
    
    /**
     * Disattivazione plugin
     */
    public function deactivate() {
        $this->removeMenuItem();
    }
    
    /**
     * Crea le tabelle necessarie
     */
    private function createTables() {
        try {
            // Tabella feed RSS
            $this->db->pdo->exec("CREATE TABLE IF NOT EXISTS {$this->prefix}rss_feeds (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(255) NOT NULL,
                url VARCHAR(500) NOT NULL,
                frequenza INT DEFAULT 3600 COMMENT 'Frequenza in secondi',
                ultimo_import DATETIME NULL,
                prossimo_import DATETIME NULL,
                stato ENUM('attivo', 'pausa', 'errore') DEFAULT 'attivo',
                messaggio_errore TEXT NULL,
                elementi_importati INT DEFAULT 0,
                creato_il TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                aggiornato_il TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_stato (stato),
                INDEX idx_prossimo (prossimo_import)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            
            // Tabella news (articoli importati dai feed)
            $this->db->pdo->exec("CREATE TABLE IF NOT EXISTS {$this->prefix}rss_news (
                id INT AUTO_INCREMENT PRIMARY KEY,
                feed_id INT NOT NULL,
                titolo VARCHAR(500) NOT NULL,
                slug VARCHAR(500) NOT NULL,
                excerpt TEXT NULL,
                link_originale VARCHAR(500) NOT NULL,
                immagine_url VARCHAR(500) NULL,
                autore_originale VARCHAR(255) NULL,
                data_pubblicazione DATETIME NOT NULL,
                creato_il TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                aggiornato_il TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                visualizzazioni INT DEFAULT 0,
                INDEX idx_feed (feed_id),
                INDEX idx_data (data_pubblicazione),
                INDEX idx_slug (slug),
                FOREIGN KEY (feed_id) REFERENCES {$this->prefix}rss_feeds(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            
            // Tabella elementi importati (evita duplicati)
            $this->db->pdo->exec("CREATE TABLE IF NOT EXISTS {$this->prefix}rss_elementi_importati (
                id INT AUTO_INCREMENT PRIMARY KEY,
                feed_id INT NOT NULL,
                guid VARCHAR(500) NOT NULL,
                news_id INT NOT NULL,
                importato_il TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_elemento (feed_id, guid),
                INDEX idx_feed (feed_id),
                INDEX idx_news (news_id),
                FOREIGN KEY (feed_id) REFERENCES {$this->prefix}rss_feeds(id) ON DELETE CASCADE,
                FOREIGN KEY (news_id) REFERENCES {$this->prefix}rss_news(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            
        } catch (Exception $e) {
            error_log("Errore creazione tabelle RSS: " . $e->getMessage());
        }
    }
    
    /**
     * Crea voce menu automaticamente
     */
    private function createMenuItem() {
        $prefix = $this->prefix;
        
        // Trova il menu "primary" (menu principale)
        $stmt = $this->db->pdo->prepare("SELECT id FROM {$prefix}menus WHERE location = 'primary' LIMIT 1");
        $stmt->execute();
        $menu = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$menu) {
            error_log("RSS Plugin: Menu 'primary' non trovato");
            return;
        }
        
        $menuId = $menu['id'];
        
        // Controlla se la voce esiste già
        $stmt = $this->db->pdo->prepare("
            SELECT id FROM {$prefix}menu_items 
            WHERE menu_id = ? AND url = '/rss-news'
        ");
        $stmt->execute([$menuId]);
        
        if ($stmt->fetch()) {
            // Voce già esistente
            return;
        }
        
        // Calcola sort_order (metti alla fine)
        $stmt = $this->db->pdo->prepare("
            SELECT MAX(sort_order) as max_order 
            FROM {$prefix}menu_items 
            WHERE menu_id = ? AND parent_id IS NULL
        ");
        $stmt->execute([$menuId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $sortOrder = ($result['max_order'] ?? -1) + 1;
        
        // Inserisci la voce menu
        $stmt = $this->db->pdo->prepare("
            INSERT INTO {$prefix}menu_items 
            (menu_id, parent_id, title, url, target, sort_order) 
            VALUES (?, NULL, ?, ?, '_self', ?)
        ");
        
        $stmt->execute([
            $menuId,
            '📰 News',
            '/rss-news',
            $sortOrder
        ]);
        
        error_log("RSS Plugin: Voce menu '📰 News' aggiunta con successo");
    }
    
    /**
     * Rimuove voce menu
     */
    private function removeMenuItem() {
        $prefix = $this->prefix;
        
        // Rimuovi tutte le voci con URL /rss-news
        $stmt = $this->db->pdo->prepare("
            DELETE FROM {$prefix}menu_items 
            WHERE url = '/rss-news'
        ");
        $stmt->execute();
        
        error_log("RSS Plugin: Voce menu rimossa");
    }
    
    /**
     * Registra il handler per i task cron
     */
    private function registerCronHandler() {
    add_cron_task_handler('rss_import', function($data, $db) {
        error_log("RSS CRON: Inizio esecuzione task");
        error_log("RSS CRON: Data ricevuta: " . print_r($data, true));
        
        if (!isset($data['feed_id'])) {
            error_log("RSS CRON ERROR: feed_id mancante nei dati");
            return [
                'success' => false,
                'errore' => 'feed_id mancante'
            ];
        }
        
        $feedId = $data['feed_id'];
        error_log("RSS CRON: Importazione feed ID $feedId");
        
        $importerPath = __DIR__ . '/includes/RSSImporter.php';
        error_log("RSS CRON: Percorso importer: $importerPath");
        
        if (!file_exists($importerPath)) {
            error_log("RSS CRON ERROR: File RSSImporter.php non trovato");
            return [
                'success' => false,
                'errore' => 'RSSImporter.php non trovato'
            ];
        }
        
        require_once $importerPath;
        
        if (!class_exists('RSSImporter')) {
            error_log("RSS CRON ERROR: Classe RSSImporter non trovata");
            return [
                'success' => false,
                'errore' => 'Classe RSSImporter non trovata'
            ];
        }
        
        try {
            $importer = new RSSImporter($db);
            error_log("RSS CRON: RSSImporter istanziato");
            
            $risultato = $importer->importaFeed($feedId);
            error_log("RSS CRON: Importazione completata. Elementi: $risultato");
            
            return [
                'success' => true,
                'importati' => $risultato
            ];
        } catch (Exception $e) {
            error_log("RSS CRON ERROR: " . $e->getMessage());
            error_log("RSS CRON ERROR Stack: " . $e->getTraceAsString());
            return [
                'success' => false,
                'errore' => $e->getMessage()
            ];
        }
    });
}

    
    /**
 * Hook: Intercetta le route del plugin
 * Chiamato da CMS->executeHook('before_route')
 */
public function hook_before_route($data) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/');
    
    // Route per la pagina news
    if ($uri === '/rss-news') {
        $_SERVER['PLUGIN_PAGE'] = 'rss-news';
        $_SERVER['PLUGIN_NAME'] = 'rss-aggregator';
    }
    
    // Route per il feed RSS
    if ($uri === '/rss-news-feed') {
        $feedFile = __DIR__ . '/feed.php';
        if (file_exists($feedFile)) {
            require $feedFile;
            exit; // Importante: interrompe l'esecuzione del CMS
        } else {
            error_log("RSS Plugin: feed.php non trovato in " . __DIR__);
            header("HTTP/1.0 404 Not Found");
            echo "Feed non trovato";
            exit;
        }
    }
    
    return $data;
}

    /**
     * Hook: Fornisce il contenuto per la pagina plugin
     */
    public function hook_get_plugin_content($route) {
        // Verifica se è la nostra pagina
        if (isset($_SERVER['PLUGIN_PAGE']) && $_SERVER['PLUGIN_PAGE'] === 'rss-news') {
            // Prepara i dati per il template
            $data = $this->getRSSNewsData();
            
            return [
                'title' => 'News dal Mondo',
                'content' => '',
                'type' => 'plugin_page',
                'plugin_name' => 'rss-aggregator',
                'template' => __DIR__ . '/views/rss-news.php',
                'data' => $data
            ];
        }
        
        return $route;
    }
    
    /**
     * Recupera i dati delle news per il frontend
     */
    private function getRSSNewsData() {
        $db = $this->db;
        $prefix = $this->prefix;
        
        // Paginazione
        $pagina = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $perPagina = 12;
        $offset = ($pagina - 1) * $perPagina;
        
        // Filtro per feed
        $feedFiltro = isset($_GET['feed']) ? (int)$_GET['feed'] : null;
        
        // Conta totale
        if ($feedFiltro) {
            $stmt = $db->pdo->prepare("SELECT COUNT(*) FROM {$prefix}rss_news WHERE feed_id = ?");
            $stmt->execute([$feedFiltro]);
        } else {
            $stmt = $db->pdo->query("SELECT COUNT(*) FROM {$prefix}rss_news");
        }
        $totale = $stmt->fetchColumn();
        $totalePagine = ceil($totale / $perPagina);
        
        // Recupera news
        if ($feedFiltro) {
            $sql = "
                SELECT n.*, f.nome as nome_feed, f.url as feed_url
                FROM {$prefix}rss_news n
                JOIN {$prefix}rss_feeds f ON n.feed_id = f.id
                WHERE n.feed_id = :feed_id
                ORDER BY n.data_pubblicazione DESC
                LIMIT :limit OFFSET :offset
            ";
            $stmt = $db->pdo->prepare($sql);
            $stmt->bindValue(':feed_id', $feedFiltro, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $perPagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $sql = "
                SELECT n.*, f.nome as nome_feed, f.url as feed_url
                FROM {$prefix}rss_news n
                JOIN {$prefix}rss_feeds f ON n.feed_id = f.id
                ORDER BY n.data_pubblicazione DESC
                LIMIT :limit OFFSET :offset
            ";
            $stmt = $db->pdo->prepare($sql);
            $stmt->bindValue(':limit', $perPagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
        }
        $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Lista feed per filtro
        $feeds = $db->pdo->query("
            SELECT f.id, f.nome, COUNT(n.id) as totale_news
            FROM {$prefix}rss_feeds f
            LEFT JOIN {$prefix}rss_news n ON f.id = n.feed_id
            WHERE f.stato = 'attivo'
            GROUP BY f.id, f.nome
            HAVING totale_news > 0
            ORDER BY f.nome
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'news' => $news,
            'feeds' => $feeds,
            'totale' => $totale,
            'pagina' => $pagina,
            'totalePagine' => $totalePagine,
            'perPagina' => $perPagina,
            'feedFiltro' => $feedFiltro
        ];
    }
    
    public function registerAdminPages($pages) {
    $pages[] = [
        'slug' => 'rss-feeds',
        'title' => 'RSS Feeds',
        'icon' => '📰',
        'callback' => [$this, 'renderFeedsList']
    ];
    
    $pages[] = [
        'slug' => 'rss-feed-form',
        'title' => 'Gestisci Feed',
        'icon' => '✏️',
        'callback' => [$this, 'renderFeedForm'],
        'hidden' => true
    ];
    
    $pages[] = [
        'slug' => 'rss-news-list',
        'title' => 'News Importate',
        'icon' => '📄',
        'callback' => [$this, 'renderNewsList']
    ];
    
    return $pages;
}
    
    /**
 * Registra menu admin separato
 */
public function registerCustomMenu($menus) {
    $menus[] = [
        'title' => 'RSS',
        'icon' => '📰',
        'submenu' => [
            [
                'slug' => 'rss-feeds',
                'title' => 'RSS Feeds',
                'icon' => '🔗'
            ],
            [
                'slug' => 'rss-feed-form',
                'title' => 'Gestisci Feed',
                'icon' => '✏️'
            ],
            [
                'slug' => 'rss-news-list',
                'title' => 'News Importate',
                'icon' => '📄'
            ]
        ]
    ];
    
    return $menus;
}


    
    /**
     * Render pagina lista feed
     */
    public function renderFeedsList() {
        $db = $this->db;
        $prefix = $this->prefix;
        
        // Gestione eliminazione feed
        if (isset($_POST['elimina_feed'])) {
            $feedId = (int)$_POST['feed_id'];
            $stmt = $db->pdo->prepare("DELETE FROM {$prefix}rss_feeds WHERE id = ?");
            $stmt->execute([$feedId]);
            $messaggio = "Feed eliminato con successo";
        }
        
        // Gestione cambio stato
        if (isset($_POST['cambia_stato'])) {
            $feedId = (int)$_POST['feed_id'];
            $nuovoStato = $_POST['nuovo_stato'];
            
            $stmt = $db->pdo->prepare("
                UPDATE {$prefix}rss_feeds 
                SET stato = ? 
                WHERE id = ?
            ");
            $stmt->execute([$nuovoStato, $feedId]);
            $messaggio = "Stato aggiornato con successo";
        }
        
        // Gestione importazione manuale
        if (isset($_POST['importa_ora'])) {
            $feedId = (int)$_POST['feed_id'];
            
            require_once __DIR__ . '/includes/RSSImporter.php';
            $importer = new RSSImporter($db);
            
            try {
                $importati = $importer->importaFeed($feedId);
                $messaggio = "Importati $importati elementi";
            } catch (Exception $e) {
                $errore = "Errore: " . $e->getMessage();
            }
        }
        
        // Recupera tutti i feed
        $stmt = $db->pdo->query("
            SELECT * FROM {$prefix}rss_feeds 
            ORDER BY creato_il DESC
        ");
        $feeds = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Include la view
        include __DIR__ . '/admin/views/feeds-list.php';
    }
    
    /**
     * Render pagina form feed
     */
    public function renderFeedForm() {
        $db = $this->db;
        $prefix = $this->prefix;
        
        $feedId = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $feed = null;
        $salvato = false;
        
        // Carica feed esistente
        if ($feedId) {
            $stmt = $db->pdo->prepare("SELECT * FROM {$prefix}rss_feeds WHERE id = ?");
            $stmt->execute([$feedId]);
            $feed = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$feed) {
                $errori = ['Feed non trovato'];
            }
        }
        
        // Gestione salvataggio
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome']);
            $url = trim($_POST['url']);
            $frequenza = (int)$_POST['frequenza'] * 3600; // Converti ore in secondi
            $stato = $_POST['stato'];
            
            // Validazione
            $errori = [];
            
            if (empty($nome)) {
                $errori[] = "Il nome è obbligatorio";
            }
            
            if (empty($url)) {
                $errori[] = "L'URL è obbligatorio";
            } elseif (!filter_var($url, FILTER_VALIDATE_URL)) {
                $errori[] = "URL non valido";
            }
            
            if ($frequenza < 3600) {
                $errori[] = "La frequenza minima è 1 ora";
            }
            
            if (empty($errori)) {
                if ($feedId) {
                    // Aggiorna feed esistente
                    $stmt = $db->pdo->prepare("
                        UPDATE {$prefix}rss_feeds 
                        SET nome = ?, url = ?, frequenza = ?, stato = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$nome, $url, $frequenza, $stato, $feedId]);
                    $messaggio = "Feed aggiornato con successo";
                } else {
                    // Crea nuovo feed
                    $stmt = $db->pdo->prepare("
                        INSERT INTO {$prefix}rss_feeds 
                        (nome, url, frequenza, stato, prossimo_import)
                        VALUES (?, ?, ?, ?, NOW())
                    ");
                    $stmt->execute([$nome, $url, $frequenza, $stato]);
                    $messaggio = "Feed creato con successo";
                    $feedId = $db->pdo->lastInsertId();
                }
                
                $salvato = true;
            }
        }
        
        // Include la view
        include __DIR__ . '/admin/views/feed-form.php';
    }
    
    /**
     * Render pagina lista news
     */
    public function renderNewsList() {
        $db = $this->db;
        $prefix = $this->prefix;
        
        // Gestione eliminazione
        if (isset($_POST['elimina_news'])) {
            $newsId = (int)$_POST['news_id'];
            $stmt = $db->pdo->prepare("DELETE FROM {$prefix}rss_news WHERE id = ?");
            $stmt->execute([$newsId]);
            $messaggio = "News eliminata con successo";
        }
        
        // Paginazione
        $pagina = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $perPagina = 20;
        $offset = ($pagina - 1) * $perPagina;
        
        // Filtro per feed
        $feedFiltro = isset($_GET['feed']) ? (int)$_GET['feed'] : null;
        
        // Conta totale
        if ($feedFiltro) {
            $stmt = $db->pdo->prepare("SELECT COUNT(*) FROM {$prefix}rss_news WHERE feed_id = ?");
            $stmt->execute([$feedFiltro]);
        } else {
            $stmt = $db->pdo->query("SELECT COUNT(*) FROM {$prefix}rss_news");
        }
        $totale = $stmt->fetchColumn();
        $totalePagine = ceil($totale / $perPagina);
        
        // Recupera news
        if ($feedFiltro) {
            $sql = "
                SELECT n.*, f.nome as nome_feed
                FROM {$prefix}rss_news n
                JOIN {$prefix}rss_feeds f ON n.feed_id = f.id
                WHERE n.feed_id = :feed_id
                ORDER BY n.data_pubblicazione DESC
                LIMIT :limit OFFSET :offset
            ";
            $stmt = $db->pdo->prepare($sql);
            $stmt->bindValue(':feed_id', $feedFiltro, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $perPagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $sql = "
                SELECT n.*, f.nome as nome_feed
                FROM {$prefix}rss_news n
                JOIN {$prefix}rss_feeds f ON n.feed_id = f.id
                ORDER BY n.data_pubblicazione DESC
                LIMIT :limit OFFSET :offset
            ";
            $stmt = $db->pdo->prepare($sql);
            $stmt->bindValue(':limit', $perPagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
        }
        $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Lista feed per filtro
        $feeds = $db->pdo->query("SELECT id, nome FROM {$prefix}rss_feeds ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
        
        // Include la view
        include __DIR__ . '/admin/views/news-list.php';
    }
    
    /**
 * Intercetta la route per il feed RSS news
 */
public function handleFeedRoute($data) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/');
    
    // Se la richiesta è per /rss-news-feed, serve il file feed.php del plugin
    if ($uri === '/rss-news-feed') {
        $feedFile = __DIR__ . '/feed.php';
        if (file_exists($feedFile)) {
            require $feedFile;
            exit; // Importante: interrompe l'esecuzione del CMS
        }
    }
    
    return $data;
}

/**
 * Aggiunge il link al feed RSS News nell'head
 */
public function addRSSFeedLink() {
    $siteTitle = $this->db->getSetting('site_title', 'MyCMS');
    $siteUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    
    echo '<!-- RSS News Feed -->' . "\n";
    echo '<link rel="alternate" type="application/rss+xml" title="' . htmlspecialchars($siteTitle) . ' - News dal Mondo" href="' . htmlspecialchars($siteUrl) . '/rss-news-feed">' . "\n";
    echo '<!-- /RSS News Feed -->' . "\n";
}

}
?>
