<?php get_header(); ?>

<div class="container">
    <h1><?= htmlspecialchars($cpt['plural_label']) ?></h1>
    <p><?= htmlspecialchars($cpt['description']) ?></p>
    
    <div class="portfolio-grid">
        <?php foreach ($posts as $post): ?>
            <article class="portfolio-item">
                <?php if ($post['featured_image']): ?>
                    <img src="/uploads/<?= htmlspecialchars($post['featured_image']) ?>" alt="">
                <?php endif; ?>
                
                <h2>
                    <a href="/<?= $cpt['slug'] ?>/<?= htmlspecialchars($post['slug']) ?>">
                        <?= htmlspecialchars($post['title']) ?>
                    </a>
                </h2>
                
                <?php if ($post['excerpt']): ?>
                    <p><?= htmlspecialchars($post['excerpt']) ?></p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</div>

<?php get_footer(); ?>
