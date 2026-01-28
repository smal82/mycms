<?php

trait AdminPostActions {

    private function handlePost() {
        switch ($this->action) {
            
            case 'reorder_dashboard_widgets':
    header('Content-Type: application/json');
    
    try {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!isset($data['order']) || !is_array($data['order'])) {
            echo json_encode(['success' => false, 'error' => 'Dati non validi']);
            exit;
        }
        
        // Usa il metodo della trait
        $this->db->reorderDashboardWidgets($data['order']);
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
    
    case 'bulk_trash_posts':
    if (!isset($_POST['ids']) || !is_array($_POST['ids'])) {
        header('Location: index.php?action=posts');
        exit;
    }
    
    $count = 0;
    foreach ($_POST['ids'] as $id) {
        $id = (int)$id;
        if ($this->db->trashPost($id)) {
            $count++;
        }
    }
    
    header('Location: index.php?action=posts&bulk_trashed=1&count=' . $count);
    exit;
            
            case 'upload_ga_credentials':
                if (!isset($_FILES['ga_json_file']) || $_FILES['ga_json_file']['error'] !== UPLOAD_ERR_OK) {
        die('Errore upload file');
    }
    
    $jsonContent = file_get_contents($_FILES['ga_json_file']['tmp_name']);
    
    // Valida che sia un JSON valido
    $credentials = json_decode($jsonContent, true);
    if (!$credentials || !isset($credentials['type']) || $credentials['type'] !== 'service_account') {
        die('File JSON non valido. Assicurati di caricare il file Service Account corretto.');
    }
    
    // Salva nel database (criptato per sicurezza - opzionale ma consigliato)
    $this->db->setSetting('ga_service_account_json', base64_encode($jsonContent));
    
    // Salva anche Property ID se presente nel measurement ID
    $measurementId = $this->db->getSetting('google_analytics');
    header('Location: index.php?action=customizer&saved=1');
    exit;

            case 'delete_widget':
                if ($this->user->hasRole(User::ROLE_ADMIN)) {
                    $this->db->deleteWidgetPermanently($_POST['id']);
                }
                header('Location: index.php?action=dashboard_widgets&deleted=1');
                exit;
            
            // === CUSTOM POST TYPES - AZIONI ===
            case 'save_custom_post_type':
                if ($this->user->hasRole(User::ROLE_ADMIN)) {
                    $data = [
                        'name' => $_POST['name'],
                        'singular_label' => $_POST['singular_label'],
                        'plural_label' => $_POST['plural_label'],
                        'slug' => $_POST['slug'],
                        'description' => $_POST['description'] ?? '',
                        'icon' => $_POST['icon'] ?? 'document',
                        'supports' => $_POST['supports'] ?? [],
                        'menu_position' => (int)($_POST['menu_position'] ?? 5),
                        'public' => isset($_POST['public']) ? 1 : 0,
                        'show_in_menu' => isset($_POST['show_in_menu']) ? 1 : 0,
                        'has_archive' => isset($_POST['has_archive']) ? 1 : 0,
                        'hierarchical' => isset($_POST['hierarchical']) ? 1 : 0,
                        'rewrite_slug' => $_POST['rewrite_slug'] ?? $_POST['slug']
                    ];
                    
                    if (isset($_POST['id']) && $_POST['id']) {
                        $data['id'] = $_POST['id'];
                    }
                    
                    $this->db->saveCustomPostType($data);
                    header('Location: index.php?action=custom_post_types&saved=1');
                } else {
                    header('Location: index.php?action=custom_post_types&error=no_permission');
                }
                exit;
            
            case 'delete_custom_post_type':
                if ($this->user->hasRole(User::ROLE_ADMIN)) {
                    $this->db->deleteCustomPostType($_POST['id']);
                    header('Location: index.php?action=custom_post_types&deleted=1');
                } else {
                    header('Location: index.php?action=custom_post_types&error=no_permission');
                }
                exit;
            
            case 'save_custom_post':
                $postType = $_POST['post_type'] ?? 'post';
                $data = [
                    'post_type' => $postType,
                    'title' => $_POST['title'],
                    'slug' => $_POST['slug'],
                    'content' => $_POST['content'] ?? '',
                    'status' => $_POST['status'] ?? 'bozza'
                ];
                
                $cpt = $this->db->getCustomPostType($postType);
                $supports = json_decode($cpt['supports'], true) ?? [];
                
                if (in_array('excerpt', $supports)) {
                    $data['excerpt'] = $_POST['excerpt'] ?? '';
                }
                
                if (in_array('featured_image', $supports)) {
                    $data['featured_image'] = $_POST['featured_image'] ?? null;
                }
                
                if (in_array('categories', $supports)) {
                    $data['categories'] = $_POST['categories'] ?? [];
                }
                
                if (isset($_POST['id']) && $_POST['id']) {
                    $data['id'] = $_POST['id'];
                }
                
                $postId = $this->db->saveCustomPost($data);
                
                if ($postId && isset($_POST['meta'])) {
                    foreach ($_POST['meta'] as $key => $value) {
                        if (!empty($key)) {
                            $this->db->updatePostMeta($postId, $key, $value);
                        }
                    }
                }
                
                header('Location: index.php?action=custom_posts_list&type=' . $postType . '&saved=1');
                exit;
            
            case 'delete_custom_post':
                $postType = $_POST['post_type'] ?? 'post';
                $this->db->deletePost($_POST['id']);
                header('Location: index.php?action=custom_posts_list&type=' . $postType . '&deleted=1');
                exit;
            
            case 'trash_media':
                if (isset($_POST['id'])) {
                    $this->db->trashMedia($_POST['id']);
                    header('Location: index.php?action=media&trashed=1');
                    exit;
                }
                break;

            case 'restore_media':
                if (isset($_POST['id'])) {
                    $this->db->restoreMedia($_POST['id']);
                    header('Location: index.php?action=trash_media&restored=1');
                    exit;
                }
                break;

            case 'delete_media_permanently':
                if (isset($_POST['id'])) {
                    $this->db->deleteMediaPermanently($_POST['id']);
                    header('Location: index.php?action=trash_media&deleted=1');
                    exit;
                }
                break;

            case 'bulk_delete_media':
                if (isset($_POST['media_ids']) && is_array($_POST['media_ids'])) {
                    foreach ($_POST['media_ids'] as $mediaId) {
                        $this->db->deleteMediaPermanently($mediaId);
                    }
                    header('Location: index.php?action=trash_media&deleted=1');
                    exit;
                }
                header('Location: index.php?action=trash_media');
                exit;

            case 'bulk_restore_media':
                if (isset($_POST['media_ids']) && is_array($_POST['media_ids'])) {
                    foreach ($_POST['media_ids'] as $mediaId) {
                        $this->db->restoreMedia($mediaId);
                    }
                    header('Location: index.php?action=trash_media&restored=1');
                    exit;
                }
                header('Location: index.php?action=trash_media');
                exit;

            case 'empty_media_trash':
                $this->db->emptyMediaTrash();
                header('Location: index.php?action=trash_media&emptied=1');
                exit;
            
            case 'update_menu_order':
                if (isset($_POST['order']) && isset($_POST['menu_id'])) {
                    $orderData = json_decode($_POST['order'], true);
                    $this->db->updateMenuItemsOrder($_POST['menu_id'], $orderData);
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false]);
                }
                exit;
            case 'google_analitcs':
                
