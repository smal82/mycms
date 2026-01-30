<?php
/**
 * FILE: /core/Api/Controllers/AnalyticsController.php
 * Controller per gestire le statistiche interne
 */

class AnalyticsController {
    private $db;
    private $pdo;
    private $prefix;
    
    public function __construct($database) {
        $this->db = $database;
        $this->pdo = $this->db->pdo;
        $this->prefix = DB_PREFIX;
    }
    
    /**
     * Traccia una visita alla pagina
     * POST /api/analytics/track
     */
    public function track() {
        try {
            // Leggi i dati JSON dal body
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [
                    'success' => false,
                    'error' => 'Invalid JSON data'
                ];
            }
            
            // Verifica che la tabella esista
            if (!$this->tableExists()) {
                return [
                    'success' => false,
                    'error' => 'Analytics table not installed'
                ];
            }
            
            $stmt = $this->pdo->prepare("
                INSERT INTO `{$this->prefix}page_visits` 
                (page_url, page_title, referrer, visit_date, visit_time, screen_width, screen_height, ip_address, user_agent)
                VALUES (?, ?, ?, CURDATE(), NOW(), ?, ?, ?, ?)
            ");
            
            $pageUrl = $data['page_url'] ?? '';
            // Se page_url è vuoto, usa "/" per indicare la homepage
            if (empty($pageUrl)) {
                $pageUrl = '/';
            }
            
            $stmt->execute([
                $pageUrl,
                $data['page_title'] ?? '',
                $data['referrer'] ?? '',
                $data['screen_width'] ?? 0,
                $data['screen_height'] ?? 0,
                $_SERVER['REMOTE_ADDR'] ?? '',
                $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
            
            return [
                'success' => true,
                'message' => 'Visit tracked'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Recupera le statistiche
     * GET /api/analytics/stats?days=30
     */
    public function stats() {
        try {
            $days = isset($_GET['days']) ? (int)$_GET['days'] : 30;
            
            // Verifica che la tabella esista
            if (!$this->tableExists()) {
                return [
                    'success' => false,
                    'error' => 'Analytics table not installed',
                    'table_exists' => false
                ];
            }
            
            // Visite giornaliere
            $stmt = $this->pdo->prepare("
                SELECT visit_date, COUNT(*) as visits
                FROM `{$this->prefix}page_visits`
                WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                GROUP BY visit_date
                ORDER BY visit_date DESC
            ");
            $stmt->execute([$days]);
            $daily_visits = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Top 20 pagine di oggi
            $stmt = $this->pdo->prepare("
                SELECT page_url, page_title, COUNT(*) as views
                FROM `{$this->prefix}page_visits`
                WHERE visit_date = CURDATE()
                GROUP BY page_url, page_title
                ORDER BY views DESC
                LIMIT 20
            ");
            $stmt->execute();
            $top_pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Visite di oggi per summary
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as today_visits
                FROM `{$this->prefix}page_visits`
                WHERE visit_date = CURDATE()
            ");
            $stmt->execute();
            $summary = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'success' => true,
                'daily_visits' => $daily_visits,
                'top_pages_today' => $top_pages,
                'summary' => $summary
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Installa la tabella analytics
     * POST /api/analytics/install
     */
    public function install() {
        try {
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
            
            $this->pdo->exec($sql);
            
            return [
                'success' => true,
                'message' => 'Analytics table created successfully'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Disinstalla la tabella analytics
     * POST /api/analytics/uninstall
     */
    public function uninstall() {
        try {
            $this->pdo->exec("DROP TABLE IF EXISTS `{$this->prefix}page_visits`");
            
            return [
                'success' => true,
                'message' => 'Analytics table removed successfully'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Verifica lo stato dell'installazione
     * GET /api/analytics/status
     */
    public function status() {
        $exists = $this->tableExists();
        $stats = null;
        
        if ($exists) {
            // Totale visite
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM `{$this->prefix}page_visits`");
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Visite oggi
            $stmt = $this->pdo->query("SELECT COUNT(*) as today FROM `{$this->prefix}page_visits` WHERE visit_date = CURDATE()");
            $today = $stmt->fetch(PDO::FETCH_ASSOC)['today'];
            
            // Prima visita
            $stmt = $this->pdo->query("SELECT MIN(visit_date) as first_date FROM `{$this->prefix}page_visits`");
            $first = $stmt->fetch(PDO::FETCH_ASSOC)['first_date'];
            
            // Ultima visita
            $stmt = $this->pdo->query("SELECT MAX(visit_time) as last_visit FROM `{$this->prefix}page_visits`");
            $last = $stmt->fetch(PDO::FETCH_ASSOC)['last_visit'];
            
            $stats = [
                'total_visits' => $total,
                'today_visits' => $today,
                'first_visit' => $first,
                'last_visit' => $last
            ];
        }
        
        return [
            'success' => true,
            'exists' => $exists,
            'stats' => $stats
        ];
    }
    
    /**
     * Verifica se la tabella esiste
     */
    private function tableExists() {
        try {
            $stmt = $this->pdo->prepare("SHOW TABLES LIKE '{$this->prefix}page_visits'");
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
