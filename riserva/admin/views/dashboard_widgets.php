<div class="admin-content">
    <h1>📊 Gestione Widget Dashboard</h1>
    
    <?php if (isset($_GET['deleted'])): ?>
        <div class="success-message">✅ Widget eliminato con successo!</div>
    <?php endif; ?>
    <?php if (isset($_GET['reordered'])): ?>
        <div class="success-message">✅ Ordine widget aggiornato con successo!</div>
    <?php endif; ?>
    
    <p style="margin-bottom: 20px; color: #666;">
        Gestisci i widget disponibili nella dashboard. <strong>Trascina le righe per riordinare i widget</strong> 🖱️
    </p>
    
    <table class="admin-table" id="widgets-table">
        <thead>
            <tr>
                <th style="width: 40px;">🔀</th>
                <th>Widget</th>
                <th style="width: 100px; text-align: center;">Posizione</th>
                <th style="width: 120px; text-align: center;">Stato</th>
                <th style="width: 250px; text-align: center;">Azioni</th>
            </tr>
        </thead>
        <tbody id="sortable-widgets">
            <?php if (!empty($widgets)): ?>
                <?php foreach ($widgets as $widget): ?>
                <tr data-id="<?php echo $widget['id']; ?>" class="draggable-row">
                    <td class="drag-handle" style="text-align: center; cursor: move;">
                        <span style="font-size: 20px;">⋮⋮</span>
                    </td>
                    <td>
                        <strong style="color: #2c3e50;"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $widget['widget_type']))); ?></strong>
                        <br>
                        <small style="color: #999; font-size: 12px;">Widget_<?php echo htmlspecialchars($widget['widget_type']); ?>.php</small>
                    </td>
                    <td style="text-align: center;">
                        <span class="position-badge" style="display: inline-block; background: #f8f9fa; padding: 4px 10px; border-radius: 4px; font-weight: bold; color: #666;">
                            <?php echo $widget['position']; ?>
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <?php if ($widget['is_active']): ?>
                            <span class="badge badge-success">✅ Attivo</span>
                        <?php else: ?>
                            <span class="badge badge-inactive">⏸️ Inattivo</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <!-- Toggle Attiva/Disattiva -->
                        <form method="POST" action="index.php?action=toggle_dashboard_widget" style="display:inline; margin-right: 5px;">
                            <input type="hidden" name="id" value="<?php echo $widget['id']; ?>">
                            <button type="submit" class="<?php echo $widget['is_active'] ? 'btnwidget' : 'btn'; ?>" style="width: 115px; height: 34px; padding: 8px 15px; font-size: 13px; margin-bottom: 10px;">
                                <?php echo $widget['is_active'] ? '⏸️ Disattiva' : '▶️ Attiva'; ?>
                            </button>
                        </form>
                        
                        <!-- Elimina (file + DB) -->
                        <form method="POST" action="index.php?action=delete_widget" style="display:inline;" class="delete-widget-form" data-widget-type="<?php echo htmlspecialchars($widget['widget_type']); ?>">
                            <input type="hidden" name="id" value="<?php echo $widget['id']; ?>">
                            <button type="button" class="btn-delete delete-widget-btn" style="width: 115px; height: 34px; padding: 8px 15px; font-size: 13px;">
                                🗑️ Elimina
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 50px; color: #999;">
                        <div style="font-size: 48px; margin-bottom: 15px;">📦</div>
                        <p style="font-size: 16px; margin-bottom: 10px;">Nessun widget disponibile</p>
                        <p style="font-size: 14px;">Crea un file <code style="background: #f5f5f5; padding: 2px 6px; border-radius: 3px;">Widget_*.php</code> in <code style="background: #f5f5f5; padding: 2px 6px; border-radius: 3px;">/core/widgets/</code></p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- Info Box -->
    <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-top: 30px; border-left: 4px solid #0066cc;">
        <h3 style="color: #2c3e50; margin-bottom: 15px; font-size: 18px;">💡 Come Creare un Nuovo Widget</h3>
        <ol style="color: #666; line-height: 1.8; padding-left: 20px;">
            <li>Crea un file <code style="background: #f5f5f5; padding: 2px 6px; border-radius: 3px;">/core/widgets/Widget_nome.php</code></li>
            <li>Il file deve contenere una classe <code style="background: #f5f5f5; padding: 2px 6px; border-radius: 3px;">Widget_nome</code> con metodo <code style="background: #f5f5f5; padding: 2px 6px; border-radius: 3px;">render($config)</code></li>
            <li>Salva il file e <strong>ricarica questa pagina</strong>: il widget apparirà automaticamente nella lista!</li>
            <li>Usa il pulsante "▶️ Attiva" per renderlo visibile nella dashboard</li>
        </ol>
    </div>
</div>

<style>
.btnwidget {
    display: inline-block;
    padding: 10px 20px;
    background: #666;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    font-size: 14px;
}

.btnwidget:hover {
    background: #555;
}

.draggable-row {
    transition: background-color 0.2s;
}

.draggable-row:hover {
    background-color: #f8f9fa;
}

.draggable-row.dragging {
    opacity: 0.5;
    background-color: #e3f2fd;
}

.drag-handle {
    cursor: move;
    user-select: none;
}

.drag-handle:hover {
    color: #007bff;
}
</style>

<!-- Libreria SortableJS per drag & drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== DRAG & DROP =====
    const tbody = document.getElementById('sortable-widgets');
    
    if (tbody) {
        // Inizializza Sortable.js
        const sortable = new Sortable(tbody, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'dragging',
            onEnd: function(evt) {
                // Raccogli il nuovo ordine
                const rows = tbody.querySelectorAll('tr[data-id]');
                const order = [];
                
                rows.forEach((row, index) => {
                    const id = row.getAttribute('data-id');
                    const newPosition = index + 1; // Posizioni partono da 1
                    order.push({ id: id, position: newPosition });
                    
                    // Aggiorna visualmente il badge della posizione
                    const positionBadge = row.querySelector('.position-badge');
                    if (positionBadge) {
                        positionBadge.textContent = newPosition;
                    }
                });
                
                // Invia i dati al server via AJAX
                fetch('index.php?action=reorder_dashboard_widgets', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ order: order })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mostra notifica di successo con SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: 'Ordine aggiornato!',
                            text: 'L\'ordine dei widget è stato salvato con successo',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Errore',
                            text: 'Impossibile aggiornare l\'ordine dei widget',
                            confirmButtonColor: '#d33'
                        });
                        console.error('Errore:', data.error);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore di connessione',
                        text: 'Impossibile comunicare con il server',
                        confirmButtonColor: '#d33'
                    });
                    console.error('Errore:', error);
                });
            }
        });
    }
    
    // ===== DELETE WIDGET CON SWEETALERT2 =====
    document.querySelectorAll('.delete-widget-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-widget-form');
            const widgetType = form.dataset.widgetType;
            
            Swal.fire({
                title: '⚠️ ATTENZIONE!',
                html: `
                    <p style="margin-bottom: 15px;">Questa azione eliminerà:</p>
                    <ul style="text-align: left; margin-left: 20px; color: #666;">
                        <li>Il widget dal database</li>
                        <li>Il file <strong>Widget_${widgetType}.php</strong> dal filesystem</li>
                    </ul>
                    <p style="margin-top: 15px; color: #d33; font-weight: bold;">Questa operazione è IRREVERSIBILE.</p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🗑️ Sì, elimina definitivamente',
                cancelButtonText: 'Annulla',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
