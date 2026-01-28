<?php
class Widget_quick_info {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function render($config = []) {
        ?>
        <div class="dashboard-widget widget-quick-info">
            <h3>Info Rapide</h3>
            <p>Versione CMS: 1.0</p>
            <p>Ultimo aggiornamento: <?php echo date('d/m/Y'); ?></p>
        </div>
        <?php
    }
}
?>