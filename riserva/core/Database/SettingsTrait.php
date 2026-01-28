<?php
trait SettingsTrait {
    public function getSetting($key, $default = null) {
        $stmt = $this->pdo->prepare("SELECT setting_value FROM " . $this->table('settings') . " WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    }
    
    public function getSettingblog($key, $default = null) {
    $stmt = $this->pdo->prepare("SELECT setting_value FROM " . $this->table('settings') . " WHERE setting_key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    $value = $result ? $result['setting_value'] : $default;
    
    // Auto-cast numerico per LIMIT/OFFSET
    if (is_numeric($value) && strpos($key, 'per_page') !== false) {
        return (int)$value;  // 20 (integer)
    }
    return $value;  // '20' (string per altro)
}


    public function saveSetting($key, $value) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO " . $this->table('settings') . " (setting_key, setting_value) 
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE setting_value = ?
            ");
            return $stmt->execute([$key, $value, $value]);
        } catch (PDOException $e) {
            error_log('Error saving setting: ' . $e->getMessage());
            return false;
        }
    }

    public function setSetting($key, $value) {
        $stmt = $this->pdo->prepare("INSERT INTO " . $this->table('settings') . " (setting_key, setting_value) VALUES (?, ?) 
                                     ON DUPLICATE KEY UPDATE setting_value=?");
        return $stmt->execute([$key, $value, $value]);
    }
}
