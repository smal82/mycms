<h1>Gestione Plugin</h1>
<table class="admin-table">
    <thead>
        <tr>
            <th>Nome Plugin</th>
            <th>Stato</th>
            <th>Azioni</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($availablePlugins as $plugin): ?>
        <tr>
            <td><?php echo htmlspecialchars($plugin); ?></td>
            <td>
                <?php if (in_array($plugin, $activePlugins)): ?>
                    <span class="badge badge-success">Attivo</span>
                <?php else: ?>
                    <span class="badge badge-inactive">Inattivo</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if (in_array($plugin, $activePlugins)): ?>
                    <!-- Plugin attivo: mostra solo Disattiva -->
                    <form method="POST" action="index.php?action=toggle_plugin" style="display:inline;">
                        <input type="hidden" name="plugin" value="<?php echo htmlspecialchars($plugin); ?>">
                        <button type="submit" class="btn">Disattiva</button>
                    </form>
                <?php else: ?>
                    <!-- Plugin disattivato: mostra Attiva ed Elimina -->
                    <form method="POST" action="index.php?action=toggle_plugin" style="display:inline;">
                        <input type="hidden" name="plugin" value="<?php echo htmlspecialchars($plugin); ?>">
                        <button type="submit" class="btn">Attiva</button>
                    </form>
                    <form method="POST" action="index.php?action=delete_plugin" style="display:inline; margin-left: 5px;" onsubmit="return confirm('Sei sicuro di voler eliminare definitivamente il plugin \'<?php echo htmlspecialchars($plugin); ?>\'? Questa azione non può essere annullata.');">
                        <input type="hidden" name="plugin" value="<?php echo htmlspecialchars($plugin); ?>">
                        <button type="submit" class="btn btn-danger" style="background-color: #dc3545; color: white;">Elimina</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
