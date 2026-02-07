<h1>Gestione Menu</h1>
<?php if (isset($_GET['deleted'])): ?>
    <div class="success-message">Menu eliminato con successo!</div>
<?php endif; ?>
<p><a href="index.php?action=edit_menu" class="btn">Nuovo Menu</a></p>
<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Posizione</th>
            <th>Azioni</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($menus as $menu): ?>
        <tr>
            <td><?php echo $menu['id']; ?></td>
            <td><?php echo htmlspecialchars($menu['name']); ?></td>
            <td><?php echo htmlspecialchars($menu['location'] ?: '-'); ?></td>
            <td>
                <form method="GET" action="index.php" style="display:inline;">
                    <input type="hidden" name="action" value="edit_menu">
                    <input type="hidden" name="id" value="<?php echo $menu['id']; ?>">
                    <button type="submit" class="btn-edit">Modifica</button>
                </form>
                <!--<a href="index.php?action=edit_menu&id=<?php echo $menu['id']; ?>">Modifica</a>-->
                <form method="POST" action="index.php?action=delete_menu" style="display:inline;" onsubmit="return confirm('Sicuro?');">
                    <input type="hidden" name="id" value="<?php echo $menu['id']; ?>">
                    <button type="submit" class="btn-delete">Elimina</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>