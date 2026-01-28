<h1>Personalizza Aspetto</h1>

<?php if (isset($_GET['saved'])): ?>
    <div class="success-message">Impostazioni salvate con successo!</div>
<?php endif; ?>

<div class="customizer-layout">
    <div class="customizer-sidebar">
        <div class="customizer-tabs">
            <button class="tab-btn active" data-tab="tema">Tema Attivo</button>
            <button class="tab-btn" data-tab="identita">Personalizza</button>
            <button class="tab-btn" data-tab="widgets">Widget</button>
            <button class="tab-btn" data-tab="menu">Menu</button>
            <button class="tab-btn" data-tab="analytics">Google Analytics</button>
        </div>
    </div>
    
    <div class="customizer-content">
         <!-- TAB: Tema -->
        <div class="tab-content active" id="tab-tema">
            <h2>Tema Attivo</h2>
            <?php 
            $activeTheme = $this->db->getSetting('active_theme', 'default');
            $themePath = THEME_PATH . '/' . $activeTheme . '/style.css';
            
            // Leggi info tema da style.css
            $themeInfo = [
                'name' => ucfirst($activeTheme),
                'description' => '',
                'version' => '',
                'author' => ''
            ];
            
            if (file_exists($themePath)) {
                $cssContent = file_get_contents($themePath);
                if (preg_match('/Theme Name:\s*(.+)/i', $cssContent, $matches)) {
                    $themeInfo['name'] = trim($matches[1]);
                }
                if (preg_match('/Description:\s*(.+)/i', $cssContent, $matches)) {
                    $themeInfo['description'] = trim($matches[1]);
                }
                if (preg_match('/Version:\s*(.+)/i', $cssContent, $matches)) {
                    $themeInfo['version'] = trim($matches[1]);
                }
                if (preg_match('/Author:\s*(.+)/i', $cssContent, $matches)) {
                    $themeInfo['author'] = trim($matches[1]);
                }
            }
            ?>
            
            <div class="theme-showcase">
                <div class="theme-info-box">
                    <h3><?php echo htmlspecialchars($themeInfo['name']); ?></h3>
                    <?php if ($themeInfo['description']): ?>
                        <p><?php echo htmlspecialchars($themeInfo['description']); ?></p>
                    <?php endif; ?>
                    <div class="theme-meta">
                        <?php if ($themeInfo['version']): ?>
                            <span>Versione: <?php echo htmlspecialchars($themeInfo['version']); ?></span>
                        <?php endif; ?>
                        <?php if ($themeInfo['author']): ?>
                            <span>Autore: <?php echo htmlspecialchars($themeInfo['author']); ?></span>
                        <?php endif; ?>
                    </div>
                    <p style="margin-top:20px;">
                        <a href="index.php?action=themes" class="btn">Cambia Tema</a>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- TAB: Personalizza -->
        <div class="tab-content" id="tab-identita">
            <h2>Identità del Sito</h2>
            <form method="POST" action="index.php?action=save_customizer" enctype="multipart/form-data" id="customizer-form">
                <input type="hidden" name="section" value="identity">
                
                <div class="form-group">
                    <label>Titolo del Sito:</label>
                    <input type="text" name="site_title" value="<?php echo htmlspecialchars($this->db->getSetting('site_title', 'Il mio CMS')); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Descrizione del Sito:</label>
                    <textarea name="site_description" rows="3"><?php echo htmlspecialchars($this->db->getSetting('site_description', '')); ?></textarea>
                    <small>Breve descrizione del tuo sito</small>
                </div>
                
                <div class="form-group">
                    <label>Logo del Sito:</label>
                    <input type="file" name="site_logo" accept="image/*" id="logo-upload">
                    <?php $currentLogo = $this->db->getSetting('site_logo'); ?>
                    <div id="logo-preview" class="image-preview">
                        <?php if ($currentLogo): ?>
                            <img src="/uploads/<?php echo htmlspecialchars($currentLogo); ?>" style="max-width:200px; margin-top:10px;">
                            <input type="hidden" name="current_logo" value="<?php echo htmlspecialchars($currentLogo); ?>">
                        <?php else: ?>
                            <p style="color:#999; margin-top:10px;">Nessun logo caricato</p>
                        <?php endif; ?>
                    </div>
                    <small>Dimensioni consigliate: 200x50px</small>
                </div>
                
                <div class="form-group">
                    <label>Favicon:</label>
                    <input type="file" name="site_favicon" accept="image/*" id="favicon-upload">
                    <?php $currentFavicon = $this->db->getSetting('site_favicon'); ?>
                    <div id="favicon-preview" class="image-preview">
                        <?php if ($currentFavicon): ?>
                            <img src="/uploads/<?php echo htmlspecialchars($currentFavicon); ?>" style="max-width:32px; margin-top:10px;">
                            <input type="hidden" name="current_favicon" value="<?php echo htmlspecialchars($currentFavicon); ?>">
                        <?php else: ?>
                            <p style="color:#999; margin-top:10px;">Nessuna favicon caricata</p>
                        <?php endif; ?>
                    </div>
                    <small>Dimensioni: 32x32px o 64x64px (formato .ico, .png)</small>
                </div>
                
                <button type="submit" class="btn">Salva Modifiche</button>
            </form>
        </div>
        
        <!-- TAB: Widget -->
        <div class="tab-content" id="tab-widgets">
            <h2>Widget del Tema</h2>
            <p>Gestisci i widget nelle diverse aree del tema.</p>
            <a href="index.php?action=theme_widgets" class="btn">Vai a Gestione Widget</a>
            
            <div style="margin-top:40px;">
                <h3>Aree Widget Disponibili</h3>
                <div class="widget-areas-info">
                    <div class="widget-area-box">
                        <h4>📍 Sidebar</h4>
                        <p>Widget visualizzati nella barra laterale delle pagine e post.</p>
                        <p><strong>Widget attivi:</strong> 
                            <?php 
                            $sidebarWidgets = $this->db->getThemeWidgets('sidebar');
                            echo count($sidebarWidgets);
                            ?>
                        </p>
                    </div>
                    <div class="widget-area-box">
                        <h4>📍 Footer</h4>
                        <p>Widget visualizzati nel piè di pagina del sito.</p>
                        <p><strong>Widget attivi:</strong> 
                            <?php 
                            $footerWidgets = $this->db->getThemeWidgets('footer');
                            echo count($footerWidgets);
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- TAB: Menu -->
        <div class="tab-content" id="tab-menu">
            <h2>Gestione Menu</h2>
            <p>I menu del tema vengono gestiti dalla sezione dedicata.</p>
            <a href="index.php?action=menus" class="btn">Vai a Gestione Menu</a>
            
            <div style="margin-top:40px;">
                <h3>Posizioni Menu nel Tema</h3>
                <table class="admin-table" style="margin-top:20px;">
                    <thead>
                        <tr>
                            <th>Posizione</th>
                            <th>Descrizione</th>
                            <th>Menu Assegnato</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>primary</strong></td>
                            <td>Menu principale (header)</td>
                            <td>
                                <?php 
                                $primaryMenu = $this->db->getMenuByLocation('primary');
                                echo $primaryMenu ? htmlspecialchars($primaryMenu['name']) : '<em>Nessuno</em>';
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>footer</strong></td>
                            <td>Menu nel footer</td>
                            <td>
                                <?php 
                                $footerMenu = $this->db->getMenuByLocation('footer');
                                echo $footerMenu ? htmlspecialchars($footerMenu['name']) : '<em>Nessuno</em>';
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-content" id="tab-analytics">
    <h2>Google Analytics</h2>
    
    <?php
    $measurementId = $this->db->getSetting('google_analytics');
    $hasCredentials = $this->db->getSetting('ga_service_account_json');
    ?>
    
    <div class="form-group">
        <label>Measurement ID:</label>
        <input type="text" 
               name="google_analytics" 
               value="<?php echo htmlspecialchars($measurementId ?: ''); ?>" 
               placeholder="G-XXXXXXXXXX">
    </div>
    
    <div class="form-group">
    <label>Property ID:</label>
    <form method="POST" action="index.php?action=save_customizer" style="margin-top:15px;">
    <input type="text" 
           name="ga_property_id" 
           value="<?php echo htmlspecialchars($this->db->getSetting('ga_property_id', '')); ?>" 
           placeholder="123456789"
           pattern="[0-9]+"
           required>
    <small>Trovalo su Google Analytics nell'URL dopo <code>/p/</code> (es: analytics.google.com/p/<strong>123456789</strong>/...)</small>
