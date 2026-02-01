<?php
/**
 * Widget per Rss Aggregator
 * Mostra le info riguardo i feed importati
 */
class Widget_rss {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function render($config = []) {
        ?>
        <div class="dashboard-widget widget-rss">
            <h3>📰  Feed RSS</h3>
        <?php
         try {
             $prefix = DB_PREFIX;

$stmt = $this->db->pdo->prepare("
    SELECT nome, elementi_importati, prossimo_import
    FROM `{$prefix}rss_feeds`
    WHERE stato = :stato
    ORDER BY prossimo_import ASC
");

$stmt->execute([
    'stato' => 'attivo'
]);

$feeds = $stmt->fetchAll(PDO::FETCH_ASSOC);

$prefix = DB_PREFIX;

$stmt = $this->db->pdo->prepare("
    SELECT nome, elementi_importati, prossimo_import
    FROM `{$prefix}rss_feeds`
    WHERE stato = :stato
    ORDER BY prossimo_import ASC
");

$stmt->execute(['stato' => 'attivo']);
$feeds = $stmt->fetchAll(PDO::FETCH_ASSOC);

$now = new DateTime();
?>

<table class="admin-table">
    <thead>
        <tr>
            <th>Feed</th>
            <th>Elementi importati</th>
            <th>Prossimo check</th>
            <th>Stato</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($feeds as $row): ?>

            <?php
            $prossimo = new DateTime($row['prossimo_import']);

            if ($prossimo > $now) {
                $diff = $now->diff($prossimo);
                $ore = $diff->h + ($diff->d * 24);
                $minuti = $diff->i;
                $mancano = $ore . "h " . str_pad($minuti, 2, '0', STR_PAD_LEFT) . "m";
                $badge = '<span class="badge badge-success">OK</span>';
            } else {
                $mancano = "Scaduto";
                $badge = '<span class="badge badge-warning">In ritardo</span>';
            }
            ?>

            <tr>
                <td><?= htmlspecialchars($row['nome']) ?></td>
                <td><?= (int)$row['elementi_importati'] ?></td>
                <td><?= $mancano ?></td>
                <td><?= $badge ?></td>
            </tr>

        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php
         } catch (Exception $e) {
            ?>
            <div class="dashboard-widget widget-internal-analytics">
                <h3>📰  Feed RSS</h3>
                <div class="error-state">
                    <p>Errore nel caricamento dei dati dei Feed RSS</p>
                    <small><?php echo htmlspecialchars($e->getMessage()); ?></small>
                </div>
            </div>
            <?php
            return;
        }
    }
}