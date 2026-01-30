<?php
require_once __DIR__ . '/core/bootstrap.php';
require_once BASE_PATH . '/core/Database.php';

// Inizializza database
$db = new Database();

// Imposta header RSS
header('Content-Type: application/rss+xml; charset=UTF-8');

// Ottieni impostazioni sito
$siteTitle = $db->getSetting('site_title', 'Il mio CMS');
$siteDescription = $db->getSetting('site_description', 'Un CMS moderno');
$siteLogo = $db->getSetting('site_logo');
$siteUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

// Ottieni ultimi 20 post pubblicati
$stmt = $db->pdo->prepare("
    SELECT p.*, u.name as author_name
    FROM " . DB_PREFIX . "posts p
    LEFT JOIN " . DB_PREFIX . "users u ON p.author_id = u.id
    WHERE p.status = 'pubblicato' AND p.deleted_at IS NULL
    ORDER BY p.created_at DESC
    LIMIT 20
");
$stmt->execute();
$posts = $stmt->fetchAll();

// Genera RSS
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
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
        <lastBuildDate><?php echo date(DATE_RSS); ?></lastBuildDate>
        <atom:link href="<?php echo htmlspecialchars($siteUrl); ?>/feed.php" rel="self" type="application/rss+xml" />
        
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
        
        <?php foreach ($posts as $post): ?>
        <item>
            <title><?php echo htmlspecialchars($post['title']); ?></title>
            <link><?php echo htmlspecialchars($siteUrl . '/post/' . $post['slug']); ?></link>
            <guid isPermaLink="true"><?php echo htmlspecialchars($siteUrl . '/post/' . $post['slug']); ?></guid>
            <pubDate><?php echo date(DATE_RSS, strtotime($post['created_at'])); ?></pubDate>
            <description><?php echo htmlspecialchars($post['excerpt'] ?: strip_tags(substr($post['content'], 0, 200)) . '...'); ?></description>
            
            <?php if (!empty($post['featured_image'])): 
                $imageUrl = $siteUrl . '/uploads/' . $post['featured_image'];
                $imagePath = BASE_PATH . '/uploads/' . $post['featured_image'];
                
                // Ottieni dimensioni immagine se esiste
                $imageSize = file_exists($imagePath) ? getimagesize($imagePath) : null;
            ?>
            <!-- Featured Image (immagine di copertina) -->
            <media:content url="<?php echo htmlspecialchars($imageUrl); ?>" medium="image" <?php if ($imageSize): ?>width="<?php echo $imageSize[0]; ?>" height="<?php echo $imageSize[1]; ?>"<?php endif; ?> />
            
            <?php endif; ?>
            <content:encoded><![CDATA[
                <?php if (!empty($post['featured_image'])): ?>
                <p><img src="<?php echo htmlspecialchars($siteUrl . '/uploads/' . $post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" /></p>
                <?php endif; ?>
                <?php echo $post['content']; ?>
            ]]></content:encoded>
            
            <?php if ($post['author_name']): ?>
<dc:creator><?php echo htmlspecialchars($post['author_name']); ?></dc:creator>
<?php endif; ?>

            
            <?php
            // Aggiungi categorie
            $categories = $db->getPostCategories($post['id']);
            foreach ($categories as $category):
            ?>
            <category><?php echo htmlspecialchars($category['name']); ?></category>
            <?php endforeach; ?>
        </item>
        <?php endforeach; ?>
    </channel>
</rss>
