<?php
$siteTitle = $this->db->getSetting('site_title', 'Nexa CMS');
$siteFavicon = $this->db->getSetting('site_favicon');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pagedash); ?> - <?php echo htmlspecialchars($siteTitle ?? 'Admin'); ?></title>
    <link rel="stylesheet" href="assets/admin.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .has-submenu > a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
        }

        .submenu {
            display: none;
            padding-left: 12px;
        }

        .has-submenu.open .submenu {
            display: block;
        }

        .submenu li a {
            font-size: 0.95em;
        }

        .submenu-arrow {
            margin-left: auto;
            opacity: 0.7;
            transition: transform 0.2s;
        }

        .has-submenu.open .submenu-arrow {
            transform: rotate(180deg);
        }

        .trash-badge {
            background: #dc3545;
            color: white;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 0.75em;
            margin-left: 5px;
        }
    </style>
    <?php if ($siteFavicon): ?>
        <link rel="icon" href="/uploads/<?php echo htmlspecialchars($siteFavicon); ?>">
    <?php endif; ?>
</head>
<body>
    <div class="admin-container">
        <nav class="admin-sidebar" id="sidebar">
            <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
            <h2>Admin CMS</h2>
            <ul>
                <li class="has-submenu <?php echo (in_array($this->action, ['dashboard', 'dashboard_widgets', 'analytics_stats', 'site_analytics'])) ? 'active' : ''; ?>">
    <a href="javascript:void(0)" onclick="toggleSubmenu(this)">
        <span class="icon">📊</span><span>Dashboard</span><span class="submenu-arrow">▾</span>
    </a>
    <ul class="submenu">
        <li>
            <a href="index.php?action=dashboard" class="<?php echo ($this->action === 'dashboard') ? 'active' : ''; ?>">
                <span class="icon">📊</span><span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="index.php?action=dashboard_widgets" class="<?php echo ($this->action === 'dashboard_widgets') ? 'active' : ''; ?>">
                <span class="icon">📦</span><span>Widget Dashboard</span>
            </a>
        </li>
        <?php if ($this->db->getSetting('google_analytics')): ?>
        <li>
            <a href="index.php?action=analytics_stats" class="<?php echo ($this->action === 'analytics_stats') ? 'active' : ''; ?>">
                <span class="icon">📈</span><span>Statistiche</span>
            </a>
        </li>
        <?php endif; ?>
        <li>
            <a href="index.php?action=site_analytics" class="<?php echo ($this->action === 'site_analytics') ? 'active' : ''; ?>">
                <span class="icon">📈</span><span>Le mie Statistiche</span>
            </a>
        </li>
    </ul>
</li>
                
                
                <?php
                // Conta elementi nei cestini
                $trashedPagesCount = count($this->db->getTrashedPages());
                $trashedPostsCount = count($this->db->getTrashedPosts());
                
                $pagesActions = ['pages', 'edit_page', 'trash_pages'];
                $isPagesActive = in_array($this->action, $pagesActions);
                
                $postsActions = ['posts', 'edit_post', 'trash_posts', 'categories'];
                $isPostsActive = in_array($this->action, $postsActions);
                ?>
                
                <!-- PAGINE con sottomenu se cestino non vuoto -->
                <?php if ($trashedPagesCount > 0): ?>
                <li class="has-submenu <?php echo $isPagesActive ? 'open active' : ''; ?>">
                    <a href="javascript:void(0)" onclick="toggleSubmenu(this)">
                        <span class="icon">📄</span>
                        <span>Pagine</span>
                        <span class="submenu-arrow">▾</span>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="index.php?action=pages" class="<?php echo (in_array($this->action, ['pages', 'edit_page'])) ? 'active' : ''; ?>">
                                <span class="icon">📋</span><span>Tutte le Pagine</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?action=trash_pages" class="<?php echo ($this->action === 'trash_pages') ? 'active' : ''; ?>">
                                <span class="icon">🗑️</span><span>Cestino <span class="trash-badge"><?php echo $trashedPagesCount; ?></span></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php else: ?>
                <li><a href="index.php?action=pages" class="<?php echo (in_array($this->action, ['pages', 'edit_page'])) ? 'active' : ''; ?>"><span class="icon">📄</span><span>Pagine</span></a></li>
                <?php endif; ?>
                
                <!-- POST -->
                
                <li class="has-submenu <?php echo $isPostsActive ? 'open active' : ''; ?>">
                    <a href="javascript:void(0)" onclick="toggleSubmenu(this)">
                        <span class="icon">📰</span>
                        <span>Post</span>
                        <span class="submenu-arrow">▾</span>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="index.php?action=posts" class="<?php echo (in_array($this->action, ['posts', 'edit_post'])) ? 'active' : ''; ?>">
                                <span class="icon">📋</span><span>Tutti i Post</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?action=categories" class="<?php echo ($this->action === 'categories') ? 'active' : ''; ?>">
                                <span class="icon">🏷️</span><span>Categorie</span>
                            </a>
                        </li>
                        <?php if ($trashedPostsCount > 0): ?>
                        <li>
                            <a href="index.php?action=trash_posts" class="<?php echo ($this->action === 'trash_posts') ? 'active' : ''; ?>">
                                <span class="icon">🗑️</span><span>Cestino <span class="trash-badge"><?php echo $trashedPostsCount; ?></span></span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php
