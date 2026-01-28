<?php
class SettingsController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll($params = []) {
        try {
            $prefix = DB_PREFIX;
            
            $stmt = $this->db->pdo->query("SELECT setting_key, setting_value FROM {$prefix}settings");
            $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            return ['status' => 200, 'data' => ['settings' => $settings]];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => $e->getMessage()]];
        }
    }
    
    public function getOne($key) {
        try {
            $prefix = DB_PREFIX;
            
            $stmt = $this->db->pdo->prepare("SELECT setting_value FROM {$prefix}settings WHERE setting_key = ?");
            $stmt->execute([$key]);
            $value = $stmt->fetchColumn();
            
            if ($value === false) {
                return ['status' => 404, 'data' => ['error' => 'Setting not found']];
            }
            
            return ['status' => 200, 'data' => ['key' => $key, 'value' => $value]];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => $e->getMessage()]];
        }
    }
    
    public function create($data) {
        return ['status' => 501, 'data' => ['error' => 'Not implemented']];
    }
    
    public function update($id, $data) {
        return ['status' => 501, 'data' => ['error' => 'Not implemented']];
    }
    
    public function delete($id) {
        return ['status' => 501, 'data' => ['error' => 'Not implemented']];
    }
}
