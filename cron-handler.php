<?php
/**
 * cron-handler.php PROD - Log solo errori
 */

$lockFile = __DIR__ . '/cron.lock';
if (file_exists($lockFile) && (time() - filemtime($lockFile)) > 300) {
    unlink($lockFile);
}
if (file_exists($lockFile)) {
    return;
}
touch($lockFile);

try {
    require_once __DIR__ . '/core/bootstrap.php';
    
    $db = new Database();
    $prefix = DB_PREFIX;
    
    $stmt = $db->pdo->prepare("
        SELECT * FROM {$prefix}scheduled_tasks 
        WHERE status = 'pending' AND scheduled_at <= NOW() 
        ORDER BY scheduled_at ASC LIMIT 10
    ");
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($tasks as $task) {
        $updateStmt = $db->pdo->prepare("
            UPDATE {$prefix}scheduled_tasks SET status = 'running' 
            WHERE id = ? AND status = 'pending'
        ");
        $updateStmt->execute([$task['id']]);
        
        if ($updateStmt->rowCount() === 0) continue;
        
        try {
            executeTask($task, $db);
            
            $db->pdo->prepare("
                UPDATE {$prefix}scheduled_tasks 
                SET status = 'completed', executed_at = NOW() WHERE id = ?
            ")->execute([$task['id']]);
            
        } catch (Exception $e) {
            error_log("CRON TASK {$task['id']} FAILED: " . $e->getMessage());
            $db->pdo->prepare("
                UPDATE {$prefix}scheduled_tasks 
                SET status = 'failed', error_message = ?, executed_at = NOW() 
                WHERE id = ?
            ")->execute([$e->getMessage(), $task['id']]);
        }
    }
    
} catch (Exception $e) {
    error_log("CRON HANDLER ERROR: " . $e->getMessage());
} finally {
    if (file_exists($lockFile)) unlink($lockFile);
}

function executeTask($task, $db) {
    $data = json_decode($task['task_data'], true);
    if (!$data) throw new Exception('JSON invalido');
    
    if ($task['task_type'] === 'publish_post') {
        publishPost($data['post_id'], $db);
        return;
    }
    
    if ($task['task_type'] === 'publish_page') {
        publishPage($data['page_id'], $db);
        return;
    }
    
    $handled = apply_cron_task_hook($task['task_type'], $data, $db);
    if (!$handled) throw new Exception("Task type: {$task['task_type']}");
}

function apply_cron_task_hook($taskType, $data, $db) {
    global $cron_task_handlers;
    if (isset($cron_task_handlers[$taskType]) && is_callable($cron_task_handlers[$taskType])) {
        $cron_task_handlers[$taskType]($data, $db);
        return true;
    }
    return false;
}

function publishPost($postId, $db) {
    global $prefix;
    $checkStmt = $db->pdo->prepare("
        SELECT id, title, status FROM {$prefix}posts WHERE id = ?
    ");
    $checkStmt->execute([$postId]);
    $post = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$post || $post['status'] === 'pubblicato') return;
    
    $stmt = $db->pdo->prepare("UPDATE {$prefix}posts SET status = 'pubblicato' WHERE id = ?");
    $stmt->execute([$postId]);
    
    if (function_exists('do_action')) {
        do_action('after_scheduled_post_published', $postId, $post);
    }
}

function publishPage($pageId, $db) {
    global $prefix;
    $checkStmt = $db->pdo->prepare("SELECT id, title, status FROM {$prefix}pages WHERE id = ?");
    $checkStmt->execute([$pageId]);
    $page = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$page || $page['status'] === 'pubblicato') return;
    
    $stmt = $db->pdo->prepare("UPDATE {$prefix}pages SET status = 'pubblicato' WHERE id = ?");
    $stmt->execute([$pageId]);
    
    if (function_exists('do_action')) {
        do_action('after_scheduled_page_published', $pageId, $page);
    }
}
?>