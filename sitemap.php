<?php
/**
 * Sitemap XML Generator
 * Versione standalone - non richiede bootstrap
 */

// Disabilita errori
error_reporting(0);
ini_set('display_errors', 0);

require_once 'core/bootstrap.php';

// Invia header PRIMA di qualsiasi output
header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex');

// Connessione database diretta
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    // Se il DB fallisce, genera una sitemap minimale
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    echo '<url><loc>https://' . $_SERVER['HTTP_HOST'] . '/</loc></url>';
    echo '</urlset>';
    exit;
}

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

// Inizia XML
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Homepage
echo "    <url>\n";
echo "        <loc>" . htmlspecialchars($baseUrl, ENT_XML1, 'UTF-8') . "/</loc>\n";
echo "        <changefreq>daily</changefreq>\n";
echo "        <priority>1.0</priority>\n";
echo "    </url>\n";

// Pagine pubblicate
try {
    $stmt = $pdo->prepare("SELECT slug, updated_at FROM " . DB_PREFIX . "pages WHERE status='pubblicato' AND deleted_at IS NULL ORDER BY created_at DESC");
    $stmt->execute();
    while ($page = $stmt->fetch()) {
        echo "    <url>\n";
        echo "        <loc>" . htmlspecialchars($baseUrl . '/' . $page['slug'], ENT_XML1, 'UTF-8') . "</loc>\n";
        if (!empty($page['updated_at'])) {
            echo "        <lastmod>" . date('Y-m-d', strtotime($page['updated_at'])) . "</lastmod>\n";
        }
        echo "        <changefreq>weekly</changefreq>\n";
        echo "        <priority>0.8</priority>\n";
        echo "    </url>\n";
    }
} catch (Exception $e) {
    // Ignora errori
}

// Post del blog pubblicati
try {
    $stmt = $pdo->prepare("SELECT slug, updated_at FROM " . DB_PREFIX . "posts WHERE status='pubblicato' AND deleted_at IS NULL ORDER BY created_at DESC");
    $stmt->execute();
    while ($post = $stmt->fetch()) {
        echo "    <url>\n";
        echo "        <loc>" . htmlspecialchars($baseUrl . '/blog/' . $post['slug'], ENT_XML1, 'UTF-8') . "</loc>\n";
        if (!empty($post['updated_at'])) {
            echo "        <lastmod>" . date('Y-m-d', strtotime($post['updated_at'])) . "</lastmod>\n";
        }
        echo "        <changefreq>monthly</changefreq>\n";
        echo "        <priority>0.6</priority>\n";
        echo "    </url>\n";
    }
} catch (Exception $e) {
    // Ignora errori
}

// Categorie blog
try {
    $stmt = $pdo->prepare("SELECT DISTINCT c.slug 
                         FROM " . DB_PREFIX . "categories c
                         INNER JOIN " . DB_PREFIX . "post_categories pc ON c.id = pc.category_id
                         INNER JOIN " . DB_PREFIX . "posts p ON pc.post_id = p.id
                         WHERE p.status='pubblicato' AND p.deleted_at IS NULL
                         ORDER BY c.name");
    $stmt->execute();
    while ($category = $stmt->fetch()) {
        echo "    <url>\n";
        echo "        <loc>" . htmlspecialchars($baseUrl . '/blog/category/' . $category['slug'], ENT_XML1, 'UTF-8') . "</loc>\n";
        echo "        <changefreq>weekly</changefreq>\n";
        echo "        <priority>0.5</priority>\n";
        echo "    </url>\n";
    }
} catch (Exception $e) {
    // Ignora errori
}

// Portfolio (se esiste)
try {
    $stmt = $pdo->prepare("SELECT slug, updated_at FROM " . DB_PREFIX . "portfolio WHERE status='pubblicato' AND deleted_at IS NULL ORDER BY created_at DESC");
    $stmt->execute();
    while ($portfolio = $stmt->fetch()) {
        echo "    <url>\n";
        echo "        <loc>" . htmlspecialchars($baseUrl . '/portfolio/' . $portfolio['slug'], ENT_XML1, 'UTF-8') . "</loc>\n";
        if (!empty($portfolio['updated_at'])) {
            echo "        <lastmod>" . date('Y-m-d', strtotime($portfolio['updated_at'])) . "</lastmod>\n";
        }
        echo "        <changefreq>monthly</changefreq>\n";
        echo "        <priority>0.7</priority>\n";
        echo "    </url>\n";
    }
} catch (Exception $e) {
    // Tabella non esiste
}

// Chiudi XML
echo "</urlset>";
exit;
