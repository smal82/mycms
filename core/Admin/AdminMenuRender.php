<?php

trait AdminMenuRender {

    public function renderMenuItemsOptions($items, $parentId = null, $level = 0, $excludeId = null) {
        $output = '';

        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId && $item['id'] != $excludeId) {
                $indent = str_repeat('—', $level);
                $output .= '<option value="' . $item['id'] . '">';
                $output .= $indent . ' ' . htmlspecialchars($item['title']);
                $output .= '</option>';

                $output .= $this->renderMenuItemsOptions($items, $item['id'], $level + 1, $excludeId);
            }
        }

        return $output;
    }

    public function renderMenuItemsTree($items, $parentId = null, $level = 0) {
    $output = '';

    foreach ($items as $item) {
        if ($item['parent_id'] == $parentId) {
            $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
            $levelClass = $level > 0 ? ' menu-item-level-' . $level : '';
            
            $output .= '<tr data-id="' . $item['id'] . '" class="menu-item' . $levelClass . '">';
            
            // Colonna drag handle
            $output .= '<td class="drag-handle">⋮⋮</td>';
            
            // Colonna titolo con indentazione
            $output .= '<td>' . $indent . htmlspecialchars($item['title']) . '</td>';
            
            // Colonna URL
            $output .= '<td>' . htmlspecialchars($item['url']) . '</td>';
            
            // Colonna azioni
            $output .= '<td>';
            $output .= '<button type="button" class="btn-icon btn-edit-icon" onclick="editMenuItem(' . 
                       $item['id'] . ', \'' . 
                       htmlspecialchars(addslashes($item['title']), ENT_QUOTES) . '\', \'' . 
                       htmlspecialchars(addslashes($item['url']), ENT_QUOTES) . '\', ' . 
                       ($item['parent_id'] ?: 'null') . ', \'' . 
                       htmlspecialchars($item['target']) . '\')" title="Modifica">✏️</button>';
            
            $output .= '<form method="POST" action="index.php?action=delete_menu_item" style="display:inline;" onsubmit="return confirm(\'Sicuro di voler eliminare questa voce?\');">';
            $output .= '<input type="hidden" name="id" value="' . $item['id'] . '">';
            $output .= '<input type="hidden" name="menu_id" value="' . $item['menu_id'] . '">';
            $output .= '<button type="submit" class="btn-icon btn-delete-icon" title="Elimina">🗑️</button>';
            $output .= '</form>';
            $output .= '</td>';
            
            $output .= '</tr>';

            // Ricorsione per i figli
            $output .= $this->renderMenuItemsTree($items, $item['id'], $level + 1);
        }
    }

    return $output;
}

}
