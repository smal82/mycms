<?php
class Widget_auth {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function render($config) {
        // Se l'utente è già loggato, mostra un messaggio di benvenuto
        if (isset($_SESSION['user_id'])) {
            $userName = $_SESSION['user_name'] ?? 'Utente';
            $userRole = $_SESSION['user_role'] ?? 'registrato';
            ?>
            <div class="widget widget-auth logged-in">
                <?php if (!empty($config['title'])): ?>
                    <h3 class="widget-title"><?php echo esc_html($config['title']); ?></h3>
                <?php endif; ?>
                
                <div class="auth-user-info">
                    <p>Ciao, <strong><?php echo esc_html($userName); ?></strong>!</p>
                    <?php if ($userRole === 'amministratore' || $userRole === 'gestore'): ?>
                        <a href="/admin/" class="btn btn-primary" target="_blank">Dashboard</a>
                    <?php endif; ?>
                    <a href="/logout.php" class="btn btn-secondary">Logout</a>
                </div>
            </div>
            <?php
            return;
        }
        
        // Controlla se le registrazioni sono attive
        $registrazioniAttive = $this->db->getSetting('registrazioni_attive', '0') === '1';
        
        ?>
        <div class="widget widget-auth">
            <?php if (!empty($config['title'])): ?>
                <h3 class="widget-title"><?php echo esc_html($config['title']); ?></h3>
            <?php endif; ?>
            
            <div class="auth-container">
                <!-- Tab Navigation -->
                <div class="auth-tabs">
                    <button type="button" class="auth-tab active" data-tab="login">Login</button>
                    <?php if ($registrazioniAttive): ?>
                        <button type="button" class="auth-tab" data-tab="register">Registrati</button>
                    <?php endif; ?>
                </div>
                
                <!-- Login Form -->
                <div class="auth-form-container active" id="login-form">
                    <form class="auth-form" id="form-login" onsubmit="return false;">
                        <div class="form-group">
                            <label for="login-email">Email</label>
                            <input type="email" id="login-email" name="email" required autocomplete="email">
                        </div>
                        <div class="form-group">
                            <label for="login-password">Password</label>
                            <input type="password" id="login-password" name="password" required autocomplete="current-password">
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="remember" id="login-remember" value="1"> Ricordami
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-auth-submit">Accedi</button>
                        <div class="auth-message" id="login-message"></div>
                    </form>
                </div>
                
                <?php if ($registrazioniAttive): ?>
                <!-- Register Form -->
                <div class="auth-form-container" id="register-form">
                    <form class="auth-form" id="form-register" onsubmit="return false;">
                        <div class="form-group">
                            <label for="register-name">Nome utente</label>
                            <input type="text" id="register-name" name="name" required autocomplete="username">
                        </div>
                        <div class="form-group">
                            <label for="register-email">Email</label>
                            <input type="email" id="register-email" name="email" required autocomplete="email">
                        </div>
                        <div class="form-group">
                            <label for="register-password">Password</label>
                            <input type="password" id="register-password" name="password" required autocomplete="new-password" minlength="6">
                        </div>
                        <div class="form-group">
                            <label for="register-password-confirm">Conferma Password</label>
                            <input type="password" id="register-password-confirm" name="password_confirm" required autocomplete="new-password" minlength="6">
                        </div>
                        <button type="submit" class="btn btn-primary btn-auth-submit">Registrati</button>
                        <div class="auth-message" id="register-message"></div>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- CSS Inline -->
        <style>
        .widget-auth {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .widget-auth.logged-in {
            text-align: center;
        }
        
        .auth-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e5e5e5;
        }
        
        .auth-tab {
            flex: 1;
            padding: 12px;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            color: #666;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
        }
        
        .auth-tab:hover {
            color: #333;
        }
        
        .auth-tab.active {
            color: #007bff;
            border-bottom-color: #007bff;
        }
        
        .auth-form-container {
            display: none;
        }
        
        .auth-form-container.active {
            display: block;
            animation: fadeIn 0.3s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .auth-form .form-group {
            margin-bottom: 15px;
        }
        
        .auth-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }
        
        .auth-form input[type="text"],
        .auth-form input[type="email"],
        .auth-form input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        
        .auth-form input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }
        
        .auth-form input[type="checkbox"] {
            margin-right: 5px;
        }
        
