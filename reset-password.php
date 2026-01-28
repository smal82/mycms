<?php
session_start();
require_once 'core/bootstrap.php';

$db = new Database();
$user = new User($db);

$success = null;
$error = null;
$step = isset($_GET['token']) ? 'reset' : 'request';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        // Step 1: Richiesta reset
        $email = trim($_POST['email']);
        $result = $user->requestPasswordReset($email);
        
        if (isset($result['success'])) {
            $success = $result['message'];
        } else {
            $error = $result['error'];
        }
    } elseif (isset($_POST['password'])) {
        // Step 2: Reset password
        $token = $_POST['token'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        
        if ($password !== $password_confirm) {
            $error = 'Le password non corrispondono';
        } elseif (strlen($password) < 6) {
            $error = 'La password deve essere di almeno 6 caratteri';
        } else {
            $result = $user->resetPassword($token, $password);
            
            if (isset($result['success'])) {
                $success = $result['message'];
                $step = 'done';
            } else {
                $error = $result['error'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Il mio CMS</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .reset-box { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 400px; width: 100%; }
        .reset-box h2 { margin-top: 0; color: #2c3e50; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        .btn { width: 100%; padding: 12px; background: #0066cc; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #0052a3; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb; }
        .links { text-align: center; margin-top: 20px; font-size: 14px; }
        .links a { color: #0066cc; text-decoration: none; }
        .links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="reset-box">
        <?php if ($step === 'request'): ?>
            <h2>Reset Password</h2>
            
            <?php if ($success): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (!$success): ?>
                <p>Inserisci la tua email per ricevere il link di reset password.</p>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" required>
                    </div>
                    
                    <button type="submit" class="btn">Invia Link Reset</button>
                </form>
            <?php endif; ?>
            
            <div class="links">
                <a href="/admin/">Torna al login</a> | <a href="/register.php">Registrati</a>
            </div>
            
        <?php elseif ($step === 'reset'): ?>
            <h2>Nuova Password</h2>
            
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                
                <div class="form-group">
                    <label>Nuova Password:</label>
                    <input type="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label>Conferma Password:</label>
                    <input type="password" name="password_confirm" required>
                </div>
                
                <button type="submit" class="btn">Cambia Password</button>
            </form>
            
        <?php else: ?>
            <h2>Password Aggiornata</h2>
            <div class="success">
                <strong>✓ La tua password è stata aggiornata con successo!</strong>
            </div>
            <div class="links">
                <a href="/admin/">Accedi ora</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>