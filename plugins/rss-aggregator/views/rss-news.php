<?php
/**
 * Template Frontend: RSS News
 * Variabili disponibili: $news, $feeds, $totale, $pagina, $totalePagine, $feedFiltro
 */

if (!defined('DB_PREFIX')) {
    die('Accesso diretto non consentito');
}
?>

<style>
    .rss-news-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }
    
    .rss-news-header {
        text-align: center;
        margin-bottom: 50px;
    }
    
    .rss-news-header h1 {
        font-size: 2.5em;
        margin-bottom: 10px;
    }
    
    .rss-news-header p {
        font-size: 1.1em;
        opacity: 0.7;
    }
    
    .rss-news-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        margin-bottom: 40px;
        padding: 20px;
        background: rgba(0,0,0,0.03);
        border-radius: 12px;
    }
    
    .rss-filter-btn {
        padding: 10px 20px;
        background: white;
        border: 2px solid #ddd;
        border-radius: 20px;
        cursor: pointer;
        text-decoration: none;
        color: #333;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .rss-filter-btn:hover {
        border-color: #0066cc;
        color: #0066cc;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .rss-filter-btn.active {
        background: #0066cc;
        color: white;
        border-color: #0066cc;
    }
    
    .rss-news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }
    
    .rss-news-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        flex-direction: column;
    }
    
    .rss-news-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    
    .rss-news-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f0f0f0;
    }
    
    .rss-news-image-placeholder {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4em;
    }
    
    .rss-news-content {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .rss-news-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        font-size: 0.85em;
    }
    
    .rss-news-source {
        background: #e7f3ff;
        color: #0066cc;
        padding: 4px 12px;
        border-radius: 12px;
        font-weight: 600;
    }
    
    .rss-news-date {
        color: #999;
    }
    
    .rss-news-title {
        font-size: 1.3em;
        margin-bottom: 12px;
        line-height: 1.4;
        font-weight: 600;
    }
    
    .rss-news-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .rss-news-title a:hover {
        color: #0066cc;
    }
    
    .rss-news-excerpt {
        color: #666;
        line-height: 1.6;
        margin-bottom: 15px;
        flex: 1;
    }
    
    .rss-news-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #0066cc;
        text-decoration: none;
        font-weight: 600;
        transition: gap 0.3s;
    }
    
    .rss-news-link:hover {
        gap: 12px;
    }
    
    .rss-pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 50px;
        flex-wrap: wrap;
    }
    
    .rss-pagination a,
    .rss-pagination span {
        padding: 10px 18px;
        background: white;
        border: 2px solid #ddd;
        border-radius: 8px;
        text-decoration: none;
        color: #333;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .rss-pagination a:hover {
        border-color: #0066cc;
        color: #0066cc;
        transform: translateY(-2px);
    }
    
    .rss-pagination .current {
        background: #0066cc;
        color: white;
        border-color: #0066cc;
    }
    
    .rss-empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #999;
    }
    
    .rss-empty-icon {
        font-size: 5em;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    @media (max-width: 768px) {
        .rss-news-grid {
            grid-template-columns: 1fr;
        }
        
        .rss-news-header h1 {
            font-size: 2em;
        }
        
        .rss-news-filters {
            flex-direction: column;
        }
        
        .rss-filter-btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="rss-news-container">
    <div class="rss-news-header">
        <h1>📰 News dal Mondo</h1>
        <p>Le ultime notizie dai migliori feed RSS</p>
    </div>
    
    <?php if (!empty($feeds)): ?>
        <div class="rss-news-filters">
            <a href="/rss-news" class="rss-filter-btn <?php echo !$feedFiltro ? 'active' : ''; ?>">
                🌐 Tutte (<?php echo number_format($totale); ?>)
            </a>
            <?php foreach ($feeds as $f): ?>
                <a href="/rss-news?feed=<?php echo $f['id']; ?>" 
                   class="rss-filter-btn <?php echo $feedFiltro == $f['id'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($f['nome']); ?> (<?php echo $f['totale_news']; ?>)
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($news)): ?>
        <div class="rss-empty-state">
            <div class="rss-empty-icon">📭</div>
            <h2>Nessuna news disponibile</h2>
            <p>Non ci sono ancora news importate dai feed RSS.</p>
        </div>
    <?php else: ?>
        <div class="rss-news-grid">
            <?php foreach ($news as $item): ?>
                <article class="rss-news-card">
                    <?php if ($item['immagine_url']): ?>
                        <img src="<?php echo htmlspecialchars($item['immagine_url']); ?>" 
                             alt="<?php echo htmlspecialchars($item['titolo']); ?>" 
                             class="rss-news-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="rss-news-image-placeholder" style="display: none;">📰</div>
                    <?php else: ?>
                        <div class="rss-news-image-placeholder">📰</div>
                    <?php endif; ?>
                    
                    <div class="rss-news-content">
                        <div class="rss-news-meta">
                            <span class="rss-news-source"><?php echo htmlspecialchars($item['nome_feed']); ?></span>
                            <span class="rss-news-date">
                                <?php 
                                $data = strtotime($item['data_pubblicazione']);
                                $diff = time() - $data;
                                
                                if ($diff < 3600) {
                                    echo floor($diff / 60) . ' min fa';
                                } elseif ($diff < 86400) {
                                    echo floor($diff / 3600) . ' ore fa';
                                } elseif ($diff < 604800) {
                                    echo floor($diff / 86400) . ' giorni fa';
                                } else {
                                    echo date('d M Y', $data);
                                }
                                ?>
                            </span>
                        </div>
                        
                        <h2 class="rss-news-title">
                            <a href="<?php echo htmlspecialchars($item['link_originale']); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer">
                                <?php echo htmlspecialchars($item['titolo']); ?>
                            </a>
                        </h2>
                        
                        <p class="rss-news-excerpt">
                            <?php echo htmlspecialchars($item['excerpt']); ?>
                        </p>
                        
                        <a href="<?php echo htmlspecialchars($item['link_originale']); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="rss-news-link">
                            Leggi tutto <span>→</span>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        
        <?php if ($totalePagine > 1): ?>
            <div class="rss-pagination">
                <?php if ($pagina > 1): ?>
                    <a href="/rss-news?p=<?php echo $pagina - 1; ?><?php echo $feedFiltro ? '&feed='.$feedFiltro : ''; ?>">
                        ← Precedente
                    </a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalePagine; $i++): ?>
                    <?php if ($i == $pagina): ?>
                        <span class="current"><?php echo $i; ?></span>
                    <?php elseif ($i == 1 || $i == $totalePagine || abs($i - $pagina) <= 2): ?>
                        <a href="/rss-news?p=<?php echo $i; ?><?php echo $feedFiltro ? '&feed='.$feedFiltro : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php elseif (abs($i - $pagina) == 3): ?>
                        <span>...</span>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($pagina < $totalePagine): ?>
                    <a href="/rss-news?p=<?php echo $pagina + 1; ?><?php echo $feedFiltro ? '&feed='.$feedFiltro : ''; ?>">
                        Successivo →
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
