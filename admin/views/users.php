<h1>Gestione Utenti</h1>
<?php if (isset($_GET['saved'])): ?>
    <div class="success-message">Utente salvato con successo!</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="success-message">Utente eliminato con successo!</div>
<?php endif; ?>
<p><a href="index.php?action=edit_user" class="btn">Nuovo Utente</a></p>
<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Ruolo</th>
            <th>Stato</th>
            <th>Ultimo accesso</th>
            <th>Azioni</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td>
                <?php 
                $roleLabels = [
                    'amministratore' => '<span class="badge badge-success">Amministratore</span>',
                    'gestore' => '<span class="badge" style="background:#17a2b8;color:white;">Gestore</span>',
                    'registrato' => '<span class="badge badge-inactive">Registrato</span>'
                ];
                echo $roleLabels[$user['role']] ?? $user['role'];
                ?>
            </td>
            <td>
                <?php if ($user['is_active']): ?>
                    <span class="badge badge-success">Attivo</span>
                <?php else: ?>
                    <span class="badge badge-inactive">Non attivo</span>
                <?php endif; ?>
            </td>
            <td><?php echo $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Mai'; ?></td>
            <td>
                <form method="GET" action="index.php" style="display:inline;">
                    <input type="hidden" name="action" value="edit_user">
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                    <button type="submit" class="btn-edit">Modifica</button>
                </form>
                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                <form method="POST" action="index.php?action=delete_user" style="display:inline;" onsubmit="return confirm('Sicuro di voler eliminare questo utente?');">
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                    <button type="submit" class="btn-delete">Elimina</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>