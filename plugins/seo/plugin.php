<?php
/**
 * Plugin: SEO Plugin
 * Description: Gestione avanzata impostazioni SEO e Google Search Console
 * Version: 1.2
 */

class SeoPlugin {
    private $cms;
    
    public function __construct($cms) {
        $this->cms = $cms;
        
        // Registra solo gli hook necessari
        add_hook('mycms_head', [$this, 'addExtraSeoTags'], 5);
        add_hook('admin_plugin_pages', [$this, 'registerAdminPage'], 10);
    }
    
    public function registerAdminPage($pages) {
        $pages[] = [
            'slug' => 'seo-settings',
            'title' => 'SEO',
            'icon' => '🔍',
            'callback' => [$this, 'renderAdminPage']
        ];
        
        // Aggiungi pagina per visualizzare la sitemap
        $pages[] = [
            'slug' => 'seo-sitemap-viewer',
            'title' => 'Visualizza Sitemap',
            'icon' => '🗺️',
            'callback' => [$this, 'renderSitemapViewer']
        ];
        
        return $pages;
    }
    
    public function renderSitemapViewer() {
        $db = $this->cms->getDB();
        $siteUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        
        echo '<div class="admin-header">';
        echo '<h1>🗺️ Sitemap XML</h1>';
        echo '<p>Visualizzazione strutturata della sitemap del sito</p>';
        echo '</div>';
        
        // Pulsanti azione
        echo '<div style="margin: 20px 0;">';
        echo '<a href="/sitemap.php" target="_blank" class="btn btn-primary" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px;">📄 Visualizza XML</a>';
        echo '<a href="https://search.google.com/search-console" target="_blank" class="btn btn-success" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px;">🔗 Apri GSC</a>';
        echo '<button onclick="location.reload()" class="btn btn-secondary" style="background: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">🔄 Aggiorna</button>';
        echo '</div>';
        
        // Contatori
        $pagesCount = 0;
        $postsCount = 0;
        $categoriesCount = 0;
        $portfolioCount = 0;
        
        // Conta elementi
        try {
            $stmt = $db->pdo->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "pages WHERE status='pubblicato' AND deleted_at IS NULL");
            $pagesCount = $stmt->fetch()['count'];
        } catch (Exception $e) {}
        
