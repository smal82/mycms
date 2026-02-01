<?php

class Admin {
    use AdminAuth;
    use AdminPostActions;
    use AdminPages;
    use AdminMenuRender;

    public $db;
    public $user;
    private $action;
    private $cms;

    public function __construct() {
        $this->db = new Database();
        $this->user = new User($this->db);
        $this->action = $_GET['action'] ?? 'dashboard';
        $this->cms = $cms ?? new CMS();
    }

    public function run() {
        if ($this->action === 'login') {
            $this->handleLogin();
            return;
        }

        if (!$this->user->isLoggedIn()) {
            $this->showLogin();
            return;
        }

        if (!$this->user->canAccessAdmin()) {
            die('Accesso negato');
        }

        if ($this->action === 'logout') {
            $this->handleLogout();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
        }

        $this->showPage();
    }
}
