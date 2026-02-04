<div class="site-main">
    <div class="container">
        <div class="content-wrapper">
            <article class="article-single">
                <?php  if (!empty($content['featured_image'])): ?>
    <div class="article-featured-image">
        <img src="/uploads/<?php echo htmlspecialchars($content['featured_image']); ?>" alt="<?php echo htmlspecialchars($title); ?>">
    </div>
<?php endif; ?>
                <header class="article-header">
                    <h1 class="article-title"><?php echo htmlspecialchars($title); ?></h1>
                </header>
                
                <div class="article-content">
                    <?php echo $body; ?>
                </div>
            </article>
            
            <aside class="sidebar">
    <div class="sidebar-widgets">
        <?php 
        if (function_exists('render_widget_area')) {
            render_widget_area('sidebar');
        }
        ?>
    </div>
</aside>

        </div>
    </div>
</div>