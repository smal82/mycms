<?php
class CMS {
    private $db;
    private $router;
    private $theme;
    private $plugins = [];
    
    public function __construct() {
    $this->db = new Database();
    $this->router = new Router();
    $this->loadTheme();
    $this->loadPlugins();
    
    // Permetti ai plugin di registrare le loro route
    do_hook('mycms_plugin_routes_init');
}

    
    public function run() {
        $this->executeHook('before_route');
        
        $route = $this->router->getRoute();
        $content = $this->getContent($route);
        
        $content = $this->executeHook('before_render', $content);
        
        $this->theme->render($route, $content);
        
        $this->executeHook('after_render');
    }
    
    private function loadTheme() {
        $activeTheme = $this->db->getSetting('active_theme', 'aurora');
        $this->theme = new Theme($activeTheme, $this->db);
    }
    
    private function loadPlugins() {
    $activePlugins = $this->db->getActivePlugins();
    foreach ($activePlugins as $plugin) {
        $pluginFile = PLUGIN_PATH . '/' . $plugin . '/plugin.php';
        if (file_exists($pluginFile)) {
            require_once $pluginFile;
            
            // Converti kebab-case a PascalCase: seo-plugin -> SeoPlugin
            $parts = explode('-', $plugin);
            $className = '';
            foreach ($parts as $part) {
                $className .= ucfirst($part);
            }
            $className .= 'Plugin';
            
            if (class_exists($className)) {
                $this->plugins[$plugin] = new $className($this);
            } else {
                error_log("Plugin class not found: $className in $pluginFile");
            }
        } else {
            error_log("Plugin file not found: $pluginFile");
        }
    }
}

    private function executeHook($hookName, $data = null) {
        foreach ($this->plugins as $plugin) {
            $method = 'hook_' . $hookName;
            if (method_exists($plugin, $method)) {
                $data = $plugin->$method($data);
            }
        }
        return $data;
    }
    
    private function getContent($route) {
        // Gestione route plugin
    if ($route['type'] === 'plugin') {
        $pluginData = $route['plugin_data'];
        
        // Assicurati che abbia almeno title e content
        if (!isset($pluginData['title'])) {
            $pluginData['title'] = 'Plugin Page';
        }
        if (!isset($pluginData['content'])) {
            $pluginData['content'] = '';
        }
        
        // Aggiungi type e db per il tema
        $pluginData['type'] = 'plugin';
        $pluginData['db'] = $this->db;
        
        return $pluginData;
    }
        if ($route['type'] === 'home') {
            // Home mostra template home.php del tema
            return [
                'title' => 'Home', 
                'content' => '', 
                'type' => 'home'
            ];
        } elseif ($route['type'] === 'blog') {
            // Lista tutti i post usando template blog.php
            return [
                'title' => 'Blog', 
                'content' => '', 
                'type' => 'blog'
            ];
        } elseif ($route['type'] === 'page') {
            $page = $this->db->getPageBySlug($route['slug']);
            if ($page) {
                $page['type'] = 'page';
                return $page;
            } else {
            // Pagina non trovata
            return [
                'title' => '404 - Pagina non trovata',
                'content' => '<p>La pagina che cerchi non esiste.</p>',
                'type' => 'page'
            ];
            }
        } elseif ($route['type'] === 'post') {
            $post = $this->db->getPostBySlug($route['slug']); 
            if ($post) {
        $post['type'] = 'post';
        return $post;
    }
            // Post non trovato
            return [
                'title' => '404 - Post non trovato',
                'content' => '<p>Il post che cerchi non esiste.</p>',
                'type' => 'post'
            ];
        
        } elseif ($route['type'] === 'custom_post_type') {
            // Gestione Custom Post Type
            $cpt = $this->db->getCustomPostType($route['cpt_name']);
            if ($cpt) {
                if ($route['is_archive']) {
                    // Archivio CPT
                    $posts = $this->db->getPostsByType($route['cpt_name'], 'pubblicato');
                    return [
                        'title' => $cpt['plural_label'],
                        'content' => '',
                        'type' => 'archive',
                        'cpt' => $cpt,
                        'posts' => $posts
                    ];
                } else {
                    // Singolo CPT
                    $post = $this->db->getPostBySlug($route['slug']);
                    if ($post && $post['post_type'] === $route['cpt_name']) {
                        $post['type'] = 'single';
                        $post['cpt'] = $cpt;
                        $post['meta'] = $this->db->getAllPostMeta($post['id']);
                        return $post;
                    }
                }
            }
            // CPT non trovato
            return [
                'title' => '404 - Contenuto non trovato',
                'content' => '<p>Il contenuto che cerchi non esiste.</p>',
                'type' => 'page'
            ];
        } else {
            // Default: cerca come pagina
            $page = $this->db->getPageBySlug($route['slug']);
            if ($page) {
                $page['type'] = 'page';
                return $page;
            }
            return [
                'title' => '404',
                'content' => '<p>Contenuto non trovato.</p>',
                'type' => 'page'
            ];
        }
    }
    
    public function getDB() {
        return $this->db;
    }
    
    public function getPluginPages() {
    $pages = [];
    $pages = apply_hook('admin_plugin_pages', $pages);
    return $pages;
}

}
?>