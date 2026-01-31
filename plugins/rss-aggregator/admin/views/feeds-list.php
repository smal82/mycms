<?php
/**
 * View: Lista feed RSS (integrata nella dashboard)
 */
if (!defined('DB_PREFIX')) {
    die('Accesso diretto non consentito');
}
?>

<style>
    .rss-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    
    .rss-header h1 {
        color: #2c3e50;
        margin: 0;
    }
    
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 5px;
        min-width: 100px;
    }
    
    .actions-grid .btn,
    .actions-grid button {
        padding: 6px 8px;
        font-size: 16px;
        min-width: 40px;
        margin: 0;
    }
    
    .actions-grid form {
        margin: 0;
        display: block;
    }
    
    .actions-grid button {
        width: 100%;
        height: 100%;
    }
    
    .feed-url {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: inline-block;
        color: #0066cc;
        text-decoration: none;
    }
    
    .feed-url:hover {
        text-decoration: underline;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    .empty-state h3 {
        font-size: 20px;
        margin-bottom: 10px;
        color: #2c3e50;
    }
    
    .empty-state p {
        font-size: 14px;
        margin-bottom: 20px;
        color: #666;
    }
    
    .error-message {
        display: block;
        font-size: 11px;
        color: #721c24;
        margin-top: 4px;
        font-style: italic;
    }
    
    .next-import-soon {
        color: #28a745;
        font-weight: bold;
    }
</style>

<div class="rss-header">
    <h1>📰 Gestione Feed RSS</h1>
    <a href="/admin/index.php?action=plugin-page&page=rss-feed-form" class="btn">
        ➕ Aggiungi Nuovo Feed
    </a>
</div>

<?php if (isset($messaggio)): ?>
    <div class="success-message">
        ✓ <?php echo htmlspecialchars($messaggio); ?>
    </div>
<?php endif; ?>

<?php if (isset($errore)): ?>
    <div class="success-message" style="background: #f8d7da; color: #721c24; border-color: #f5c6cb;">
        ✗ <?php echo htmlspecialchars($errore); ?>
    </div>
<?php endif; ?>

<?php if (empty($feeds)): ?>
    <div class="empty-state">
        <div class="empty-state-icon">📰</div>
        <h3>Nessun feed configurato</h3>
        <p>Inizia aggiungendo il tuo primo feed RSS per aggregare automaticamente contenuti esterni.</p>
        <a href="/admin/index.php?action=plugin-page&page=rss-feed-form" class="btn">
            Aggiungi il primo feed
        </a>
    </div>
<?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 50px;">ID</th>
                <th>Nome Feed</th>
                <th>URL</th>
                <th style="width: 100px;">Frequenza</th>
                <th style="width: 120px;">Stato</th>
                <th style="width: 130px;">Ultimo Import</th>
                <th style="width: 130px;">Prossimo Import</th>
                <th style="width: 80px; text-align: center;">Importati</th>
                <th style="width: 120px; text-align: center;">Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($feeds as $feed): ?>
                <tr>
                    <td><strong>#<?php echo $feed['id']; ?></strong></td>
                    
                    <td>
                        <strong><?php echo htmlspecialchars($feed['nome']); ?></strong>
                    </td>
                    
                    <td>
                        <a href="<?php echo htmlspecialchars($feed['url']); ?>" 
                           target="_blank" 
                           class="feed-url"
                           title="<?php echo htmlspecialchars($feed['url']); ?>">
                            <?php echo htmlspecialchars($feed['url']); ?>
                        </a>
                    </td>
                    
                    <td>
                        <?php echo round($feed['frequenza'] / 3600, 1); ?> ore
                    </td>
                    
                    <td>
                        <span class="badge badge-<?php 
                            echo $feed['stato'] === 'attivo' ? 'success' : 
                                ($feed['stato'] === 'pausa' ? 'warning' : 'inactive'); 
                        ?>">
                            <?php echo strtoupper($feed['stato']); ?>
                        </span>
                        <?php if ($feed['stato'] === 'errore' && $feed['messaggio_errore']): ?>
                            <span class="error-message" title="<?php echo htmlspecialchars($feed['messaggio_errore']); ?>">
                                <?php echo substr(htmlspecialchars($feed['messaggio_errore']), 0, 40); ?>...
                            </span>
                        <?php endif; ?>
                    </td>
                    
                    <td>
                        <?php 
                        if ($feed['ultimo_import']) {
                            echo date('d/m/Y H:i', strtotime($feed['ultimo_import']));
                        } else {
                            echo '<em style="color: #999;">Mai</em>';
                        }
                        ?>
                    </td>
                    
                    <td>
                        <?php 
                        if ($feed['prossimo_import']) {
                            $diff = strtotime($feed['prossimo_import']) - time();
                            if ($diff > 0) {
                                $ore = floor($diff / 3600);
                                $minuti = floor(($diff % 3600) / 60);
                                echo "Tra {$ore}h {$minuti}m";
                            } else {
                                echo '<span class="next-import-soon">Ora</span>';
                            }
                        } else {
                            echo '<em style="color: #999;">-</em>';
                        }
                        ?>
                    </td>
                    
                    <td style="text-align: center;">
                        <strong style="color: #0066cc;"><?php echo number_format($feed['elementi_importati']); ?></strong>
                    </td>
                    
                    <td>
                        <div class="actions-grid">
                            <a href="/admin/index.php?action=plugin-page&page=rss-feed-form&id=<?php echo $feed['id']; ?>" 
                               class="btn-edit" 
                               title="Modifica"
                               style="text-align: center; line-height: 1.5;">
                                ✏️
                            </a>
                            
                            <form method="post" style="margin: 0;">
                                <input type="hidden" name="feed_id" value="<?php echo $feed['id']; ?>">
                                <button type="submit" 
                                        name="importa_ora" 
                                        class="btn-edit" 
                                        title="Importa Ora"
                                        style="background: #28a745;">
                                    ▶️
                                </button>
                            </form>
                            
                            <form method="post" style="margin: 0;">
                                <input type="hidden" name="feed_id" value="<?php echo $feed['id']; ?>">
                                <input type="hidden" name="nuovo_stato" value="<?php echo $feed['stato'] === 'attivo' ? 'pausa' : 'attivo'; ?>">
                                <button type="submit" 
                                        name="cambia_stato" 
                                        class="btnwidget" 
                                        title="<?php echo $feed['stato'] === 'attivo' ? 'Metti in Pausa' : 'Riattiva'; ?>"
                                        style="background: #ffc107;">
                                    <?php echo $feed['stato'] === 'attivo' ? '⏸️' : '▶️'; ?>
                                </button>
                            </form>
                            
                            <form method="post" 
                                  style="margin: 0;" 
                                  onsubmit="return confirm('Sei sicuro di voler eliminare questo feed?\n\nI post già importati NON verranno eliminati.');">
                                <input type="hidden" name="feed_id" value="<?php echo $feed['id']; ?>">
                                <button type="submit" 
                                        name="elimina_feed" 
                                        class="btn-delete" 
                                        title="Elimina">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
