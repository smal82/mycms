<?php
class PageController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll($params = []) {
        try {
            $prefix = DB_PREFIX;
            
            $stmt = $this->db->pdo->query("
                SELECT title, slug, content, created_at, updated_at
                FROM {$prefix}pages 
                WHERE status = 'pubblicato'
                ORDER BY title ASC
            ");
            $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return ['status' => 200, 'data' => ['pages' => $pages]];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => $e->getMessage()]];
        }
    }
    
    public function getOne($id) {
        try {
            $prefix = DB_PREFIX;
            
            $stmt = $this->db->pdo->prepare("
                SELECT title, slug, content, created_at, updated_at
                FROM {$prefix}pages 
                WHERE id = ? OR slug = ?
            ");
            $stmt->execute([$id, $id]);
            $page = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$page) {
                return ['status' => 404, 'data' => ['error' => 'Page not found']];
            }
            
            return ['status' => 200, 'data' => $page];
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
