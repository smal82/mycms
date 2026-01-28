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
?>
