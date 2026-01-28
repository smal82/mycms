<h1>🗑️ Cestino Post</h1>

<?php if (isset($_GET['restored'])): ?>
    <div class="success-message">Post ripristinato con successo!</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="success-message">Post eliminato definitivamente!</div>
<?php endif; ?>
<?php if (isset($_GET['emptied'])): ?>
    <div class="success-message">Cestino svuotato completamente!</div>
<?php endif; ?>

<?php if (empty($trashedPosts)): ?>
    <div class="empty-state">
        <div class="empty-state-icon">🗑️</div>
        <h2>Il cestino è vuoto</h2>
        <p>I post eliminati verranno visualizzati qui.</p>
        <a href="index.php?action=posts" class="btn">← Torna ai Post</a>
    </div>
<?php else: ?>
    <!-- Barra azioni -->
    <div class="trash-actions-bar">
        <div class="trash-actions-left">
            <a href="index.php?action=posts" class="btn btn-secondary">← Torna ai Post</a>
            <span class="trash-count" id="selectedCount" style="display: none;">
                <strong>0</strong> post selezionati
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
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">
                        <input type="checkbox" id="selectAll" title="Seleziona/Deseleziona tutto">
                    </th>
                    <th>ID</th>
                    <th>Titolo</th>
                    <th>Autore</th>
                    <th>Eliminato il</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trashedPosts as $post): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="post_ids[]" value="<?php echo $post['id']; ?>" class="post-checkbox">
                    </td>
                    <td><?php echo $post['id']; ?></td>
                    <td><?php echo htmlspecialchars($post['title']); ?></td>
                    <td><?php echo htmlspecialchars($post['author_name'] ?? 'N/D'); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($post['deleted_at'])); ?></td>
                    <td class="actions-cell">
                        <button type="button" class="btn btn-sm btn-success restore-single-btn" 
                                data-post-id="<?php echo $post['id']; ?>" 
                                data-post-title="<?php echo htmlspecialchars($post['title']); ?>" 
                                title="Ripristina">
                            ↻
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-permanently-btn" 
                                data-post-id="<?php echo $post['id']; ?>" 
                                data-post-title="<?php echo htmlspecialchars($post['title']); ?>" 
                                title="Elimina definitivamente">
                            ✕
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
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

.btn-sm {
    padding: 5px 10px;
    font-size: 14px;
    min-width: auto;
}

.actions-cell {
    white-space: nowrap;
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
    const postCheckboxes = document.querySelectorAll('.post-checkbox');
    const selectedCount = document.getElementById('selectedCount');
    const bulkRestoreBtn = document.getElementById('bulkRestoreBtn');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const emptyTrashBtn = document.getElementById('emptyTrashBtn');
    const bulkForm = document.getElementById('bulkForm');

    // Funzione per aggiornare lo stato dei pulsanti
    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.post-checkbox:checked');
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
            postCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }

    // Aggiorna quando si seleziona/deseleziona una singola checkbox
    postCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(postCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(postCheckboxes).some(cb => cb.checked);
            
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            }
            
            updateBulkActions();
        });
    });

    // Ripristina singolo post
    document.querySelectorAll('.restore-single-btn').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const postTitle = this.dataset.postTitle;
            
            Swal.fire({
                title: 'Ripristinare il post?',
                html: `Vuoi ripristinare il post:<br><br>"<strong>${postTitle}</strong>"`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '↻ Sì, ripristina',
                cancelButtonText: 'Annulla',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Crea e invia il form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'index.php?action=restore_post';
                    
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'id';
                    input.value = postId;
                    
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });

    // Ripristina selezionati con SweetAlert2
    if (bulkRestoreBtn) {
        bulkRestoreBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.post-checkbox:checked');
            if (checkedBoxes.length === 0) return;
            
            Swal.fire({
                title: 'Ripristinare i post?',
                html: `Vuoi ripristinare <strong>${checkedBoxes.length}</strong> post?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '↻ Sì, ripristina',
                cancelButtonText: 'Annulla',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    bulkForm.action = 'index.php?action=bulk_restore_posts';
                    bulkForm.submit();
                }
            });
        });
    }

    // Elimina selezionati con SweetAlert2
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.post-checkbox:checked');
            if (checkedBoxes.length === 0) return;
            
            Swal.fire({
                title: 'ATTENZIONE!',
                html: `Stai per eliminare <strong>DEFINITIVAMENTE ${checkedBoxes.length}</strong> post.<br><br>Questa azione <strong>NON può essere annullata!</strong>`,
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
                    bulkForm.action = 'index.php?action=bulk_delete_posts';
                    bulkForm.submit();
                }
            });
        });
    }

    // Svuota cestino con SweetAlert2
    if (emptyTrashBtn) {
        emptyTrashBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'ATTENZIONE!',
                html: 'Stai per eliminare <strong>DEFINITIVAMENTE TUTTI</strong> i post nel cestino.<br><br>Questa azione <strong>NON può essere annullata!</strong>',
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
                    form.action = 'index.php?action=empty_post_trash';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    }

    // Elimina singolo post definitivamente con SweetAlert2
    document.querySelectorAll('.delete-permanently-btn').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const postTitle = this.dataset.postTitle;
            
            Swal.fire({
                title: 'ATTENZIONE!',
                html: `Stai per eliminare <strong>DEFINITIVAMENTE</strong> il post:<br><br>"<strong>${postTitle}</strong>"<br><br>Questa azione <strong>NON può essere annullata!</strong>`,
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
                    form.action = 'index.php?action=delete_post_permanently';
                    
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'id';
                    input.value = postId;
                    
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});
</script>
