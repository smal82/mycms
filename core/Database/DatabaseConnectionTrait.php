<?php
trait DatabaseConnectionTrait {
    public $pdo;
    private $prefix;

    public function initDatabase() {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->prefix = defined('DB_PREFIX') ? DB_PREFIX : '';
        } catch (PDOException $e) {
            die("Errore connessione database: " . $e->getMessage());
        }
    }

    private function table($name) {
        return $this->prefix . $name;
    }
}
