<?php
trait PostMetaTrait {
    public function updatePostMeta($postId, $metaKey, $metaValue) {
    // Controlla se esiste
    $stmt = $this->pdo->prepare("SELECT meta_id FROM " . $this->table('post_meta') . " 
        WHERE post_id=? AND meta_key=?");
    $stmt->execute([$postId, $metaKey]);
    $exists = $stmt->fetch();
    
    if ($exists) {
        // Update
        $stmt = $this->pdo->prepare("UPDATE " . $this->table('post_meta') . " 
            SET meta_value=? WHERE post_id=? AND meta_key=?");
        return $stmt->execute([$metaValue, $postId, $metaKey]);
    } else {
        // Insert
        $stmt = $this->pdo->prepare("INSERT INTO " . $this->table('post_meta') . " 
            (post_id, meta_key, meta_value) VALUES (?, ?, ?)");
        return $stmt->execute([$postId, $metaKey, $metaValue]);
    }
}
    public function getPostMeta($postId, $metaKey, $single = true) {
    $stmt = $this->pdo->prepare("SELECT meta_value FROM " . $this->table('post_meta') . " 
        WHERE post_id=? AND meta_key=?");
    $stmt->execute([$postId, $metaKey]);
    
    if ($single) {
        $result = $stmt->fetch();
        return $result ? $result['meta_value'] : null;
    }
    
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
    public function getAllPostMeta($postId) {
    $stmt = $this->pdo->prepare("SELECT meta_key, meta_value FROM " . $this->table('post_meta') . " 
        WHERE post_id=?");
    $stmt->execute([$postId]);
    
    $meta = [];
    while ($row = $stmt->fetch()) {
        $meta[$row['meta_key']] = $row['meta_value'];
    }
    return $meta;
}
    public function deletePostMeta($postId, $metaKey) {
    $stmt = $this->pdo->prepare("DELETE FROM " . $this->table('post_meta') . " 
        WHERE post_id=? AND meta_key=?");
    return $stmt->execute([$postId, $metaKey]);
}
}