<button type="submit" class="btn">Salva</button>
        </form>
</div>
    
    <?php if ($measurementId && !$hasCredentials): ?>
        <div class="warning-message">
            ⚠️ Per visualizzare le statistiche nella dashboard, devi configurare un Service Account.
            <a href="index.php?action=analytics_setup_guide" class="btn" style="margin-top:10px;">
                Guida Configurazione
            </a>
        </div>
        
        <h3 style="margin-top:30px;">Carica credenziali Service Account</h3>
        <form method="POST" action="index.php?action=upload_ga_credentials" enctype="multipart/form-data">
            <div class="form-group">
                <label>File JSON Service Account:</label>
                <input type="file" name="ga_json_file" accept=".json" required>
                <small>Il file scaricato da Google Cloud Console</small>
            </div>
            <button type="submit" class="btn">Carica Credenziali</button>
        </form>
    <?php elseif ($hasCredentials): ?>
        <div class="success-message">
            ✓ Service Account configurato<br>
            <a href="index.php?action=analytics_stats" class="btn" style="margin-top:10px;">
                Visualizza Statistiche
            </a>
        </div>
        
        
            
        
        <form method="POST" action="index.php?action=remove_ga_credentials" style="margin-top:15px;">
            <button type="submit" class="btn-delete">Rimuovi Credenziali</button>
        </form>
    <?php endif; ?>
