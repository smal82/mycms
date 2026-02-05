<div class="blog-header">
    <div class="container">
        <h1 class="blog-title">Blog</h1>
        <p>Tutti i nostri articoli</p>
    </div>
</div>

<div class="site-main">
    <div class="container">
        <div class="content-wrapper">
            <div class="posts-list">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <article class="post-list-item">
                            <?php if ($post['featured_image']): ?>
                                <div class="post-list-image">
                                    <a href="../post/<?php echo htmlspecialchars($post['slug']); ?>">
                                        <img src="/uploads/<?php echo htmlspecialchars($post['featured_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($post['title']); ?>">
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="post-list-content">
                                <h2 class="post-list-title">
                                    <a href="../post/<?php echo htmlspecialchars($post['slug']); ?>">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </h2>
                                <div class="post-list-meta">
                                    📅 <?php echo date('d M Y', strtotime($post['created_at'])); ?> 
                                    | ✍️ <?php echo htmlspecialchars($post['author_name']); ?>
                                </div>
                                <?php if ($post['excerpt']): ?>
                                    <p class="post-list-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                                <?php endif; ?>
                                <a href="../post/<?php echo htmlspecialchars($post['slug']); ?>" class="read-more">
                                    Continua a leggere <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align:center; color:#718096; padding: 60px;">Nessun post ancora pubblicato.</p>
                <?php endif; ?>
            

            <?php if ($totalPages > 1): ?>
                <nav class="pagination-nav" role="navigation" aria-label="Paginazione Blog">
                    <ul class="pagination">
                        <!-- Previous -->
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Pagina precedente">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Numeri pagine -->
                        <?php 
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);
                        if ($startPage > 1): ?>
                            <li class="page-item"><a class="page-link" href="?page=1">1</a></li>
                            <?php if ($startPage > 2): ?><li class="page-item disabled"><span>...</span></li><?php endif; ?>
                        <?php endif; ?>

                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($endPage < $totalPages): ?>
                            <?php if ($endPage < $totalPages - 1): ?><li class="page-item disabled"><span>...</span></li><?php endif; ?>
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a></li>
                        <?php endif; ?>

                        <!-- Next -->
                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Pagina successiva">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
        <!-- Sidebar -->
        <aside class="sidebar">
    <div class="sidebar-widgets">
        <?php 
        
            if (has_widgets('sidebar')) {
            render_widget_area('sidebar');
        }
        
        ?>
    </div>
</aside>
</div>
    </div>
</div>