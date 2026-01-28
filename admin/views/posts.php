<h1>Gestione Post</h1>
<?php if (isset($_GET['saved'])): ?>
    <div class="success-message">Post salvato con successo!</div>
<?php endif; ?>
<?php if (isset($_GET['trashed'])): ?>
    <div class="success-message">Post spostato nel cestino!</div>
<?php endif; ?>
<?php if (isset($_GET['bulk_trashed'])): ?>
    <div class="success-message"><?php echo (int)$_GET['count']; ?> post spostati nel cestino!</div>
<?php endif; ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div>
        <a href="index.php?action=edit_post" class="btn">Nuovo Post</a>
        <a href="index.php?action=trash_posts" class="btn btn-secondary" style="background-color: #6c757d;">
            🗑️ Cestino Post <?php 
            $trashedCount = count($this->db->getTrashedPosts());
            if ($trashedCount > 0) echo "($trashedCount)";
            ?>
        </a>
    </div>
    
    <div id="bulk-actions" style="display: none; align-items: center; gap: 10px;">
        <span id="selected-count" style="font-weight: bold; color: #666;">0 selezionati</span>
        <button type="button" id="bulk-trash-btn" class="btn-delete" style="padding: 10px 20px;">
            🗑️ Cestina selezionati
        </button>
        <button type="button" id="deselect-all-btn" class="btn btn-secondary" style="background-color: #6c757d; padding: 10px 20px;">
            ✖️ Deseleziona tutti
        </button>
    </div>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th style="width: 40px;">
                <input type="checkbox" id="select-all" title="Seleziona tutti">
            </th>
            <th>ID</th>
            <th>Titolo</th>
            <th>Slug</th>
            <th>Categorie</th>
            <th>Autore</th>
            <th>Stato</th>
            <th>Data</th>
            <th>Azioni</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($posts as $post): ?>
        <tr>
            <td>
                <input type="checkbox" class="post-checkbox" value="<?php echo $post['id']; ?>">
            </td>
            <td><?php echo $post['id']; ?></td>
            <td><?php echo htmlspecialchars($post['title']); ?></td>
            <td>
                <?php if ($post['status'] === 'pubblicato'): ?>
                    <a href="../post/<?php echo htmlspecialchars($post['slug']); ?>" 
                       target="_blank" 
                       class="slug-link"
                       title="Visualizza il post sul sito">
                        <?php echo htmlspecialchars($post['slug']); ?>
                    </a>
                <?php else: ?>
                    <span class="slug-inactive" title="Post non pubblicato">
                        <?php echo htmlspecialchars($post['slug']); ?>
                    </span>
                <?php endif; ?>
            </td>
            <td>
                <?php
                $categories = $this->db->getPostCategories($post['id']);
                if (!empty($categories)) {
                    $categoryNames = array_map(function($cat) {
                        return htmlspecialchars($cat['name']);
                    }, $categories);
                    echo implode(', ', $categoryNames);
                } else {
                    echo '<span style="color: #999;">Nessuna</span>';
                }
                ?>
            </td>
            <td><?php echo htmlspecialchars($post['author_name'] ?? 'N/D'); ?></td>
            <td>
                <?php if ($post['status'] === 'pubblicato'): ?>
                    <span class="badge badge-success">Pubblicato</span>
                <?php elseif ($post['status'] === 'programmato'): ?>
                    <span class="badge badge-warning">Programmato</span>
                <?php else: ?>
                    <span class="badge badge-inactive">Bozza</span>
                <?php endif; ?>
            </td>
            <td><?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></td>
            <td>
                <form method="GET" action="index.php" style="display:inline;">
                    <input type="hidden" name="action" value="edit_post">
                    <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                    <button type="submit" class="btn-edit">Modifica</button>
                </form>
                <form method="POST" action="index.php?action=trash_post" style="display:inline;" class="trash-form" data-post-title="<?php echo htmlspecialchars($post['title']); ?>">
                    <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                    <button type="button" class="btn-delete trash-btn">Cestina</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if ($totalPages > 1): ?>
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="index.php?action=posts&page=<?php echo $page - 1; ?>" class="btn btn-secondary">← Precedente</a>
    <?php endif; ?>
    
    <span class="pagination-info">
        Pagina <?php echo $page; ?> di <?php echo $totalPages; ?> 
        (<?php echo $totalPosts; ?> post totali)
    </span>
    
    <?php if ($page < $totalPages): ?>
        <a href="index.php?action=posts&page=<?php echo $page + 1; ?>" class="btn btn-secondary">Successiva →</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<style>
