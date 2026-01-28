<?php
/**
 * FILE: /core/User.php
 * Gestione utenti, ruoli e autenticazione
 */

class User {
    private $db;
    private $prefix;
    
    const ROLE_ADMIN = 'amministratore';
    const ROLE_MANAGER = 'gestore';
    const ROLE_REGISTERED = 'registrato';
    
    public function __construct($db) {
        $this->db = $db;
        $this->prefix = defined('DB_PREFIX') ? DB_PREFIX : '';
    }
    
    private function table($name) {
        return $this->prefix . $name;
    }
    
    // === AUTENTICAZIONE ===
    public function login($identifier, $password, $remember = false) {
    // Determina se l'identifier è un'email o un username
    $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
    
    if ($isEmail) {
        // Login con email
        $stmt = $this->db->pdo->prepare("SELECT * FROM " . $this->table('users') . " WHERE email = ? AND is_active = 1");
    } else {
        // Login con nome utente
        $stmt = $this->db->pdo->prepare("SELECT * FROM " . $this->table('users') . " WHERE name = ? AND is_active = 1");
    }
    
    $stmt->execute([$identifier]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['name'];
        
        // Aggiorna ultimo accesso
        $this->updateLastLogin($user['id']);
        
        // Cookie "ricordami"
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + (86400 * 30), '/', '', true, true);
            
            // Salva il token nel database
            $hashedToken = hash('sha256', $token);
            $stmt = $this->db->pdo->prepare("UPDATE " . $this->table('users') . " SET remember_token = ? WHERE id = ?");
            $stmt->execute([$hashedToken, $user['id']]);
        }
        
