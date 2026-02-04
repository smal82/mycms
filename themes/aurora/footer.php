<footer class="site-footer">
        <div class="container">
            <div class="footer-widgets">
                <?php 
        // Carica widget area footer
        if (function_exists('render_widget_area')) {
            render_widget_area('footer');
        }
        ?>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($this->db->getSetting('site_title', 'Il mio CMS')); ?>. Tutti i diritti riservati.</p>
            </div>
        </div>
    </footer>
    <?php do_hook('mycms_footer'); ?>
    <?php
    $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'amministratore';
    if (!$isAdmin) {
        ?>
    <script src="<?php echo get_theme_uri(); ?>/js/analytics.js" async></script>
    <?php } ?>
</body>
</html>