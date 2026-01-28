<?php
/**
 * Plugin: Ricerca Avanzata
 * Widget ricerca + pagina risultati /ricerca
 */

class RicercaAvanzataPlugin {
    private $db;
    
    public function __construct($cms) {
        $this->db = $cms->getDB();
        
        // Registra widget nella tab "Altri"
        add_hook('mycms_plugin_widgets_init', [$this, 'register_widget']);
        add_hook('mycms_plugin_widget_form', [$this, 'widget_form'], 10, 2);
        add_hook('mycms_plugin_widget_save', [$this, 'widget_save'], 10, 3);
        add_hook('mycms_plugin_widget_display', [$this, 'widget_display'], 10, 3);
        
        // Registra route pagina ricerca
        add_hook('mycms_plugin_routes_init', [$this, 'register_routes']);
    }
    
    // === WIDGET ===
    
    public function register_widget() {
        register_plugin_widget('ricerca_avanzata', [
            'name' => 'Ricerca Avanzata',
            'icon' => '🔍',
            'description' => 'Form di ricerca che porta alla pagina /ricerca'
        ]);
    }
    
    public function widget_form($widgetType, $widgetConfig) {
        if ($widgetType !== 'ricerca_avanzata') return;
        
        $city = json_decode($widgetConfig['config'] ?? '{}', true)['placeholder'] ?? '';
        ?>
        <div class="form-group">
            <label>Placeholder del campo:</label>
            <input type="text" name="placeholder" value="<?php echo esc_attr($city); ?>" placeholder="es: Cerca post e pagine...">
            <small>Testo che appare nel campo vuoto</small>
        </div>
        <?php
    }
    
    public function widget_save($config, $widgetType, $postData) {
        if ($widgetType !== 'ricerca_avanzata') return $config;
        
        return [
            'placeholder' => $postData['placeholder'] ?? 'Cerca...'
        ];
    }
    
    public function widget_display($widgetType, $config, $widget) {
        if ($widgetType !== 'ricerca_avanzata') return;
        
        $placeholder = $config['placeholder'] ?? 'Cerca...';
        ?>
        <div class="widget-ricerca">
            <h3 class="widget-title">🔍 Ricerca</h3>
            <form method="GET" action="/ricerca" class="search-widget-form">
                <div class="search-input-wrapper">
                    <input type="text" name="q" placeholder="<?php echo esc_attr($placeholder); ?>">
                    <button type="submit">Cerca</button>
                </div>
            </form>
        </div>
        <style>
            .widget-ricerca .search-input-wrapper {
                display: flex;
                gap: 5px;
            }
            .widget-ricerca input {
                flex: 1;
                padding: 8px 12px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .widget-ricerca button {
                padding: 8px 16px;
                background: #0066cc;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }
        </style>
        <?php
    }
    
    // === ROUTE PAGINA RISULTATI ===
    
    public function register_routes() {
        register_plugin_route('/ricerca', [$this, 'render_search_results']);
    }
    
    public function render_search_results($matches) {
        $query = $_GET['q'] ?? '';
        $results = [];
        
        if ($query) {
            $results = $this->perform_search($query);
        }
        
        $content = $this->generate_search_html($query, $results);
        
        return [
            'title' => $query ? 'Risultati per "' . $query . '"' : 'Ricerca',
            'content' => $content
        ];
    }
    
    private function perform_search($query) {
        $prefix = DB_PREFIX;
        $results = [];
        
        // Cerca nei post
        $stmt = $this->db->pdo->prepare("
            SELECT title, slug FROM {$prefix}posts 
            WHERE status = 'pubblicato' 
            AND (title LIKE ? OR content LIKE ?)
            ORDER BY created_at DESC LIMIT 10
        ");
        $term = '%' . $query . '%';
        $stmt->execute([$term, $term]);
        
        foreach ($stmt->fetchAll() as $post) {
            $results[] = [
                'title' => $post['title'],
                'url' => '/post/' . $post['slug'],
                'type' => 'Post'
            ];
        }
        
        // Cerca nelle pagine
        $stmt = $this->db->pdo->prepare("
            SELECT title, slug FROM {$prefix}pages 
            WHERE title LIKE ? OR content LIKE ?
            LIMIT 10
        ");
        $stmt->execute([$term, $term]);
        
        foreach ($stmt->fetchAll() as $page) {
            $results[] = [
                'title' => $page['title'],
                'url' => '/page/' . $page['slug'],
                'type' => 'Pagina'
            ];
        }
        
        return $results;
    }
    
    private function generate_search_html($query, $results) {
        $html = '<div class="search-results-page">';
        
        // Form ricerca (sempre visibile)
        $html .= '<form method="GET" class="search-main-form">';
        $html .= '<input type="text" name="q" value="' . esc_attr($query) . '" placeholder="Cerca...">';
        $html .= '<button type="submit">🔍 Cerca</button>';
        $html .= '</form>';
        
        // Risultati
        if ($query) {
            $count = count($results);
            $html .= '<h2>Risultati per: <strong>"' . esc_html($query) . '"</strong></h2>';
            $html .= '<p class="results-count">Trovati <strong>' . $count . '</strong> risultati</p>';
            
            if ($results) {
                $html .= '<div class="results-list">';
                foreach ($results as $result) {
                    $html .= '<div class="result-item">';
                    $html .= '<h3><a href="' . esc_url($result['url']) . '">' . esc_html($result['title']) . '</a></h3>';
                    $html .= '<span class="result-type">' . esc_html($result['type']) . '</span>';
                    $html .= '</div>';
                }
                $html .= '</div>';
            } else {
                $html .= '<div class="no-results">Nessun risultato trovato per "<strong>' . esc_html($query) . '</strong>".</div>';
            }
        }
        
        $html .= '</div>';
        
        // Stili inline
        $html .= '<style>
            .search-results-page { padding: 20px 0; }
            .search-main-form { margin-bottom: 30px; }
            .search-main-form input { 
                width: 400px; padding: 12px; border: 2px solid #ddd; border-radius: 8px 0 0 8px; 
            }
            .search-main-form button { 
                padding: 12px 24px; background: #0066cc; color: white; border: none; 
                border-radius: 0 8px 8px 0; cursor: pointer; 
            }
            .results-count { margin: 20px 0; color: #666; }
            .result-item { 
                padding: 20px; border-bottom: 1px solid #eee; 
            }
            .result-item h3 { margin: 0 0 10px 0; }
            .result-item a { color: #0066cc; text-decoration: none; }
            .result-type { 
                background: #e9ecef; color: #495057; padding: 4px 12px; 
                border-radius: 20px; font-size: 12px; font-weight: bold; 
            }
            .no-results { 
                padding: 40px; text-align: center; color: #999; font-size: 18px; 
            }
            @media (max-width: 768px) {
                .search-main-form input { width: 250px; }
            }
        </style>';
        
        return $html;
    }
}
