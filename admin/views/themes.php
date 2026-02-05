<h1>Gestione Temi</h1>
<?php if (isset($_GET['changed'])): ?>
    <div class="success-message">Tema cambiato con successo!</div>
<?php endif; ?>
<form method="POST" action="index.php?action=change_theme">
    <div class="form-group">
        <label>Tema Attivo:</label>
        <select name="theme" onchange="this.form.submit()">
            <?php foreach ($availableThemes as $theme): ?>
                <option value="<?php echo htmlspecialchars($theme); ?>" <?php echo $theme === $currentTheme ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($theme); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</form>
<div class="themes-list">
    <?php foreach ($availableThemes as $theme): ?>
        <div class="theme-box <?php echo $theme === $currentTheme ? 'active' : ''; ?>">
            <h3><?php echo htmlspecialchars($theme); ?></h3>
            <?php if ($theme === $currentTheme): ?>
                <span class="badge">Attivo</span>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>