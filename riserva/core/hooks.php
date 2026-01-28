<?php
/**
 * FILE: /core/hooks.php
 * Sistema hook generico per MyCMS
 */

global $mycms_hooks;
$mycms_hooks = [];

/**
 * Registra un hook
 * 
 * @param string $hookName Nome dell'hook
 * @param callable $callback Funzione da eseguire
 * @param int $priority Priorità di esecuzione (default 10)
 * 
 * Esempio:
 * add_hook('mycms_head', function() {
 *     echo '<meta name="custom" content="value">';
 * });
 */
function add_hook($hookName, $callback, $priority = 10) {
    global $mycms_hooks;
    if (!isset($mycms_hooks[$hookName])) {
        $mycms_hooks[$hookName] = [];
    }
    $mycms_hooks[$hookName][] = ['callback' => $callback, 'priority' => $priority];
}

/**
 * Esegue un hook (action)
 * 
 * @param string $hookName Nome dell'hook
 * @param mixed ...$args Argomenti da passare alle callback
 * 
 * Esempio:
 * do_hook('mycms_after_save_post', $postId, $data);
 */
function do_hook($hookName, ...$args) {
    global $mycms_hooks;
    if (isset($mycms_hooks[$hookName])) {
        usort($mycms_hooks[$hookName], fn($a, $b) => $a['priority'] <=> $b['priority']);
        foreach ($mycms_hooks[$hookName] as $hook) {
            call_user_func_array($hook['callback'], $args);
        }
    }
}

/**
 * Applica un hook con filtro (modifica un valore)
 * 
 * @param string $hookName Nome dell'hook
 * @param mixed $value Valore da filtrare
 * @return mixed Valore modificato
 * 
 * Esempio:
 * $post = apply_hook('mycms_post_content', $post);
 */
function apply_hook($hookName, $value) {
    global $mycms_hooks;
    if (isset($mycms_hooks[$hookName])) {
        usort($mycms_hooks[$hookName], fn($a, $b) => $a['priority'] <=> $b['priority']);
        foreach ($mycms_hooks[$hookName] as $hook) {
            $value = call_user_func($hook['callback'], $value);
        }
    }
    return $value;
}

/**
 * Rimuove un hook
 * 
 * @param string $hookName Nome dell'hook
 * @param callable $callback Callback da rimuovere
 */
function remove_hook($hookName, $callback) {
    global $mycms_hooks;
    if (isset($mycms_hooks[$hookName])) {
        foreach ($mycms_hooks[$hookName] as $key => $hook) {
            if ($hook['callback'] === $callback) {
                unset($mycms_hooks[$hookName][$key]);
            }
        }
    }
}

/**
 * ========================================
 * SISTEMA WIDGET PER PLUGIN
 * ========================================
 */

/**
 * Registra un widget personalizzato da un plugin
 * 
 * @param string $widget_type Tipo univoco del widget (es: 'meteo', 'newsletter')
 * @param array $config Configurazione del widget
 *   - name: Nome visualizzato (es: "Widget Meteo")
 *   - icon: Emoji o icona (es: "🌤️")
 *   - description: Descrizione breve (opzionale)
 * 
 * Esempio:
 * register_plugin_widget('meteo', [
 *     'name' => 'Widget Meteo',
 *     'icon' => '🌤️',
 *     'description' => 'Mostra le previsioni meteo'
 * ]);
 */
function register_plugin_widget($widget_type, $config) {
    global $mycms_plugin_widgets;
    
    if (!isset($mycms_plugin_widgets)) {
        $mycms_plugin_widgets = [];
    }
    
    // Validazione base
    if (empty($widget_type) || !is_array($config)) {
        return false;
    }
    
    // Registra il widget
    $mycms_plugin_widgets[$widget_type] = array_merge([
        'name' => $widget_type,
        'icon' => '🔌',
        'description' => ''
    ], $config);
    
    return true;
}

/**
 * Ottieni tutti i widget registrati dai plugin
 * 
 * @return array Array di widget registrati
 */
function get_registered_plugin_widgets() {
    global $mycms_plugin_widgets;
    return $mycms_plugin_widgets ?? [];
}

/**
 * Verifica se un widget type è un widget da plugin
 * 
 * @param string $widget_type Tipo del widget da verificare
 * @return bool True se è un widget da plugin
 */
function is_plugin_widget($widget_type) {
    $pluginWidgets = get_registered_plugin_widgets();
    return isset($pluginWidgets[$widget_type]);
}

/**
 * ========================================
 * SISTEMA ROUTING PER PLUGIN
 * ========================================
 */

/**
 * Registra una route personalizzata per un plugin
 * 
 * @param string $pattern Pattern della route (regex o stringa)
 * @param callable $callback Funzione da eseguire quando la route matcha
 * 
 * Esempio:
 * register_plugin_route('/ricerca', function($matches) {
 *     $query = $_GET['q'] ?? '';
 *     return [
 *         'title' => 'Risultati ricerca',
 *         'content' => '<div>Risultati per: ' . $query . '</div>'
 *     ];
 * });
 * 
 * Con parametri:
 * register_plugin_route('/meteo/([a-z]+)', function($matches) {
 *     $citta = $matches[1];
 *     return [
 *         'title' => "Meteo $citta",
 *         'content' => '<div>Dati meteo...</div>'
 *     ];
 * });
 */
function register_plugin_route($pattern, $callback) {
    global $mycms_plugin_routes;
    
    if (!isset($mycms_plugin_routes)) {
        $mycms_plugin_routes = [];
    }
    
    $mycms_plugin_routes[] = [
        'pattern' => $pattern,
        'callback' => $callback
    ];
}

/**
 * Ottieni tutte le route registrate dai plugin
 * 
 * @return array Array di route registrate
 */
function get_plugin_routes() {
    global $mycms_plugin_routes;
    return $mycms_plugin_routes ?? [];
}

/**
 * Verifica se una URI matcha una route di un plugin
 * Restituisce i dati del plugin se trova un match, altrimenti null
 * 
 * @param string $uri URI da verificare
 * @return array|null Dati del plugin o null
 */
function match_plugin_route($uri) {
    $routes = get_plugin_routes();
    
    foreach ($routes as $route) {
        $pattern = $route['pattern'];
        
        // Se il pattern è una stringa semplice (non regex)
        if (strpos($pattern, '(') === false && strpos($pattern, ')') === false) {
            if ($uri === $pattern || rtrim($uri, '/') === $pattern) {
                $result = call_user_func($route['callback'], []);
                if ($result && is_array($result)) {
                    return $result;
                }
            }
        } 
        // Se è una regex
        else {
            if (preg_match('#^' . $pattern . '$#', $uri, $matches)) {
                $result = call_user_func($route['callback'], $matches);
                if ($result && is_array($result)) {
                    return $result;
                }
            }
        }
    }
    
    return null;
}

?>
