<?php
trait PluginTrait {
    public function getActivePlugins() {
        try {
            $stmt = $this->pdo->query("SELECT name FROM " . $this->table('active_plugins'));
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $result ?: [];
        } catch (PDOException $e) {
            error_log('Error getting active plugins: ' . $e->getMessage());
            return [];
        }
    }
    
    public function getAvailablePluginsList() {
        $plugins = [];
        if (is_dir(PLUGIN_PATH)) {
            $dirs = scandir(PLUGIN_PATH);
            foreach ($dirs as $dir) {
                if ($dir !== '.' && $dir !== '..' && is_dir(PLUGIN_PATH . '/' . $dir)) {
                    $pluginFile = PLUGIN_PATH . '/' . $dir . '/plugin.php';
                    if (file_exists($pluginFile)) {
                        $plugins[] = $dir;
                    }
                }
            }
        }
        return $plugins;
    }
    
    public function activatePlugin($name) {
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO " . $this->table('active_plugins') . " (name) VALUES (?)");
        return $stmt->execute([$name]);
    }
    
    public function deactivatePlugin($name) {
        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table('active_plugins') . " WHERE name=?");
        return $stmt->execute([$name]);
    }
}