// Conta elementi nel cestino media
$trashedMediaCount = count($this->db->getTrashedMedia());
$mediaActions = ['media', 'trash_media'];
$isMediaActive = in_array($this->action, $mediaActions);
?>

<!-- MEDIA con sottomenu se cestino non vuoto -->
<?php if ($trashedMediaCount > 0): ?>
<li class="has-submenu <?php echo $isMediaActive ? 'open active' : ''; ?>">
    <a href="javascript:void(0)" onclick="toggleSubmenu(this)">
        <span class="icon">🖼️</span>
        <span>Media</span>
        <span class="submenu-arrow">▾</span>
    </a>
    <ul class="submenu">
        <li>
            <a href="index.php?action=media" class="<?php echo ($this->action === 'media') ? 'active' : ''; ?>">
                <span class="icon">📂</span><span>Galleria</span>
            </a>
        </li>
        <li>
            <a href="index.php?action=trash_media" class="<?php echo ($this->action === 'trash_media') ? 'active' : ''; ?>">
                <span class="icon">🗑️</span><span>Cestino <span class="trash-badge"><?php echo $trashedMediaCount; ?></span></span>
            </a>
        </li>
    </ul>
</li>
<?php else: ?>
<li><a href="index.php?action=media" class="<?php echo ($this->action === 'media') ? 'active' : ''; ?>"><span class="icon">🖼️</span><span>Media</span></a></li>
<?php endif; ?>

                
                <?php if ($this->user->hasRole(User::ROLE_ADMIN)): ?>
                
                <?php
                $appearanceActions = ['customizer', 'menus', 'edit_menu', 'themes', 'theme_widgets'];
                $isAppearanceActive = in_array($this->action, $appearanceActions);
                ?>
                
                <li class="has-submenu <?php echo $isAppearanceActive ? 'open active' : ''; ?>">
                    <a href="javascript:void(0)" onclick="toggleSubmenu(this)">
                        <span class="icon">🎭</span>
                        <span>Aspetto</span>
                        <span class="submenu-arrow">▾</span>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="index.php?action=customizer" class="<?php echo ($this->action === 'customizer') ? 'active' : ''; ?>">
                                <span class="icon">🎨</span><span>Personalizza</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?action=menus" class="<?php echo in_array($this->action, ['menus', 'edit_menu']) ? 'active' : ''; ?>">
                                <span class="icon">🔗</span><span>Menu</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?action=themes" class="<?php echo ($this->action === 'themes') ? 'active' : ''; ?>">
                                <span class="icon">🧩</span><span>Temi</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?action=theme_widgets" class="<?php echo ($this->action === 'theme_widgets') ? 'active' : ''; ?>">
                                <span class="icon">📦</span><span>Widget Tema</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
<?php
// Ottieni menu custom prima
$customMenus = apply_hook('admin_custom_menus', []);

