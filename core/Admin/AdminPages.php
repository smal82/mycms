<?php

trait AdminPages {

    private function showPage() {
        // Mappa delle azioni e titoli
    $pageTitles = [
        'analytics_setup_guide' => 'Guida Configurazione Analytics',
        'analytics_stats' => 'Statistiche Analytics',
        'impostazioni_generali' => 'Impostazioni Generali',
        'impostazioni_lettura' => 'Impostazioni Lettura',
        'impostazioni_permalink' => 'Impostazioni Permalink',
        'custom_post_types' => 'Tipi di Post Personalizzati',
        'custom_post_types_edit' => 'Modifica CPT',
        'custom_posts_list' => 'Lista Post Personalizzati',
        'custom_posts_edit' => 'Modifica Post Personalizzato',
        'trash_media' => 'Cestino Media',
        'media' => 'Media',
        'customizer' => 'Personalizza',
        'pages' => 'Pagine',
        'edit_page' => 'Modifica Pagina',
        'posts' => 'Articoli',
        'edit_post' => 'Modifica Articolo',
        'categories' => 'Categorie',
        'contents' => 'Contenuti',
        'edit_content' => 'Modifica Contenuto',
        'themes' => 'Temi',
        'plugins' => 'Plugin',
        'plugin-page' => 'Pagina Plugin',
        'menus' => 'Menu',
        'edit_menu' => 'Modifica Menu',
        'dashboard_widgets' => 'Widget Bacheca',
        'theme_widgets' => 'Widget Tema',
        'users' => 'Utenti',
        'edit_user' => 'Modifica Utente',
        'profile' => 'Profilo',
        'trash_posts' => 'Cestino Articoli',
        'trash_pages' => 'Cestino Pagine',
        'backup' => 'Backup',
        'edit_theme_widget'=> 'Modifica il widget',
        'analytics_setup_guide' => 'Guida Configurazione Analytics',
        'site_analytics' => 'Statistiche Sito Interno'
    ];
    
    // Imposta il titolo della pagina
    $pagedash = $pageTitles[$this->action] ?? 'Bacheca';
        include ADMIN_PATH . '/views/header.php';
        
        switch ($this->action) {
            
            case 'analytics_stats':
                case 'analytics_stats':
    // Carica dati Google Analytics
    try {
        $propertyId = $this->db->getSetting('ga_property_id');
        $serviceAccountJson = base64_decode($this->db->getSetting('ga_service_account_json'));
        
        if (!$propertyId || !$serviceAccountJson) {
            $error = 'Google Analytics non configurato. Vai nelle Impostazioni per configurarlo.';
        } else {
            require_once BASE_PATH . '/core/GoogleAnalyticsAPI.php';
            $analytics = new GoogleAnalyticsAPI($serviceAccountJson, $propertyId);
            
            // Carica dati ultimi 30 giorni
            $visitors = $analytics->getVisitors(30);
            $pageViews = $analytics->getPageViews(30);
            $topPages = $analytics->getTopPages(30, 10);
            $visitorsByCountry = $analytics->getVisitorsByCountry(30, 10); // ← AGGIUNGI QUESTA RIGA
        }
    } catch (Exception $e) {
        $error = 'Errore nel caricamento dei dati: ' . $e->getMessage();
    }
    
    include ADMIN_PATH . '/views/analytics-stats.php';
    break;

            case 'site_analytics':
                include ADMIN_PATH . '/views/site-analytics.php';
                break;
                
            case 'edit_theme_widget':
                include ADMIN_PATH . '/views/edit_theme_widget.php';
                break;
            
            case 'analytics_setup_guide':
                include ADMIN_PATH . '/views/analytics-setup-guide.php';
                break;
            case 'analytics_stats':
                include ADMIN_PATH . '/views/analytics-stats.php';
                break;
            
            case 'impostazioni_generali':
    $settings = $this->db->getImpostazioniGenerali();
    include ADMIN_PATH . '/views/impostazioni_generali.php';
    break;
case 'impostazioni_lettura':
    $settingsLettura = $this->db->getImpostazioniLettura();
    include ADMIN_PATH . '/views/impostazioni_lettura.php';
    break;
case 'impostazioni_permalink':
    $settingsPermalink = $this->db->getImpostazioniPermalink();
    include ADMIN_PATH . '/views/impostazioni_permalink.php';
    break;

            case 'custom_post_types':
                if (!$this->user->hasRole(User::ROLE_ADMIN)) {
                    die('Accesso negato');
                }
                $customPostTypes = $this->db->getCustomPostTypes();
                include ADMIN_PATH . '/views/custom-post-types.php';
                break;
            
            case 'custom_post_types_edit':
                if (!$this->user->hasRole(User::ROLE_ADMIN)) {
                    die('Accesso negato');
                }
                $id = $_GET['id'] ?? null;
                $cpt = null;
                
                if ($id) {
                    $stmt = $this->db->pdo->prepare("SELECT * FROM " . DB_PREFIX . "custom_post_types WHERE id=?");
                    $stmt->execute([$id]);
                    $cpt = $stmt->fetch();
                    
                    if ($cpt) {
                        $cpt['supports'] = json_decode($cpt['supports'], true) ?? [];
                    }
                }
                
                include ADMIN_PATH . '/views/custom-post-types-edit.php';
                break;
            
            case 'custom_posts_list':
                $postType = $_GET['type'] ?? 'post';
                $cpt = $this->db->getCustomPostType($postType);
                
                if (!$cpt) {
                    die('Custom Post Type non trovato');
                }
                
                $posts = $this->db->getPostsByType($postType);
                include ADMIN_PATH . '/views/custom-posts-list.php';
                break;
            
            case 'custom_posts_edit':
                $postType = $_GET['type'] ?? 'post';
                $id = $_GET['id'] ?? null;
                
                $cpt = $this->db->getCustomPostType($postType);
                if (!$cpt) {
                    die('Custom Post Type non trovato');
                }
                
                $supports = json_decode($cpt['supports'], true) ?? [];
                
                $post = null;
                if ($id) {
                    $post = $this->db->getPostById($id);
                    
                    if ($post && in_array('categories', $supports)) {
                        $post['categories'] = array_column($this->db->getPostCategories($id), 'id');
                    }
                    
                    if ($post) {
                        $post['meta'] = $this->db->getAllPostMeta($id);
                    }
                }
                
                $categories = in_array('categories', $supports) ? $this->db->getAllCategories() : [];
                
                include ADMIN_PATH . '/views/custom-posts-edit.php';
                break;
            
            case 'trash_media':
                $trashedMedia = $this->db->getTrashedMedia();
                include ADMIN_PATH . '/views/trash_media.php';
                break;
            
            case 'media':
                $dbMedia = $this->db->getAllUploads();
                
                $dbMediaByFilename = [];
                foreach ($dbMedia as $item) {
                    $dbMediaByFilename[$item['filename']] = $item;
                }
                
                $trashedMedia = $this->db->getTrashedMedia();
                $trashedFilenames = array_column($trashedMedia, 'filename');
                
                $uploadDir = '../uploads/';
                $media = [];
                
                if (is_dir($uploadDir)) {
                    $files = array_diff(scandir($uploadDir), ['.', '..', '.htaccess']);
                    
                    foreach ($files as $file) {
                        $filepath = $uploadDir . $file;
                        
                        if (in_array($file, $trashedFilenames)) {
                            continue;
                        }
                        
                        if (!is_file($filepath)) {
                            continue;
                        }
                        
                        if (substr($file, 0, 1) === '.') {
                            continue;
                        }
                        
                        if (isset($dbMediaByFilename[$file])) {
                            if ($dbMediaByFilename[$file]['deleted_at'] === null) {
                                $media[] = $dbMediaByFilename[$file];
                            }
                        }
                    }
                    
                    usort($media, function($a, $b) {
                        $timeA = strtotime($a['created_at'] ?? '0');
                        $timeB = strtotime($b['created_at'] ?? '0');
                        return $timeB - $timeA;
                    });
                }
                
                include ADMIN_PATH . '/views/media.php';
                break;

            case 'customizer':
                include ADMIN_PATH . '/views/customizer.php';
                break;
            
            case 'pages':
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = 20;  // 20 pagine per pagina
    $offset = ($page - 1) * $perPage;
    
    $totalPages = $this->db->countPages();
    $totalPagesCount = ceil($totalPages / $perPage);
    $pages = $this->db->getPagesPaginated($offset, $perPage);
    
    include ADMIN_PATH . '/views/pages.php';
    break;


            case 'edit_page':
                $page = isset($_GET['id']) ? $this->db->getPageById($_GET['id']) : null;
                include ADMIN_PATH . '/views/edit_page.php';
                break;

            case 'posts':
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = 10;
    $offset = ($page - 1) * $perPage;
    
    $totalPosts = $this->db->countPosts();
    $totalPages = ceil($totalPosts / $perPage);
    $posts = $this->db->getPostsPaginated($offset, $perPage);
    
    include ADMIN_PATH . '/views/posts.php';
    break;


            case 'edit_post':
                $post = isset($_GET['id']) ? $this->db->getPostById($_GET['id']) : null;
                include ADMIN_PATH . '/views/edit_post.php';
                break;

            case 'categories':
                $categories = $this->db->getAllCategories();
                include ADMIN_PATH . '/views/categories.php';
                break;
            
            case 'contents':
                $contents = $this->db->getAllContents();
                include ADMIN_PATH . '/views/contents.php';
                break;
            
            case 'edit_content':
                $content = isset($_GET['id']) ? $this->db->getContentById($_GET['id']) : null;
                include ADMIN_PATH . '/views/edit_content.php';
                break;
            
            case 'themes':
                if (!$this->user->hasRole(User::ROLE_ADMIN)) {
                    die('Accesso negato');
                }
                $currentTheme = $this->db->getSetting('active_theme');
                $availableThemes = $this->db->getAvailableThemes();
                include ADMIN_PATH . '/views/themes.php';
                break;
            
            case 'plugins':
                if (!$this->user->hasRole(User::ROLE_ADMIN)) {
                    die('Accesso negato');
                }
                $activePlugins = $this->db->getActivePlugins();
                $availablePlugins = $this->db->getAvailablePluginsList();
                include ADMIN_PATH . '/views/plugins.php';
                break;

            case 'plugin-page':
                if (!$this->user->hasRole(User::ROLE_ADMIN)) {
                    die('Accesso negato');
                }
                $this->renderPluginPage();
                break;
            
            case 'menus':
                $menus = $this->db->getAllMenus();
                include ADMIN_PATH . '/views/menus.php';
                break;
            
            case 'edit_menu':
                $menu = isset($_GET['id']) ? $this->db->getMenuById($_GET['id']) : null;
                $menuItems = $menu ? $this->db->getMenuItems($menu['id']) : [];
                include ADMIN_PATH . '/views/edit_menu.php';
                break;
            
            case 'dashboard_widgets':
                $availableWidgets = [];
                foreach (glob(BASE_PATH . '/core/widgets/Widget_*.php') as $file) {
                    require_once $file;
                    $widgetName = basename($file, '.php');
                    $widgetType = str_replace('Widget_', '', $widgetName);
                    $availableWidgets[] = $widgetType;
                }
                
                $this->db->syncDashboardWidgets($availableWidgets);
                
                $widgets = $this->db->getAllDashboardWidgetsForManagement();
                
                include ADMIN_PATH . '/views/dashboard_widgets.php';
                break;
            
            case 'theme_widgets':
                // Gestione AJAX per caricamento menu
                if (isset($_GET['ajax']) && $_GET['ajax'] === 'get_menus') {
                    header('Content-Type: application/json');
                    try {
                        $prefix = DB_PREFIX;
                        $stmt = $this->db->pdo->query("SELECT id, name FROM {$prefix}menus ORDER BY name");
                        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        echo json_encode($menus);
                    } catch (Exception $e) {
                        echo json_encode(['error' => $e->getMessage()]);
                    }
                    exit;
                }
                
                $widgets = $this->db->getAllThemeWidgets();
                include ADMIN_PATH . '/views/theme_widgets.php';
                break;
            
            case 'users':
                if (!$this->user->hasRole(User::ROLE_ADMIN)) {
                    die('Accesso negato');
                }
                $users = $this->user->getAllUsers();
                include ADMIN_PATH . '/views/users.php';
                break;
            
            case 'edit_user':
                if (!$this->user->hasRole(User::ROLE_ADMIN)) {
                    die('Accesso negato');
                }
                $editUser = isset($_GET['id']) ? $this->user->getUserById($_GET['id']) : null;
                include ADMIN_PATH . '/views/edit_user.php';
                break;
            
            case 'profile':
                $currentUser = $this->user->getCurrentUser();
                include ADMIN_PATH . '/views/profile.php';
                break;
            
            case 'trash_posts':
                $trashedPosts = $this->db->getTrashedPosts();
                include ADMIN_PATH . '/views/trash_posts.php';
                break;

            case 'trash_pages':
                $trashedPages = $this->db->getTrashedPages();
                include ADMIN_PATH . '/views/trash_pages.php';
                break;
                
            case 'backup':
                if (!$this->user->hasRole(User::ROLE_ADMIN)) {
                    die('Accesso negato');
                }
                
                // Recupera impostazioni
                $stmt = $this->db->pdo->prepare("SELECT setting_value FROM " . DB_PREFIX . "settings WHERE setting_key = 'backup_max_limit'");
                $stmt->execute();
                $maxBackups = $stmt->fetchColumn() ?: 5;
                
                // Lista backup esistenti
                $backupDir = BASE_PATH . '/backups/';
                if (!is_dir($backupDir)) {
                    mkdir($backupDir, 0755, true);
                }
                
                $backups = [];
                $files = glob($backupDir . '*.zip');
                usort($files, function($a, $b) {
                    return filemtime($b) - filemtime($a);
                });
                
                foreach ($files as $file) {
                    $backups[] = [
                        'filename' => basename($file),
                        'size' => filesize($file),
                        'date' => filemtime($file),
                        'path' => $file
                    ];
                }
                
                include ADMIN_PATH . '/views/backup.php';
                break;

            default:
                $dashboardWidgets = $this->db->getDashboardWidgets();
                $currentUser = $this->user->getCurrentUser();
                include ADMIN_PATH . '/views/dashboard.php';
        }
        
        include ADMIN_PATH . '/views/footer.php';
    }

    private function renderPluginPage() {
        if (!isset($_GET['page'])) {
            echo '<h1>Errore</h1>';
            return;
        }

        $pluginPages = $this->cms->getPluginPages();
        $pageSlug = $_GET['page'];

        foreach ($pluginPages as $page) {
            if ($page['slug'] === $pageSlug) {
                call_user_func($page['callback']);
                return;
            }
        }

        echo '<h1>Pagina plugin non trovata</h1>';
    }
}
