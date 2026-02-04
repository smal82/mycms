<h1>Gestione Pagine</h1>
<?php if (isset($_GET['saved'])): ?>
    <div class="success-message">Pagina salvata con successo!</div>
<?php endif; ?>
<?php if (isset($_GET['trashed'])): ?>
    <div class="success-message">Pagina spostata nel cestino!</div>
<?php endif; ?>
<p>
    <a href="index.php?action=edit_page" class="btn">Nuova Pagina</a>
    <a href="index.php?action=trash_pages" class="btn btn-secondary" style="background-color: #6c757d;">
        🗑️ Cestino Pagine <?php 
        $trashedCount = count($this->db->getTrashedPages());
        if ($trashedCount > 0) echo "($trashedCount)";
        ?>
    </a>
</p>
<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titolo</th>
            <th>Slug</th>
            <th>Autore</th>
            <th>Stato</th>
            <th>Data</th>
            <th>Azioni</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pages as $page): ?>
        <tr>
            <td><?php echo $page['id']; ?></td>
            <td><?php echo htmlspecialchars($page['title']); ?></td>
            <td>
                <?php if ($page['status'] === 'pubblicato'): ?>
                    <a href="../page/<?php echo htmlspecialchars($page['slug']); ?>" 
                       target="_blank" 
                       class="slug-link"
                       title="Visualizza la pagina sul sito">
                        <?php echo htmlspecialchars($page['slug']); ?>
                    </a>
                <?php else: ?>
                    <span class="slug-inactive" title="Pagina non pubblicata">
                        <?php echo htmlspecialchars($page['slug']); ?>
                    </span>
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($page['author_name'] ?? 'N/D'); ?></td>
            <td>
                <?php if ($page['status'] === 'pubblicato'): ?>
                    <span class="badge badge-success">Pubblicato</span>
                <?php else: ?>
                    <span class="badge badge-inactive">Bozza</span>
                <?php endif; ?>
            </td>
            <td><?php echo date('d/m/Y H.i', strtotime($page['created_at'])); ?></td>
            <td>
                <form method="GET" action="index.php" style="display:inline;">
                    <input type="hidden" name="action" value="edit_page">
                    <input type="hidden" name="id" value="<?php echo $page['id']; ?>">
                    <button type="submit" class="btn-edit">Modifica</button>
                </form>
                <form method="POST" action="index.php?action=trash_page" style="display:inline;" class="trash-form" data-page-title="<?php echo htmlspecialchars($page['title']); ?>">
                    <input type="hidden" name="id" value="<?php echo $page['id']; ?>">
                    <button type="button" class="btn-delete trash-btn">Cestina</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if ($totalPagesCount > 1): ?>
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="index.php?action=pages&page=<?php echo $page - 1; ?>" class="btn btn-secondary">← Precedente</a>
    <?php endif; ?>
    
    <span class="pagination-info">
        Pagina <?php echo $page; ?> di <?php echo $totalPagesCount; ?> 
        (<?php echo $totalPages; ?> pagine totali)
    </span>
    
    <?php if ($page < $totalPagesCount): ?>
        <a href="index.php?action=pages&page=<?php echo $page + 1; ?>" class="btn btn-secondary">Successiva →</a>
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestione cestinamento con SweetAlert2
    document.querySelectorAll('.trash-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.trash-form');
            const pageTitle = form.dataset.pageTitle;
            
            Swal.fire({
                title: 'Spostare nel cestino?',
                html: `Vuoi spostare la pagina <strong>"${pageTitle}"</strong> nel cestino?`,
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