</div>

    </div>
</div>

<style>
.customizer-layout {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 30px;
    margin-top: 20px;
}

.customizer-sidebar {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    height: fit-content;
}

.customizer-tabs {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.tab-btn {
    padding: 12px 20px;
    background: none;
    border: none;
    text-align: left;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 15px;
    color: #4a5568;
}

.tab-btn:hover {
    background: #f7fafc;
}

.tab-btn.active {
    background: #667eea;
    color: white;
    font-weight: 600;
}

.customizer-content {
    background: white;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.image-preview img {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 5px;
}

.widget-areas-info {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-top: 20px;
}

.widget-area-box {
    background: #f7fafc;
    padding: 25px;
    border-radius: 8px;
    border: 2px solid #e2e8f0;
}

.widget-area-box h4 {
    margin-bottom: 10px;
    color: #2d3748;
}

.widget-area-box p {
    color: #718096;
    font-size: 14px;
    line-height: 1.6;
    margin: 8px 0;
}

.theme-showcase {
    margin-top: 20px;
}

.theme-info-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px;
    border-radius: 12px;
}

.theme-info-box h3 {
    font-size: 28px;
    margin-bottom: 15px;
}

.theme-meta {
    display: flex;
    gap: 20px;
    margin-top: 15px;
    opacity: 0.9;
}

.theme-info-box .btn {
    background: white;
    color: #667eea;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Tab switching
$('.tab-btn').click(function() {
    const tab = $(this).data('tab');
    
    $('.tab-btn').removeClass('active');
    $(this).addClass('active');
    
    $('.tab-content').removeClass('active');
    $('#tab-' + tab).addClass('active');
});

// Upload logo con anteprima
$('#logo-upload').change(function() {
    uploadImage(this, 'logo-preview', 'site_logo');
});

$('#favicon-upload').change(function() {
    uploadImage(this, 'favicon-preview', 'site_favicon');
});

function uploadImage(input, previewId, fieldName) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('file', input.files[0]);
        
        $.ajax({
            url: 'upload.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    $('#' + previewId).html(
                        '<img src="' + data.url + '" style="max-width:200px; margin-top:10px; border:2px solid #e0e0e0; border-radius:8px; padding:5px;">' +
                        '<input type="hidden" name="' + fieldName + '" value="' + data.filename + '">'
                    );
                } else {
                    alert('Errore upload: ' + (data.error || 'Sconosciuto'));
                }
            }
        });
    }
}
</script>