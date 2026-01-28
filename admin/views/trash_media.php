<h1>🗑️ Cestino Media</h1>

<?php if (isset($_GET['restored'])): ?>
    <div class="success-message">File ripristinato con successo!</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="success-message">File eliminato definitivamente!</div>
<?php endif; ?>
<?php if (isset($_GET['emptied'])): ?>
    <div class="success-message">Cestino svuotato completamente!</div>
<?php endif; ?>

<?php if (empty($trashedMedia)): ?>
    <div class="empty-state">
        <div class="empty-state-icon">🗑️</div>
        <h2>Il cestino è vuoto</h2>
        <p>I file eliminati verranno visualizzati qui.</p>
        <a href="index.php?action=media" class="btn">← Torna alla Galleria</a>
    </div>
<?php else: ?>
    <!-- Barra azioni -->
    <div class="trash-actions-bar">
        <div class="trash-actions-left">
            <a href="index.php?action=media" class="btn btn-secondary">← Torna alla Galleria</a>
            <span class="trash-count" id="selectedCount" style="display: none;">
                <strong>0</strong> file selezionati
            </span>
        </div>
        <div class="trash-actions-right">
            <button type="button" id="bulkRestoreBtn" class="btn btn-success" style="display: none;">
                ↻ Ripristina Selezionati
            </button>
            <button type="button" id="bulkDeleteBtn" class="btn btn-danger" style="display: none;">
                🗑️ Elimina Selezionati
            </button>
            <button type="button" id="emptyTrashBtn" class="btn btn-danger">
                🗑️ Svuota Cestino
            </button>
        </div>
    </div>

    <form method="POST" id="bulkForm">
        <div class="media-grid">
            <?php foreach ($trashedMedia as $item): ?>
                <div class="media-item">
                    <div class="media-checkbox">
                        <input type="checkbox" name="media_ids[]" value="<?php echo $item['id']; ?>" class="media-checkbox-input">
                    </div>
                    
                    <?php if (in_array(strtolower(pathinfo($item['filename'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])): ?>
                        <img src="/uploads/<?php echo htmlspecialchars($item['filename']); ?>" alt="<?php echo htmlspecialchars($item['original_name']); ?>" class="media-deleted-overlay">
                    <?php else: ?>
                        <div class="media-icon media-deleted-overlay">📄</div>
                    <?php endif; ?>
                    
                    <div class="media-info">
                        <div class="media-name" title="<?php echo htmlspecialchars($item['original_name']); ?>">
                            <?php echo htmlspecialchars($item['original_name']); ?>
                        </div>
                        <div class="media-meta">
                            <?php echo number_format($item['size'] / 1024, 2); ?> KB
                        </div>
                        <div class="media-meta">
                            Eliminato: <?php echo date('d/m/Y H:i', strtotime($item['deleted_at'])); ?>
                        </div>
                        <div class="media-actions">
                            <button type="button" class="btn btn-sm btn-success restore-single-btn" 
                                    data-media-id="<?php echo $item['id']; ?>" 
                                    data-filename="<?php echo htmlspecialchars($item['original_name']); ?>" 
                                    title="Ripristina">
                                ↻
                            </button>
                            <button type="button" class="btn btn-sm btn-danger delete-permanently-btn" 
                                    data-media-id="<?php echo $item['id']; ?>" 
                                    data-filename="<?php echo htmlspecialchars($item['original_name']); ?>" 
                                    title="Elimina definitivamente">
                                ✕
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </form>
    
    <!-- Checkbox "Seleziona tutti" -->
    <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
        <label>
            <input type="checkbox" id="selectAll"> Seleziona / Deseleziona tutti
        </label>
    </div>
<?php endif; ?>

<style>
.trash-actions-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 5px;
    flex-wrap: wrap;
    gap: 10px;
}

.trash-actions-left,
.trash-actions-right {
    display: flex;
    align-items: center;
    gap: 10px;
}

.trash-count {
    padding: 8px 15px;
    background: #fff;
    border-radius: 4px;
    border: 1px solid #dee2e6;
}

.trash-count strong {
    color: #007bff;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border-radius: 8px;
    margin: 40px 0;
}

.empty-state-icon {
    font-size: 72px;
    margin-bottom: 20px;
    opacity: 0.3;
}

.empty-state h2 {
    color: #6c757d;
    margin-bottom: 10px;
}

.empty-state p {
    color: #adb5bd;
    margin-bottom: 30px;
}

.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.media-item {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
}

.media-checkbox {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 10;
}

.media-checkbox-input {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.media-deleted-overlay {
    opacity: 0.5;
    filter: grayscale(50%);
}

.media-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
}

.media-icon {
    width: 100%;
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 72px;
    background: #f8f9fa;
}

.media-info {
    padding: 15px;
}

.media-name {
    font-weight: 600;
    margin-bottom: 5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 14px;
}

.media-meta {
    color: #6c757d;
    font-size: 12px;
    margin-bottom: 5px;
}

.media-actions {
    display: flex;
    gap: 5px;
    margin-top: 10px;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 14px;
    min-width: auto;
}

@media (max-width: 768px) {
    .trash-actions-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .trash-actions-left,
    .trash-actions-right {
        justify-content: center;
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const mediaCheckboxes = document.querySelectorAll('.media-checkbox-input');
    const selectedCount = document.getElementById('selectedCount');
    const bulkRestoreBtn = document.getElementById('bulkRestoreBtn');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const emptyTrashBtn = document.getElementById('emptyTrashBtn');
    const bulkForm = document.getElementById('bulkForm');

    // Funzione per aggiornare lo stato dei pulsanti
    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.media-checkbox-input:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            selectedCount.style.display = 'block';
            selectedCount.querySelector('strong').textContent = count;
            bulkRestoreBtn.style.display = 'inline-block';
            bulkDeleteBtn.style.display = 'inline-block';
        } else {
            selectedCount.style.display = 'none';
            bulkRestoreBtn.style.display = 'none';
            bulkDeleteBtn.style.display = 'none';
        }
    }

    // Seleziona/Deseleziona tutto
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            mediaCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }

    // Aggiorna quando si seleziona/deseleziona una singola checkbox
    mediaCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(mediaCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(mediaCheckboxes).some(cb => cb.checked);
            
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            }
            
            updateBulkActions();
        });
    });

    // Ripristina singolo file
    document.querySelectorAll('.restore-single-btn').forEach(button => {
        button.addEventListener('click', function() {
            const mediaId = this.dataset.mediaId;
            const filename = this.dataset.filename;
            
            Swal.fire({
                title: 'Ripristinare il file?',
                html: `Vuoi ripristinare il file:<br><br>"<strong>${filename}</strong>"`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '↻ Sì, ripristina',
                cancelButtonText: 'Annulla',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'index.php?action=restore_media';
                    
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'id';
                    input.value = mediaId;
                    
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });

    // Ripristina selezionati
    if (bulkRestoreBtn) {
        bulkRestoreBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.media-checkbox-input:checked');
            if (checkedBoxes.length === 0) return;
            
            Swal.fire({
                title: 'Ripristinare i file?',
                html: `Vuoi ripristinare <strong>${checkedBoxes.length}</strong> file?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '↻ Sì, ripristina',
                cancelButtonText: 'Annulla',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    bulkForm.action = 'index.php?action=bulk_restore_media';
                    bulkForm.submit();
                }
            });
        });
    }

    // Elimina selezionati
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.media-checkbox-input:checked');
            if (checkedBoxes.length === 0) return;
            
            Swal.fire({
                title: 'ATTENZIONE!',
                html: `Stai per eliminare <strong>DEFINITIVAMENTE ${checkedBoxes.length}</strong> file.<br><br>Questa azione <strong>NON può essere annullata!</strong>`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🗑️ Sì, elimina definitivamente',
                cancelButtonText: 'Annulla',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    bulkForm.action = 'index.php?action=bulk_delete_media';
                    bulkForm.submit();
                }
            });
        });
    }

    // Svuota cestino
    if (emptyTrashBtn) {
        emptyTrashBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'ATTENZIONE!',
                html: 'Stai per eliminare <strong>DEFINITIVAMENTE TUTTI</strong> i file nel cestino.<br><br>Questa azione <strong>NON può essere annullata!</strong>',
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🗑️ Sì, svuota il cestino',
                cancelButtonText: 'Annulla',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'index.php?action=empty_media_trash';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    }

    // Elimina singolo file definitivamente
    document.querySelectorAll('.delete-permanently-btn').forEach(button => {
        button.addEventListener('click', function() {
            const mediaId = this.dataset.mediaId;
            const filename = this.dataset.filename;
            
            Swal.fire({
                title: 'ATTENZIONE!',
                html: `Stai per eliminare <strong>DEFINITIVAMENTE</strong> il file:<br><br>"<strong>${filename}</strong>"<br><br>Questa azione <strong>NON può essere annullata!</strong>`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🗑️ Sì, elimina definitivamente',
                cancelButtonText: 'Annulla',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'index.php?action=delete_media_permanently';
                    
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'id';
                    input.value = mediaId;
                    
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});
</script>
