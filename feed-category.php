<?php
require_once __DIR__ . '/core/bootstrap.php';
require_once BASE_PATH . '/core/Database.php';

$db = new Database();

// Ottieni slug categoria dall'URL
$categorySlug = $_GET['category'] ?? '';

if (!$categorySlug) {
    header('HTTP/1.0 404 Not Found');
    exit('Categoria non specificata');
}

// Ottieni categoria
$stmt = $db->pdo->prepare("SELECT * FROM " . DB_PREFIX . "categories WHERE slug = ?");
$stmt->execute([$categorySlug]);
$category = $stmt->fetch();

if (!$category) {
    header('HTTP/1.0 404 Not Found');
    exit('Categoria non trovata');
}

// Imposta header RSS
header('Content-Type: application/rss+xml; charset=UTF-8');

// Ottieni impostazioni sito
$siteTitle = $db->getSetting('site_title', 'Il mio CMS');
$siteUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

// Ottieni post della categoria
$stmt = $db->pdo->prepare("
    SELECT p.*, u.name as author_name
    FROM " . DB_PREFIX . "posts p
    LEFT JOIN " . DB_PREFIX . "users u ON p.author_id = u.id
    INNER JOIN " . DB_PREFIX . "post_categories pc ON p.id = pc.post_id
    WHERE pc.category_id = ? AND p.status = 'pubblicato' AND p.deleted_at IS NULL
    ORDER BY p.created_at DESC
    LIMIT 20
");
$stmt->execute([$category['id']]);
$posts = $stmt->fetchAll();

// Genera RSS
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<rss version="2.0" 
     xmlns:atom="http://www.w3.org/2005/Atom" 
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:media="http://search.yahoo.com/mrss/">
    <channel>
        <title><?php echo htmlspecialchars($siteTitle . ' - ' . $category['name']); ?></title>
        <link><?php echo htmlspecialchars($siteUrl . '/category/' . $category['slug']); ?></link>
        <description><?php echo htmlspecialchars($category['description'] ?: 'Post nella categoria ' . $category['name']); ?></description>
        <language>it</language>
        <lastBuildDate><?php echo date(DATE_RSS); ?></lastBuildDate>
        <atom:link href="<?php echo htmlspecialchars($siteUrl); ?>/feed-category.php?category=<?php echo urlencode($category['slug']); ?>" rel="self" type="application/rss+xml" />
        
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
            <!-- Immagine in formato Media RSS -->
            <media:content url="<?php echo htmlspecialchars($imageUrl); ?>" medium="image" <?php if ($imageSize): ?>width="<?php echo $imageSize[0]; ?>" height="<?php echo $imageSize[1]; ?>"<?php endif; ?> />
            <media:thumbnail url="<?php echo htmlspecialchars($imageUrl); ?>" />
            
            <!-- Enclosure per compatibilità con lettori RSS -->
            <enclosure url="<?php echo htmlspecialchars($imageUrl); ?>" <?php if ($imageSize): ?>length="<?php echo filesize($imagePath); ?>" type="<?php echo $imageSize['mime']; ?>"<?php endif; ?> />
            <?php endif; ?>
            
            <content:encoded><![CDATA[
                <?php if (!empty($post['featured_image'])): ?>
                <p><img src="<?php echo htmlspecialchars($siteUrl . '/uploads/' . $post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="max-width: 100%; height: auto;" /></p>
                <?php endif; ?>
                <?php echo $post['content']; ?>
            ]]></content:encoded>
            
            <?php if ($post['author_name']): ?>
            <author><?php echo htmlspecialchars($post['author_name']); ?></author>
            <?php endif; ?>
            
            <category><?php echo htmlspecialchars($category['name']); ?></category>
        </item>
        <?php endforeach; ?>
    </channel>
</rss>