        .btn-auth-submit {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-auth-submit:hover {
            background: #0056b3;
        }
        
        .btn-auth-submit:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        
        .auth-message {
            margin-top: 15px;
            padding: 12px;
            border-radius: 4px;
            font-size: 14px;
            display: none;
        }
        
        .auth-message:not(:empty) {
            display: block;
        }
        
        .auth-message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .auth-message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .auth-user-info {
            text-align: center;
        }
        
        .auth-user-info p {
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .auth-user-info .btn {
            display: inline-block;
            margin: 5px;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .auth-user-info .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .auth-user-info .btn-primary:hover {
            background: #0056b3;
        }
        
        .auth-user-info .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .auth-user-info .btn-secondary:hover {
            background: #545b62;
        }
        </style>
        
        <!-- JavaScript per gestione AJAX -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verifica che jQuery sia caricato, altrimenti usa vanilla JS
            var useJQuery = typeof jQuery !== 'undefined';
            
            // Gestione tab
            var tabs = document.querySelectorAll('.auth-tab');
            tabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    var targetTab = this.getAttribute('data-tab');
                    
                    // Rimuovi active da tutti i tab
                    document.querySelectorAll('.auth-tab').forEach(function(t) {
                        t.classList.remove('active');
                    });
                    document.querySelectorAll('.auth-form-container').forEach(function(c) {
                        c.classList.remove('active');
                    });
                    
                    // Attiva il tab selezionato
                    this.classList.add('active');
                    document.getElementById(targetTab + '-form').classList.add('active');
                });
            });
            
            // Login AJAX
            var loginForm = document.getElementById('form-login');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    var messageEl = document.getElementById('login-message');
                    var button = this.querySelector('button[type="submit"]');
                    var email = document.getElementById('login-email').value;
                    var password = document.getElementById('login-password').value;
                    var remember = document.getElementById('login-remember').checked ? 1 : 0;
                    
                    button.disabled = true;
                    button.textContent = 'Accesso in corso...';
                    messageEl.className = 'auth-message';
                    messageEl.textContent = '';
                    
                    var formData = new FormData();
                    formData.append('action', 'login');
                    formData.append('email', email);
                    formData.append('password', password);
                    formData.append('remember', remember);
                    
                    fetch('/auth-handler.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        if (data.success) {
                            messageEl.className = 'auth-message success';
                            messageEl.textContent = data.message || 'Login effettuato! Ricarico la pagina...';
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            messageEl.className = 'auth-message error';
                            messageEl.textContent = data.message || 'Errore durante il login';
                            button.disabled = false;
                            button.textContent = 'Accedi';
                        }
                    })
                    .catch(function(error) {
                        console.error('Errore:', error);
                        messageEl.className = 'auth-message error';
                        messageEl.textContent = 'Errore di connessione. Riprova.';
                        button.disabled = false;
                        button.textContent = 'Accedi';
                    });
                });
            }
            
            // Registrazione AJAX
            var registerForm = document.getElementById('form-register');
            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    var messageEl = document.getElementById('register-message');
                    var button = this.querySelector('button[type="submit"]');
                    var name = document.getElementById('register-name').value;
                    var email = document.getElementById('register-email').value;
                    var password = document.getElementById('register-password').value;
                    var passwordConfirm = document.getElementById('register-password-confirm').value;
                    
                    // Validazione password
                    if (password !== passwordConfirm) {
                        messageEl.className = 'auth-message error';
                        messageEl.textContent = 'Le password non corrispondono';
                        return;
                    }
                    
                    if (password.length < 6) {
                        messageEl.className = 'auth-message error';
                        messageEl.textContent = 'La password deve essere di almeno 6 caratteri';
                        return;
                    }
                    
                    button.disabled = true;
                    button.textContent = 'Registrazione in corso...';
                    messageEl.className = 'auth-message';
                    messageEl.textContent = '';
                    
                    var formData = new FormData();
                    formData.append('action', 'register');
                    formData.append('name', name);
                    formData.append('email', email);
                    formData.append('password', password);
                    
                    fetch('/auth-handler.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        if (data.success) {
                            messageEl.className = 'auth-message success';
                            messageEl.textContent = data.message || 'Registrazione completata!';
                            registerForm.reset();
                            
                            // Passa al tab login dopo 2 secondi
                            setTimeout(function() {
                                document.querySelector('.auth-tab[data-tab="login"]').click();
                                messageEl.textContent = '';
                            }, 2000);
                        } else {
                            messageEl.className = 'auth-message error';
                            messageEl.textContent = data.message || 'Errore durante la registrazione';
                        }
                        button.disabled = false;
                        button.textContent = 'Registrati';
                    })
                    .catch(function(error) {
                        console.error('Errore:', error);
                        messageEl.className = 'auth-message error';
                        messageEl.textContent = 'Errore di connessione. Riprova.';
                        button.disabled = false;
                        button.textContent = 'Registrati';
                    });
                });
            }
        });
        </script>
        <?php
    }
}