// Crea lista degli slug già nei menu custom
$customSlugs = [];
foreach ($customMenus as $menu) {
    if (isset($menu['submenu'])) {
        foreach ($menu['submenu'] as $sub) {
            $customSlugs[] = $sub['slug'];
        }
    }
}

// Ottieni le pagine dei plugin
$pluginPages = $this->cms->getPluginPages();

// Filtra escludendo quelle già nei menu custom
$pluginPagesFiltered = array_filter($pluginPages, function($page) use ($customSlugs) {
    return !in_array($page['slug'], $customSlugs);
});

// Determina se siamo in una sezione plugin
$isPluginActive = $this->action === 'plugins' || 
                  ($this->action === 'plugin-page' && 
                   isset($_GET['page']) && 
                   in_array($_GET['page'], array_column($pluginPagesFiltered, 'slug')));
?>
<?php if (empty($pluginPagesFiltered)): ?>
    <li><a href="index.php?action=plugins" class="<?php echo ($this->action === 'plugins') ? 'active' : ''; ?>"><span class="icon">⚡</span><span>Plugin</span></a></li>
<?php else: ?>
    <li class="has-submenu <?php echo $isPluginActive ? 'active' : ''; ?>">
        <a href="javascript:void(0)" onclick="toggleSubmenu(this)">
            <span class="icon">⚡</span>
            <span>Plugin</span>
            <span class="submenu-arrow">▾</span>
        </a>
        <ul class="submenu">
            <li><a href="index.php?action=plugins" class="<?php echo ($this->action === 'plugins') ? 'active' : ''; ?>"><span class="icon">⚙️</span><span>Gestione Plugin</span></a></li>
            <?php foreach ($pluginPagesFiltered as $page): ?>
                <li>
                    <a href="index.php?action=plugin-page&page=<?php echo urlencode($page['slug']); ?>" 
                       class="<?php echo ($this->action === 'plugin-page' && isset($_GET['page']) && $_GET['page'] === $page['slug']) ? 'active' : ''; ?>">
                        <span class="icon"><?php echo htmlspecialchars($page['icon'] ?? '📄'); ?></span><span><?php echo htmlspecialchars($page['title']); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </li>
<?php endif; ?>

<?php
// Renderizza menu custom
foreach ($customMenus as $menu):
    $isActive = false;
    if (isset($menu['submenu'])) {
        foreach ($menu['submenu'] as $sub) {
            if ($this->action === 'plugin-page' && isset($_GET['page']) && $_GET['page'] === $sub['slug']) {
                $isActive = true;
                break;
            }
        }
    }
?>
<li class="has-submenu <?php echo $isActive ? 'open active' : ''; ?>">
    <a href="javascript:void(0)" onclick="toggleSubmenu(this)">
        <span class="icon"><?php echo htmlspecialchars($menu['icon'] ?? '📦'); ?></span>
        <span><?php echo htmlspecialchars($menu['title']); ?></span>
        <span class="submenu-arrow">▾</span>
    </a>
    <ul class="submenu">
        <?php foreach ($menu['submenu'] as $subpage): ?>
        <li>
            <a href="index.php?action=plugin-page&page=<?php echo urlencode($subpage['slug']); ?>" 
               class="<?php echo ($this->action === 'plugin-page' && isset($_GET['page']) && $_GET['page'] === $subpage['slug']) ? 'active' : ''; ?>">
                <span class="icon"><?php echo htmlspecialchars($subpage['icon'] ?? '📄'); ?></span>
                <span><?php echo htmlspecialchars($subpage['title']); ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</li>
<?php endforeach; ?>


                <li>
                    <a href="index.php?action=custom_post_types" class="<?php echo ($this->action === 'custom_post_types') ? 'active' : ''; ?>">
                    <span class="icon">⚙️</span>
                    <span>Gestisci CPT</span>
                </a>
                </li>
                <?php