        return $user;
    }
    
    return false;
}

    
    public function logout() {
    // Cancella il token remember dal database se esiste
    if (isset($_SESSION['user_id'])) {
        $stmt = $this->db->pdo->prepare("UPDATE " . $this->table('users') . " SET remember_token = NULL WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
    }
    
    // Cancella il cookie
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/', '', true, true);
    }
    
    session_destroy();
}

    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return $this->getUserById($_SESSION['user_id']);
    }
    
    public function hasRole($role) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }
    
    public function canAccessAdmin() {
        return $this->hasRole(self::ROLE_ADMIN) || $this->hasRole(self::ROLE_MANAGER);
    }
    
    // === GESTIONE UTENTI ===
    public function getUserById($id) {
        $stmt = $this->db->pdo->prepare("SELECT * FROM " . $this->table('users') . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getUserByEmail($email) {
        $stmt = $this->db->pdo->prepare("SELECT * FROM " . $this->table('users') . " WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function getUserByName($name) {
        $stmt = $this->db->pdo->prepare("SELECT * FROM " . $this->table('users') . " WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }
    
    public function getAllUsers() {
        return $this->db->pdo->query("SELECT id, name, email, role, is_active, created_at, last_login FROM " . $this->table('users') . " ORDER BY created_at DESC")->fetchAll();
    }
    
    public function createUser($data) {
        // Verifica email unica
        if ($this->getUserByEmail($data['email'])) {
            return ['error' => 'Email già registrata'];
        }
        
        // Verifica nome utente unico
        if ($this->getUserByName($data['name'])) {
            return ['error' => 'Nome utente già in uso'];
        }
        
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $activationToken = $data['activation_token'] ?? null;
        $isActive = isset($data['is_active']) ? $data['is_active'] : 0;
        
        $stmt = $this->db->pdo->prepare("INSERT INTO " . $this->table('users') . " (name, email, password, role, is_active, activation_token) VALUES (?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([
            $data['name'],
            $data['email'],
            $hashedPassword,
            $data['role'] ?? self::ROLE_REGISTERED,
            $isActive,
            $activationToken
        ]);
        
        if ($success) {
            return ['success' => true, 'user_id' => $this->db->pdo->lastInsertId()];
        }
        
        return ['error' => 'Errore durante la creazione dell\'utente'];
    }
    
    public function updateUser($id, $data) {
        $fields = [];
        $values = [];
        
        if (isset($data['name'])) {
            // Verifica nome unico
            $existingUser = $this->getUserByName($data['name']);
            if ($existingUser && $existingUser['id'] != $id) {
                return ['error' => 'Nome utente già in uso'];
            }
            $fields[] = "name = ?";
            $values[] = $data['name'];
        }
        
        if (isset($data['email'])) {
            // Verifica email unica
            $existingUser = $this->getUserByEmail($data['email']);
            if ($existingUser && $existingUser['id'] != $id) {
                return ['error' => 'Email già in uso'];
            }
            $fields[] = "email = ?";
            $values[] = $data['email'];
        }
        
        if (isset($data['role'])) {
            $fields[] = "role = ?";
            $values[] = $data['role'];
        }
        
        if (isset($data['is_active'])) {
            $fields[] = "is_active = ?";
            $values[] = $data['is_active'];
        }
        
        if (isset($data['password']) && !empty($data['password'])) {
            $fields[] = "password = ?";
            $values[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        if (empty($fields)) {
            return ['error' => 'Nessun campo da aggiornare'];
        }
        
        $values[] = $id;
        $sql = "UPDATE " . $this->table('users') . " SET " . implode(", ", $fields) . " WHERE id = ?";
        $stmt = $this->db->pdo->prepare($sql);
        
        return $stmt->execute($values) ? ['success' => true] : ['error' => 'Errore durante l\'aggiornamento'];
    }
    
    public function deleteUser($id) {
        // Non permettere di cancellare se stessi
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
            return ['error' => 'Non puoi eliminare il tuo account mentre sei loggato'];
        }
        
        $stmt = $this->db->pdo->prepare("DELETE FROM " . $this->table('users') . " WHERE id = ?");
        return $stmt->execute([$id]) ? ['success' => true] : ['error' => 'Errore durante l\'eliminazione'];
    }
    
    private function updateLastLogin($userId) {
        $stmt = $this->db->pdo->prepare("UPDATE " . $this->table('users') . " SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
    }
    
    // === REGISTRAZIONE E ATTIVAZIONE ===
    public function register($name, $email, $password) {
        $activationToken = bin2hex(random_bytes(32));
        
        $result = $this->createUser([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => self::ROLE_REGISTERED,
            'is_active' => 0,
            'activation_token' => $activationToken
        ]);
        
        if (isset($result['success'])) {
            // Invia email di attivazione
            $this->sendActivationEmail($email, $name, $activationToken);
            return ['success' => true, 'message' => 'Registrazione completata. Controlla la tua email per attivare l\'account.'];
        }
        
        return $result;
    }
    
    public function activateAccount($token) {
        $stmt = $this->db->pdo->prepare("SELECT id FROM " . $this->table('users') . " WHERE activation_token = ? AND is_active = 0");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if ($user) {
            $stmt = $this->db->pdo->prepare("UPDATE " . $this->table('users') . " SET is_active = 1, activation_token = NULL WHERE id = ?");
            $stmt->execute([$user['id']]);
            return ['success' => true, 'message' => 'Account attivato con successo!'];
        }
        
        return ['error' => 'Token di attivazione non valido o già utilizzato'];
    }
    
    private function sendActivationEmail($email, $name, $token) {
        $activationUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/activate.php?token=' . $token;
        
        $subject = 'Attiva il tuo account';
        $message = "Ciao $name,\n\n";
        $message .= "Grazie per esserti registrato!\n\n";
        $message .= "Clicca sul link seguente per attivare il tuo account:\n";
        $message .= $activationUrl . "\n\n";
        $message .= "Se non hai richiesto questa registrazione, ignora questa email.\n\n";
        $message .= "Saluti,\nIl team di " . $_SERVER['HTTP_HOST'];
        
        $headers = "From: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n";
        $headers .= "Reply-To: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // In produzione usa una libreria email professionale come PHPMailer
        mail($email, $subject, $message, $headers);
    }
    
    // === PASSWORD RESET ===
    public function requestPasswordReset($email) {
        $user = $this->getUserByEmail($email);
        if (!$user) {
            return ['error' => 'Email non trovata'];
        }
        
        $resetToken = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $this->db->pdo->prepare("UPDATE " . $this->table('users') . " SET reset_token = ?, reset_token_expiry = ? WHERE id = ?");
        $stmt->execute([$resetToken, $expiry, $user['id']]);
        
        $this->sendPasswordResetEmail($user['email'], $user['name'], $resetToken);
        
        return ['success' => true, 'message' => 'Email di reset password inviata'];
    }
    
    public function resetPassword($token, $newPassword) {
        $stmt = $this->db->pdo->prepare("SELECT id FROM " . $this->table('users') . " WHERE reset_token = ? AND reset_token_expiry > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if ($user) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->db->pdo->prepare("UPDATE " . $this->table('users') . " SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
            $stmt->execute([$hashedPassword, $user['id']]);
            return ['success' => true, 'message' => 'Password aggiornata con successo'];
        }
        
        return ['error' => 'Token non valido o scaduto'];
    }
    
    private function sendPasswordResetEmail($email, $name, $token) {
        $resetUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/reset-password.php?token=' . $token;
        
        $subject = 'Reset della password';
        $message = "Ciao $name,\n\n";
        $message .= "Hai richiesto il reset della password.\n\n";
        $message .= "Clicca sul link seguente per reimpostare la password:\n";
        $message .= $resetUrl . "\n\n";
        $message .= "Questo link è valido per 1 ora.\n\n";
        $message .= "Se non hai richiesto il reset, ignora questa email.\n\n";
        $message .= "Saluti,\nIl team di " . $_SERVER['HTTP_HOST'];
        
        $headers = "From: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n";
        
        mail($email, $subject, $message, $headers);
    }
}
?>