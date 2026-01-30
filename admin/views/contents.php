<h1>Gestione Contenuti</h1>
<?php if (isset($_GET['saved'])): ?>
    <div class="success-message">Contenuto salvato con successo!</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="success-message">Contenuto eliminato con successo!</div>
<?php endif; ?>
<p><a href="index.php?action=edit_content" class="btn">Nuovo Contenuto</a></p>
<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Titolo</th>
            <th>Slug</th>
            <th>Data</th>
            <th>Azioni</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($contents as $content): ?>
        <tr>
            <td><?php echo $content['id']; ?></td>
            <td><?php echo htmlspecialchars($content['type']); ?></td>
            <td><?php echo htmlspecialchars($content['title']); ?></td>
            <td><?php echo htmlspecialchars($content['slug']); ?></td>
            <td><?php echo $content['created_at']; ?></td>
            <td>
                <form method="GET" action="index.php" style="display:inline;">
                    <input type="hidden" name="action" value="edit_content">
                    <input type="hidden" name="id" value="<?php echo $content['id']; ?>">
                    <button type="submit" class="btn-edit">Modifica</button>
                </form>
                <form method="POST" action="index.php?action=delete_content" style="display:inline;" onsubmit="return confirm('Sicuro di voler eliminare?');">
                    <input type="hidden" name="id" value="<?php echo $content['id']; ?>">
                    <button type="submit" class="btn-delete">Elimina</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>