<?php
if (!defined('DB_PREFIX')) {
    die('Accesso diretto non consentito');
}
?>

<style>
    .news-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .news-filters {
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .news-thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }
    
    .pagination {
        display: flex;
        gap: 5px;
        justify-content: center;
        margin-top: 20px;
    }
    
    .pagination a,
    .pagination span {
        padding: 8px 12px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        color: #333;
    }
    
    .pagination a:hover {
        background: #f0f0f0;
    }
    
    .pagination .current {
        background: #0066cc;
        color: white;
        border-color: #0066cc;
    }
</style>

<div class="news-header">
    <h1>📄 News Importate (<?php echo number_format($totale); ?>)</h1>
</div>

<?php if (isset($messaggio)): ?>
    <div class="success-message">
        ✓ <?php echo htmlspecialchars($messaggio); ?>
    </div>
<?php endif; ?>

<div class="news-filters">
    <form method="get">
        <input type="hidden" name="action" value="plugin-page">
        <input type="hidden" name="page" value="rss-news-list">
        <label>Filtra per Feed:</label>
        <select name="feed" onchange="this.form.submit()">
            <option value="">Tutti i feed</option>
            <?php foreach ($feeds as $f): ?>
                <option value="<?php echo $f['id']; ?>" <?php echo $feedFiltro == $f['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($f['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<?php if (empty($news)): ?>
    <div class="empty-state">
        <p>Nessuna news importata</p>
    </div>
<?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 80px;">Immagine</th>
                <th>Titolo</th>
                <th style="width: 150px;">Feed</th>
                <th style="width: 130px;">Data</th>
                <th style="width: 80px; text-align: center;">Views</th>
                <th style="width: 120px; text-align: center;">Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($news as $item): ?>
                <tr>
                    <td>
                        <?php if ($item['immagine_url']): ?>
                            <img src="<?php echo htmlspecialchars($item['immagine_url']); ?>" 
                                 alt="" 
                                 class="news-thumbnail"
                                 onerror="this.style.display='none'">
                        <?php else: ?>
                            <div class="news-thumbnail" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center;">📰</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($item['titolo']); ?></strong><br>
                        <small style="color: #666;"><?php echo htmlspecialchars($item['excerpt']); ?></small>
                    </td>
                    <td><?php echo htmlspecialchars($item['nome_feed']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($item['data_pubblicazione'])); ?></td>
                    <td style="text-align: center;"><?php echo number_format($item['visualizzazioni']); ?></td>
                    <td style="text-align: center;">
                        <a href="<?php echo htmlspecialchars($item['link_originale']); ?>" 
                           target="_blank" 
                           class="btn-edit"
                           title="Vedi Originale">
                            🔗
                        </a>
                        <form method="post" style="display: inline;" onsubmit="return confirm('Eliminare questa news?');">
                            <input type="hidden" name="news_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" name="elimina_news" class="btn-delete">🗑️</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php if ($totalePagine > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalePagine; $i++): ?>
                <?php if ($i == $pagina): ?>
                    <span class="current"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?action=plugin-page&page=rss-news-list&p=<?php echo $i; ?><?php echo $feedFiltro ? '&feed='.$feedFiltro : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
