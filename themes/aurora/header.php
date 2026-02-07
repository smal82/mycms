<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($siteDescription); ?>">
    <title><?php echo htmlspecialchars($titlepage); ?></title>
    <?php echo $this->db->getRobotsMeta(); ?>
    <?php if ($siteFavicon): ?>
        <link rel="icon" href="/uploads/<?php echo htmlspecialchars($siteFavicon); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="<?php echo $this->asset('style.css'); ?>">
    <?php
    if (isset($cptName)) {
        ?>
        <link rel="stylesheet" href="<?php echo $this->asset('style-cpt.css'); ?>">
        <?php
    }
    ?>
    <!-- Feed RSS -->
<link rel="alternate" type="application/rss+xml" title="<?php echo htmlspecialchars($siteTitle); ?> - Feed RSS" href="/feed.php">
    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="<?php echo (isset($content) && isset($content['id'])) ? 'article' : 'website'; ?>" />
    <meta property="og:title" content="<?php echo htmlspecialchars($titlepage); ?>" />
    <?php 
    $ogDescription = isset($content['excerpt']) && !empty($content['excerpt']) 
        ? $content['excerpt'] 
        : $sitemotto;
    $ogDescription = mb_substr($ogDescription, 0, 155);
    ?>
    <meta property="og:description" content="<?php echo htmlspecialchars($ogDescription); ?>" />
    <meta property="og:url" content="<?php echo htmlspecialchars('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" />
    <meta property="og:site_name" content="<?php echo htmlspecialchars($siteTitle); ?>" />
    <meta property="og:locale" content="it_IT" />
    
    <?php if (isset($content['featured_image']) && $content['featured_image']): ?>
    <!-- Immagine del Post -->
    <meta property="og:image" content="<?php echo htmlspecialchars('https://' . $_SERVER['HTTP_HOST'] . '/uploads/' . $content['featured_image']); ?>" />
    <meta property="og:image:secure_url" content="<?php echo htmlspecialchars('https://' . $_SERVER['HTTP_HOST'] . '/uploads/' . $content['featured_image']); ?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="<?php echo htmlspecialchars($title); ?>" />
    <meta property="og:image:type" content="image/jpeg" />
    <?php else: ?>
    <!-- Immagine Fallback -->
    <meta property="og:image" content="<?php echo htmlspecialchars('https://' . $_SERVER['HTTP_HOST'] . '/uploads/hesperos-opengraph-v2.png'); ?>" />
    <meta property="og:image:secure_url" content="<?php echo htmlspecialchars('https://' . $_SERVER['HTTP_HOST'] . '/uploads/hesperos-opengraph-v2.png'); ?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="<?php echo htmlspecialchars($siteTitle); ?>" />
    <meta property="og:image:type" content="image/png" />
    <?php endif; ?>
    
    <?php if (isset($content) && isset($content['id'])): ?>
    <!-- Tag Specifici per Articoli -->
    <meta property="article:published_time" content="<?php echo date('c', strtotime($content['created_at'])); ?>" />
    <?php if (isset($content['updated_at']) && $content['updated_at']): ?>
    <meta property="article:modified_time" content="<?php echo date('c', strtotime($content['updated_at'])); ?>" />
    <?php endif; ?>
    <meta property="article:author" content="<?php echo htmlspecialchars($content['author_name']); ?>" />
    <?php 
    $ogCategories = $this->db->getPostCategories($content['id']);
    if (!empty($ogCategories)): ?>
    <meta property="article:section" content="<?php echo htmlspecialchars($ogCategories[0]['name']); ?>" />
    <?php endif; ?>
    <?php endif; ?>
    
    <!-- Twitter/X Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo htmlspecialchars($titlepage); ?>" />
    <meta name="twitter:description" content="<?php echo htmlspecialchars($ogDescription); ?>" />
    
    <?php if (isset($content['featured_image']) && $content['featured_image']): ?>
    <!-- Immagine del Post per X -->
    <meta name="twitter:image" content="<?php echo htmlspecialchars('https://' . $_SERVER['HTTP_HOST'] . '/uploads/' . $content['featured_image']); ?>" />
    <meta name="twitter:image:alt" content="<?php echo htmlspecialchars($title); ?>" />
    <?php else: ?>
    <!-- Immagine Fallback per X -->
    <meta name="twitter:image" content="<?php echo htmlspecialchars('https://' . $_SERVER['HTTP_HOST'] . '/uploads/hesperos-opengraph-twitter-v2.png'); ?>" />
    <meta name="twitter:image:alt" content="<?php echo htmlspecialchars($siteTitle); ?>" />
    <?php endif; ?>

    <?php do_hook('mycms_head'); ?>
</head>

<body>
    <header class="site-header">
        <div class="container">
            <div class="header-inner">
                <div class="site-logo">
                    <?php if ($siteLogo): ?>
                        <a href="/"><img src="/uploads/<?php echo htmlspecialchars($siteLogo); ?>" alt="<?php echo htmlspecialchars($siteTitle); ?>"></a>
                    <?php endif; ?>
                    <div class="site-branding">
                        <h1 class="site-title"><a href="/"><?php echo htmlspecialchars($siteTitle); ?></a></h1>
                        <p class="site-description"><?php echo htmlspecialchars($sitemotto); ?></p>
                    </div>
                </div>
                
                <button class="mobile-toggle" onclick="toggleMenu()" id="mobile-toggle" aria-label="Toggle menu">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
                
                <nav class="main-nav" id="main-nav">
                    <button class="menu-close" onclick="toggleMenu()" aria-label="Close menu">✕</button>
                    <?php $this->renderMultiLevelMenu('primary'); ?>
                </nav>
            </div>
        </div>
    </header>
    
    <script>
function toggleMenu() {
    const nav = document.getElementById('main-nav');
    const toggle = document.getElementById('mobile-toggle');
    
    nav.classList.toggle('active');
    toggle.classList.toggle('active');
    
    if (nav.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

// Solo per mobile: gestione sottomenu
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth <= 768) {
        document.querySelectorAll('.menu-item-has-children > a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                this.parentElement.classList.toggle('active');
            });
        });
    }
});
</script>


