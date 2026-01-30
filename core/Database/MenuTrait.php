<?php
trait MenuTrait {
    public function getAllMenus() {
        return $this->pdo->query("SELECT * FROM " . $this->table('menus') . " ORDER BY name")->fetchAll();
    }
    
    public function getMenuById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('menus') . " WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getMenuByLocation($location) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('menus') . " WHERE location=?");
        $stmt->execute([$location]);
        return $stmt->fetch();
    }
    
    public function saveMenu($data) {
        if (isset($data['id']) && $data['id']) {
            $stmt = $this->pdo->prepare("UPDATE " . $this->table('menus') . " SET name=?, location=? WHERE id=?");
            $stmt->execute([$data['name'], $data['location'], $data['id']]);
            return $data['id'];
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO " . $this->table('menus') . " (name, location) VALUES (?, ?)");
            $stmt->execute([$data['name'], $data['location']]);
            return $this->pdo->lastInsertId();
        }
    }
    
    public function deleteMenu($id) {
        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table('menus') . " WHERE id=?");
        return $stmt->execute([$id]);
    }
    
    public function getMenuItems($menuId) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('menu_items') . " WHERE menu_id=? ORDER BY sort_order, id");
        $stmt->execute([$menuId]);
        return $stmt->fetchAll();
    }
    
    public function saveMenuItem($data) {
        // Gestisci il tipo di link
        if (isset($data['link_type'])) {
            if ($data['link_type'] === 'page' && !empty($data['page_id'])) {
                // Link a pagina
                $page = $this->getPageById($data['page_id']);
                if ($page) {
                    $data['url'] = '/page/' . $page['slug'];
                    if (empty($data['title'])) {
                        $data['title'] = $page['title'];
                    }
                }
            } elseif ($data['link_type'] === 'post' && !empty($data['post_id'])) {
                // Link a post
                $post = $this->getPostById($data['post_id']);
                if ($post) {
                    $data['url'] = '/post/' . $post['slug'];
                    if (empty($data['title'])) {
                        $data['title'] = $post['title'];
                    }
                }
            }
        }
        
        // Gestisci parent_id
        $parentId = !empty($data['parent_id']) ? $data['parent_id'] : null;
        
        if (isset($data['id']) && $data['id']) {
            // Aggiorna esistente
            $stmt = $this->pdo->prepare("UPDATE " . $this->table('menu_items') . " 
                                         SET title=?, url=?, target=?, parent_id=?, sort_order=? 
                                         WHERE id=?");
            return $stmt->execute([
                $data['title'], 
                $data['url'], 
                $data['target'], 
                $parentId,
                $data['sort_order'] ?? 0, 
                $data['id']
            ]);
        } else {
            // Calcola sort_order automaticamente
            if (!isset($data['sort_order'])) {
                $stmt = $this->pdo->prepare("SELECT MAX(sort_order) as max_order FROM " . $this->table('menu_items') . " WHERE menu_id=?");
                $stmt->execute([$data['menu_id']]);
                $result = $stmt->fetch();
                $data['sort_order'] = ($result['max_order'] ?? -1) + 1;
            }
            
            // Inserisci nuovo
            $stmt = $this->pdo->prepare("INSERT INTO " . $this->table('menu_items') . " 
                                         (menu_id, parent_id, title, url, target, sort_order) 
                                         VALUES (?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['menu_id'], 
                $parentId,
                $data['title'], 
                $data['url'], 
                $data['target'], 
                $data['sort_order']
            ]);
        }
    }
    
    public function deleteMenuItem($id) {
        // Elimina anche tutti i figli ricorsivamente
        $this->deleteMenuItemRecursive($id);
        return true;
    }

    private function deleteMenuItemRecursive($id) {
        // Trova tutti i figli
        $stmt = $this->pdo->prepare("SELECT id FROM " . $this->table('menu_items') . " WHERE parent_id=?");
        $stmt->execute([$id]);
        $children = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Elimina ricorsivamente tutti i figli
        foreach ($children as $childId) {
            $this->deleteMenuItemRecursive($childId);
        }
        
        // Elimina l'elemento corrente
        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table('menu_items') . " WHERE id=?");
        $stmt->execute([$id]);
    }

    public function updateMenuItemsOrder($menuId, $orderData) {
        $stmt = $this->pdo->prepare("UPDATE " . $this->table('menu_items') . " SET sort_order=? WHERE id=?");
        
        foreach ($orderData as $item) {
            $stmt->execute([$item['sort_order'], $item['id']]);
        }
        
        return true;
    }
}
