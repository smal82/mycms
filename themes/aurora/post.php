<div class="site-main">
    <div class="container">
        <div class="content-wrapper">
            <article class="article-single">
                <?php if ($content['featured_image']): ?>
                    <div class="article-featured-image">
                        <img src="/uploads/<?php echo htmlspecialchars($content['featured_image']); ?>" alt="<?php echo htmlspecialchars($title); ?>">
                    </div>
                <?php endif; ?>
                
                <header class="article-header">
                    <h1 class="article-title"><?php echo htmlspecialchars($title); ?></h1>
                    <div class="article-meta">
                        <span>📅 <?php echo date('d M Y', strtotime($content['created_at'])); ?></span> | 
                        <span>✍️️   <?php echo htmlspecialchars($content['author_name']); ?></span>
                        <?php 
                        $categories = $this->db->getPostCategories($content['id']);
                        if (!empty($categories)): ?>
                            <span>🏷️ <?php echo implode(', ', array_column($categories, 'name')); ?></span>
                        <?php endif; ?>
                    </div>
                </header>
                
                <div class="article-content">
                    <?php echo $body; ?>
                </div>
            </article>
            
            <aside class="sidebar">
    <div class="sidebar-widgets">
        
        <?php 
        if (has_widgets('sidebarpost')) {
            render_widget_area('sidebarpost');
        }
        ?>
    </div>
</aside>
        </div>
    </div>
</div>