                header('Location: index.php?action=customizer&saved=1');
                exit;
            
            case 'save_customizer':
                if (isset($_POST['site_title'])) {
                    $this->db->setSetting('site_title', $_POST['site_title']);
                }
                if (isset($_POST['ga_property_id'])) {
                    $this->db->setSetting('ga_property_id', $_POST['ga_property_id']);
                }
                if (isset($_POST['site_description'])) {
                    $this->db->setSetting('site_description', $_POST['site_description']);
                }
                if (isset($_POST['site_logo'])) {
                    $this->db->setSetting('site_logo', $_POST['site_logo']);
                } elseif (isset($_POST['current_logo'])) {
                    $this->db->setSetting('site_logo', $_POST['current_logo']);
                }
                if (isset($_POST['site_favicon'])) {
                    $this->db->setSetting('site_favicon', $_POST['site_favicon']);
                } elseif (isset($_POST['current_favicon'])) {
                    $this->db->setSetting('site_favicon', $_POST['current_favicon']);
                }
                if (isset($_POST['google_analytics'])) {
                    $this->db->setSetting('google_analytics', $_POST['google_analytics']);
                }
                header('Location: index.php?action=customizer&saved=1');
                exit;
            case 'save_impostazioni':
                if (isset($_POST['site_title'])) {
                    $this->db->setSetting('site_title', $_POST['site_title']);
                }
                if (isset($_POST['site_description'])) {
                    $this->db->setSetting('site_description', $_POST['site_description']);
                }
                
