<?php
require_once '../core/bootstrap.php';

// Solo admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /admin/login.php');
    exit;
}

$stats = get_task_stats();
$pendingTasks = get_scheduled_tasks('pending', 50);
$recentCompleted = get_scheduled_tasks('completed', 20);
$failedTasks = get_scheduled_tasks('failed', 20);

include 'header.php';
?>

<div class="admin-content">
    <h1>📅 Task Programmati</h1>
    
    <!-- Statistiche -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>⏳ In Attesa</h3>
            <p class="stat-number"><?php echo $stats['pending']; ?></p>
        </div>
        <div class="stat-card">
            <h3>✅ Completati</h3>
            <p class="stat-number"><?php echo $stats['completed']; ?></p>
        </div>
        <div class="stat-card">
            <h3>❌ Falliti</h3>
            <p class="stat-number"><?php echo $stats['failed']; ?></p>
        </div>
        <div class="stat-card">
            <h3>🔄 In Esecuzione</h3>
            <p class="stat-number"><?php echo $stats['running']; ?></p>
        </div>
    </div>
    
    <!-- Task in Attesa -->
    <h2>⏳ Task in Attesa</h2>
    <?php if (!empty($pendingTasks)): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Programmato per</th>
                    <th>Creato</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingTasks as $task): 
                    $data = json_decode($task['task_data'], true);
                ?>
                    <tr>
                        <td>#<?php echo $task['id']; ?></td>
                        <td><?php echo htmlspecialchars($task['task_type']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($task['scheduled_at'])); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($task['created_at'])); ?></td>
                        <td>
                            <?php if ($task['task_type'] === 'publish_post' && isset($data['post_id'])): ?>
                                <a href="/admin/edit-post.php?id=<?php echo $data['post_id']; ?>">Vedi Post</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nessun task in attesa.</p>
    <?php endif; ?>
    
    <!-- Task Falliti -->
    <?php if (!empty($failedTasks)): ?>
        <h2>❌ Task Falliti</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Errore</th>
                    <th>Eseguito</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($failedTasks as $task): ?>
                    <tr>
                        <td>#<?php echo $task['id']; ?></td>
                        <td><?php echo htmlspecialchars($task['task_type']); ?></td>
                        <td><?php echo htmlspecialchars($task['error_message']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($task['executed_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
