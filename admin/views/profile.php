<h1>Il Mio Profilo</h1>

<?php if (isset($_GET['saved'])): ?>
    <div class="success-message">Profilo aggiornato con successo!</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="error" style="background:#f8d7da;color:#721c24;padding:15px;border-radius:4px;margin-bottom:20px;">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>

<div class="two-column-layout">
    <div class="column">
        <h2>Informazioni Account</h2>
        <form method="POST" action="index.php?action=update_profile">
            <div class="form-group">
                <label>Nome:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($currentUser['name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($currentUser['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Ruolo:</label>
                <input type="text" value="<?php echo ucfirst($currentUser['role']); ?>" disabled>
                <small>Il ruolo può essere modificato solo da un amministratore</small>
            </div>
            
            <button type="submit" class="btn">Aggiorna Informazioni</button>
        </form>
    </div>
    
    <div class="column">
        <h2>Cambia Password</h2>
        <form method="POST" action="index.php?action=update_profile">
            <div class="form-group">
                <label>Nuova Password:</label>
                <input type="password" name="password" placeholder="Inserisci nuova password">
            </div>
            
            <div class="form-group">
                <label>Conferma Password:</label>
                <input type="password" name="password_confirm" placeholder="Conferma nuova password">
            </div>
            
            <button type="submit" class="btn">Aggiorna Password</button>
        </form>
        
        <hr style="margin:30px 0;">
        
        <h2>Statistiche Account</h2>
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-label">Membro dal</span>
                <span class="stat-value" style="font-size:14px;"><?php echo date('d/m/Y', strtotime($currentUser['created_at'])); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Ultimo accesso</span>
                <span class="stat-value" style="font-size:14px;"><?php echo $currentUser['last_login'] ? date('d/m/Y H:i', strtotime($currentUser['last_login'])) : 'Primo accesso'; ?></span>
            </div>
        </div>
    </div>
</div>