<?php
$siteTitle = $this->db->getSetting('site_title', 'Il mio CMS');
$siteDescription = $this->db->getSetting('site_description');
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Benvenuto su <?php echo htmlspecialchars($siteTitle); ?></h1>
            <?php if ($siteDescription): ?>
                <p class="hero-subtitle"><?php echo htmlspecialchars($siteDescription); ?></p>
            <?php endif; ?>
            <div class="hero-cta">
                <a href="/blog" class="btn btn-primary">Scopri il Blog</a>
                <a href="/page/chi-siamo" class="btn btn-secondary">Chi Siamo</a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Perché Sceglierci</h2>
            <p class="section-subtitle">Scopri cosa ci rende unici</p>
        </div>
        <div class="features-grid">
            <div class="feature-box">
                <div class="feature-icon">🚀</div>
                <h3>Veloce</h3>
                <p>Prestazioni ottimizzate per un'esperienza utente fluida e reattiva.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon">🎨</div>
                <h3>Design Moderno</h3>
                <p>Interfaccia pulita e intuitiva che cattura l'attenzione.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon">📱</div>
                <h3>Responsive</h3>
                <p>Perfetto su qualsiasi dispositivo, dal mobile al desktop.</p>
            </div>
        </div>
    </div>
</section>

<!-- Latest Posts -->
<section class="posts-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Ultimi Articoli</h2>
            <p class="section-subtitle">Resta aggiornato con le nostre novità</p>
        </div>
        
        <div class="posts-grid">
            <?php 
            $posts = $this->db->getPublishedPosts(6);
            if (!empty($posts)):
                foreach ($posts as $post): ?>
                    <article class="post-card">
                        <?php if ($post['featured_image']): ?>
                            <div class="post-card-image">
                                <a href="/post/<?php echo htmlspecialchars($post['slug']); ?>">
                                    <img src="/uploads/<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="post-card-content">
                            <h3 class="post-card-title">
                                <a href="/post/<?php echo htmlspecialchars($post['slug']); ?>">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                            </h3>
                            <?php if ($post['excerpt']): ?>
                                <p class="post-card-excerpt"><?php echo htmlspecialchars(substr($post['excerpt'], 0, 120)); ?>...</p>
                            <?php endif; ?>
                            <div class="post-card-meta">
                                <?php echo date('d M Y', strtotime($post['created_at'])); ?> | ✍️ <?php echo htmlspecialchars($post['author_name']); ?>
                            </div>
                            <a href="/post/<?php echo htmlspecialchars($post['slug']); ?>" class="read-more">Leggi di più →</a>
                        </div>
                    </article>
                <?php endforeach;
            else: ?>
                <p style="grid-column: 1/-1; text-align:center; color:#718096;">Nessun post ancora pubblicato.</p>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($posts)): ?>
            <div style="text-align:center; margin-top:40px;">
                <a href="/blog" class="btn btn-primary">Vedi Tutti gli Articoli</a>
            </div>
        <?php endif; ?>
    </div>
</section>