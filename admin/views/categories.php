<h1>Gestione Categorie</h1>
<?php if (isset($_GET['saved'])): ?>
    <div class="success-message">Categoria salvata con successo!</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="success-message">Categoria eliminata con successo!</div>
<?php endif; ?>

<div class="two-column-layout">
    <div class="column">
        <h2><?php echo isset($_GET['edit']) ? 'Modifica Categoria' : 'Aggiungi Nuova Categoria'; ?></h2>
        <?php 
        $editCategory = null;
        if (isset($_GET['edit'])) {
            $editCategory = $this->db->getCategoryById($_GET['edit']);
        }
        ?>
        <form method="POST" action="index.php?action=save_category">
            <?php if ($editCategory): ?>
                <input type="hidden" name="id" value="<?php echo $editCategory['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Nome:</label>
                <input type="text" name="name" id="cat-name" value="<?php echo $editCategory ? htmlspecialchars($editCategory['name']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Slug:</label>
                <input type="text" name="slug" id="cat-slug" value="<?php echo $editCategory ? htmlspecialchars($editCategory['slug']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Descrizione:</label>
                <textarea name="description" rows="3"><?php echo $editCategory ? htmlspecialchars($editCategory['description']) : ''; ?></textarea>
            </div>
            
            <button type="submit" class="btn"><?php echo $editCategory ? 'Aggiorna' : 'Aggiungi'; ?> Categoria</button>
            <?php if ($editCategory): ?>
                <a href="index.php?action=categories" class="btn btn-secondary">Annulla</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="column">
        <h2>Categorie Esistenti</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Slug</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                    <td><?php echo htmlspecialchars($category['slug']); ?></td>
                    <td>
                        <a href="index.php?action=categories&edit=<?php echo $category['id']; ?>" class="btn-edit">Modifica</a>
                        <form method="POST" action="index.php?action=delete_category" style="display:inline;" onsubmit="return confirm('Eliminare questa categoria?');">
                            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                            <button type="submit" class="btn-delete">Elimina</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#cat-name').on('input', function() {
    if (!$('#cat-slug').data('manual')) {
        var slug = $(this).val()
            .toLowerCase()
            .replace(/[àáâãäå]/g, 'a')
            .replace(/[èéêë]/g, 'e')
            .replace(/[ìíîï]/g, 'i')
            .replace(/[òóôõö]/g, 'o')
            .replace(/[ùúûü]/g, 'u')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        $('#cat-slug').val(slug);
    }
});

$('#cat-slug').on('input', function() {
    $(this).data('manual', true);
});
</script>