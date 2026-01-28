<?php
trait CustomPostTypeTrait {
    public function getCustomPostTypes($publicOnly = false) {
    $sql = "SELECT * FROM " . $this->table('custom_post_types');
    if ($publicOnly) {
        $sql .= " WHERE public = 1 AND show_in_menu = 1";
    }
    $sql .= " ORDER BY menu_position, name";
    return $this->pdo->query($sql)->fetchAll();
}
    public function getCustomPostType($name) {
    $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('custom_post_types') . " WHERE name=?");
    $stmt->execute([$name]);
    return $stmt->fetch();
}
    public function saveCustomPostType($data) {
    $errors = [];
    
    // === VALIDAZIONE NOME (solo per nuovi CPT) ===
    if (!isset($data['id']) || !$data['id']) {
        if (empty($data['name'])) {
            $errors[] = "Il nome tecnico è obbligatorio";
        } else {
            // Solo lettere minuscole, numeri e underscore
            if (!preg_match('/^[a-z0-9_]+$/', $data['name'])) {
                $errors[] = "Il nome tecnico può contenere solo lettere minuscole, numeri e underscore";
            }
            
            // Lunghezza massima 50 caratteri
            if (strlen($data['name']) > 50) {
                $errors[] = "Il nome tecnico non può superare i 50 caratteri";
            }
            
            // Lunghezza minima 3 caratteri
            if (strlen($data['name']) < 3) {
                $errors[] = "Il nome tecnico deve avere almeno 3 caratteri";
            }
            
            // Nomi riservati
            $reserved = ['post', 'page', 'attachment', 'revision', 'nav_menu_item', 'admin', 'dashboard', 'settings', 'users', 'plugins', 'themes'];
            if (in_array($data['name'], $reserved)) {
                $errors[] = "Il nome '{$data['name']}' è riservato dal sistema";
            }
            
            // Controlla se nome già esiste
            $stmt = $this->pdo->prepare("SELECT id FROM " . $this->table('custom_post_types') . " WHERE name=?");
            $stmt->execute([$data['name']]);
            if ($stmt->fetch()) {
                $errors[] = "Esiste già un Custom Post Type con nome '{$data['name']}'";
            }
        }
    }
    
    // === VALIDAZIONE SLUG ===
    if (empty($data['slug'])) {
        $errors[] = "Lo slug è obbligatorio";
    } else {
        // Solo lettere minuscole, numeri e trattini
        if (!preg_match('/^[a-z0-9-]+$/', $data['slug'])) {
            $errors[] = "Lo slug può contenere solo lettere minuscole, numeri e trattini";
        }
        
        // Lunghezza massima 100 caratteri
        if (strlen($data['slug']) > 100) {
            $errors[] = "Lo slug non può superare i 100 caratteri";
        }
        
        // Non può iniziare o finire con trattino
        if (preg_match('/^-|-$/', $data['slug'])) {
            $errors[] = "Lo slug non può iniziare o finire con un trattino";
        }
        
        // Controlla slug univoco (escluso record corrente se in update)
        $checkSlugSql = "SELECT id FROM " . $this->table('custom_post_types') . " WHERE slug=?";
        $params = [$data['slug']];
        if (isset($data['id']) && $data['id']) {
            $checkSlugSql .= " AND id != ?";
            $params[] = $data['id'];
        }
        $stmt = $this->pdo->prepare($checkSlugSql);
        $stmt->execute($params);
        if ($stmt->fetch()) {
            $errors[] = "Esiste già un Custom Post Type con slug '{$data['slug']}'";
        }
    }
    
    // === VALIDAZIONE LABEL ===
    if (empty($data['singular_label'])) {
        $errors[] = "L'etichetta singolare è obbligatoria";
    } elseif (strlen($data['singular_label']) > 100) {
        $errors[] = "L'etichetta singolare non può superare i 100 caratteri";
    }
    
    if (empty($data['plural_label'])) {
        $errors[] = "L'etichetta plurale è obbligatoria";
    } elseif (strlen($data['plural_label']) > 100) {
        $errors[] = "L'etichetta plurale non può superare i 100 caratteri";
    }
    
    // === VALIDAZIONE MENU POSITION ===
    if (isset($data['menu_position']) && !is_numeric($data['menu_position'])) {
        $errors[] = "La posizione nel menu deve essere un numero";
    }
    
    // === VALIDAZIONE ICON ===
    if (!empty($data['icon']) && strlen($data['icon']) > 50) {
        $errors[] = "Il nome dell'icona non può superare i 50 caratteri";
    }
    
    // === VALIDAZIONE SUPPORTS ===
    if (isset($data['supports'])) {
        if (!is_array($data['supports'])) {
            $errors[] = "Il campo 'supports' deve essere un array";
        } else {
            $validSupports = ['title', 'content', 'featured_image', 'excerpt', 'categories', 'author', 'comments'];
            foreach ($data['supports'] as $support) {
                if (!in_array($support, $validSupports)) {
                    $errors[] = "Funzionalità '{$support}' non valida. Valori consentiti: " . implode(', ', $validSupports);
                }
            }
        }
    }
    
    // Se ci sono errori, lanciali
    if (!empty($errors)) {
        throw new Exception("Errori di validazione:\n- " . implode("\n- ", $errors));
    }
    
    // === SALVATAGGIO ===
    // Prepara il JSON per supports
    $supports = isset($data['supports']) ? json_encode($data['supports']) : json_encode(['title', 'content']);
    
    if (isset($data['id']) && $data['id']) {
        // Update
        $stmt = $this->pdo->prepare("UPDATE " . $this->table('custom_post_types') . " 
            SET singular_label=?, plural_label=?, slug=?, description=?, icon=?, supports=?, 
                menu_position=?, public=?, show_in_menu=?, has_archive=?, hierarchical=?, rewrite_slug=?
            WHERE id=?");
        return $stmt->execute([
            $data['singular_label'],
            $data['plural_label'],
            $data['slug'],
            $data['description'] ?? '',
            $data['icon'] ?? 'document',
            $supports,
            $data['menu_position'] ?? 5,
            $data['public'] ?? 1,
            $data['show_in_menu'] ?? 1,
            $data['has_archive'] ?? 1,
            $data['hierarchical'] ?? 0,
            $data['rewrite_slug'] ?? $data['slug'],
            $data['id']
        ]);
    } else {
        // Insert
        $stmt = $this->pdo->prepare("INSERT INTO " . $this->table('custom_post_types') . " 
            (name, singular_label, plural_label, slug, description, icon, supports, menu_position, public, show_in_menu, has_archive, hierarchical, rewrite_slug) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['name'],
            $data['singular_label'],
            $data['plural_label'],
            $data['slug'],
            $data['description'] ?? '',
            $data['icon'] ?? 'document',
            $supports,
            $data['menu_position'] ?? 5,
            $data['public'] ?? 1,
            $data['show_in_menu'] ?? 1,
            $data['has_archive'] ?? 1,
            $data['hierarchical'] ?? 0,
            $data['rewrite_slug'] ?? $data['slug']
        ]);
    }
}
    public function deleteCustomPostType($id) {
    // Prima elimina tutti i post di questo tipo
    $cpt = $this->pdo->query("SELECT name FROM " . $this->table('custom_post_types') . " WHERE id = $id")->fetch();
    if ($cpt) {
        $this->pdo->prepare("DELETE FROM " . $this->table('posts') . " WHERE post_type=?")->execute([$cpt['name']]);
    }
    
    $stmt = $this->pdo->prepare("DELETE FROM " . $this->table('custom_post_types') . " WHERE id=?");
    return $stmt->execute([$id]);
}
    public function getPostsByType($postType, $status = null) {
    $sql = "SELECT p.*, u.name as author_name 
            FROM " . $this->table('posts') . " p 
            LEFT JOIN " . $this->table('users') . " u ON p.author_id = u.id 
            WHERE p.post_type = ? AND p.deleted_at IS NULL";
    
    $params = [$postType];
    
    if ($status) {
        $sql .= " AND p.status = ?";
        $params[] = $status;
    }
    
    $sql .= " ORDER BY p.created_at DESC";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
    public function saveCustomPost($data) {
    // Hook prima del salvataggio
    $data = apply_hook('mycms_before_save_custom_post', $data);
    
    // Assicurati che post_type sia impostato
    if (!isset($data['post_type'])) {
        $data['post_type'] = 'post';
    }
    
    if (isset($data['id']) && $data['id']) {
        $stmt = $this->pdo->prepare("UPDATE " . $this->table('posts') . " 
            SET post_type=?, title=?, slug=?, content=?, excerpt=?, featured_image=?, status=? 
            WHERE id=?");
        $result = $stmt->execute([
            $data['post_type'],
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['excerpt'] ?? '',
            $data['featured_image'] ?? null,
            $data['status'],
            $data['id']
        ]);
        $postId = $data['id'];
    } else {
        $stmt = $this->pdo->prepare("INSERT INTO " . $this->table('posts') . " 
            (post_type, title, slug, content, excerpt, featured_image, status, author_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([
            $data['post_type'],
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['excerpt'] ?? '',
            $data['featured_image'] ?? null,
            $data['status'],
            $_SESSION['user_id']
        ]);
        $postId = $this->pdo->lastInsertId();
    }
    
    // Gestisci categorie se supportate
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
    do_hook('mycms_after_save_custom_post', $postId, $data);
    
    return $postId;
}
}
