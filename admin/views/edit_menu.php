<h1><?php echo $menu ? 'Modifica Menu' : 'Nuovo Menu'; ?></h1>
<?php if (isset($_GET['saved'])): ?>
    <div class="success-message">Menu salvato!</div>
<?php endif; ?>
<?php if (isset($_GET['item_saved'])): ?>
    <div class="success-message">Voce menu salvata!</div>
<?php endif; ?>
<?php if (isset($_GET['item_deleted'])): ?>
    <div class="success-message">Voce menu eliminata!</div>
<?php endif; ?>

<div class="two-column-layout">
    <div class="column">
        <h2>Impostazioni Menu</h2>
        <form method="POST" action="index.php?action=save_menu">
            <?php if ($menu): ?>
                <input type="hidden" name="id" value="<?php echo $menu['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Nome Menu:</label>
                <input type="text" name="name" value="<?php echo $menu ? htmlspecialchars($menu['name']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Posizione:</label>
                <select name="location">
                    <option value="">Nessuna</option>
                    <option value="primary" <?php echo ($menu && $menu['location'] === 'primary') ? 'selected' : ''; ?>>Primary</option>
                    <option value="footer" <?php echo ($menu && $menu['location'] === 'footer') ? 'selected' : ''; ?>>Footer</option>
                    <option value="sidebar" <?php echo ($menu && $menu['location'] === 'sidebar') ? 'selected' : ''; ?>>Sidebar</option>
                </select>
            </div>
            
            <button type="submit" class="btn">Salva Menu</button>
            <a href="index.php?action=menus" class="btn btn-secondary">Indietro</a>
        </form>
    </div>
    
    <?php if ($menu): ?>
    <div class="column">
        <h2>Voci Menu</h2>
        <p><button onclick="toggleNewItemForm()" class="btn">➕ Aggiungi Voce</button></p>
        
        <div id="new-item-form" style="display:none; background:#f5f5f5; padding:20px; margin-bottom:20px; border-radius:8px;">
            <h3 id="form-title">Nuova Voce</h3>
            <form method="POST" action="index.php?action=save_menu_item" id="menu-item-form">
                <input type="hidden" name="menu_id" value="<?php echo $menu['id']; ?>">
                <input type="hidden" name="id" id="item-id" value="">
                
                <div class="form-group">
                    <label>Tipo:</label>
                    <select name="link_type" id="link-type" onchange="toggleLinkFields()">
                        <option value="custom">Link personalizzato</option>
                        <option value="page">Pagina</option>
                        <option value="post">Post</option>
                    </select>
                </div>
                
                <div class="form-group" id="field-page" style="display:none;">
                    <label>Seleziona Pagina:</label>
                    <select name="page_id" id="page-select">
                        <option value="">-- Seleziona --</option>
                        <?php 
                        $allPages = $this->db->getAllPages();
                        foreach ($allPages as $page): ?>
                            <option value="<?php echo $page['id']; ?>" data-slug="<?php echo htmlspecialchars($page['slug']); ?>">
                                <?php echo htmlspecialchars($page['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group" id="field-post" style="display:none;">
                    <label>Seleziona Post:</label>
                    <select name="post_id" id="post-select">
                        <option value="">-- Seleziona --</option>
                        <?php 
                        $allPosts = $this->db->getAllPosts();
                        foreach ($allPosts as $post): ?>
                            <option value="<?php echo $post['id']; ?>" data-slug="<?php echo htmlspecialchars($post['slug']); ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Titolo:</label>
                    <input type="text" name="title" id="item-title" required>
                </div>
                
                <div class="form-group" id="field-url">
                    <label>URL:</label>
                    <input type="text" name="url" id="item-url" placeholder="/pagina">
                </div>
                
                <div class="form-group">
                    <label>Genitore:</label>
                    <select name="parent_id" id="item-parent">
                        <option value="">Nessuno (livello principale)</option>
                        <?php echo $this->renderMenuItemsOptions($menuItems); ?>
                    </select>
                    <small>Seleziona un elemento genitore per creare un sottomenu</small>
                </div>
                
                <div class="form-group">
                    <label>Target:</label>
                    <select name="target" id="item-target">
                        <option value="_self">Stessa finestra</option>
                        <option value="_blank">Nuova finestra</option>
                    </select>
                </div>
                
                <button type="submit" class="btn" id="submit-btn">Aggiungi</button>
                <button type="button" onclick="cancelEdit()" class="btn btn-secondary">Annulla</button>
            </form>
        </div>
        
        <div id="menu-items-container">
            <?php if (empty($menuItems)): ?>
                <p style="color:#999;">Nessuna voce ancora. Aggiungi la prima voce al menu.</p>
            <?php else: ?>
                <table class="admin-table menu-items-table">
                    <thead>
                        <tr>
    <th width="30">🔀</th>
    <th>Titolo</th>
    <th>URL</th>
    <th width="100">Azioni</th>
</tr>

                    </thead>
                    <tbody id="sortable-menu">
                        <?php echo $this->renderMenuItemsTree($menuItems); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.menu-items-table tbody tr {
    cursor: move;
    transition: background-color 0.2s;
}

.menu-items-table tbody tr:hover {
    background-color: #f0f0f0;
}

.menu-items-table tbody tr.dragging {
    opacity: 0.5;
}

.menu-item-level-1 {
    padding-left: 30px !important;
}

.menu-item-level-2 {
    padding-left: 60px !important;
}

.menu-item-level-3 {
    padding-left: 90px !important;
}

.menu-item-level-4 {
    padding-left: 120px !important;
}

.drag-handle {
    cursor: grab;
    font-size: 18px;
    user-select: none;
}

.drag-handle:active {
    cursor: grabbing;
}

.btn-icon {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    padding: 5px 8px;
    border-radius: 4px;
    transition: background 0.2s;
}

.btn-icon:hover {
    background: #f0f0f0;
}

.btn-edit-icon {
    color: #28a745;
}

.btn-delete-icon {
    color: #dc3545;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script>
let editingItemId = null;

function toggleNewItemForm() {
    const form = $('#new-item-form');
    if (form.is(':visible')) {
        form.slideUp();
    } else {
        cancelEdit();
        form.slideDown();
    }
}

function toggleLinkFields() {
    const type = $('#link-type').val();
    $('#field-page, #field-post, #field-url').hide();
    
    if (type === 'page') {
        $('#field-page').show();
        $('#item-url').removeAttr('required');
    } else if (type === 'post') {
        $('#field-post').show();
        $('#item-url').removeAttr('required');
    } else {
        $('#field-url').show();
        $('#item-url').attr('required', 'required');
    }
}

// Auto-compila titolo e URL quando si seleziona una pagina
$('#page-select').on('change', function() {
    const selected = $(this).find(':selected');
    if (selected.val()) {
        const title = selected.text();
        const slug = selected.data('slug');
        $('#item-title').val(title);
        $('#item-url').val('/page/' + slug);
    }
});

// Auto-compila titolo e URL quando si seleziona un post
$('#post-select').on('change', function() {
    const selected = $(this).find(':selected');
    if (selected.val()) {
        const title = selected.text();
        const slug = selected.data('slug');
        $('#item-title').val(title);
        $('#item-url').val('/post/' + slug);
    }
});

function editMenuItem(id, title, url, parentId, target) {
    editingItemId = id;
    $('#item-id').val(id);
    $('#item-title').val(title);
    $('#item-url').val(url);
    $('#item-parent').val(parentId || '');
    $('#item-target').val(target);
    $('#form-title').text('Modifica Voce');
    $('#submit-btn').text('Aggiorna');
    
    // Determina il tipo di link
    if (url.startsWith('/page/')) {
        $('#link-type').val('page');
    } else if (url.startsWith('/post/')) {
        $('#link-type').val('post');
    } else {
        $('#link-type').val('custom');
    }
    toggleLinkFields();
    
    $('#new-item-form').slideDown();
    
    // Rimuovi l'opzione di selezionare se stesso come genitore
    $('#item-parent option').prop('disabled', false);
    $('#item-parent option[value="' + id + '"]').prop('disabled', true);
}

function cancelEdit() {
    editingItemId = null;
    $('#menu-item-form')[0].reset();
    $('#item-id').val('');
    $('#form-title').text('Nuova Voce');
    $('#submit-btn').text('Aggiungi');
    $('#item-parent option').prop('disabled', false);
    toggleLinkFields();
}

// Drag & Drop con jQuery UI Sortable - VERSIONE MIGLIORATA
$(function() {
    $("#sortable-menu").sortable({
        handle: ".drag-handle",
        axis: "y",  // Permette solo movimento verticale
        cursor: "move",
        opacity: 0.7,
        placeholder: {
            element: function(currentItem) {
                return $('<tr class="ui-state-highlight"><td colspan="4">&nbsp;</td></tr>')[0];
            },
            update: function() {
                return;
            }
        },
        helper: function(e, tr) {
            // Crea un clone che mantiene le larghezze delle colonne
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index) {
                $(this).width($originals.eq(index).width());
            });
            $helper.css({
                'background-color': '#fff',
                'box-shadow': '0 2px 8px rgba(0,0,0,0.2)',
                'border': '1px solid #ddd'
            });
            return $helper;
        },
        start: function(e, ui) {
            ui.placeholder.height(ui.item.height());
        },
        update: function(event, ui) {
            updateMenuOrder();
        }
    }).disableSelection();
    
    console.log('Sortable initialized');
});

function updateMenuOrder() {
    const order = [];
    let position = 0;
    
    $('#sortable-menu tr').each(function() {
        const itemId = $(this).data('id');
        if (itemId) {
            order.push({
                id: itemId,
                sort_order: position
            });
            position++;
        }
    });
    
    console.log('Nuovo ordine:', order);
    
    $.ajax({
        url: 'index.php?action=update_menu_order',
        method: 'POST',
        data: {
            menu_id: <?php echo $menu['id'] ?? 0; ?>,
            order: JSON.stringify(order)
        },
        success: function(response) {
            console.log('Ordine aggiornato con successo');
        },
        error: function(xhr, status, error) {
            console.error('Errore aggiornamento ordine:', error);
            alert('Errore durante l\'aggiornamento dell\'ordine');
        }
    });
}

// Inizializza
$(document).ready(function() {
    toggleLinkFields();
    
    // Test per verificare che il sortable sia attivo
    setTimeout(function() {
        if ($("#sortable-menu").hasClass('ui-sortable')) {
            console.log('✓ Sortable attivo');
        } else {
            console.error('✗ Sortable NON attivo');
        }
    }, 500);
});
</script>
