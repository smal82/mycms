<?php
session_start();
require_once __DIR__ . '/core/bootstrap.php';
require_once BASE_PATH . '/core/Database.php';

header('Content-Type: application/json');

$db = new Database();
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'login':
        handleLogin($db);
        break;
        
    case 'register':
        handleRegister($db);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Azione non valida']);
}

function handleLogin($db) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) && $_POST['remember'] == 1;
    
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email e password sono obbligatori']);
        return;
    }
    
    // Verifica credenziali
    $user = $db->getUserByEmail($email);
    
    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Credenziali non valide']);
        return;
    }
    
    // Verifica se l'account è attivo
    if (!$user['is_active']) {
        echo json_encode(['success' => false, 'message' => 'Account non attivo. Controlla la tua email.']);
        return;
    }
    
    // Imposta sessione
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    
    // Aggiorna last_login
    $prefix = DB_PREFIX;
    $stmt = $db->pdo->prepare("UPDATE {$prefix}users SET last_login = NOW() WHERE id = ?");
    $stmt->execute([$user['id']]);
    
    // Cookie "ricordami"
    if ($remember) {
        $token = bin2hex(random_bytes(32));
        setcookie('remember_token', $token, time() + (86400 * 30), '/', '', true, true);
        
        // Salva il token nel database
        $hashedToken = hash('sha256', $token);
        $stmt = $db->pdo->prepare("UPDATE {$prefix}users SET remember_token = ? WHERE id = ?");
        $stmt->execute([$hashedToken, $user['id']]);
    }
    
    echo json_encode(['success' => true, 'message' => 'Login effettuato con successo!']);
}

function handleRegister($db) {
    // Controlla se le registrazioni sono attive
    $registrazioniAttive = $db->getSetting('registrazioni_attive', '0') === '1';
    
    if (!$registrazioniAttive) {
        echo json_encode(['success' => false, 'message' => 'Le registrazioni sono temporaneamente disabilitate']);
        return;
    }
    
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validazione
    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Tutti i campi sono obbligatori']);
        return;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email non valida']);
        return;
    }
    
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'La password deve essere di almeno 6 caratteri']);
        return;
    }
    
    // Controlla se l'email esiste già
    $existingUser = $db->getUserByEmail($email);
    if ($existingUser) {
        echo json_encode(['success' => false, 'message' => 'Email già registrata']);
        return;
    }
    
    // Controlla se il nome utente esiste già
    $prefix = DB_PREFIX;
    $stmt = $db->pdo->prepare("SELECT id FROM {$prefix}users WHERE name = ?");
    $stmt->execute([$name]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Nome utente già esistente']);
        return;
    }
    
    // Crea nuovo utente
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $activationToken = bin2hex(random_bytes(16));
    
    // Determina se richiede attivazione
    $requiresActivation = $db->getSetting('require_email_activation', '0') === '1';
    $isActive = $requiresActivation ? 0 : 1;
    
    try {
        $stmt = $db->pdo->prepare("
            INSERT INTO {$prefix}users (name, email, password, role, is_active, activation_token, created_at)
            VALUES (?, ?, ?, 'registrato', ?, ?, NOW())
        ");
        
        $stmt->execute([$name, $email, $hashedPassword, $isActive, $requiresActivation ? $activationToken : null]);
        
        if ($requiresActivation) {
            // TODO: Invia email di attivazione
            // sendActivationEmail($email, $name, $activationToken);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Registrazione completata! Controlla la tua email per attivare l\'account.'
            ]);
        } else {
            echo json_encode([
                'success' => true, 
                'message' => 'Registrazione completata! Puoi effettuare il login.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Errore durante la registrazione: ' . $e->getMessage()]);
    }
}
