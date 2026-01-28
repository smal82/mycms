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
    
    // Determina quale template usare
    $template = null;
    
    // === GESTIONE PAGINE PLUGIN ===
if ($route['type'] === 'plugin') {
    // Il plugin ha restituito solo il contenuto centrale
    // Il sistema lo avvolge automaticamente nel layout del tema
    
    $this->db = $content['db'] ?? $this->db;
    
    // Include header del tema
    $headerPath = $this->themePath . '/header.php';
    if (file_exists($headerPath)) {
        include $headerPath;
    }
    
    // Contenuto principale (dove va il plugin)
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <main class="main-content">
                    <h1><?php echo esc_html($content['title'] ?? 'Plugin Page'); ?></h1>
                    <div class="plugin-content">
                        <?php echo $content['content'] ?? ''; ?>
                    </div>
                </main>
            </div>
            
            <!-- Sidebar se ci sono widget -->
            <?php if (has_widgets('sidebar')): ?>
                <div class="col-md-4">
                    <aside class="sidebar">
                        <?php render_widget_area('sidebar'); ?>
                    </aside>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    
    // Include footer del tema
    $footerPath = $this->themePath . '/footer.php';
    if (file_exists($footerPath)) {
        include $footerPath;
    }
    
    return;
}
    // Se è la home
    if ($route['type'] === 'home' || $route['slug'] === 'home') {
        $template = 'index.php';
    }
    // Se è il blog
    elseif ($route['type'] === 'blog' || $route['slug'] === 'blog') {
        // In route per blog (blog.php)
        // Prepara le variabili per il template blog
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $postsPerPage = (int)$this->db->getSetting('posts_per_page', 10);
    
    // Calcola l'OFFSET in base alla pagina corrente
    $offset = ($page - 1) * $postsPerPage;
    
    // Query con LIMIT e OFFSET per la paginazione
    $stmt = $this->db->pdo->prepare("
        SELECT p.*, u.name as author_name 
        FROM " . DB_PREFIX . "posts p
        LEFT JOIN " . DB_PREFIX . "users u ON p.author_id = u.id
        WHERE p.status = 'pubblicato' AND p.deleted_at IS NULL
        ORDER BY p.id DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', $postsPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Conta totale post per la paginazione
    $countStmt = $this->db->pdo->query("SELECT COUNT(*) FROM " . DB_PREFIX . "posts WHERE status='pubblicato' AND deleted_at IS NULL");
    $totalPosts = $countStmt->fetchColumn();
    $totalPages = ceil($totalPosts / $postsPerPage);
$template = 'blog.php';
    }
    // Se è un post
    elseif ($route['type'] === 'post') {
        // Se author_name non è presente, recuperalo dal database
if (empty($content['author_name']) && !empty($content['author_id'])) {
    $userStmt = $this->db->pdo->prepare("SELECT name FROM " . DB_PREFIX . "users WHERE id = ?");
    $userStmt->execute([$content['author_id']]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);
    $content['author_name'] = $user['name'] ?? '';
}
        $template = 'post.php';
    }
    elseif ($content['type'] === 'archive' && isset($content['cpt'])) {
        // Template archivio CPT
        $cptName = $content['cpt']['name'];
        $archiveTemplate = $this->themePath . '/archive-' . $cptName . '.php';
        
        if (file_exists($archiveTemplate)) {
            extract($content);
            include $archiveTemplate;
        } else {
            // Fallback su archive.php generico
            $this->renderGenericArchive($content);
        }
        return;
    }
    
    elseif ($content['type'] === 'single' && isset($content['cpt'])) {
        // Template singolo CPT
        $cptName = $content['cpt']['name'];
        $singleTemplate = $this->themePath . '/single-' . $cptName . '.php';
        
        if (file_exists($singleTemplate)) {
            extract($content);
            include $singleTemplate;
        } else {
            // Fallback su single.php generico
            $this->renderGenericSingle($content);
        }
        return;
    }
    // Se è una pagina
    elseif ($route['type'] === 'page') {
        $template = 'page.php';
    }
    
    $this->include('header.php', compact('title', 'content'));
    
    // Prova a caricare il template specifico
if ($template) {
    // Prepara le variabili da passare al template
    $templateVars = compact('content', 'title', 'body', 'route');
    
    // Se è il blog, aggiungi le variabili specifiche
    if ($route['type'] === 'blog' || $route['slug'] === 'blog') {
        $templateVars = array_merge($templateVars, compact('posts', 'page', 'totalPages', 'postsPerPage', 'totalPosts'));
    }
    
    if ($this->include($template, $templateVars)) {
        // Template caricato con successo
    } else {
        // Fallback a index.php
        $this->include('index', compact('content', 'title', 'body', 'route'));
    }
} else {
    // Fallback a index.php
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