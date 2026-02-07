<?php
session_start();
require_once 'core/bootstrap.php';

$db = new Database();
$user = new User($db);

$success = null;
$error = null;

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $result = $user->activateAccount($token);
    
    if (isset($result['success'])) {
        $success = $result['message'];
    } else {
        $error = $result['error'];
    }
} else {
    $error = 'Token di attivazione mancante';
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attivazione Account - Il mio CMS</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .activation-box { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 500px; width: 100%; text-align: center; }
        .activation-box h2 { margin-top: 0; color: #2c3e50; }
        .success { background: #d4edda; color: #155724; padding: 20px; border-radius: 4px; margin: 20px 0; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; padding: 20px; border-radius: 4px; margin: 20px 0; border: 1px solid #f5c6cb; }
        .btn { display: inline-block; padding: 12px 30px; background: #0066cc; color: white; text-decoration: none; border-radius: 4px; margin: 10px 5px; }
        .btn:hover { background: #0052a3; }
    </style>
</head>
<body>
    <div class="activation-box">
        <h2>Attivazione Account</h2>
        
        <?php if ($success): ?>
            <div class="success">
                <strong>✓ <?php echo htmlspecialchars($success); ?></strong>
                <p>Ora puoi accedere al tuo account.</p>
            </div>
            <a href="/admin/" class="btn">Accedi ora</a>
        <?php else: ?>
            <div class="error">
                <strong>✗ <?php echo htmlspecialchars($error); ?></strong>
            </div>
            <a href="/register.php" class="btn">Registrati di nuovo</a>
            <a href="/" class="btn" style="background:#666;">Torna alla home</a>
        <?php endif; ?>
    </div>
</body>
</html>