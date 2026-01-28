<?php
/**
 * Plugin: SEO Plugin
 * Description: Gestione avanzata impostazioni SEO
 * Version: 1.0
 */

class SeoPlugin {
    private $cms;
    
    public function __construct($cms) {
        $this->cms = $cms;
        
        // Registra solo gli hook necessari
        add_hook('mycms_head', [$this, 'addExtraSeoTags'], 5);
        add_hook('mycms_footer', [$this, 'addAnalytics'], 10);
        add_hook('admin_plugin_pages', [$this, 'registerAdminPage'], 10);
    }
    
    public function registerAdminPage($pages) {
        $pages[] = [
            'slug' => 'seo-settings',
            'title' => 'SEO Settings',
            'icon' => '🔍',
            'callback' => [$this, 'renderAdminPage']
        ];
        return $pages;
    }
    
    public function renderAdminPage() {
        $db = $this->cms->getDB();
        
        // Salva i dati se submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Modifica direttamente site_description (usato dall'header del tema)
            $db->saveSetting('site_description', $_POST['site_description'] ?? '');
            $db->saveSetting('seo_keywords', $_POST['seo_keywords'] ?? '');
            $db->saveSetting('seo_author', $_POST['seo_author'] ?? '');
            echo '<div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">✅ Impostazioni SEO salvate!</div>';
        }
        
        // Recupera valori attuali
        $siteDescription = $db->getSetting('site_description', 'Un CMS moderno');
        $seoKeywords = $db->getSetting('seo_keywords', 'cms, php, custom');
        $seoAuthor = $db->getSetting('seo_author', 'MyCMS');
        
        echo '<div class="admin-header">';
        echo '<h1>🔍 SEO Settings</h1>';
        echo '<p>Configura le impostazioni SEO del tuo sito</p>';
        echo '</div>';
        
        echo '<div class="card" style="margin: 20px 0;">';
        echo '<h2>Meta Tags Generali</h2>';
        echo '<form method="post">';
        
        echo '<div class="form-group" style="margin-bottom: 20px;">';
        echo '<label style="display: block; margin-bottom: 5px; font-weight: bold;">Meta Description</label>';
        echo '<textarea name="site_description" rows="3" class="form-control" style="width: 100%; padding: 10px;">' . htmlspecialchars($siteDescription) . '</textarea>';
        echo '<small style="color: #666;">Descrizione generale del sito (max 160 caratteri). Questo valore viene usato nel meta tag description.</small>';
        echo '</div>';
        
        echo '<div class="form-group" style="margin-bottom: 20px;">';
        echo '<label style="display: block; margin-bottom: 5px; font-weight: bold;">Meta Keywords</label>';
        echo '<input type="text" name="seo_keywords" class="form-control" style="width: 100%; padding: 10px;" value="' . htmlspecialchars($seoKeywords) . '">';
        echo '<small style="color: #666;">Parole chiave separate da virgola</small>';
        echo '</div>';
        
        echo '<div class="form-group" style="margin-bottom: 20px;">';
        echo '<label style="display: block; margin-bottom: 5px; font-weight: bold;">Autore del Sito</label>';
        echo '<input type="text" name="seo_author" class="form-control" style="width: 100%; padding: 10px;" value="' . htmlspecialchars($seoAuthor) . '">';
        echo '</div>';
        
        echo '<button type="submit" class="btn btn-primary" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">💾 Salva Impostazioni</button>';
        echo '</form>';
        echo '</div>';
    }
    
    public function addExtraSeoTags() {
        $db = $this->cms->getDB();
        $keywords = $db->getSetting('seo_keywords', 'cms, php, custom');
        $author = $db->getSetting('seo_author', 'MyCMS');
        
        echo '<!-- SEO Plugin - Extra Tags -->' . "\n";
        echo '<meta name="keywords" content="' . htmlspecialchars($keywords) . '">' . "\n";
        echo '<meta name="author" content="' . htmlspecialchars($author) . '">' . "\n";
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<link rel="canonical" href="https://' . htmlspecialchars($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . '">' . "\n";
        echo '<!-- /SEO Plugin -->' . "\n";
    }
    
    public function addAnalytics() {
        
    }
}
?>
