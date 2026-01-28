<div class="admin-content">
    <div class="page-header">
        <h1>Custom Post Types</h1>
        <a href="index.php?action=custom_post_types_edit" class="btn">+ Nuovo Tipo</a>
    </div>
    
    <?php if (isset($_GET['saved'])): ?>
        <div class="success-message">Custom Post Type salvato con successo!</div>
    <?php endif; ?>
    
    <?php if (isset($_GET['deleted'])): ?>
        <div class="success-message">Custom Post Type eliminato con successo!</div>
    <?php endif; ?>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>Icona</th>
                <th>Nome</th>
                <th>Label</th>
                <th>Slug</th>
                <th>Posizione Menu</th>
                <th>Supporta</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($customPostTypes)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">
                        Nessun Custom Post Type registrato
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($customPostTypes as $cpt): 
                    $supports = json_decode($cpt['supports'], true) ?? [];
                ?>
                <tr>
                    <td>
                        <span style="font-size: 24px;">
                            <?= htmlspecialchars($cpt['icon']) ?>
                        </span>
                    </td>
                    <td><strong><?= htmlspecialchars($cpt['name']) ?></strong></td>
                    <td>
                        <?= htmlspecialchars($cpt['singular_label']) ?> / 
                        <?= htmlspecialchars($cpt['plural_label']) ?>
                    </td>
                    <td><code><?= htmlspecialchars($cpt['slug']) ?></code></td>
                    <td><?= $cpt['menu_position'] ?></td>
                    <td>
                        <small><?= implode(', ', $supports) ?></small>
                    </td>
                    <td>
                        <button type="button" class="btn" 
                                onclick="location.href='index.php?action=custom_posts_list&type=<?= $cpt['name'] ?>'">
                            📋 Contenuti
                        </button>
                        <button type="button" class="btn-edit" 
                                onclick="location.href='index.php?action=custom_post_types_edit&id=<?= $cpt['id'] ?>'">
                            ✏️ Modifica
                        </button>
                        <form method="POST" action="index.php?action=delete_custom_post_type" style="display:inline;"
                              onsubmit="return confirm('Eliminare questo tipo? Tutti i contenuti associati verranno eliminati!')">
                            <input type="hidden" name="id" value="<?= $cpt['id'] ?>">
                            <button type="submit" class="btn-delete">🗑️ Elimina</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
code {
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 12px;
    color: #e83e8c;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.page-header h1 {
    margin: 0;
}

.btn {
    white-space: nowrap;
}
</style>