                $this->db->setSetting('registrazioni_attive', isset($_POST['registrazioni_attive']) ? '1' : '0');
                if (isset($_POST['site_logo'])) {
                    $this->db->setSetting('site_logo', $_POST['site_logo']);
                } elseif (isset($_POST['current_logo'])) {
                    $this->db->setSetting('site_logo', $_POST['current_logo']);
                }
                if (isset($_POST['site_favicon'])) {
                    $this->db->setSetting('site_favicon', $_POST['site_favicon']);
                } elseif (isset($_POST['current_favicon'])) {
                    $this->db->setSetting('site_favicon', $_POST['current_favicon']);
                }
                header('Location: index.php?action=impostazioni_generali&saved=1');
                exit;
            case 'save_lettura':
                if (isset($_POST['posts_per_page'])) {
                    $this->db->setSetting('posts_per_page', $_POST['posts_per_page']);
                }
                $this->db->setSetting('search_engine_visibility', isset($_POST['search_engine_visibility']) ? '1' : '0');
            
                header('Location: index.php?action=impostazioni_lettura&saved=1');
                exit;
            
            case 'save_page':
                $this->db->savePage($_POST);
                header('Location: index.php?action=pages&saved=1');
                exit;

            case 'delete_page':
                $this->db->deletePage($_POST['id']);
                header('Location: index.php?action=pages&deleted=1');
                exit;

            case 'save_post':
                $postId = $this->db->savePost($_POST);
                
