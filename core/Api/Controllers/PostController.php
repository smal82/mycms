<?php
class PostController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll($params = []) {
        try {
            $page = isset($params['page']) ? (int)$params['page'] : 1;
            $perPage = isset($params['per_page']) ? (int)$params['per_page'] : 10;
            $offset = ($page - 1) * $perPage;
            
            $prefix = DB_PREFIX;
            
            // Query con JOIN - SENZA author_id
            $stmt = $this->db->pdo->prepare("
                SELECT 
                    p.title, 
                    p.slug, 
                    p.content, 
                    p.excerpt,
                    p.featured_image,
                    u.name as author_name,
                    p.created_at, 
                    p.updated_at
                FROM {$prefix}posts p
                LEFT JOIN {$prefix}users u ON p.author_id = u.id
                WHERE p.status = 'pubblicato' AND p.deleted_at IS NULL
                ORDER BY p.created_at DESC
                LIMIT :limit OFFSET :offset
            ");
            
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Conta totale
            $totalStmt = $this->db->pdo->query("SELECT COUNT(*) FROM {$prefix}posts WHERE status = 'pubblicato' AND deleted_at IS NULL");
            $total = $totalStmt->fetchColumn();
            
            return [
                'status' => 200,
                'data' => [
                    'posts' => $posts,
                    'pagination' => [
                        'total' => (int)$total,
                        'page' => $page,
                        'per_page' => $perPage,
                        'total_pages' => ceil($total / $perPage)
                    ]
                ]
            ];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => $e->getMessage()]];
        }
    }
    
    public function getOne($id) {
        try {
            $prefix = DB_PREFIX;
            
            $stmt = $this->db->pdo->prepare("
                SELECT 
                    p.title, 
                    p.slug, 
                    p.content,
                    p.excerpt,
                    p.featured_image,
                    u.name as author_name,
                    p.created_at, 
                    p.updated_at
                FROM {$prefix}posts p
                LEFT JOIN {$prefix}users u ON p.author_id = u.id
                WHERE (p.id = ? OR p.slug = ?) AND p.deleted_at IS NULL
            ");
            $stmt->execute([$id, $id]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$post) {
                return ['status' => 404, 'data' => ['error' => 'Post not found']];
            }
            
            return ['status' => 200, 'data' => $post];
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
    
    private function generateSlug($title) {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
}
