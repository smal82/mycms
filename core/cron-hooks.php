<?php
/**
 * FILE: /core/cron-hooks.php
 * Sistema di hook per task programmati personalizzati
 * Permette ai plugin di registrare i propri task schedulati
 */

// Array globale per registrare gli handler
global $cron_task_handlers;
$cron_task_handlers = [];

/**
 * Registra un handler per un tipo di task personalizzato
 * 
 * @param string $taskType Nome del tipo di task (es: 'send_newsletter', 'backup_db')
 * @param callable $callback Funzione da eseguire quando il task viene eseguito
 * 
 * Esempio di utilizzo in un plugin:
 * add_cron_task_handler('send_newsletter', function($data, $db) {
 *     $emails = $data['emails'];
 *     foreach ($emails as $email) {
 *         mail($email, 'Newsletter', 'Contenuto...');
 *     }
 * });
 */
function add_cron_task_handler($taskType, $callback) {
    global $cron_task_handlers;
    
    if (!is_callable($callback)) {
        throw new Exception("Il callback per il task '$taskType' non è valido");
    }
    
    $cron_task_handlers[$taskType] = $callback;
}

/**
 * Programma un nuovo task
 * 
 * @param string $taskType Tipo di task
 * @param array $taskData Dati del task (verrà convertito in JSON)
 * @param string $scheduledAt Data/ora di esecuzione (formato: 'Y-m-d H:i:s')
 * @return int ID del task creato
 * 
 * Esempio:
 * schedule_task('send_newsletter', [
 *     'emails' => ['user1@example.com', 'user2@example.com'],
 *     'subject' => 'Newsletter Gennaio'
 * ], '2026-01-25 09:00:00');
 */
function schedule_task($taskType, $taskData, $scheduledAt) {
    $db = new Database();
    $prefix = DB_PREFIX;
    
    // Valida la data
    $timestamp = strtotime($scheduledAt);
    if ($timestamp === false) {
        throw new Exception("Data non valida: $scheduledAt");
    }
    
    $stmt = $db->pdo->prepare("
        INSERT INTO {$prefix}scheduled_tasks (task_type, task_data, scheduled_at, status)
        VALUES (?, ?, ?, 'pending')
    ");
    
    $stmt->execute([
        $taskType,
        json_encode($taskData),
        date('Y-m-d H:i:s', $timestamp)
    ]);
    
    return $db->pdo->lastInsertId();
}

/**
 * Cancella un task programmato (solo se ancora pending)
 * 
 * @param int $taskId ID del task da cancellare
 * @return bool
 */
function cancel_scheduled_task($taskId) {
    $db = new Database();
    $prefix = DB_PREFIX;
    
    $stmt = $db->pdo->prepare("
        DELETE FROM {$prefix}scheduled_tasks 
        WHERE id = ? AND status = 'pending'
    ");
    
    return $stmt->execute([$taskId]);
}

/**
 * Ottieni tutti i task programmati
 * 
 * @param string|null $status Filtra per status (pending, completed, failed, running)
 * @param int $limit Numero massimo di risultati
 * @return array
 */
function get_scheduled_tasks($status = null, $limit = 100) {
    $db = new Database();
    $prefix = DB_PREFIX;
    
    if ($status) {
        $stmt = $db->pdo->prepare("
            SELECT * FROM {$prefix}scheduled_tasks 
            WHERE status = ? 
            ORDER BY scheduled_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$status, $limit]);
    } else {
        $stmt = $db->pdo->prepare("
            SELECT * FROM {$prefix}scheduled_tasks 
            ORDER BY scheduled_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
    }
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Ottieni statistiche sui task
 * 
 * @return array Contatori per status
 */
function get_task_stats() {
    $db = new Database();
    $prefix = DB_PREFIX;
    
    $stmt = $db->pdo->query("
        SELECT status, COUNT(*) as count 
        FROM {$prefix}scheduled_tasks 
        GROUP BY status
    ");
    
    $stats = [
        'pending' => 0,
        'running' => 0,
        'completed' => 0,
        'failed' => 0
    ];
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stats[$row['status']] = (int)$row['count'];
    }
    
    return $stats;
}

/**
 * Pulisci task completati più vecchi di X giorni
 * 
 * @param int $days Numero di giorni (default: 30)
 * @return int Numero di task eliminati
 */
function cleanup_old_tasks($days = 30) {
    $db = new Database();
    $prefix = DB_PREFIX;
    
    $stmt = $db->pdo->prepare("
        DELETE FROM {$prefix}scheduled_tasks 
        WHERE status IN ('completed', 'failed') 
        AND executed_at < DATE_SUB(NOW(), INTERVAL ? DAY)
    ");
    
    $stmt->execute([$days]);
    
    return $stmt->rowCount();
}
?>
