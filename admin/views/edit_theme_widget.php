<?php
// Ottieni il widget da modificare
$widgetId = $_GET['id'] ?? null;
if (!$widgetId) {
    header('Location: index.php?action=theme_widgets');
    exit;
}

$widget = $this->db->getThemeWidgetById($widgetId);
if (!$widget) {
    header('Location: index.php?action=theme_widgets');
    exit;
}

$config = json_decode($widget['config'], true) ?: [];
$menus = $this->db->getAllMenus();
?>

<div class="admin-content">
    <h1>✏️ Modifica Widget</h1>
    
    <div style="margin-bottom: 20px;">
        <a href="index.php?action=theme_widgets" class="btn">← Torna ai Widget</a>
    </div>

    <?php if ($widget['widget_type'] == 'menu'): ?>
        <!-- FORM MODIFICA WIDGET MENU -->
        <div class="card">
            <h2>Modifica Widget Menu</h2>
            <form method="POST" action="index.php?action=update_theme_widget">
                <input type="hidden" name="id" value="<?php echo $widget['id']; ?>">
                <input type="hidden" name="widget_type" value="menu">
                
                <div class="form-group">
                    <label>Menu da visualizzare:</label>
                    <select name="menu_id" required>
                        <option value="">-- Seleziona Menu --</option>
                        <?php foreach ($menus as $menu): ?>
                            <option value="<?php echo $menu['id']; ?>" <?php echo ($config['menu_id'] ?? '') == $menu['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($menu['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Titolo Widget (opzionale):</label>
                    <input type="text" name="widget_title" value="<?php echo htmlspecialchars($config['title'] ?? ''); ?>" placeholder="Es: Menu Principale">
                </div>
                
                <div class="form-group">
                    <label>Area di visualizzazione:</label>
                    <select name="area_name" required>
                        <option value="sidebar" <?php echo $widget['area_name'] == 'sidebar' ? 'selected' : ''; ?>>Sidebar</option>
                        <option value="footer" <?php echo $widget['area_name'] == 'footer' ? 'selected' : ''; ?>>Footer</option>
                        <option value="sidebarpost" <?php echo $widget['area_name'] == 'sidebarpost' ? 'selected' : ''; ?>>Post</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Posizione (ordine):</label>
                    <input type="number" name="position" value="<?php echo $widget['position']; ?>" min="0">
                    <small>Numero più basso = posizione più alta</small>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_active" value="1" <?php echo $widget['is_active'] ? 'checked' : ''; ?>>
                        Widget attivo
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary">💾 Salva Modifiche</button>
            </form>
        </div>
        
    <?php elseif ($widget['widget_type'] == 'text'): ?>
        <!-- FORM MODIFICA WIDGET TESTO -->
        <div class="card">
            <h2>Modifica Widget Testo</h2>
            <form method="POST" action="index.php?action=update_theme_widget">
                <input type="hidden" name="id" value="<?php echo $widget['id']; ?>">
                <input type="hidden" name="widget_type" value="text">
                
                <div class="form-group">
                    <label>Titolo Widget:</label>
                    <input type="text" name="widget_title" value="<?php echo htmlspecialchars($config['title'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Contenuto (HTML supportato):</label>
                    <textarea name="widget_content" rows="8" required><?php echo htmlspecialchars($config['content'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Area di visualizzazione:</label>
                    <select name="area_name" required>
                        <option value="sidebar" <?php echo $widget['area_name'] == 'sidebar' ? 'selected' : ''; ?>>Sidebar</option>
                        <option value="footer" <?php echo $widget['area_name'] == 'footer' ? 'selected' : ''; ?>>Footer</option>
                        <option value="sidebarpost" <?php echo $widget['area_name'] == 'sidebarpost' ? 'selected' : ''; ?>>Post</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Posizione (ordine):</label>
                    <input type="number" name="position" value="<?php echo $widget['position']; ?>" min="0">
                    <small>Numero più basso = posizione più alta</small>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_active" value="1" <?php echo $widget['is_active'] ? 'checked' : ''; ?>>
                        Widget attivo
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary">💾 Salva Modifiche</button>
            </form>
        </div>
        
    <?php elseif ($widget['widget_type'] == 'recent_posts'): ?>
        <!-- FORM MODIFICA WIDGET POST RECENTI -->
        <div class="card">
            <h2>Modifica Widget Post Recenti</h2>
            <form method="POST" action="index.php?action=update_theme_widget">
                <input type="hidden" name="id" value="<?php echo $widget['id']; ?>">
                <input type="hidden" name="widget_type" value="recent_posts">
                
                <div class="form-group">
                    <label>Titolo Widget:</label>
                    <input type="text" name="widget_title" value="<?php echo htmlspecialchars($config['title'] ?? 'Post Recenti'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Numero di post da mostrare:</label>
                    <input type="number" name="posts_limit" value="<?php echo $config['limit'] ?? 5; ?>" min="1" max="20" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="show_date" value="1" <?php echo ($config['show_date'] ?? true) ? 'checked' : ''; ?>>
                        Mostra data pubblicazione
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="show_excerpt" value="1" <?php echo ($config['show_excerpt'] ?? false) ? 'checked' : ''; ?>>
                        Mostra estratto
                    </label>
                </div>
                
                <div class="form-group">
                    <label>Area di visualizzazione:</label>
                    <select name="area_name" required>
                        <option value="sidebar" <?php echo $widget['area_name'] == 'sidebar' ? 'selected' : ''; ?>>Sidebar</option>
                        <option value="footer" <?php echo $widget['area_name'] == 'footer' ? 'selected' : ''; ?>>Footer</option>
                        <option value="sidebarpost" <?php echo $widget['area_name'] == 'sidebarpost' ? 'selected' : ''; ?>>Post</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Posizione (ordine):</label>
                    <input type="number" name="position" value="<?php echo $widget['position']; ?>" min="0">
                    <small>Numero più basso = posizione più alta</small>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_active" value="1" <?php echo $widget['is_active'] ? 'checked' : ''; ?>>
                        Widget attivo
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary">💾 Salva Modifiche</button>
            </form>
        </div>
        
    <?php elseif ($widget['widget_type'] == 'auth'): ?>
        <!-- FORM MODIFICA WIDGET AUTH -->
        <div class="card">
            <h2>Modifica Widget Login/Registrazione</h2>
            <form method="POST" action="index.php?action=update_theme_widget">
                <input type="hidden" name="id" value="<?php echo $widget['id']; ?>">
                <input type="hidden" name="widget_type" value="auth">
                
                <div class="form-group">
                    <label>Titolo Widget:</label>
                    <input type="text" name="widget_title" value="<?php echo htmlspecialchars($config['title'] ?? 'Area Utente'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Area di visualizzazione:</label>
                    <select name="area_name" required>
                        <option value="sidebar" <?php echo $widget['area_name'] == 'sidebar' ? 'selected' : ''; ?>>Sidebar</option>
                        <option value="footer" <?php echo $widget['area_name'] == 'footer' ? 'selected' : ''; ?>>Footer</option>
                        <option value="sidebarpost" <?php echo $widget['area_name'] == 'sidebarpost' ? 'selected' : ''; ?>>Post</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Posizione (ordine):</label>
                    <input type="number" name="position" value="<?php echo $widget['position']; ?>" min="0">
                    <small>Numero più basso = posizione più alta</small>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_active" value="1" <?php echo $widget['is_active'] ? 'checked' : ''; ?>>
                        Widget attivo
                    </label>
                </div>
                
                <div class="form-group">
                    <div class="info-box">
                        <strong>ℹ️ Informazioni:</strong>
                        <ul style="margin: 10px 0; padding-left: 20px;">
                            <li>Mostra form di login per utenti non autenticati</li>
                            <li>Mostra form di registrazione se abilitata in Impostazioni → Generale</li>
                            <li>Mostra info utente e link dashboard per utenti loggati</li>
                        </ul>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">💾 Salva Modifiche</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
.card {
    background: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 8px;
    color: #333;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
}

.form-group textarea {
    font-family: monospace;
    resize: vertical;
}

.form-group small {
    display: block;
    color: #666;
    margin-top: 5px;
    font-size: 13px;
}

.info-box {
    background: #e7f3ff;
    border-left: 4px solid #007bff;
    padding: 15px;
    border-radius: 5px;
}

.btn-primary {
    background: #28a745;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.btn-primary:hover {
    background: #218838;
}
</style>
