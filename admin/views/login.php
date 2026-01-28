<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin CMS</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 300px; }
        .login-box h2 { margin-top: 0; }
        .login-box input[type="text"], .login-box input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .login-box button { width: 100%; padding: 10px; background: #0066cc; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .login-box button:hover { background: #0052a3; }
        .error { color: red; margin-bottom: 10px; background: #f8d7da; padding: 10px; border-radius: 4px; }
        .info { color: #666; font-size: 12px; margin-top: 10px; }
        .info a { color: #0066cc; }
        .remember-me { display: flex; align-items: center; margin: 15px 0; }
        .remember-me input[type="checkbox"] { width: auto; margin: 0 8px 0 0; }
        .remember-me label { margin: 0; font-size: 14px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="index.php?action=login">
            <input type="text" name="identifier" placeholder="Email o Nome Utente" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">Ricordami</label>
            </div>
            
            <button type="submit">Accedi</button>
        </form>
        <div class="info">
            <?php if ($this->db->getSetting('registrazioni_attive', 0) == 1): ?>
    <a href="../register.php">Registrati</a> | 
<?php endif; ?><a href="../reset-password.php">Password dimenticata?</a>
        </div>
    </div>
</body>
</html>
