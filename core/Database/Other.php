<?php
trait OtherTrait {
    
    // === GESTIONE UPLOAD ===
    public function saveUpload($data) {
        $stmt = $this->pdo->prepare("INSERT INTO " . $this->table('uploads') . " (filename, original_name, mime_type, size, uploaded_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['filename'], $data['original_name'], $data['mime_type'], $data['size'], $_SESSION['user_id'] ?? null]);
        return $this->pdo->lastInsertId();
    }
    
    // === GESTIONE CESTINO POST ===
    
    /**
     * Sposta un post nel cestino
     */
    public function trashPost($id) {
        $stmt = $this->pdo->prepare("UPDATE " . $this->table('posts') . " SET deleted_at = NOW() WHERE id=?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Recupera tutti i post nel cestino
     */
    public function getTrashedPosts() {
        return $this->pdo->query("SELECT p.*, u.name as author_name FROM " . $this->table('posts') . " p 
                                  LEFT JOIN " . $this->table('users') . " u ON p.author_id = u.id 
                                  WHERE p.deleted_at IS NOT NULL 
                                  ORDER BY p.deleted_at DESC")->fetchAll();
    }
    
    /**
 * Ripristina un post dal cestino
 */
public function restorePost($id) {
    $stmt = $this->pdo->prepare("UPDATE " . $this->table('posts') . " SET deleted_at = NULL WHERE id=?");
    return $stmt->execute([$id]);
}
    
    /**
     * Elimina definitivamente un post
     */
    public function deletePostPermanently($id) {
        // Elimina prima le associazioni con le categorie
        $this->pdo->prepare("DELETE FROM " . $this->table('post_categories') . " WHERE post_id=?")->execute([$id]);
        // Elimina il post
        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table('posts') . " WHERE id=?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Svuota completamente il cestino dei post
     */
    public function emptyPostTrash() {
        $trashedPosts = $this->getTrashedPosts();
        foreach ($trashedPosts as $post) {
            $this->deletePostPermanently($post['id']);
        }
        return true;
    }
    
    // === GESTIONE CESTINO PAGINE ===
    
    /**
     * Sposta una pagina nel cestino
     */
    public function trashPage($id) {
        $stmt = $this->pdo->prepare("UPDATE " . $this->table('pages') . " SET deleted_at = NOW() WHERE id=?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Recupera tutte le pagine nel cestino
     */
    public function getTrashedPages() {
        return $this->pdo->query("SELECT p.*, u.name as author_name FROM " . $this->table('pages') . " p 
                                  LEFT JOIN " . $this->table('users') . " u ON p.author_id = u.id 
                                  WHERE p.deleted_at IS NOT NULL 
                                  ORDER BY p.deleted_at DESC")->fetchAll();
    }
    
    /**
     * Ripristina una pagina dal cestino
     */
    public function restorePage($id) {
        $stmt = $this->pdo->prepare("UPDATE " . $this->table('pages') . " SET deleted_at = NULL WHERE id=?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Elimina definitivamente una pagina
     */
    public function deletePagePermanently($id) {
        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table('pages') . " WHERE id=?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Svuota completamente il cestino delle pagine
     */
    public function emptyPageTrash() {
        $trashedPages = $this->getTrashedPages();
        foreach ($trashedPages as $page) {
            $this->deletePagePermanently($page['id']);
        }
        return true;
    }
    
    /**
 * Ottieni utente per email
 */
public function getUserByEmail($email) {
    $prefix = DB_PREFIX;
    $stmt = $this->pdo->prepare("SELECT * FROM {$prefix}users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    
}