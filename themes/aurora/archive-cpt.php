<div class="blog-header">
    <div class="container">
        <h1 class="blog-title"><?= htmlspecialchars($cpt['plural_label']) ?></h1>
        <?= htmlspecialchars($cpt['description']) ?>
    </div>
</div>
<div class="site-main">
<div class="container">
    <div class="portfolio-grid">
        <?php foreach ($posts as $post): ?>
            <article class="portfolio-item">
                <?php if ($post['featured_image']): ?>
                    <img src="/uploads/<?= htmlspecialchars($post['featured_image']) ?>" alt="">
                <?php endif; ?>
                
                <h2 class="article-title">
                    <a href="/<?= $cpt['slug'] ?>/<?= htmlspecialchars($post['slug']) ?>">
                        <?= htmlspecialchars($post['title']) ?>
                    </a>
                </h2>
                
                <?php if ($post['excerpt']): ?>
                    <p class="paragrafo"><?= htmlspecialchars($post['excerpt']) ?></p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</div>
</div>
