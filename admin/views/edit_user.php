<h1><?php echo $editUser ? 'Modifica Utente' : 'Nuovo Utente'; ?></h1>

<form method="POST" action="index.php?action=save_user">
    <?php if ($editUser): ?>
        <input type="hidden" name="id" value="<?php echo $editUser['id']; ?>">
    <?php endif; ?>
    
    <div class="form-group">
        <label>Nome:</label>
        <input type="text" name="name" value="<?php echo $editUser ? htmlspecialchars($editUser['name']) : ''; ?>" required>
    </div>
    
    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $editUser ? htmlspecialchars($editUser['email']) : ''; ?>" required>
    </div>
    
    <div class="form-group">
        <label>Password:</label>
        <input type="password" name="password" placeholder="<?php echo $editUser ? 'Lascia vuoto per non modificare' : 'Password'; ?>" <?php echo $editUser ? '' : 'required'; ?>>
        <?php if ($editUser): ?>
            <small>Lascia vuoto per mantenere la password attuale</small>
        <?php endif; ?>
    </div>
    
    <div class="form-group">
        <label>Ruolo:</label>
        <select name="role" required>
            <option value="registrato" <?php echo ($editUser && $editUser['role'] === 'registrato') ? 'selected' : ''; ?>>Registrato</option>
            <option value="gestore" <?php echo ($editUser && $editUser['role'] === 'gestore') ? 'selected' : ''; ?>>Gestore</option>
            <option value="amministratore" <?php echo ($editUser && $editUser['role'] === 'amministratore') ? 'selected' : ''; ?>>Amministratore</option>
        </select>
    </div>
    
    <div class="form-group">
        <label>
            <input type="checkbox" name="is_active" value="1" <?php echo ($editUser && $editUser['is_active']) || !$editUser ? 'checked' : ''; ?>>
            Account attivo
        </label>
    </div>
    
    <button type="submit" class="btn">Salva Utente</button>
    <a href="index.php?action=users" class="btn btn-secondary">Annulla</a>
</form>