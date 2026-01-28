<?php
class Router {
    public function getRoute() {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/');
    
    // === PRIORITÀ 1: Controlla se un plugin gestisce questa route ===
    $pluginData = match_plugin_route($uri);
    if ($pluginData !== null) {
        return [
            'type' => 'plugin',
            'slug' => '',
            'plugin_data' => $pluginData
        ];
    }
    
    // === PRIORITÀ 2: Route sistema ===
    if ($uri === '' || $uri === '/' || $uri == '/index.php') {
        return ['type' => 'home', 'slug' => ''];
    }
    
    if ($uri === '/blog') {
        return ['type' => 'blog', 'slug' => ''];
    }
    
    // Blog post: /post/slug-del-post
    if (preg_match('#^/post/([a-z0-9-]+)$#', $uri, $matches)) {
        return ['type' => 'post', 'slug' => $matches[1]];
    }
    
    // Pagina statica: /page/slug-pagina
    if (preg_match('#^/page/([a-z0-9-]+)$#', $uri, $matches)) {
        return ['type' => 'page', 'slug' => $matches[1]];
    }
    
    // === PRIORITÀ 3: Custom Post Types ===
    $db = new Database();
    $customPostTypes = $db->getCustomPostTypes(true);
    
    foreach ($customPostTypes as $cpt) {
        $slug = $cpt['rewrite_slug'] ?: $cpt['slug'];
        
        // Archivio CPT: /portfolio
        if ($uri === '/' . $slug) {
            return [
                'type' => 'custom_post_type',
                'cpt_name' => $cpt['name'],
                'is_archive' => true,
                'slug' => ''
            ];
        }
        
        // Singolo CPT: /portfolio/nome-progetto
        if (preg_match('#^/' . preg_quote($slug, '#') . '/([a-z0-9-]+)$#', $uri, $matches)) {
            return [
                'type' => 'custom_post_type',
                'cpt_name' => $cpt['name'],
                'is_archive' => false,
                'slug' => $matches[1]
            ];
        }
    }
    
    // Default: prova come pagina statica
    return ['type' => 'page', 'slug' => ltrim($uri, '/')];
}


}
?>