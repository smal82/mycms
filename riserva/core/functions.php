<?php
/**
 * FILE: /core/functions.php
 * Funzioni helper del CMS
 */
 
 /**
 * Verifica se l'utente corrente è amministratore
 */
function is_admin_user() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'amministratore';
}


/**
 * Renderizza i widget di un'area specifica
 */
function render_widget_area($area_name) {
    $db = new Database();
    $widgets = $db->getThemeWidgets($area_name);
    do_hook('before_render_widget_area', $area_name, $widgets);
    
    if (empty($widgets)) {
        return;
    }
    
    echo '<div class="widget-area widget-area-' . esc_attr($area_name) . '">';
    
    foreach ($widgets as $widget) {
        $config = json_decode($widget['config'], true) ?: [];
        $widgetType = $widget['widget_type'];
        
        echo '<div class="widget widget-' . esc_attr($widgetType) . '">';
        
        // === WIDGET STANDARD ===
        if (in_array($widgetType, ['menu', 'text', 'recent_posts', 'auth'])) {
            switch ($widgetType) {
                case 'menu':
                    render_menu_widget($config, $db);
                    break;
                case 'text':
                    render_text_widget($config);
                    break;
                case 'recent_posts':
                    render_recent_posts_widget($config, $db);
                    break;
                case 'auth':
                    render_auth_widget($config, $db);
                    break;
            }
        } 
        // === WIDGET PLUGIN ===
        else if (is_plugin_widget($widgetType)) {
            do_hook('mycms_plugin_widget_display', $widgetType, $config, $widget);
        }
        // Widget sconosciuto
        else {
            echo '<p>Widget non configurato.</p>';
        }
        
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Controlla se esistono widget attivi per un'area specifica
 * @param string $area_name (es: 'sidebar', 'footer')
 * @return bool
 */
function has_widgets($area_name) {
    $db = new Database();
    $widgets = $db->getThemeWidgets($area_name);
    return !empty($widgets);
}

/**
 * Renderizza widget menu
 */
function render_menu_widget($config, $db) {
    $menuId = $config['menu_id'] ?? null;
    $title = $config['title'] ?? '';
    
    if (!$menuId) {
        return;
    }
    
    if ($title) {
        echo '<h3 class="widget-title">' . esc_html($title) . '</h3>';
    }
    
    $menu = $db->getMenuById($menuId);
    if ($menu) {
        $items = $db->getMenuItems($menuId);
        
        echo '<nav class="widget-menu">';
        echo '<ul>';
        foreach ($items as $item) {
            if ($item['parent_id'] === null) {
                echo '<li><a href="' . esc_url($item['url']) . '"';
                if ($item['target'] === '_blank') {
                    echo ' target="_blank"';
                }
                echo '>' . esc_html($item['title']) . '</a></li>';
            }
        }
        echo '</ul>';
        echo '</nav>';
    }
}

function render_recent_posts_widget($config, $db) {
    $title = $config['title'] ?? 'Post Recenti';
    $limit = $config['limit'] ?? 5;
    $showDate = $config['show_date'] ?? true;
    $showExcerpt = $config['show_excerpt'] ?? false;
    
    if ($title) {
        echo '<h3 class="widget-title">' . esc_html($title) . '</h3>';
    }
    
    $posts = $db->getPublishedPosts($limit);
    
    if (!empty($posts)) {
        echo '<ul class="widget-recent-posts">';
        foreach ($posts as $post) {
            echo '<li>';
            echo '<a href="/post/' . esc_url($post['slug']) . '">' . esc_html($post['title']) . '</a>';
            
            if ($showDate) {
                echo '<span class="post-date"> - ' . date('d/m/Y', strtotime($post['created_at'])) . '</span>';
            }
            
            if ($showExcerpt && !empty($post['excerpt'])) {
                echo '<p class="post-excerpt">' . esc_html($post['excerpt']) . '</p>';
            }
            
            echo '</li>';
        }
        echo '</ul>';
    }
}


/**
 * Renderizza widget testo
 */
function render_text_widget($config) {
    $title = $config['title'] ?? '';
    $content = $config['content'] ?? '';
    
    if ($title) {
        echo '<h3 class="widget-title">' . esc_html($title) . '</h3>';
    }
    
    echo '<div class="widget-text">' . $content . '</div>';
}

/**
 * Renderizza widget autenticazione (LOGIN/REGISTRAZIONE)
 */
function render_auth_widget($config, $db) {
    // Carica la classe del widget
    $widgetFile = BASE_PATH . '/core/Themes/widgets/Widget_auth.php';
    
    if (!file_exists($widgetFile)) {
        echo '<p style="color:red;">Errore: File Widget_auth.php non trovato in ' . $widgetFile . '</p>';
        return;
    }
    
    require_once $widgetFile;
    
    if (!class_exists('Widget_auth')) {
        echo '<p style="color:red;">Errore: Classe Widget_auth non trovata</p>';
        return;
    }
    
    $widget = new Widget_auth($db);
    $widget->render($config);
}


/**
 * Escape HTML attributes
 */
function esc_attr($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Escape HTML
 */
function esc_html($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Escape URL
 */
function esc_url($url) {
    return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
}

/**
 * Ottiene l'URI del tema attivo
 */
function get_theme_uri() {
    $db = new Database();
    $activeTheme = $db->getSetting('active_theme', 'aurora');
    return '/themes/' . $activeTheme;
}

?>