.admin-table {
    position: relative;
}

.admin-table thead tr {
    position: sticky;
    top: 0;
    z-index: 10;
    background-color: #f8f9fa;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.admin-table thead th {
    background-color: #f8f9fa;
    padding: 12px;
    border-bottom: 2px solid #dee2e6;
}

.slug-link {
    color: #007bff;
    text-decoration: none;
    transition: color 0.2s;
}

.slug-link:hover {
    color: #0056b3;
    text-decoration: none;
}

.slug-inactive {
    color: #6c757d;
    font-family: monospace;
    font-size: 0.9em;
    font-style: italic;
}

.pagination {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 5px;
}

.pagination-info {
    font-weight: bold;
    color: #495057;
}

.post-checkbox, #select-all {
    cursor: pointer;
    width: 18px;
    height: 18px;
}

tr.selected {
    background-color: #e3f2fd !important;
}

#bulk-actions {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const postCheckboxes = document.querySelectorAll('.post-checkbox');
    const bulkActionsDiv = document.getElementById('bulk-actions');
    const selectedCountSpan = document.getElementById('selected-count');
    const bulkTrashBtn = document.getElementById('bulk-trash-btn');
    const deselectAllBtn = document.getElementById('deselect-all-btn');
    
    // Funzione per aggiornare il conteggio e la visibilità delle azioni bulk
    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.post-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            bulkActionsDiv.style.display = 'flex';
            selectedCountSpan.textContent = `${count} selezionato${count > 1 ? 'i' : ''}`;
        } else {
            bulkActionsDiv.style.display = 'none';
        }
        
        // Evidenzia le righe selezionate
        postCheckboxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            if (checkbox.checked) {
                row.classList.add('selected');
            } else {
                row.classList.remove('selected');
            }
        });
    }
    
    // Seleziona/deseleziona tutti
    selectAllCheckbox.addEventListener('change', function() {
        postCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });
    
    // Singolo checkbox
    postCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Aggiorna lo stato di "seleziona tutti"
            const allChecked = Array.from(postCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(postCheckboxes).some(cb => cb.checked);
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
            
            updateBulkActions();
        });
    });
    
    // Deseleziona tutti
    deselectAllBtn.addEventListener('click', function() {
        selectAllCheckbox.checked = false;
        postCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateBulkActions();
    });
    
    // Eliminazione multipla con SweetAlert2
    bulkTrashBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.post-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        const count = ids.length;
        
        if (count === 0) return;
        
        Swal.fire({
            title: 'Spostare nel cestino?',
            html: `Vuoi spostare <strong>${count} post</strong> nel cestino?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `🗑️ Sì, sposta ${count} post`,
            cancelButtonText: 'Annulla',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Crea un form e invialo
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'index.php?action=bulk_trash_posts';
                
                ids.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
    
    // Eliminazione singola con SweetAlert2
    document.querySelectorAll('.trash-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.trash-form');
            const postTitle = form.dataset.postTitle;
            
            Swal.fire({
                title: 'Spostare nel cestino?',
                html: `Vuoi spostare il post <strong>"${postTitle}"</strong> nel cestino?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🗑️ Sì, sposta nel cestino',
                cancelButtonText: 'Annulla',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
