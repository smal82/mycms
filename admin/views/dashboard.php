<div class="admin-content">
    <h1>Dashboard</h1>
    
    <div class="dashboard-widgets-container">
        <?php 
        // Ottieni solo i widget attivi dal database
        $prefix = DB_PREFIX;
        $stmt = $this->db->pdo->prepare("
            SELECT widget_type, config, position 
            FROM {$prefix}dashboard_widgets 
            WHERE is_active = 1 
            ORDER BY position ASC
        ");
        $stmt->execute();
        $activeWidgets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Se non ci sono widget attivi, usa i widget di default
        if (empty($activeWidgets)) {
            $activeWidgets = [
                ['widget_type' => 'stats', 'config' => null, 'position' => 1],
                ['widget_type' => 'recent_content', 'config' => '{"limit":5}', 'position' => 2],
                ['widget_type' => 'quick_info', 'config' => null, 'position' => 3],
            ];
        }
        
        // Carica solo i file dei widget attivi
        $widgetTypes = array_unique(array_column($activeWidgets, 'widget_type'));
        foreach ($widgetTypes as $type) {
            $widgetFile = BASE_PATH . '/core/widgets/Widget_' . $type . '.php';
            if (file_exists($widgetFile)) {
                require_once $widgetFile;
            }
        }
        
        // Renderizza i widget nell'ordine specificato
        foreach ($activeWidgets as $widget): 
            $widgetClass = 'Widget_' . $widget['widget_type'];
            if (class_exists($widgetClass)) {
                $widgetInstance = new $widgetClass($this->db);
                $config = $widget['config'] ? json_decode($widget['config'], true) : [];
                $widgetInstance->render($config);
            }
        endforeach; 
        ?>
    </div>
    
    <?php if ($this->user->hasRole(User::ROLE_ADMIN)): ?>
        <p><a href="index.php?action=dashboard_widgets" class="btn">Gestisci Widget Dashboard</a></p>
    <?php endif; ?>
</div>
