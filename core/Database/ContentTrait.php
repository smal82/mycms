<?php
trait ContentTrait {
    public function getPage($slug) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('contents') . " WHERE type='page' AND slug=?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
    public function getPost($slug) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('contents') . " WHERE type='post' AND slug=?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
    
    public function getHomePage() {
        $stmt = $this->pdo->query("SELECT * FROM " . $this->table('contents') . " WHERE slug='home' LIMIT 1");
        return $stmt->fetch();
    }
    
    public function getAllContents($type = null) {
        if ($type) {
            $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('contents') . " WHERE type=? ORDER BY created_at DESC");
            $stmt->execute([$type]);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM " . $this->table('contents') . " ORDER BY created_at DESC");
        }
        return $stmt->fetchAll();
    }
    
    public function getContentById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('contents') . " WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function saveContent($data) {
        if (isset($data['id']) && $data['id']) {
            $stmt = $this->pdo->prepare("UPDATE " . $this->table('contents') . " SET type=?, title=?, slug=?, content=? WHERE id=?");
            return $stmt->execute([$data['type'], $data['title'], $data['slug'], $data['content'], $data['id']]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO " . $this->table('contents') . " (type, title, slug, content) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$data['type'], $data['title'], $data['slug'], $data['content']]);
        }
    }
    
    public function deleteContent($id) {
        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table('contents') . " WHERE id=?");
        return $stmt->execute([$id]);
    }
}
