<h1>Gestione Widget Tema</h1>

<?php if (isset($_GET['saved'])): ?>
    <div class="success-message">✓ Widget salvato con successo!</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="success-message">✓ Widget eliminato!</div>
<?php endif; ?>
<?php if (isset($_GET['toggled'])): ?>
    <div class="success-message">✓ Stato widget aggiornato!</div>
<?php endif; ?>
<?php if (isset($_GET['reordered'])): ?>
    <div class="success-message">✓ Posizioni widget aggiornate!</div>
<?php endif; ?>

<!-- Tabs per selezionare il tipo di widget -->
<div class="widget-tabs">
    <button class="tab-button active" onclick="switchTab('menu')">📋 Widget Menu</button>
    <button class="tab-button" onclick="switchTab('text')">📝 Widget Testo</button>
    <button class="tab-button" onclick="switchTab('recent_posts')">📰 Post Recenti</button>
    <button class="tab-button" onclick="switchTab('auth')">👤 Login/Registrazione</button>
    <button class="tab-button" onclick="switchTab('altri')">Altri</button>
</div>

<!-- FORM WIDGET MENU -->
<div id="tab-menu" class="tab-content active">
    <div class="card">
        <h2>Crea Widget Menu</h2>
        <form method="POST" action="index.php?action=save_theme_widget">
            <div class="form-group">
                <label>Area di visualizzazione:</label>
                <select name="area_name" required>
                    <option value="sidebar">Sidebar</option>
                    <option value="footer">Footer</option>
                    <option value="sidebarpost">Post</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Seleziona Menu:</label>
                <select name="menu_id" required>
                    <option value="">-- Scegli un menu --</option>
                    <?php
                    $prefix = DB_PREFIX;
                    $menus = $this->db->pdo->query("SELECT id, name FROM {$prefix}menus ORDER BY name")->fetchAll();
                    foreach ($menus as $menu):
                    ?>
                        <option value="<?php echo $menu['id']; ?>"><?php echo htmlspecialchars($menu['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Titolo Widget (facoltativo):</label>
                <input type="text" name="widget_title" placeholder="Es: Menu Principale">
                <small>Lascia vuoto per non mostrare un titolo</small>
            </div>
            
            <div class="form-group">
                <label>Posizione (ordine):</label>
                <input type="number" name="position" value="0" min="0">
                <small>Numero più basso = posizione più alta</small>
            </div>
            
            <input type="hidden" name="widget_type" value="menu">
            <input type="hidden" name="is_active" value="1">
            
            <button type="submit" class="btn btn-primary">💾 Salva Widget Menu</button>
        </form>
    </div>
</div>

<!-- FORM WIDGET TESTO -->
<div id="tab-text" class="tab-content">
    <div class="card">
        <h2>Crea Widget Testo</h2>
        <form method="POST" action="index.php?action=save_theme_widget">
            <div class="form-group">
                <label>Area di visualizzazione:</label>
                <select name="area_name" required>
                    <option value="sidebar">Sidebar</option>
                    <option value="footer">Footer</option>
                    <option value="sidebarpost">Post</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Titolo Widget:</label>
                <input type="text" name="widget_title" required placeholder="Es: Chi Siamo">
            </div>
            
            <div class="form-group">
                <label>Contenuto:</label>
                <textarea name="widget_content" rows="8" required placeholder="Inserisci il testo o codice HTML"></textarea>
                <small>Puoi usare HTML per formattare il testo (es: &lt;p&gt;, &lt;strong&gt;, &lt;a&gt;, ecc.)</small>
            </div>
            
            <div class="form-group">
                <label>Posizione (ordine):</label>
                <input type="number" name="position" value="0" min="0">
            </div>
            
            <input type="hidden" name="widget_type" value="text">
            <input type="hidden" name="is_active" value="1">
            
            <button type="submit" class="btn btn-primary">💾 Salva Widget Testo</button>
        </form>
    </div>
</div>

<!-- FORM WIDGET POST RECENTI -->
<div id="tab-recent_posts" class="tab-content">
    <div class="card">
        <h2>Crea Widget Post Recenti</h2>
        <form method="POST" action="index.php?action=save_theme_widget">
            <div class="form-group">
                <label>Area di visualizzazione:</label>
                <select name="area_name" required>
                    <option value="sidebar">Sidebar</option>
                    <option value="footer">Footer</option>
                    <option value="sidebarpost">Post</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Titolo Widget:</label>
                <input type="text" name="widget_title" value="Post Recenti" required>
            </div>
            
            <div class="form-group">
                <label>Numero di post da mostrare:</label>
                <input type="number" name="posts_limit" value="5" min="1" max="20" required>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="show_date" value="1" checked>
                    Mostra data pubblicazione
                </label>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="show_excerpt" value="1">
                    Mostra estratto del post
                </label>
            </div>
            
            <div class="form-group">
                <label>Posizione (ordine):</label>
                <input type="number" name="position" value="0" min="0">
            </div>
            
            <input type="hidden" name="widget_type" value="recent_posts">
            <input type="hidden" name="is_active" value="1">
            
            <button type="submit" class="btn btn-primary">💾 Salva Widget Post Recenti</button>
        </form>
    </div>
</div>

<!-- FORM WIDGET AUTH (NUOVO) -->
<div id="tab-auth" class="tab-content">
    <div class="card">
        <h2>Crea Widget Login/Registrazione</h2>
        <form method="POST" action="index.php?action=save_theme_widget">
            <div class="form-group">
                <label>Area di visualizzazione:</label>
                <select name="area_name" required>
                    <option value="sidebar">Sidebar</option>
                    <option value="footer">Footer</option>
                    <option value="sidebarpost">Post</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Titolo Widget:</label>
                <input type="text" name="widget_title" value="Area Utente" required>
            </div>
            
            <div class="form-group">
                <label>Posizione (ordine):</label>
                <input type="number" name="position" value="0" min="0">
                <small>Numero più basso = posizione più alta</small>
            </div>
            
            <div class="form-group">
                <div class="info-box">
                    <strong>ℹ️ Informazioni:</strong>
                    <ul style="margin: 10px 0; padding-left: 20px;">
                        <li>Mostra form di login per utenti non autenticati</li>
                        <li>Mostra form di registrazione se abilitata in Impostazioni → Generale</li>
                        <li>Mostra info utente e link dashboard per utenti loggati</li>
                        <li>Tutto gestito via AJAX senza refresh della pagina</li>
                    </ul>
                </div>
            </div>
            
            <input type="hidden" name="widget_type" value="auth">
            <input type="hidden" name="is_active" value="1">
            
            <button type="submit" class="btn btn-primary">💾 Salva Widget Login</button>
        </form>
    </div>
</div>

<!-- ALTRI WIDGET ANCHE DEI PLUGIN -->
<!-- TAB ALTRI (WIDGET DA PLUGIN) -->
<div id="tab-altri" class="tab-content">
    <?php
    // Hook: permette ai plugin di registrare i loro widget
    do_hook('mycms_plugin_widgets_init');
    
    // Ottieni tutti i widget registrati dai plugin
    $pluginWidgets = get_registered_plugin_widgets();
    
    if (empty($pluginWidgets)): ?>
        <div class="card">
            <h2>🔌 Widget da Plugin</h2>
            <div style="text-align: center; padding: 40px; color: #999;">
                <p style="font-size: 18px; margin-bottom: 10px;">📦 Nessun widget disponibile</p>
                <p>I plugin installati possono aggiungere widget personalizzati in questa sezione.</p>
                <p style="margin-top: 20px;">
                    <small>Installa un plugin che supporta widget per vederli comparire qui.</small>
                </p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($pluginWidgets as $widgetType => $widgetConfig): ?>
            <div class="card" style="margin-bottom: 25px;">
                <h2>
                    <?php echo htmlspecialchars($widgetConfig['icon'] ?? '🔌'); ?> 
                    <?php echo htmlspecialchars($widgetConfig['name'] ?? $widgetType); ?>
                </h2>
                
                <?php if (!empty($widgetConfig['description'])): ?>
                    <p style="color: #666; margin-bottom: 20px; font-size: 14px;">
                        <?php echo htmlspecialchars($widgetConfig['description']); ?>
                    </p>
                <?php endif; ?>
                
                <form method="POST" action="index.php?action=save_theme_widget">
                    <!-- Campi standard per tutti i widget -->
                    <div class="form-group">
                        <label>Area di visualizzazione:</label>
                        <select name="area_name" required>
                            <option value="sidebar">Sidebar</option>
                            <option value="footer">Footer</option>
                            <option value="sidebarpost">Post</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Posizione (ordine):</label>
                        <input type="number" name="position" value="0" min="0">
                        <small>Numero più basso = posizione più alta</small>
                    </div>
                    
                    <?php
                    // Hook: permette al plugin di aggiungere i suoi campi personalizzati
                    do_hook('mycms_plugin_widget_form', $widgetType, $widgetConfig);
                    ?>
                    
                    <!-- Campi nascosti -->
                    <input type="hidden" name="widget_type" value="<?php echo htmlspecialchars($widgetType); ?>">
                    <input type="hidden" name="is_active" value="1">
                    
                    <button type="submit" class="btn btn-primary">
                        💾 Salva Widget <?php echo htmlspecialchars($widgetConfig['name'] ?? $widgetType); ?>
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


<hr style="margin: 40px 0;">

<!-- LISTA WIDGET ESISTENTI -->
<h2>Widget Attivi</h2>

<!-- Filtro per area -->
<div style="margin-bottom: 20px;">
    <label style="font-weight: bold; margin-right: 10px;">Filtra per area:</label>
    <select id="area-filter" onchange="filterByArea()" style="padding: 8px; border-radius: 5px; border: 1px solid #ddd;">
        <option value="">Tutte le aree</option>
        <option value="sidebar">Sidebar</option>
        <option value="footer">Footer</option>
        <option value="sidebarpost">Post</option>
    </select>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th style="width: 40px;">🔀</th>
            <th>Tipo</th>
            <th>Area</th>
            <th>Dettagli</th>
            <th style="width: 80px;">Posizione</th>
            <th style="width: 100px;">Stato</th>
            <th style="width: 250px;">Azioni</th>
        </tr>
    </thead>
    <tbody id="sortable-widgets">
        <?php if (empty($widgets)): ?>
            <tr class="no-widgets-row">
                <td colspan="7" style="text-align:center; padding:20px; color:#999;">
                    Nessun widget configurato. Usa i form sopra per crearne uno!
                </td>
            </tr>
        <?php else: ?>
            <?php 
            // Raggruppa widget per area
            $widgetsByArea = [];
            foreach ($widgets as $widget) {
                $widgetsByArea[$widget['area_name']][] = $widget;
            }
            
            foreach ($widgetsByArea as $areaName => $areaWidgets): ?>
                <!-- Intestazione Area -->
                <tr class="area-header" data-area="<?php echo htmlspecialchars($areaName); ?>">
                    <td colspan="7" style="background: #f8f9fa; font-weight: bold; padding: 12px;">
                        📍 Area: <?php echo htmlspecialchars($areaName); ?>
                    </td>
                </tr>
                
                <?php foreach ($areaWidgets as $widget): 
                    $config = json_decode($widget['config'], true) ?: [];
                ?>
                <tr class="widget-row draggable-row" data-id="<?php echo $widget['id']; ?>" data-area="<?php echo htmlspecialchars($widget['area_name']); ?>">
                    <td class="drag-handle" style="text-align: center; cursor: move;">
                        <span style="font-size: 20px;">⋮⋮</span>
                    </td>
                    <td>
                        <?php 
                        switch($widget['widget_type']) {
                            case 'menu': echo '📋 Menu'; break;
                            case 'text': echo '📝 Testo'; break;
                            case 'recent_posts': echo '📰 Post Recenti'; break;
                            case 'auth': echo '👤 Login/Registrazione'; break;
                            default: echo htmlspecialchars($widget['widget_type']);
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($widget['area_name']); ?></td>
                    <td>
                        <?php 
                        if ($widget['widget_type'] == 'menu') {
                            $menuId = $config['menu_id'] ?? '';
                            if ($menuId) {
                                $prefix = DB_PREFIX;
                                $menuData = $this->db->pdo->query("SELECT name FROM {$prefix}menus WHERE id=$menuId")->fetch();
                                echo 'Menu: ' . htmlspecialchars($menuData['name'] ?? "ID $menuId");
                            }
                            if (!empty($config['title'])) {
                                echo '<br><small>Titolo: ' . htmlspecialchars($config['title']) . '</small>';
                            }
                        } elseif ($widget['widget_type'] == 'text') {
                            echo htmlspecialchars($config['title'] ?? '');
                        } elseif ($widget['widget_type'] == 'recent_posts') {
                            echo ($config['limit'] ?? 5) . ' post';
                            if (!empty($config['title'])) {
                                echo '<br><small>' . htmlspecialchars($config['title']) . '</small>';
                            }
                        } elseif ($widget['widget_type'] == 'auth') {
                            echo htmlspecialchars($config['title'] ?? 'Area Utente');
                        }
                        ?>
                    </td>
                    <td style="text-align: center;">
                        <span class="position-badge"><?php echo $widget['position']; ?></span>
                    </td>
                    <td style="text-align: center;">
                        <?php if ($widget['is_active']): ?>
                            <span class="badge badge-success">✓ Attivo</span>
                        <?php else: ?>
                            <span class="badge badge-inactive">✗ Inattivo</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <!-- Pulsante Modifica -->
                        <button onclick="editWidget(<?php echo $widget['id']; ?>)" class="btn-edit" style="margin-bottom: 5px;">
                            ✏️ Modifica
                        </button>
                        
                        <!-- Toggle Attiva/Disattiva -->
                        <form method="POST" action="index.php?action=toggle_theme_widget" style="display:inline; margin-right: 5px;">
                            <input type="hidden" name="id" value="<?php echo $widget['id']; ?>">
                            <button type="submit" class="<?php echo $widget['is_active'] ? 'btn-toggle' : 'btn'; ?>" style="margin-bottom: 5px;">
                                <?php echo $widget['is_active'] ? '⏸️ Disattiva' : '▶️ Attiva'; ?>
                            </button>
                        </form>
                        
                        <!-- Elimina -->
                        <form method="POST" action="index.php?action=delete_theme_widget" style="display:inline;" onsubmit="return confirm('Eliminare questo widget?');">
                            <input type="hidden" name="id" value="<?php echo $widget['id']; ?>">
                            <button type="submit" class="btn-delete">🗑️ Elimina</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<style>
.widget-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    border-bottom: 2px solid #ddd;
    padding-bottom: 10px;
    flex-wrap: wrap;
}

.tab-button {
    padding: 10px 20px;
    border: none;
    background: #f5f5f5;
    cursor: pointer;
    border-radius: 5px 5px 0 0;
    font-size: 16px;
    transition: all 0.3s;
}

.tab-button:hover {
    background: #e0e0e0;
}

.tab-button.active {
    background: #007bff;
    color: white;
}

.tab-content {
    display: none;
    animation: fadeIn 0.3s;
}

.tab-content.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.card {
    background: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    margin-bottom: 20px;
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

.info-box strong {
    color: #007bff;
}

.info-box ul {
    font-size: 14px;
    color: #333;
}

.btn-primary {
    background: #28a745;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-primary:hover {
    background: #218838;
}

.badge {
    padding: 5px 10px;
    border-radius: 3px;
    font-size: 12px;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-inactive {
    background: #f8d7da;
    color: #721c24;
}

.area-header {
    background: #f8f9fa !important;
}

.draggable-row {
    transition: background-color 0.2s;
    cursor: default;
}

.draggable-row:hover {
    background-color: #f8f9fa;
}

.draggable-row.dragging {
    opacity: 0.5;
    background-color: #e3f2fd;
}

.drag-handle {
    cursor: move !important;
    user-select: none;
}

.drag-handle:hover {
    color: #007bff;
}

.position-badge {
    display: inline-block;
    background: #f8f9fa;
    padding: 4px 10px;
    border-radius: 4px;
    font-weight: bold;
    color: #666;
}

.btn-edit {
    background: #007bff;
    color: white;
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
}

.btn-edit:hover {
    background: #0056b3;
}

.btn-toggle {
    background: #666;
    color: white;
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
}

.btn-toggle:hover {
    background: #555;
}
</style>

<!-- Libreria SortableJS per drag & drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    
    document.getElementById('tab-' + tabName).classList.add('active');
    event.target.classList.add('active');
}

function filterByArea() {
    const filter = document.getElementById('area-filter').value;
    const rows = document.querySelectorAll('.widget-row, .area-header');
    
    rows.forEach(row => {
        if (!filter || row.dataset.area === filter) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function editWidget(id) {
    // Reindirizza a pagina di modifica
    window.location.href = 'index.php?action=edit_theme_widget&id=' + id;
}

// Drag & Drop per riordinare widget
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('sortable-widgets');
    
    if (tbody && tbody.querySelectorAll('.widget-row').length > 0) {
        new Sortable(tbody, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'dragging',
            filter: '.area-header, .no-widgets-row',
            onEnd: function(evt) {
                const rows = tbody.querySelectorAll('.widget-row');
                const order = [];
                
                rows.forEach((row, index) => {
                    const id = row.getAttribute('data-id');
                    const newPosition = index + 1;
                    order.push({ id: id, position: newPosition });
                    
                    const positionBadge = row.querySelector('.position-badge');
                    if (positionBadge) {
                        positionBadge.textContent = newPosition;
                    }
                });
                
                // Invia al server
                fetch('index.php?action=reorder_theme_widgets', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ order: order })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Ordine aggiornato!',
                            text: 'Le posizioni dei widget sono state salvate',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    }
                })
                .catch(error => {
                    console.error('Errore:', error);
                });
            }
        });
    }
});
</script>
