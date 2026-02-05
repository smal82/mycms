<?php
trait WidgetTrait {
    public function getDashboardWidgets() {
    $stmt = $this->pdo->prepare("
        SELECT * FROM " . DB_PREFIX . "dashboard_widgets 
        ORDER BY position ASC, id ASC
    ");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Attiva/Disattiva un widget tema
 */
public function toggleThemeWidget($id) {
    $stmt = $this->pdo->prepare("UPDATE " . $this->table('theme_widget_areas') . " SET is_active = NOT is_active WHERE id=?");
    return $stmt->execute([$id]);
}

/**
 * Riordina widget tema dopo drag & drop
 */
public function reorderThemeWidgets($order) {
    $prefix = $this->prefix;
    
    $this->pdo->beginTransaction();
    
    try {
        foreach ($order as $item) {
            $stmt = $this->pdo->prepare("
                UPDATE {$prefix}theme_widget_areas 
                SET position = ? 
                WHERE id = ?
            ");
            $stmt->execute([$item['position'], $item['id']]);
        }
        
        $this->pdo->commit();
        return true;
    } catch (Exception $e) {
        $this->pdo->rollBack();
        throw $e;
    }
}

/**
 * Ottieni un singolo widget tema per modifica
 */
public function getThemeWidgetById($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('theme_widget_areas') . " WHERE id=?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}


public function getAllDashboardWidgetsForManagement() {
    return $this->pdo->query("SELECT * FROM " . $this->table('dashboard_widgets') . " ORDER BY position")->fetchAll();
}

public function toggleDashboardWidget($id) {
    $stmt = $this->pdo->prepare("UPDATE " . $this->table('dashboard_widgets') . " SET is_active = NOT is_active WHERE id=?");
    return $stmt->execute([$id]);
}

/**
 * Aggiorna le posizioni dei widget dashboard dopo drag & drop
 */
public function reorderDashboardWidgets($order) {
    $prefix = $this->prefix;
    
    $this->pdo->beginTransaction();
    
    try {
        foreach ($order as $item) {
            $stmt = $this->pdo->prepare("
                UPDATE {$prefix}dashboard_widgets 
                SET position = ? 
                WHERE id = ?
            ");
            $stmt->execute([$item['position'], $item['id']]);
        }
        
        $this->pdo->commit();
        return true;
    } catch (Exception $e) {
        $this->pdo->rollBack();
        throw $e;
    }
}


/**
 * Sincronizza widget disponibili nel filesystem con il database
 * Aggiunge widget nuovi, rimuove widget cancellati fisicamente
 */
public function syncDashboardWidgets($availableWidgets) {
    $prefix = $this->prefix;
    
    // Ottieni widget esistenti nel DB
    $stmt = $this->pdo->query("SELECT widget_type FROM {$prefix}dashboard_widgets");
    $dbWidgets = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Aggiungi widget nuovi (presenti nel filesystem ma non nel DB)
    foreach ($availableWidgets as $widget) {
        if (!in_array($widget, $dbWidgets)) {
            // Calcola prossima posizione
            $maxPos = $this->pdo->query("SELECT MAX(position) FROM {$prefix}dashboard_widgets")->fetchColumn();
            $nextPos = ($maxPos ?? 0) + 1;
            
            $stmt = $this->pdo->prepare("
                INSERT INTO {$prefix}dashboard_widgets (widget_type, position, is_active, config) 
                VALUES (?, ?, 0, NULL)
            ");
            $stmt->execute([$widget, $nextPos]);
        }
    }
    
    // Rimuovi widget cancellati (presenti nel DB ma non nel filesystem)
    foreach ($dbWidgets as $dbWidget) {
        if (!in_array($dbWidget, $availableWidgets)) {
            $stmt = $this->pdo->prepare("DELETE FROM {$prefix}dashboard_widgets WHERE widget_type = ?");
            $stmt->execute([$dbWidget]);
        }
    }
}

/**
 * Elimina widget dal DB e dal filesystem
 */
public function deleteWidgetPermanently($id) {
    $prefix = $this->prefix;
    
    // Ottieni il widget_type prima di eliminarlo
    $stmt = $this->pdo->prepare("SELECT widget_type FROM {$prefix}dashboard_widgets WHERE id = ?");
    $stmt->execute([$id]);
    $widgetType = $stmt->fetchColumn();
    
    if (!$widgetType) {
        return false;
    }
    
    // Elimina dal database
    $stmt = $this->pdo->prepare("DELETE FROM {$prefix}dashboard_widgets WHERE id = ?");
    $stmt->execute([$id]);
    
    // Elimina il file fisico
    $widgetFile = BASE_PATH . '/core/widgets/Widget_' . $widgetType . '.php';
    if (file_exists($widgetFile)) {
        unlink($widgetFile);
    }
    
    return true;
}

    
    // === WIDGET TEMA ===
    public function getThemeWidgets($area) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table('theme_widget_areas') . " WHERE area_name=? AND is_active=1 ORDER BY position");
        $stmt->execute([$area]);
        return $stmt->fetchAll();
    }
    
    public function getAllThemeWidgets() {
        return $this->pdo->query("SELECT * FROM " . $this->table('theme_widget_areas') . " ORDER BY area_name, position")->fetchAll();
    }
    
    public function saveThemeWidget($data) {
        if (isset($data['id']) && $data['id']) {
            $stmt = $this->pdo->prepare("UPDATE " . $this->table('theme_widget_areas') . " SET area_name=?, widget_type=?, position=?, is_active=?, config=? WHERE id=?");
            return $stmt->execute([$data['area_name'], $data['widget_type'], $data['position'], $data['is_active'], $data['config'], $data['id']]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO " . $this->table('theme_widget_areas') . " (area_name, widget_type, position, is_active, config) VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute([$data['area_name'], $data['widget_type'], $data['position'], $data['is_active'], $data['config']]);
        }
    }
    
    public function deleteThemeWidget($id) {
        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table('theme_widget_areas') . " WHERE id=?");
        return $stmt->execute([$id]);
    }
}