$usersActions = ['users', 'edit_user', 'profile'];
$isUsersActive = in_array($this->action, $usersActions);
?>
                <li class="has-submenu <?php echo $isUsersActive ? 'active' : ''; ?>">
    <a href="javascript:void(0)" onclick="toggleSubmenu(this)">
        <span class="icon">👥</span>
        <span>Utenti</span>
        <span class="submenu-arrow">▾</span>
    </a>
    <ul class="submenu">
        <li><a href="index.php?action=users" class="<?php echo (in_array($this->action, ['users', 'edit_user'])) ? 'active' : ''; ?>"><span class="icon">👥</span><span>Utenti</span></a></li>
        <li><a href="index.php?action=profile" class="<?php echo ($this->action === 'profile') ? 'active' : ''; ?>"><span class="icon">⚙️</span><span>Profilo</span></a></li>
    </ul>
</li>

                <?php endif; ?>
                
                <!-- MENU STRUMENTI -->
<?php if ($this->user->hasRole(User::ROLE_ADMIN)): ?>
<?php
$toolsActions = ['backup'];
$isToolsActive = in_array($this->action, $toolsActions);
?>
<li class="has-submenu <?php echo $isToolsActive ? 'open active' : ''; ?>">
    <a href="javascript:void(0)" onclick="toggleSubmenu(this)">
        <span class="icon">🔧</span>
        <span>Strumenti</span>
        <span class="submenu-arrow">▾</span>
    </a>
    <ul class="submenu">
        <li>
            <a href="index.php?action=backup" class="<?php echo ($this->action === 'backup') ? 'active' : ''; ?>">
                <span class="icon">💾</span><span>Backup</span>
            </a>
        </li>
    </ul>
</li>
<?php endif; ?>
                <?php if ($this->user->hasRole(User::ROLE_ADMIN)): ?>
                <?php
$settingActions = ['impostazioni_generali', 'impostazioni_lettura'];
$isSettingsActive = in_array($this->action, $settingActions);
?>
                <li class="has-submenu <?php echo $isSettingsActive ? 'open active' : ''; ?>">
  <a href="javascript:void(0)" onclick="toggleSubmenu(this)">
                        <span class="icon">⚙️</span>
                        <span>Impostazioni</span>
                        <span class="submenu-arrow">▾</span>
                    </a>
  <ul class="submenu">
    <li><a href="?action=impostazioni_generali" class="<?php echo ($this->action === 'impostazioni_generali') ? 'active' : ''; ?>">Generali</a></li>
    <li><a href="?action=impostazioni_lettura" class="<?php echo ($this->action === 'impostazioni_lettura') ? 'active' : ''; ?>">Lettura</a></li>
    
  </ul>
</li>
<?php endif; ?>
                
                <li><a href="../" target="_blank"><span class="icon">🌐</span><span>Vedi Sito</span></a></li>
                <li><a href="index.php?action=logout"><span class="icon">🚪</span><span>Logout</span></a></li>
            </ul>
        </nav>
        <main class="admin-content">
            
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');
    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
}

// Ripristina stato sidebar al caricamento
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        document.getElementById('sidebar').classList.add('collapsed');
    }
    
    // Chiudi tutti i menu tranne quelli con classe "active"
    document.querySelectorAll('.has-submenu').forEach(function(menu) {
        if (!menu.classList.contains('active')) {
            menu.classList.remove('open');
        } else {
            menu.classList.add('open');
        }
    });
    
    // Aggiungi event listener a tutti i link dei sottomenu
    document.querySelectorAll('.submenu a').forEach(function(link) {
        link.addEventListener('click', function() {
            // Chiudi tutti i menu prima di navigare
            closeAllMenusExceptActive();
        });
    });
});

// Funzione per chiudere tutti i menu tranne quelli attivi
function closeAllMenusExceptActive() {
    document.querySelectorAll('.has-submenu').forEach(function(menu) {
        if (!menu.classList.contains('active')) {
            menu.classList.remove('open');
        }
    });
}

// Funzione per toggle sottomenu
function toggleSubmenu(element) {
    const li = element.closest('.has-submenu');
    const wasOpen = li.classList.contains('open');
    
    // Chiudi tutti gli altri sottomenu aperti
    document.querySelectorAll('.has-submenu').forEach(function(menu) {
        if (menu !== li && !menu.classList.contains('active')) {
            menu.classList.remove('open');
        }
    });
    
    // Toggle del menu cliccato
    if (wasOpen && !li.classList.contains('active')) {
        li.classList.remove('open');
    } else {
        li.classList.add('open');
    }
}
</script>
