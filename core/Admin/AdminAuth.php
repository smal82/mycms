<?php

trait AdminAuth {

    private function handleLogin() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $identifier = trim($_POST['identifier'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $remember = isset($_POST['remember']) && $_POST['remember'] == 1;

        $loggedUser = $this->user->login($identifier, $password, $remember);

        if ($loggedUser) {
            if ($this->user->canAccessAdmin()) {
                header('Location: index.php?action=dashboard');
                exit;
            } else {
                $this->user->logout();
                $error = 'Non hai i permessi per accedere al pannello amministrazione.';
            }
        } else {
            $error = 'Credenziali non corrette.';
        }
    }

    $this->showLogin($error ?? null);
}


    private function handleLogout() {
        $this->user->logout();
        header('Location: index.php?action=login');
        exit;
    }

    private function showLogin($error = null) {
        include ADMIN_PATH . '/views/login.php';
    }
}
