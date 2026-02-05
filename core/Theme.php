<?php
class Theme {
    private $name;
    private $path;
    private $db;
    
    public function __construct($name, $db) {
        $this->name = $name;
        $this->path = THEME_PATH . '/' . $name;
        $this->db = $db;
    }
    
    public function render($route, $content) {
    $title = $content['title'] ?? 'Untitled';
    $body = $content['content'] ?? '';
    $featured_image = $content['featured_image'] ?? '';
    
    $siteTitle = $this->db->getSetting('site_title');
$siteDescription = $this->db->getSetting('site_description');
$sitemotto = $this->db->getSetting('site_motto');
$siteLogo = $this->db->getSetting('site_logo');
$siteFavicon = $this->db->getSetting('site_favicon');
$analytics = $this->db->getSetting('google_analytics', '');

$titleFinal = trim($title ?? '');
    
    if ($titleFinal === '' || strtolower($titleFinal) === 'home') {
        $titlepage = htmlspecialchars($siteTitle);
} else {
    $titlepage = htmlspecialchars($titleFinal) . ' - ' . htmlspecialchars($siteTitle);
    
}
    
    // Determina quale template usare
    $template = null;
    
    // PRIMA DI TUTTO: Gestisci plugin_page
    if ($content['type'] === 'plugin_page') {
        // Renderizza template plugin
        if (isset($content['template']) && file_exists($content['template'])) {
            // Estrai i dati per il template
            if (isset($content['data'])) {
                extract($content['data']);
            }
            
            $this->include('header.php', compact('title', 'content'));
            include $content['template'];
            $this->include('footer.php');
        } else {
            // Template non trovato
            $this->include('header.php', compact('title', 'content'));
            echo '<h1>Errore</h1><p>Template del plugin non trovato.</p>';
            $this->include('footer.php');
        }
        return; // IMPORTANTE: Esci qui!
    }
    
    // Se è la home
    if ($route['type'] === 'home' || $route['slug'] === 'home') {
        $template = 'index.php';
    }
    // Se è il blog
    elseif ($route['type'] === 'blog' || $route['slug'] === 'blog') {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $postsPerPage = (int)$this->db->getSetting('posts_per_page', 10);
        $offset = ($page - 1) * $postsPerPage;
        
        $stmt = $this->db->pdo->prepare("
            SELECT p.*, u.name as author_name 
            FROM " . DB_PREFIX . "posts p
            LEFT JOIN " . DB_PREFIX . "users u ON p.author_id = u.id
            WHERE p.status = 'pubblicato' AND p.deleted_at IS NULL AND p.post_type = 'post'
            ORDER BY p.id DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $postsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $countStmt = $this->db->pdo->query("SELECT COUNT(*) FROM " . DB_PREFIX . "posts WHERE status='pubblicato' AND deleted_at IS NULL");
        $totalPosts = $countStmt->fetchColumn();
        $totalPages = ceil($totalPosts / $postsPerPage);
        $template = 'blog.php';
    }
    // Se è un post
    elseif ($route['type'] === 'post') {
        if (empty($content['author_name']) && !empty($content['author_id'])) {
            $userStmt = $this->db->pdo->prepare("SELECT name FROM " . DB_PREFIX . "users WHERE id = ?");
            $userStmt->execute([$content['author_id']]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            $content['author_name'] = $user['name'] ?? '';
        }
        $template = 'post.php';
    }
    elseif ($content['type'] === 'archive' && isset($content['cpt'])) {
        $cptName = $content['cpt']['name'];
        $archiveTemplate = $this->path . '/archive-' . $cptName . '.php';
        
        if (file_exists($archiveTemplate)) {
            $this->include('header.php', compact('title', 'titlepage', 'content', 'cptName', 'siteTitle', 'siteDescription', 'sitemotto', 'siteLogo', 'siteFavicon', 'analytics'));
            extract($content);
            include $archiveTemplate;
            $this->include('footer.php');
        } else {
            $this->renderGenericArchive($content);
        }
        return;
    }
    elseif ($content['type'] === 'single' && isset($content['cpt'])) {
        $cptName = $content['cpt']['name'];
        $singleTemplate = $this->path . '/single-' . $cptName . '.php';
        
        if (file_exists($singleTemplate)) {
            $this->include('header.php', compact('title', 'titlepage', 'content', 'cptName', 'siteTitle', 'siteDescription', 'sitemotto', 'siteLogo', 'siteFavicon', 'analytics'));
            extract($content);
            include $singleTemplate;
            $this->include('footer.php');
        } else {
            $this->renderGenericSingle($content);
        }
        return;
    }
    // Se è una pagina
    elseif ($route['type'] === 'page') {
        $template = 'page.php';
    }
    
    $this->include('header.php', compact('title', 'titlepage', 'content', 'siteTitle', 'siteDescription', 'sitemotto', 'siteLogo', 'siteFavicon', 'analytics'));
    
    if ($template) {
        $templateVars = compact('content', 'title', 'body', 'route');
        
        if ($route['type'] === 'blog' || $route['slug'] === 'blog') {
            $templateVars = array_merge($templateVars, compact('posts', 'page', 'totalPages', 'postsPerPage', 'totalPosts'));
        }
        
        if ($this->include($template, $templateVars)) {
            // Template caricato con successo
        } else {
            $this->include('index', compact('content', 'title', 'body', 'route'));
        }
    } else {
        $this->include('index', compact('content', 'title', 'body', 'route'));
    }
    
    $this->include('footer.php');
}

    
    private function include($file, $vars = []) {
        $filepath = $this->path . '/' . $file;
        if (file_exists($filepath)) {
            extract($vars);
            include $filepath;
            return true;
        }
        return false;
    }
    
    public function asset($file) {
        return '/themes/' . $this->name . '/' . $file;
    }
    
    public function widgetArea($areaName) {
        $widgets = $this->db->getThemeWidgets($areaName);
        echo '<div class="widget-area widget-area-' . htmlspecialchars($areaName) . '">';
        foreach ($widgets as $widget) {
            $this->renderWidget($widget);
        }
        echo '</div>';
    }
    
    private function renderWidget($widget) {
        $widgetClass = 'Widget_' . $widget['widget_type'];
        if (class_exists($widgetClass)) {
            $widgetInstance = new $widgetClass($this->db);
            $widgetInstance->render($widget['config'] ? json_decode($widget['config'], true) : []);
        }
    }
    
    /**
     * Renderizza un menu semplice (backward compatibility)
     */
    public function menu($location) {
        $menu = $this->db->getMenuByLocation($location);
        if ($menu) {
            $items = $this->db->getMenuItems($menu['id']);
            echo '<nav class="menu menu-' . htmlspecialchars($location) . '">';
            foreach ($items as $item) {
                // Mostra solo gli item di primo livello
                if (empty($item['parent_id'])) {
                    echo '<a href="' . htmlspecialchars($item['url']) . '" target="' . htmlspecialchars($item['target']) . '">';
                    echo htmlspecialchars($item['title']);
                    echo '</a>';
                }
            }
            echo '</nav>';
        }
    }
    
    /**
 * Renderizza un menu multilivello CLASSICO
 */
public function renderMultiLevelMenu($location) {
    $menu = $this->db->getMenuByLocation($location);
    if (!$menu) {
        return;
    }
    
    $items = $this->db->getMenuItems($menu['id']);
    if (empty($items)) {
        return;
    }
    
    echo '<ul class="menu menu-' . htmlspecialchars($location) . '">';
    echo $this->renderMenuLevel($items, null, 0);
    echo '</ul>';
}

/**
 * Renderizza ricorsivamente i livelli del menu
 */
private function renderMenuLevel($items, $parentId = null, $level = 0) {
    $output = '';
    
    foreach ($items as $item) {
        if ($item['parent_id'] == $parentId) {
            $hasChildren = $this->hasChildren($items, $item['id']);
            $childClass = $hasChildren ? ' menu-item-has-children' : '';
            
            $output .= '<li class="menu-item menu-level-' . $level . $childClass . '">';
            $output .= '<a href="' . htmlspecialchars($item['url']) . '" ';
            $output .= 'target="' . htmlspecialchars($item['target']) . '">';
            $output .= htmlspecialchars($item['title']);
            $output .= '</a>';
            
            // Renderizza ricorsivamente i figli
            if ($hasChildren) {
                $output .= '<ul class="submenu submenu-level-' . ($level + 1) . '">';
                $output .= $this->renderMenuLevel($items, $item['id'], $level + 1);
                $output .= '</ul>';
            }
            
            $output .= '</li>';
        }
    }
    
    return $output;
}

/**
 * Controlla se un menu item ha figli
 */
private function hasChildren($items, $parentId) {
    foreach ($items as $item) {
        if ($item['parent_id'] == $parentId) {
            return true;
        }
    }
    return false;
}


    
    private function renderGenericArchive($content) {
    extract($content);
    include $this->themePath . '/header.php';
    echo '<div class="container"><h1>' . htmlspecialchars($cpt['plural_label']) . '</h1>';
    foreach ($posts as $post) {
        echo '<article><h2><a href="/' . $cpt['slug'] . '/' . $post['slug'] . '">' . 
             htmlspecialchars($post['title']) . '</a></h2></article>';
    }
    echo '</div>';
    include $this->themePath . '/footer.php';
}

private function renderGenericSingle($content) {
    extract($content);
    include $this->themePath . '/header.php';
    echo '<div class="container"><h1>' . htmlspecialchars($content['title']) . '</h1>';
    echo '<div>' . nl2br(htmlspecialchars($content['content'])) . '</div></div>';
    include $this->themePath . '/footer.php';
}
    
}
?>