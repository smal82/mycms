<?php
/**
 * FILE: /admin/analytics-installer.php
 * Gestione tabelle analytics con prefisso dinamico
 */

require_once __DIR__ . '/../core/bootstrap.php';

class AnalyticsInstaller {
    private $db;
    private $pdo;
    private $prefix;
    
    public function __construct() {
        $this->db = new Database();
        $this->pdo = $this->db->pdo;
        $this->prefix = DB_PREFIX;
    }
    
    /**
     * Crea la tabella page_visits
     */
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->prefix}page_visits` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `page_url` varchar(500) NOT NULL,
            `page_title` varchar(255) DEFAULT NULL,
            `referrer` varchar(500) DEFAULT NULL,
            `visit_date` date NOT NULL,
            `visit_time` datetime NOT NULL,
            `screen_width` int(11) DEFAULT NULL,
            `screen_height` int(11) DEFAULT NULL,
            `ip_address` varchar(45) DEFAULT NULL,
            `user_agent` text,
            PRIMARY KEY (`id`),
            KEY `idx_visit_date` (`visit_date`),
            KEY `idx_page_url` (`page_url`(191)),
            KEY `idx_date_url` (`visit_date`, `page_url`(191))
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        try {
            $this->pdo->exec($sql);
            return ['success' => true, 'message' => 'Tabella analytics creata con successo'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Verifica se la tabella esiste
     */
    public function tableExists() {
        try {
            $stmt = $this->pdo->prepare("SHOW TABLES LIKE '{$this->prefix}page_visits'");
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Elimina la tabella
     */
    public function dropTable() {
        try {
            $this->pdo->exec("DROP TABLE IF EXISTS `{$this->prefix}page_visits`");
            return ['success' => true, 'message' => 'Tabella eliminata con successo'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Ottieni statistiche sulla tabella
     */
    public function getTableStats() {
        if (!$this->tableExists()) {
            return null;
        }
        
        $stats = [];
        
        // Totale visite
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM `{$this->prefix}page_visits`");
        $stats['total_visits'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Visite oggi
        $stmt = $this->pdo->query("SELECT COUNT(*) as today FROM `{$this->prefix}page_visits` WHERE visit_date = CURDATE()");
        $stats['today_visits'] = $stmt->fetch(PDO::FETCH_ASSOC)['today'];
        
        // Prima visita
        $stmt = $this->pdo->query("SELECT MIN(visit_date) as first_date FROM `{$this->prefix}page_visits`");
        $stats['first_visit'] = $stmt->fetch(PDO::FETCH_ASSOC)['first_date'];
        
        // Ultima visita
        $stmt = $this->pdo->query("SELECT MAX(visit_time) as last_visit FROM `{$this->prefix}page_visits`");
        $stats['last_visit'] = $stmt->fetch(PDO::FETCH_ASSOC)['last_visit'];
        
        return $stats;
    }
}

// Se chiamato direttamente via AJAX
if (basename($_SERVER['PHP_SELF']) === 'analytics-installer.php' && isset($_GET['action'])) {
    $installer = new AnalyticsInstaller();
    
    switch ($_GET['action']) {
        case 'install':
            $result = $installer->createTable();
            break;
        case 'uninstall':
            $result = $installer->dropTable();
            break;
        case 'status':
            $result = [
                'success' => true,
                'exists' => $installer->tableExists(),
                'stats' => $installer->getTableStats()
            ];
            break;
        default:
            $result = ['success' => false, 'message' => 'Azione non valida'];
    }
    
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}
?>
