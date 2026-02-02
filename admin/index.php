<?php
session_start();

// Error reporting per debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../core/bootstrap.php';

// Aggiungi queste 3 righe per l'auto-login
require_once BASE_PATH . '/core/auto-login.php';
$db = new Database();
checkRememberMe($db);

$admin = new Admin();
$admin->run();
?>
