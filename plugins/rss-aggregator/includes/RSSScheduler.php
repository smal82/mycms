<?php
/**
 * Scheduler automatico per i feed RSS
 * Controlla quali feed devono essere importati e li schedula
 */

class RSSScheduler {
    private $db;
    private $prefix;
    
    public function __construct($db) {
        $this->db = $db;
        $this->prefix = DB_PREFIX;
    }
    
    /**
     * Controlla e schedula i feed da importare
     */
    public function checkAndSchedule() {
        // Trova feed che devono essere importati
        $stmt = $this->db->pdo->prepare("
            SELECT id, nome, frequenza, prossimo_import
            FROM {$this->prefix}rss_feeds
            WHERE stato = 'attivo'
            AND (prossimo_import IS NULL OR prossimo_import <= NOW())
        ");
        
        $stmt->execute();
        $feeds = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($feeds as $feed) {
            $this->schedulaFeed($feed['id'], $feed['frequenza']);
        }
    }
    
    /**
     * Schedula l'importazione di un feed
     */
    private function schedulaFeed($feedId, $frequenza) {
        // Controlla se esiste già un task pending per questo feed
        $stmt = $this->db->pdo->prepare("
            SELECT id FROM {$this->prefix}scheduled_tasks
            WHERE task_type = 'rss_import'
            AND task_data LIKE ?
            AND status = 'pending'
        ");
        
        $stmt->execute(['%"feed_id":' . $feedId . '%']);
        
        if ($stmt->fetch()) {
            return; // Task già schedulato
        }
        
        // Schedula nuovo task
        if (function_exists('schedule_task')) {
            schedule_task('rss_import', [
                'feed_id' => $feedId
            ], date('Y-m-d H:i:s'));
        }
        
        // Aggiorna prossimo import
        $prossimoImport = date('Y-m-d H:i:s', time() + $frequenza);
        
        $stmt = $this->db->pdo->prepare("
            UPDATE {$this->prefix}rss_feeds
            SET prossimo_import = ?
            WHERE id = ?
        ");
        
        $stmt->execute([$prossimoImport, $feedId]);
    }
}
?>
