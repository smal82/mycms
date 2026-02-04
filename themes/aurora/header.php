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
    <!-- Feed RSS -->
<link rel="alternate" type="application/rss+xml" title="<?php echo htmlspecialchars($siteTitle); ?> - Feed RSS" href="/feed.php">

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


