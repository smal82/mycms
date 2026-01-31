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
    }
    
    public function run() {
        // IMPORTANTE: Esegui hook PRIMA di getRoute()
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
        // PRIMA DI TUTTO: Controlla se è una pagina plugin
        if (isset($_SERVER['PLUGIN_PAGE']) && isset($_SERVER['PLUGIN_NAME'])) {
            $route['type'] = 'plugin_page';
            $content = $this->executeHook('get_plugin_content', $route);
            
            if (isset($content['type']) && $content['type'] === 'plugin_page') {
                return $content;
            }
        }
        
        if ($route['type'] === 'home') {
            return [
                'title' => 'Home', 
                'content' => '', 
                'type' => 'home'
            ];
        } elseif ($route['type'] === 'blog') {
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
            return [
                'title' => '404 - Post non trovato',
                'content' => '<p>Il post che cerchi non esiste.</p>',
                'type' => 'post'
            ];
        } elseif ($route['type'] === 'custom_post_type') {
            $cpt = $this->db->getCustomPostType($route['cpt_name']);
            if ($cpt) {
                if ($route['is_archive']) {
                    $posts = $this->db->getPostsByType($route['cpt_name'], 'pubblicato');
                    return [
                        'title' => $cpt['plural_label'],
                        'content' => '',
                        'type' => 'archive',
                        'cpt' => $cpt,
                        'posts' => $posts
                    ];
                } else {
                    $post = $this->db->getPostBySlug($route['slug']);
                    if ($post && $post['post_type'] === $route['cpt_name']) {
                        $post['type'] = 'single';
                        $post['cpt'] = $cpt;
                        $post['meta'] = $this->db->getAllPostMeta($post['id']);
                        return $post;
                    }
                }
            }
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
