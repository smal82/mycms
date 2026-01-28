<?php
class Widget_stats {
    private $db;
    private $user;
    
    public function __construct($db) {
        $this->db = $db;
        $this->user = new User($this->db);
    }
    
    public function render($config = []) {
        $prefix = DB_PREFIX;
        
        // Conta i post
        $stmt = $this->db->pdo->prepare("
            SELECT COUNT(*) 
            FROM {$prefix}posts 
            WHERE post_type = 'post' 
            AND deleted_at IS NULL
        ");
        $stmt->execute();
        $totalPosts = $stmt->fetchColumn();
        
        // Conta le pagine
        $stmt = $this->db->pdo->prepare("
    SELECT COUNT(*) 
    FROM {$prefix}pages 
    WHERE deleted_at IS NULL
");

        $stmt->execute();
        $totalPages = $stmt->fetchColumn();
        
        // Tema attivo e plugin
        $activeTheme = $this->db->getSetting('active_theme');
        $activePlugins = count($this->db->getActivePlugins());
        ?>
        <div class="dashboard-widget widget-stats">
            <h3>📊  Riepilogo</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-value"><?php echo $totalPosts; ?></span>
                    <span class="stat-label">Post</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo $totalPages; ?></span>
                    <span class="stat-label">Pagine</span>
                </div>
                <?php if ($this->user->hasRole(User::ROLE_ADMIN)): ?>
                <div class="stat-item">
                    <span class="stat-value"><?php echo htmlspecialchars($activeTheme); ?></span>
                    <span class="stat-label">Tema</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo $activePlugins; ?></span>
                    <span class="stat-label">Plugin Attivi</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
?>
