<?php
/**
 * Feed RSS per News Aggregate
 * Questo file viene servito dal plugin RSS Aggregator
 */

require_once __DIR__ . '/../../core/bootstrap.php';
require_once __DIR__ . '/../../core/Database.php';

$db = new Database();
$prefix = DB_PREFIX;

// Header XML
header('Content-Type: application/rss+xml; charset=utf-8');

// Recupera impostazioni sito
$siteTitle = $db->getSetting('site_title', '');
$siteDescription = $db->getSetting('site_description', '');
$siteLogo = $db->getSetting('site_logo');
$siteUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

// Recupera ultime 50 news dalla tabella rss_news
$stmt = $db->pdo->prepare("
    SELECT 
        n.id,
        n.titolo,
        n.slug,
        n.excerpt,
        n.link_originale,
        n.immagine_url,
        n.autore_originale,
        n.data_pubblicazione,
        f.nome as nome_feed
    FROM {$prefix}rss_news n
    JOIN {$prefix}rss_feeds f ON n.feed_id = f.id
    ORDER BY n.data_pubblicazione DESC
    LIMIT 50
");
$stmt->execute();
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Genera XML
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0" 
     xmlns:atom="http://www.w3.org/2005/Atom" 
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:media="http://search.yahoo.com/mrss/"
     xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <title><?php echo htmlspecialchars($siteTitle); ?></title>
        <link><?php echo htmlspecialchars($siteUrl); ?></link>
        <description><?php echo htmlspecialchars($siteDescription); ?></description>
        <language>it</language>
        <lastBuildDate><?php echo date('r'); ?></lastBuildDate>
        <atom:link href="<?php echo htmlspecialchars($siteUrl); ?>/rss-news-feed" rel="self" type="application/rss+xml" />
        <?php if ($siteLogo): 
            $logoUrl = $siteUrl . '/uploads/' . $siteLogo;
            $logoPath = BASE_PATH . '/uploads/' . $siteLogo;
            $logoSize = file_exists($logoPath) ? getimagesize($logoPath) : null;
        ?>
        <!-- Logo del sito -->
        <image>
            <url><?php echo htmlspecialchars($logoUrl); ?></url>
            <title><?php echo htmlspecialchars($siteTitle); ?></title>
            <link><?php echo htmlspecialchars($siteUrl); ?></link>
            <?php if ($logoSize): ?>
            <width><?php echo min($logoSize[0], 144); ?></width>
            <height><?php echo min($logoSize[1], 400); ?></height>
            <?php endif; ?>
        </image>
        <?php endif; ?>
        <?php foreach ($news as $item): ?>
        <item>
            <title><?php echo htmlspecialchars($item['titolo']); ?></title>
            <link><?php echo htmlspecialchars($item['link_originale']); ?></link>
    <guid isPermaLink="true"><?php echo htmlspecialchars($item['link_originale']); ?></guid>
            <pubDate><?php echo date('r', strtotime($item['data_pubblicazione'])); ?></pubDate>
            <description><?php echo htmlspecialchars($item['excerpt'] ?? ''); ?></description>
            <?php if (!empty($item['autore_originale'])): ?>
            <dc:creator><?php echo htmlspecialchars($item['autore_originale']); ?></dc:creator>
            <?php endif; ?>
            <category><?php echo htmlspecialchars($item['nome_feed']); ?></category>
            <?php if (!empty($item['immagine_url'])): ?>
    <?php
    // Tenta di recuperare la dimensione dell'immagine
    $imageLength = 0;
    $headers = @get_headers($item['immagine_url'], 1);
    if ($headers && isset($headers['Content-Length'])) {
        $imageLength = is_array($headers['Content-Length']) 
            ? end($headers['Content-Length']) 
            : $headers['Content-Length'];
    }
    
    // Determina il tipo MIME
    $imageType = 'image/jpeg'; // default
    if (isset($headers['Content-Type'])) {
        $imageType = is_array($headers['Content-Type']) 
            ? end($headers['Content-Type']) 
            : $headers['Content-Type'];
    }
    ?>
    <enclosure url="<?php echo htmlspecialchars($item['immagine_url']); ?>" 
               length="<?php echo $imageLength > 0 ? $imageLength : '0'; ?>" 
               type="<?php echo htmlspecialchars($imageType); ?>" />
<?php endif; ?>

            <content:encoded><![CDATA[
                <?php if (!empty($item['immagine_url'])): ?>
                <img src="<?php echo htmlspecialchars($item['immagine_url']); ?>" alt="<?php echo htmlspecialchars($item['titolo']); ?>" style="max-width: 100%; height: auto;" />
                <?php endif; ?>
                <p><?php echo htmlspecialchars($item['excerpt'] ?? ''); ?></p>
                <p><a href="<?php echo htmlspecialchars($item['link_originale']); ?>" target="_blank">Leggi l'articolo originale</a></p>
            ]]></content:encoded>
        </item>
        <?php endforeach; ?>
        
    </channel>
</rss>
