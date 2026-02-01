<?php
// File: /core/auto-login.php
// Verifica automatica del cookie "ricordami"

function checkRememberMe($db) {
    // Se l'utente è già loggato, non fare nulla
    if (isset($_SESSION['user_id'])) {
        return;
    }
    
    // Verifica se esiste il cookie remember_token
    if (!isset($_COOKIE['remember_token'])) {
        return;
    }
    
    $token = $_COOKIE['remember_token'];
    $hashedToken = hash('sha256', $token);
    
    // Cerca l'utente con questo token
    $prefix = DB_PREFIX;
    $stmt = $db->pdo->prepare("SELECT * FROM {$prefix}users WHERE remember_token = ? AND is_active = 1");
    $stmt->execute([$hashedToken]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Token valido, effettua il login automatico
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        
        // Aggiorna last_login
        $stmt = $db->pdo->prepare("UPDATE {$prefix}users SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$user['id']]);
        
        // Rigenera un nuovo token per sicurezza
        $newToken = bin2hex(random_bytes(32));
        setcookie('remember_token', $newToken, time() + (86400 * 30), '/', '', true, true);
        
        $newHashedToken = hash('sha256', $newToken);
        $stmt = $db->pdo->prepare("UPDATE {$prefix}users SET remember_token = ? WHERE id = ?");
        $stmt->execute([$newHashedToken, $user['id']]);
    } else {
        // Token non valido, elimina il cookie
        setcookie('remember_token', '', time() - 3600, '/', '', true, true);
    }
}