                if (isset($_POST['status']) && $_POST['status'] === 'programmato' && !empty($_POST['scheduled_at'])) {
                    $prefix = DB_PREFIX;
                    $this->db->pdo->prepare("
                        DELETE FROM {$prefix}scheduled_tasks 
                        WHERE task_type = 'publish_post' 
                        AND JSON_EXTRACT(task_data, '$.post_id') = ?
                        AND status = 'pending'
                    ")->execute([$postId]);
                    
                    schedule_task('publish_post', [
                        'post_id' => $postId
                    ], $_POST['scheduled_at']);
                }
                
                header('Location: index.php?action=posts&saved=1');
                exit;

            case 'delete_post':
                $this->db->deletePost($_POST['id']);
                header('Location: index.php?action=posts&deleted=1');
                exit;

            case 'save_category':
                $this->db->saveCategory($_POST);
                header('Location: index.php?action=categories&saved=1');
                exit;

            case 'delete_category':
                $this->db->deleteCategory($_POST['id']);
                header('Location: index.php?action=categories&deleted=1');
                exit;
            
            case 'save_content':
                $this->db->saveContent($_POST);
                header('Location: index.php?action=contents&saved=1');
                exit;
            
            case 'delete_content':
                $this->db->deleteContent($_POST['id']);
                header('Location: index.php?action=contents&deleted=1');
                exit;
            
            case 'change_theme':
                if ($this->user->hasRole(User::ROLE_ADMIN)) {
                    $this->db->setSetting('active_theme', $_POST['theme']);
                }
                header('Location: index.php?action=themes&changed=1');
                exit;
            
            case 'toggle_plugin':
                if ($this->user->hasRole(User::ROLE_ADMIN)) {
                    $plugin = $_POST['plugin'];
                    $active = $this->db->getActivePlugins();
                    if (in_array($plugin, $active)) {
                        $this->db->deactivatePlugin($plugin);
                    } else {
                        $this->db->activatePlugin($plugin);
                    }
                }
                header('Location: index.php?action=plugins');
                exit;
            
            case 'save_menu':
                $menuId = $this->db->saveMenu($_POST);
                header('Location: index.php?action=edit_menu&id=' . ($menuId ?: $_POST['id']) . '&saved=1');
                exit;
            
            case 'delete_menu':
                $this->db->deleteMenu($_POST['id']);
                header('Location: index.php?action=menus&deleted=1');
                exit;
            
            case 'save_menu_item':
                $this->db->saveMenuItem($_POST);
                header('Location: index.php?action=edit_menu&id=' . $_POST['menu_id'] . '&item_saved=1');
                exit;
            
            case 'delete_menu_item':
                $this->db->deleteMenuItem($_POST['id']);
                header('Location: index.php?action=edit_menu&id=' . $_POST['menu_id'] . '&item_deleted=1');
                exit;
            
            case 'toggle_dashboard_widget':
                $this->db->toggleDashboardWidget($_POST['id']);
                header('Location: index.php?action=dashboard_widgets');
                exit;
            
            case 'save_theme_widget':
    $widgetType = $_POST['widget_type'];
    $config = [];
    
    // Widget standard del sistema
    if (in_array($widgetType, ['menu', 'text', 'recent_posts', 'auth'])) {
        switch($widgetType) {
            case 'menu':
                $config = [
                    'menu_id' => (int)$_POST['menu_id'],
                    'title' => $_POST['widget_title'] ?? ''
                ];
                break;
                
            case 'text':
                $config = [
                    'title' => $_POST['widget_title'],
                    'content' => $_POST['widget_content']
                ];
                break;
                
            case 'recent_posts':
                $config = [
                    'title' => $_POST['widget_title'],
                    'limit' => (int)$_POST['posts_limit'],
                    'show_date' => isset($_POST['show_date']),
                    'show_excerpt' => isset($_POST['show_excerpt'])
                ];
                break;
                
            case 'auth':
                $config = [
                    'title' => $_POST['widget_title'] ?? 'Area Utente'
                ];
                break;
        }
    } 
    // Widget da plugin
    else if (is_plugin_widget($widgetType)) {
        // Hook: permette al plugin di processare e validare i suoi dati
        $config = apply_hook('mycms_plugin_widget_save', [], $widgetType, $_POST);
    }
    
    // Salva nel database con prefisso corretto
    $data = [
        'area_name' => $_POST['area_name'],
        'widget_type' => $widgetType,
        'config' => json_encode($config),
        'position' => (int)$_POST['position'],
        'is_active' => (int)$_POST['is_active']
    ];
    
    $this->db->saveThemeWidget($data);
    header('Location: index.php?action=theme_widgets&saved=1');
    exit;

            
            case 'delete_theme_widget':
                $this->db->deleteThemeWidget($_POST['id']);
                header('Location: index.php?action=theme_widgets&deleted=1');
                exit;
                
            case 'toggle_theme_widget':
    $this->db->toggleThemeWidget($_POST['id']);
    header('Location: index.php?action=theme_widgets&toggled=1');
    exit;

case 'reorder_theme_widgets':
    header('Content-Type: application/json');
    
    try {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!isset($data['order']) || !is_array($data['order'])) {
            echo json_encode(['success' => false, 'error' => 'Dati non validi']);
            exit;
        }
        
        $this->db->reorderThemeWidgets($data['order']);
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;

case 'edit_theme_widget':
    // Questo verrà gestito nella view, non è un'azione POST
    break;
    
    case 'update_theme_widget':
    $widgetType = $_POST['widget_type'];
    $config = [];
    
    // Widget standard del sistema
    if (in_array($widgetType, ['menu', 'text', 'recent_posts', 'auth'])) {
        switch($widgetType) {
            case 'menu':
                $config = [
                    'menu_id' => (int)$_POST['menu_id'],
                    'title' => $_POST['widget_title'] ?? ''
                ];
                break;
                
            case 'text':
                $config = [
                    'title' => $_POST['widget_title'],
                    'content' => $_POST['widget_content']
                ];
                break;
                
            case 'recent_posts':
                $config = [
                    'title' => $_POST['widget_title'],
                    'limit' => (int)$_POST['posts_limit'],
                    'show_date' => isset($_POST['show_date']),
                    'show_excerpt' => isset($_POST['show_excerpt'])
                ];
                break;
                
            case 'auth':
                $config = [
                    'title' => $_POST['widget_title'] ?? 'Area Utente'
                ];
                break;
        }
    }
    // Widget da plugin
    else if (is_plugin_widget($widgetType)) {
        $config = apply_hook('mycms_plugin_widget_save', [], $widgetType, $_POST);
    }
    
    $data = [
        'id' => (int)$_POST['id'],
        'area_name' => $_POST['area_name'],
        'widget_type' => $widgetType,
        'config' => json_encode($config),
        'position' => (int)$_POST['position'],
        'is_active' => isset($_POST['is_active']) ? 1 : 0
    ];
    
    $this->db->saveThemeWidget($data);
    header('Location: index.php?action=theme_widgets&saved=1');
    exit;

            case 'save_user':
                if ($this->user->hasRole(User::ROLE_ADMIN)) {
                    if (isset($_POST['id']) && $_POST['id']) {
                        $this->user->updateUser($_POST['id'], $_POST);
                    } else {
                        $this->user->createUser($_POST);
                    }
                }
                header('Location: index.php?action=users&saved=1');
                exit;
            
            case 'delete_user':
                if ($this->user->hasRole(User::ROLE_ADMIN)) {
                    $this->user->deleteUser($_POST['id']);
                }
                header('Location: index.php?action=users&deleted=1');
                exit;
            
            case 'update_profile':
                $currentUser = $this->user->getCurrentUser();
                $result = $this->user->updateUser($currentUser['id'], $_POST);
                if (isset($result['success'])) {
                    header('Location: index.php?action=profile&saved=1');
                } else {
                    header('Location: index.php?action=profile&error=' . urlencode($result['error']));
                }
                exit;
                
            case 'trash_post':
                if (isset($_POST['id'])) {
                    $this->db->trashPost($_POST['id']);
                    header('Location: index.php?action=posts&trashed=1');
                    exit;
                }
                break;

            case 'restore_post':
                if (isset($_POST['id'])) {
                    $this->db->restorePost($_POST['id']);
                    header('Location: index.php?action=trash_posts&restored=1');
                    exit;
                }
                break;

            case 'delete_post_permanently':
                if (isset($_POST['id'])) {
                    $this->db->deletePostPermanently($_POST['id']);
                    header('Location: index.php?action=trash_posts&deleted=1');
                    exit;
                }
                break;

            case 'bulk_restore_posts':
                if (isset($_POST['post_ids']) && is_array($_POST['post_ids'])) {
                    foreach ($_POST['post_ids'] as $postId) {
                        $this->db->restorePost($postId);
                    }
                    header('Location: index.php?action=trash_posts&restored=1');
                    exit;
                }
                header('Location: index.php?action=trash_posts');
                exit;

            case 'empty_post_trash':
                $this->db->emptyPostTrash();
                header('Location: index.php?action=trash_posts&emptied=1');
                exit;

            case 'trash_page':
                if (isset($_POST['id'])) {
                    $this->db->trashPage($_POST['id']);
                    header('Location: index.php?action=pages&trashed=1');
                    exit;
                }
                break;

            case 'restore_page':
                if (isset($_POST['id'])) {
                    $this->db->restorePage($_POST['id']);
                    header('Location: index.php?action=trash_pages&restored=1');
                    exit;
                }
                break;

            case 'delete_page_permanently':
                if (isset($_POST['id'])) {
                    $this->db->deletePagePermanently($_POST['id']);
                    header('Location: index.php?action=trash_pages&deleted=1');
                    exit;
                }
                break;

            case 'bulk_delete_pages':
                if (isset($_POST['page_ids']) && is_array($_POST['page_ids'])) {
                    foreach ($_POST['page_ids'] as $pageId) {
                        $this->db->deletePagePermanently($pageId);
                    }
                    header('Location: index.php?action=trash_pages&deleted=1');
                    exit;
                }
                header('Location: index.php?action=trash_pages');
                exit;

            case 'empty_page_trash':
                $this->db->emptyPageTrash();
                header('Location: index.php?action=trash_pages&emptied=1');
                exit;
                
                        case 'delete_backup':
                if ($this->user->hasRole(User::ROLE_ADMIN)) {
                    $filename = basename($_POST['filename']);
                    $filepath = BASE_PATH . '/backups/' . $filename;
                    
                    if (file_exists($filepath) && unlink($filepath)) {
                        echo json_encode(['success' => true, 'message' => 'Backup eliminato con successo']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Errore nell\'eliminazione del backup']);
                    }
                }
                exit;
            
            case 'delete_all_backups':
                if ($this->user->hasRole(User::ROLE_ADMIN)) {
                    $backupDir = BASE_PATH . '/backups/';
                    $files = glob($backupDir . '*.zip');
                    $deleted = 0;
                    
                    foreach ($files as $file) {
                        if (is_file($file) && unlink($file)) {
                            $deleted++;
                        }
                    }
                    
                    echo json_encode(['success' => true, 'message' => "Eliminati $deleted backup"]);
                }
                exit;
            
            case 'save_backup_settings':
                if ($this->user->hasRole(User::ROLE_ADMIN)) {
                    $maxBackups = intval($_POST['max_backups']);
                    if ($maxBackups < 1) $maxBackups = 5;
                    
                    $stmt = $this->db->pdo->prepare("
                        INSERT INTO " . DB_PREFIX . "settings (setting_key, setting_value) 
                        VALUES ('backup_max_limit', :value)
                        ON DUPLICATE KEY UPDATE setting_value = :value
                    ");
                    $stmt->execute([':value' => $maxBackups]);
                    
                    echo json_encode(['success' => true, 'message' => 'Impostazioni salvate con successo']);
                }
                exit;

        }
    }
}
