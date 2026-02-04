<?php get_header(); ?>

<div class="container">
    <article class="single-portfolio">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        
        <?php if ($post['featured_image']): ?>
            <img src="/uploads/<?= htmlspecialchars($post['featured_image']) ?>" 
                 alt="<?= htmlspecialchars($post['title']) ?>" 
                 class="featured-image">
        <?php endif; ?>
        
        <div class="content">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>
        
        <?php if (!empty($post['meta'])): ?>
            <div class="custom-fields">
                <h3>Informazioni Aggiuntive</h3>
                <?php foreach ($post['meta'] as $key => $value): ?>
                    <p><strong><?= htmlspecialchars($key) ?>:</strong> <?= htmlspecialchars($value) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </article>
</div>

<?php get_footer(); ?>
