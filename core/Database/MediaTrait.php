<?php
trait MediaTrait {
    public function trashMedia($id) {
    $stmt = $this->pdo->prepare("UPDATE " . $this->table('uploads') . " SET deleted_at = NOW() WHERE id=?");
    return $stmt->execute([$id]);
}
    public function getTrashedMedia() {
    return $this->pdo->query("SELECT * FROM " . $this->table('uploads') . " 
                              WHERE deleted_at IS NOT NULL 
                              ORDER BY deleted_at DESC")->fetchAll();
}
    public function restoreMedia($id) {
    $stmt = $this->pdo->prepare("UPDATE " . $this->table('uploads') . " SET deleted_at = NULL WHERE id=?");
    return $stmt->execute([$id]);
}
    public function deleteMediaPermanently($id) {
    // Ottieni info file
    $stmt = $this->pdo->prepare("SELECT filename FROM " . $this->table('uploads') . " WHERE id=?");
    $stmt->execute([$id]);
    $media = $stmt->fetch();
    
    if ($media) {
        // Elimina file fisico
        $filepath = '../uploads/' . $media['filename'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        
        // Elimina dal database
        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table('uploads') . " WHERE id=?");
        return $stmt->execute([$id]);
    }
    
    return false;
}
    public function  emptyMediaTrash() {
    $trashedMedia = $this->getTrashedMedia();
    foreach ($trashedMedia as $media) {
        $this->deleteMediaPermanently($media['id']);
    }
    return true;
}
    public function getAllUploads() {
    return $this->pdo->query("SELECT * FROM " . $this->table('uploads') . " 
                              WHERE deleted_at IS NULL 
                              ORDER BY created_at DESC")->fetchAll();
}
    public function isMediaDeleted($filename) {
    $stmt = $this->pdo->prepare("SELECT deleted_at FROM " . $this->table('uploads') . " WHERE filename=?");
    $stmt->execute([$filename]);
    $result = $stmt->fetch();
    
    return $result && $result['deleted_at'] !== null;
}
    public function getMediaByFilename($filename) {
    $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('uploads') . " WHERE filename=?");
    $stmt->execute([$filename]);
    return $stmt->fetch();
}
}