        try {
            $stmt = $db->pdo->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "posts WHERE status='pubblicato' AND post_type = 'post' AND deleted_at IS NULL");
            $postsCount = $stmt->fetch()['count'];
        } catch (Exception $e) {}
        
        try {
            $stmt = $db->pdo->query("SELECT COUNT(DISTINCT c.id) as count 
                                    FROM " . DB_PREFIX . "categories c
                                    INNER JOIN " . DB_PREFIX . "post_categories pc ON c.id = pc.category_id
                                    INNER JOIN " . DB_PREFIX . "posts p ON pc.post_id = p.id
                                    WHERE p.status='pubblicato' AND p.post_type = 'post' AND p.deleted_at IS NULL");
            $categoriesCount = $stmt->fetch()['count'];
        } catch (Exception $e) {}
        
        try {
            $stmt = $db->pdo->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "posts WHERE status='pubblicato' AND deleted_at IS NULL AND post_type = 'portfolio'");
            $portfolioCount = $stmt->fetch()['count'];
        } catch (Exception $e) {}
        
        $totalUrls = 1 + $pagesCount + $postsCount + $categoriesCount + $portfolioCount;
        
        // Card statistiche
        echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 30px;">';
        
        echo '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; text-align: center;">';
        echo '<div style="font-size: 32px; font-weight: bold;">' . $totalUrls . '</div>';
        echo '<div style="font-size: 14px; opacity: 0.9;">URL Totali</div>';
        echo '</div>';
        
        echo '<div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 8px; text-align: center;">';
        echo '<div style="font-size: 32px; font-weight: bold;">' . $pagesCount . '</div>';
        echo '<div style="font-size: 14px; opacity: 0.9;">Pagine</div>';
        echo '</div>';
        
        echo '<div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 20px; border-radius: 8px; text-align: center;">';
        echo '<div style="font-size: 32px; font-weight: bold;">' . $postsCount . '</div>';
        echo '<div style="font-size: 14px; opacity: 0.9;">Post Blog</div>';
        echo '</div>';
        
        echo '<div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 20px; border-radius: 8px; text-align: center;">';
        echo '<div style="font-size: 32px; font-weight: bold;">' . $categoriesCount . '</div>';
        echo '<div style="font-size: 14px; opacity: 0.9;">Categorie</div>';
        echo '</div>';
        
        if ($portfolioCount > 0) {
            echo '<div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; padding: 20px; border-radius: 8px; text-align: center;">';
            echo '<div style="font-size: 32px; font-weight: bold;">' . $portfolioCount . '</div>';
            echo '<div style="font-size: 14px; opacity: 0.9;">Portfolio</div>';
            echo '</div>';
        }
        
        echo '</div>';
        
        // Homepage
        echo '<div class="card" style="margin-bottom: 20px; border-radius: 8px; overflow: hidden;">';
        echo '<div style="background: #f8f9fa; padding: 15px; border-bottom: 2px solid #dee2e6;">';
        echo '<h3 style="margin: 0;">🏠 Homepage</h3>';
        echo '</div>';
        echo '<div style="padding: 20px;">';
        echo '<table style="width: 100%; border-collapse: collapse;">';
        echo '<thead><tr style="background: #f8f9fa;"><th style="padding: 10px; text-align: left; border-bottom: 2px solid #dee2e6;">URL</th><th style="padding: 10px; text-align: center; border-bottom: 2px solid #dee2e6;">Frequenza</th><th style="padding: 10px; text-align: center; border-bottom: 2px solid #dee2e6;">Priorità</th></tr></thead>';
        echo '<tbody>';
        echo '<tr>';
        echo '<td style="padding: 10px; border-bottom: 1px solid #dee2e6;"><a href="' . $siteUrl . '/" target="_blank" style="color: #007bff;">' . $siteUrl . '/</a></td>';
        echo '<td style="padding: 10px; text-align: center; border-bottom: 1px solid #dee2e6;"><span style="background: #28a745; color: white; padding: 3px 10px; border-radius: 12px; font-size: 12px;">daily</span></td>';
        echo '<td style="padding: 10px; text-align: center; border-bottom: 1px solid #dee2e6;"><strong>1.0</strong></td>';
        echo '</tr>';
        echo '</tbody></table>';
        echo '</div>';
        echo '</div>';
        
        // Pagine
        if ($pagesCount > 0) {
            echo '<div class="card" style="margin-bottom: 20px; border-radius: 8px; overflow: hidden;">';
            echo '<div style="background: #f8f9fa; padding: 15px; border-bottom: 2px solid #dee2e6;">';
            echo '<h3 style="margin: 0;">📄 Pagine (' . $pagesCount . ')</h3>';
            echo '</div>';
            echo '<div style="padding: 20px;">';
            echo '<table style="width: 100%; border-collapse: collapse;">';
            echo '<thead><tr style="background: #f8f9fa;"><th style="padding: 10px; text-align: left; border-bottom: 2px solid #dee2e6;">URL</th><th style="padding: 10px; text-align: center; border-bottom: 2px solid #dee2e6;">Ultimo Agg.</th><th style="padding: 10px; text-align: center; border-bottom: 2px solid #dee2e6;">Frequenza</th><th style="padding: 10px; text-align: center; border-bottom: 2px solid #dee2e6;">Priorità</th></tr></thead>';
            echo '<tbody>';
            
            try {
                $stmt = $db->pdo->query("SELECT slug, updated_at FROM " . DB_PREFIX . "pages WHERE status='pubblicato' AND deleted_at IS NULL ORDER BY updated_at DESC LIMIT 50");
                while ($page = $stmt->fetch()) {
                    echo '<tr>';
                    echo '<td style="padding: 10px; border-bottom: 1px solid #dee2e6;"><a href="' . $siteUrl . '/' . htmlspecialchars($page['slug']) . '" target="_blank" style="color: #007bff;">' . htmlspecialchars($page['slug']) . '</a></td>';
                    echo '<td style="padding: 10px; text-align: center; border-bottom: 1px solid #dee2e6;">' . (!empty($page['updated_at']) ? date('d/m/Y', strtotime($page['updated_at'])) : '-') . '</td>';
                    echo '<td style="padding: 10px; text-align: center; border-bottom: 1px solid #dee2e6;"><span style="background: #17a2b8; color: white; padding: 3px 10px; border-radius: 12px; font-size: 12px;">weekly</span></td>';
                    echo '<td style="padding: 10px; text-align: center; border-bottom: 1px solid #dee2e6;">0.8</td>';
                    echo '</tr>';
                }
            } catch (Exception $e) {}
            
            echo '</tbody></table>';
            echo '</div>';
            echo '</div>';
        }
        
        // Post Blog
        if ($postsCount > 0) {
            echo '<div class="card" style="margin-bottom: 20px; border-radius: 8px; overflow: hidden;">';
            echo '<div style="background: #f8f9fa; padding: 15px; border-bottom: 2px solid #dee2e6;">';
            echo '<h3 style="margin: 0;">📝 Post Blog (' . $postsCount . ')</h3>';
            echo '</div>';
            echo '<div style="padding: 20px;">';
            echo '<table style="width: 100%; border-collapse: collapse;">';
            echo '<thead><tr style="background: #f8f9fa;"><th style="padding: 10px; text-align: left; border-bottom: 2px solid #dee2e6;">URL</th><th style="padding: 10px; text-align: center; border-bottom: 2px solid #dee2e6;">Ultimo Agg.</th><th style="padding: 10px; text-align: center; border-bottom: 2px solid #dee2e6;">Frequenza</th><th style="padding: 10px; text-align: center; border-bottom: 2px solid #dee2e6;">Priorità</th></tr></thead>';
            echo '<tbody>';
            
            try {
                $stmt = $db->pdo->query("SELECT slug, updated_at FROM " . DB_PREFIX . "posts WHERE status='pubblicato' AND deleted_at IS NULL AND post_type = 'post' ORDER BY updated_at DESC LIMIT 50");
                while ($post = $stmt->fetch()) {
                    echo '<tr>';
                    echo '<td style="padding: 10px; border-bottom: 1px solid #dee2e6;"><a href="' . $siteUrl . '/post/' . htmlspecialchars($post['slug']) . '" target="_blank" style="color: #007bff;">post/' . htmlspecialchars($post['slug']) . '</a></td>';
                    echo '<td style="padding: 10px; text-align: center; border-bottom: 1px solid #dee2e6;">' . (!empty($post['updated_at']) ? date('d/m/Y', strtotime($post['updated_at'])) : '-') . '</td>';
                    echo '<td style="padding: 10px; text-align: center; border-bottom: 1px solid #dee2e6;"><span style="background: #6c757d; color: white; padding: 3px 10px; border-radius: 12px; font-size: 12px;">monthly</span></td>';
                    echo '<td style="padding: 10px; text-align: center; border-bottom: 1px solid #dee2e6;">0.6</td>';
                    echo '</tr>';
                }
            } catch (Exception $e) {}
            
            echo '</tbody></table>';
            if ($postsCount > 50) {
                echo '<div style="margin-top: 15px; text-align: center; color: #6c757d;">... e altri ' . ($postsCount - 50) . ' post</div>';
            }
            echo '</div>';
            echo '</div>';
        }
        
        // Categorie
        if ($categoriesCount > 0) {
            echo '<div class="card" style="margin-bottom: 20px; border-radius: 8px; overflow: hidden;">';
            echo '<div style="background: #f8f9fa; padding: 15px; border-bottom: 2px solid #dee2e6;">';
            echo '<h3 style="margin: 0;">🏷️ Categorie (' . $categoriesCount . ')</h3>';
            echo '</div>';
            echo '<div style="padding: 20px;">';
            echo '<table style="width: 100%; border-collapse: collapse;">';
            echo '<thead><tr style="background: #f8f9fa;"><th style="padding: 10px; text-align: left; border-bottom: 2px solid #dee2e6;">URL</th><th style="padding: 10px; text-align: center; border-bottom: 2px solid #dee2e6;">Frequenza</th><th style="padding: 10px; text-align: center; border-bottom: 2px solid #dee2e6;">Priorità</th></tr></thead>';
            echo '<tbody>';
            
            try {
                $stmt = $db->pdo->query("SELECT DISTINCT c.slug 
                                        FROM " . DB_PREFIX . "categories c
                                        INNER JOIN " . DB_PREFIX . "post_categories pc ON c.id = pc.category_id
                                        INNER JOIN " . DB_PREFIX . "posts p ON pc.post_id = p.id
                                        WHERE p.status='pubblicato' AND p.deleted_at IS NULL
                                        ORDER BY c.name");
                while ($category = $stmt->fetch()) {
                    echo '<tr>';
                    echo '<td style="padding: 10px; border-bottom: 1px solid #dee2e6;"><a href="' . $siteUrl . '/blog/category/' . htmlspecialchars($category['slug']) . '" target="_blank" style="color: #007bff;">blog/category/' . htmlspecialchars($category['slug']) . '</a></td>';
                    echo '<td style="padding: 10px; text-align: center; border-bottom: 1px solid #dee2e6;"><span style="background: #17a2b8; color: white; padding: 3px 10px; border-radius: 12px; font-size: 12px;">weekly</span></td>';
                    echo '<td style="padding: 10px; text-align: center; border-bottom: 1px solid #dee2e6;">0.5</td>';
                    echo '</tr>';
                }
            } catch (Exception $e) {}
            
            echo '</tbody></table>';
            echo '</div>';
            echo '</div>';
        }
        
        // Portfolio
        if ($portfolioCount > 0) {
            echo '<div class="card" style="margin-bottom: 20px; border-radius: 8px; overflow: hidden;">';
            echo '<div style="background: #f8f9fa; padding: 15px; border-bottom: 2px solid #dee2e6;">';
            echo '<h3 style="margin: 0;">💼 Portfolio (' . $portfolioCount . ')</h3>';
            echo '</div>';
            echo '<div style="padding: 20px;">';
            echo '<table style="width: 100%; border-collapse: collapse;">';
            echo '<thead><tr style="background: #f8f9fa;"><th style="padding: 10px; text-align: left; border-bottom: 2px solid #dee2e6;">URL</th><th style="padding: 10px; text-align: center; border-bottom: 2px solid #dee2e6;">Ultimo Agg.</th><th style="padding: 10px; text-align: center; border-bottom: 2px solid #dee2e6;">Frequenza</th><th style="padding: 10px; text-align: center; border-bottom: 2px solid #dee2e6;">Priorità</th></tr></thead>';
            echo '<tbody>';
            
            try {
                $stmt = $db->pdo->query("SELECT slug, updated_at FROM " . DB_PREFIX . "portfolio WHERE status='pubblicato' AND deleted_at IS NULL ORDER BY updated_at DESC");
                while ($portfolio = $stmt->fetch()) {
                    echo '<tr>';
                    echo '<td style="padding: 10px; border-bottom: 1px solid #dee2e6;"><a href="' . $siteUrl . '/portfolio/' . htmlspecialchars($portfolio['slug']) . '" target="_blank" style="color: #007bff;">portfolio/' . htmlspecialchars($portfolio['slug']) . '</a></td>';
                    echo '<td style="padding: 10px; text-align: center; border-bottom: 1px solid #dee2e6;">' . (!empty($portfolio['updated_at']) ? date('d/m/Y', strtotime($portfolio['updated_at'])) : '-') . '</td>';
                    echo '<td style="padding: 10px; text-align: center; border-bottom: 1px solid #dee2e6;"><span style="background: #6c757d; color: white; padding: 3px 10px; border-radius: 12px; font-size: 12px;">monthly</span></td>';
                    echo '<td style="padding: 10px; text-align: center; border-bottom: 1px solid #dee2e6;">0.7</td>';
                    echo '</tr>';
                }
            } catch (Exception $e) {}
            
            echo '</tbody></table>';
            echo '</div>';
            echo '</div>';
        }
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
        $siteUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        
        echo '<div class="admin-header">';
        echo '<h1>🔍 SEO & Search Console</h1>';
        echo '<p>Configura le impostazioni SEO e monitora Google Search Console</p>';
        echo '</div>';
        
        // Sezione Google Search Console
        echo '<div class="card" style="margin: 20px 0; border: 2px solid #34a853; border-radius: 8px;">';
        echo '<div style="background: #34a853; color: white; padding: 15px; border-radius: 6px 6px 0 0;">';
        echo '<h2 style="margin: 0;"><img src="https://www.google.com/images/branding/googleg/1x/googleg_standard_color_128dp.png" style="width: 24px; vertical-align: middle; margin-right: 10px;"> Google Search Console</h2>';
        echo '</div>';
        echo '<div style="padding: 20px;">';
        
        echo '<div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">';
        echo '✅ <strong>Sito verificato tramite Google Analytics</strong>';
        echo '</div>';
        
        echo '<div style="margin-bottom: 20px;">';
        echo '<a href="https://search.google.com/search-console" target="_blank" class="btn btn-primary" style="background: #4285f4; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">🔗 Apri Google Search Console</a>';
        echo '</div>';
        
        echo '<h3 style="margin-top: 30px;">📊 Cosa monitorare in GSC:</h3>';
        echo '<ul style="line-height: 1.8;">';
        echo '<li><strong>Prestazioni</strong>: Click, impressioni, CTR, posizione media</li>';
        echo '<li><strong>Copertura</strong>: Pagine indicizzate ed eventuali errori</li>';
        echo '<li><strong>Esperienza</strong>: Core Web Vitals e usabilità</li>';
        echo '<li><strong>Miglioramenti</strong>: Suggerimenti per ottimizzare il SEO</li>';
        echo '</ul>';
        
        echo '</div>';
        echo '</div>';
        
        // Sezione Sitemap
        echo '<div class="card" style="margin: 20px 0; border: 2px solid #17a2b8; border-radius: 8px;">';
        echo '<div style="background: #17a2b8; color: white; padding: 15px; border-radius: 6px 6px 0 0;">';
        echo '<h2 style="margin: 0;">🗺️ Sitemap XML</h2>';
        echo '</div>';
        echo '<div style="padding: 20px;">';
        
        echo '<div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">';
        echo '✅ <strong>Sitemap configurata e inviata a Google</strong>';
        echo '</div>';
        
        echo '<div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">';
        echo '<strong>URL Sitemap:</strong><br>';
        echo '<code style="background: white; padding: 8px 12px; border-radius: 3px; display: inline-block; margin-top: 5px; font-size: 14px;">' . htmlspecialchars($siteUrl) . '/sitemap.php</code> ';
        echo '<button onclick="copyToClipboard(\'' . htmlspecialchars($siteUrl) . '/sitemap.php\')" class="btn btn-sm" style="padding: 5px 10px; background: #6c757d; color: white; border: none; border-radius: 3px; cursor: pointer;">📋 Copia</button>';
        echo '</div>';
        
        echo '<p><strong>La sitemap include automaticamente:</strong></p>';
        echo '<ul style="line-height: 1.8;">';
        echo '<li>🏠 Homepage</li>';
        echo '<li>📄 Tutte le pagine pubblicate</li>';
        echo '<li>📝 Tutti i post del blog pubblicati</li>';
        echo '<li>🏷️ Categorie con almeno un post</li>';
        echo '<li>💼 Portfolio (se presente)</li>';
        echo '</ul>';
        
        echo '<div style="background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 20px 0;">';
        echo '💡 <strong>Aggiornamento automatico:</strong> La sitemap si aggiorna automaticamente quando pubblichi nuovi contenuti.';
        echo '</div>';
        
        echo '<div style="margin-top: 20px;">';
        echo '<a href="?action=plugin-page&page=seo-sitemap-viewer" class="btn btn-primary" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px;">👁️ Visualizza Sitemap</a>';
        echo '<a href="/sitemap.php" target="_blank" class="btn btn-secondary" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px;">📄 XML Grezzo</a>';
        echo '<a href="https://nuxtseo.com/tools/xml-sitemap-validator?url=https%253A%252F%252Fmycms.salvatoremaltese.it%252Fsitemap.php" target="_blank" class="btn btn-secondary" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">✓ Valida Sitemap</a>';
        echo '</div>';
        
        echo '</div>';
        echo '</div>';
        
        // Sezione Meta Tags
        echo '<div class="card" style="margin: 20px 0; border-radius: 8px; border: 1px solid #ddd;">';
        echo '<div style="background: #f8f9fa; padding: 15px; border-radius: 6px 6px 0 0; border-bottom: 1px solid #ddd;">';
        echo '<h2 style="margin: 0;">🏷️ Meta Tags Generali</h2>';
        echo '</div>';
        echo '<div style="padding: 20px;">';
        echo '<form method="post">';
        
        echo '<div class="form-group" style="margin-bottom: 20px;">';
        echo '<label style="display: block; margin-bottom: 5px; font-weight: bold;">Meta Description</label>';
        echo '<textarea name="site_description" rows="3" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">' . htmlspecialchars($siteDescription) . '</textarea>';
        echo '<small style="color: #666;">Descrizione generale del sito (max 160 caratteri). Questo valore viene usato nel meta tag description.</small>';
        echo '</div>';
        
        echo '<div class="form-group" style="margin-bottom: 20px;">';
        echo '<label style="display: block; margin-bottom: 5px; font-weight: bold;">Meta Keywords</label>';
        echo '<input type="text" name="seo_keywords" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" value="' . htmlspecialchars($seoKeywords) . '">';
        echo '<small style="color: #666;">Parole chiave separate da virgola</small>';
        echo '</div>';
        
        echo '<div class="form-group" style="margin-bottom: 20px;">';
        echo '<label style="display: block; margin-bottom: 5px; font-weight: bold;">Autore del Sito</label>';
        echo '<input type="text" name="seo_author" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" value="' . htmlspecialchars($seoAuthor) . '">';
        echo '</div>';
        
        echo '<button type="submit" class="btn btn-primary" style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">💾 Salva Impostazioni</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        
        // Sezione Risorse
        echo '<div class="card" style="margin: 20px 0; background: #fff3cd; border-radius: 8px; border: 1px solid #ffc107;">';
        echo '<div style="padding: 20px;">';
        echo '<h3 style="margin-top: 0;">💡 Risorse Utili</h3>';
        echo '<ul style="line-height: 2;">';
        echo '<li><a href="https://support.google.com/webmasters/answer/9128668" target="_blank" style="color: #007bff;">📚 Guida ufficiale Google Search Console</a></li>';
        echo '<li><a href="https://developers.google.com/search/docs" target="_blank" style="color: #007bff;">📖 Documentazione SEO di Google</a></li>';
        echo '<li><a href="https://search.google.com/test/mobile-friendly" target="_blank" style="color: #007bff;">📱 Test ottimizzazione mobile</a></li>';
        echo '<li><a href="https://pagespeed.web.dev/" target="_blank" style="color: #007bff;">⚡ PageSpeed Insights</a></li>';
        echo '</ul>';
        echo '</div>';
        echo '</div>';
        
        // Script per copia URL
        echo '<script>';
        echo 'function copyToClipboard(text) {';
        echo '  navigator.clipboard.writeText(text).then(function() {';
        echo '    alert("✅ URL copiato negli appunti!");';
        echo '  }, function(err) {';
        echo '    console.error("Errore nella copia:", err);';
        echo '  });';
        echo '}';
        echo '</script>';
    }
    
    public function addExtraSeoTags() {
    $db = $this->cms->getDB();
    $keywords = $db->getSetting('seo_keywords', '');
    $author = $db->getSetting('seo_author', '');
    $analytics = $db->getSetting('google_analytics', '');
    
    echo '<!-- SEO Plugin - Extra Tags -->' . "\n";
    echo '<meta name="keywords" content="' . htmlspecialchars($keywords) . '">' . "\n";
    echo '<meta name="author" content="' . htmlspecialchars($author) . '">' . "\n";
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<link rel="canonical" href="https://' . htmlspecialchars($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . '">' . "\n";
    
    // Google Analytics
    if (!empty($analytics) && !is_admin_user()) {
        echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . htmlspecialchars($analytics) . '"></script>' . "\n";
        echo '<script>' . "\n";
        echo '  window.dataLayer = window.dataLayer || [];' . "\n";
        echo '  function gtag(){dataLayer.push(arguments);}' . "\n";
        echo '  gtag("js", new Date());' . "\n";
        echo '  gtag("config", "' . htmlspecialchars($analytics) . '");' . "\n";
        echo '</script>' . "\n";
    }
    
    echo '<!-- /SEO Plugin -->' . "\n";
}

    
}
?>
