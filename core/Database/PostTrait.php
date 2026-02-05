<?php
trait PostTrait {
    
    public function getAllPosts() {
        return $this->pdo->query("SELECT p.*, u.name as author_name FROM " . $this->table('posts') . " p 
                                  LEFT JOIN " . $this->table('users') . " u ON p.author_id = u.id 
                                  WHERE p.deleted_at IS NULL
                                  ORDER BY p.created_at DESC")->fetchAll();
    }
    
    public function getPublishedPosts($limit = null, $offset = 0) {
        $sql = "SELECT p.*, u.name as author_name FROM " . $this->table('posts') . " p 
                LEFT JOIN " . $this->table('users') . " u ON p.author_id = u.id 
                WHERE p.status='pubblicato' AND p.deleted_at IS NULL
                ORDER BY p.created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        return $this->pdo->query($sql)->fetchAll();
    }
    
    public function getRecentPost($limit = null, $offset = 0) {
        $sql = "SELECT p.*, u.name as author_name FROM " . $this->table('posts') . " p 
                LEFT JOIN " . $this->table('users') . " u ON p.author_id = u.id 
                WHERE p.status='pubblicato' AND p.deleted_at IS NULL and p.post_type = 'post'
                ORDER BY p.created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        return $this->pdo->query($sql)->fetchAll();
    }
    
    public function getPostById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('posts') . " WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getPostBySlug($slug) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('posts') . " WHERE slug = ? AND post_type = 'post' AND status = 'pubblicato' AND deleted_at IS NULL");
        $stmt->execute([$slug]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($post) {
            // Hook per modificare il contenuto del post
            $post = apply_hook('mycms_post_content', $post);
        }
        
        return $post;
    }
    
    public function getCPTBySlug($slug) {
        $stmt = $this->pdo->prepare("
    SELECT * 
    FROM " . $this->table('posts') . " 
    WHERE slug = ? 
      AND post_type <> 'post' 
      AND status = 'pubblicato' 
      AND deleted_at IS NULL
");
        $stmt->execute([$slug]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($post) {
            // Hook per modificare il contenuto del post
            $post = apply_hook('mycms_post_content', $post);
        }
        
        return $post;
    }

    
    public function savePost($data) {
        // Hook prima del salvataggio
        $data = apply_hook('mycms_before_save_post', $data);
        
        if (isset($data['id']) && $data['id']) {
            $stmt = $this->pdo->prepare("UPDATE " . $this->table('posts') . " SET title=?, slug=?, content=?, excerpt=?, featured_image=?, status=? WHERE id=?");
            $result = $stmt->execute([$data['title'], $data['slug'], $data['content'], $data['excerpt'] ?? '', $data['featured_image'] ?? null, $data['status'], $data['id']]);
            $postId = $data['id'];
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO " . $this->table('posts') . " (title, slug, content, excerpt, featured_image, status, author_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$data['title'], $data['slug'], $data['content'], $data['excerpt'] ?? '', $data['featured_image'] ?? null, $data['status'], $_SESSION['user_id']]);
            $postId = $this->pdo->lastInsertId();
        }
        
        // Gestisci categorie
        if ($result && isset($data['categories'])) {
            $this->pdo->prepare("DELETE FROM " . $this->table('post_categories') . " WHERE post_id=?")->execute([$postId]);
            if (!empty($data['categories'])) {
                $stmt = $this->pdo->prepare("INSERT INTO " . $this->table('post_categories') . " (post_id, category_id) VALUES (?, ?)");
                foreach ($data['categories'] as $catId) {
                    $stmt->execute([$postId, $catId]);
                }
            }
        }
        
        // Hook dopo il salvataggio
        do_hook('mycms_after_save_post', $postId, $data);
        
        return $postId;
    }
    
    public function deletePost($id) {
        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table('posts') . " WHERE id=?");
        return $stmt->execute([$id]);
    }
    
    public function getPostCategories($postId) {
        $stmt = $this->pdo->prepare("SELECT c.* FROM " . $this->table('categories') . " c 
                                     INNER JOIN " . $this->table('post_categories') . " pc ON c.id = pc.category_id 
                                     WHERE pc.post_id = ?");
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }
    
    // === GESTIONE CATEGORIE ===
    public function getAllCategories() {
        return $this->pdo->query("SELECT * FROM " . $this->table('categories') . " ORDER BY name")->fetchAll();
    }
    
    public function getCategoryById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('categories') . " WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getCategoryBySlug($slug) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('categories') . " WHERE slug=?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
    
    public function saveCategory($data) {
        if (isset($data['id']) && $data['id']) {
            $stmt = $this->pdo->prepare("UPDATE " . $this->table('categories') . " SET name=?, slug=?, description=? WHERE id=?");
            return $stmt->execute([$data['name'], $data['slug'], $data['description'] ?? '', $data['id']]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO " . $this->table('categories') . " (name, slug, description) VALUES (?, ?, ?)");
            return $stmt->execute([$data['name'], $data['slug'], $data['description'] ?? '']);
        }
    }
    
    public function deleteCategory($id) {
        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table('categories') . " WHERE id=?");
        return $stmt->execute([$id]);
    }
    
    public function countPosts() {
    $stmt = $this->pdo->prepare("
        SELECT COUNT(*) 
        FROM " . DB_PREFIX . "posts 
        WHERE deleted_at IS NULL AND post_type = 'post'
    ");
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function getPostsPaginated($offset, $limit) {
    $stmt = $this->pdo->prepare("
        SELECT p.*, u.name as author_name
        FROM " . DB_PREFIX . "posts p
        LEFT JOIN " . DB_PREFIX . "users u ON p.author_id = u.id
        WHERE p.deleted_at IS NULL AND p.post_type = 'post'
        ORDER BY p.created_at DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}



    
}