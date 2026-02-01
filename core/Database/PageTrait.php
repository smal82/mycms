<?php
trait PageTrait {
    public function getAllPages() {
        return $this->pdo->query("SELECT p.*, u.name as author_name FROM " . $this->table('pages') . " p 
                                  LEFT JOIN " . $this->table('users') . " u ON p.author_id = u.id 
                                  WHERE p.deleted_at IS NULL
                                  ORDER BY p.created_at DESC")->fetchAll();
    }
    
    public function getPageById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('pages') . " WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getPageBySlug($slug) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('pages') . " WHERE slug=? AND status='pubblicato' AND deleted_at IS NULL");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
    
    public function savePage($data) {
        if (isset($data['id']) && $data['id']) {
            $stmt = $this->pdo->prepare("UPDATE " . $this->table('pages') . " SET title=?, slug=?, content=?, featured_image=?, status=? WHERE id=?");
            return $stmt->execute([$data['title'], $data['slug'], $data['content'], $data['featured_image'] ?? null, $data['status'], $data['id']]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO " . $this->table('pages') . " (title, slug, content, featured_image, status, author_id) VALUES (?, ?, ?, ?, ?, ?)");
            return $stmt->execute([$data['title'], $data['slug'], $data['content'], $data['featured_image'] ?? null, $data['status'], $_SESSION['user_id']]);
        }
    }
    
    public function deletePage($id) {
        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table('pages') . " WHERE id=?");
        return $stmt->execute([$id]);
    }
    
    public function countPages() {
    $stmt = $this->pdo->prepare("
        SELECT COUNT(*) 
        FROM " . DB_PREFIX . "pages 
        WHERE deleted_at IS NULL
    ");
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function getPagesPaginated($offset, $limit) {
    $stmt = $this->pdo->prepare("
        SELECT p.*, u.name as author_name
        FROM " . DB_PREFIX . "pages p
        LEFT JOIN " . DB_PREFIX . "users u ON p.author_id = u.id
        WHERE p.deleted_at IS NULL
        ORDER BY p.created_at DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

}
