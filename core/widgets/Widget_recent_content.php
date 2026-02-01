<?php
class Widget_recent_content {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function render($config = []) {
        $limit = isset($config['limit']) ? (int)$config['limit'] : 5;
        $prefix = DB_PREFIX;
        
        // ===== ULTIMI POST (da tabella posts) =====
        $stmtPosts = $this->db->pdo->prepare("
            SELECT id, title, created_at 
            FROM {$prefix}posts 
            WHERE post_type = 'post' 
            AND deleted_at IS NULL
            ORDER BY created_at DESC 
            LIMIT {$limit}
        ");
        $stmtPosts->execute();
        $recentPosts = $stmtPosts->fetchAll(PDO::FETCH_ASSOC);
        
        // ===== ULTIME PAGINE (da tabella pages) =====
        $stmtPages = $this->db->pdo->prepare("
            SELECT id, title, created_at 
            FROM {$prefix}pages 
            WHERE deleted_at IS NULL
            ORDER BY created_at DESC 
            LIMIT {$limit}
        ");
        $stmtPages->execute();
        $recentPages = $stmtPages->fetchAll(PDO::FETCH_ASSOC);
        ?>
        
        <div class="dashboard-widget widget-recent-content">
            <h3>📝 Contenuti Recenti</h3>
            
            <!-- Tab Navigation -->
            <div class="widget-tabs">
                <button class="tab-btn active" data-tab="posts-tab">Post (<?php echo count($recentPosts); ?>)</button>
                <button class="tab-btn" data-tab="pages-tab">Pagine (<?php echo count($recentPages); ?>)</button>
            </div>
            
            <!-- Tab Content: Post -->
            <div id="posts-tab" class="tab-content active">
                <?php if (!empty($recentPosts)): ?>
                    <ul class="recent-list">
                        <?php foreach ($recentPosts as $post): ?>
                        <li>
                            <a href="index.php?action=edit_post&id=<?php echo $post['id']; ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                            <span class="meta"><?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="empty-state">Nessun post ancora creato.</p>
                <?php endif; ?>
            </div>
            
            <!-- Tab Content: Pagine -->
            <div id="pages-tab" class="tab-content">
                <?php if (!empty($recentPages)): ?>
                    <ul class="recent-list">
                        <?php foreach ($recentPages as $page): ?>
                        <li>
                            <a href="index.php?action=edit_page&id=<?php echo $page['id']; ?>">
                                <?php echo htmlspecialchars($page['title']); ?>
                            </a>
                            <span class="meta"><?php echo date('d/m/Y H:i', strtotime($page['created_at'])); ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="empty-state">Nessuna pagina ancora creata.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <style>
            .widget-tabs {
                display: flex;
                border-bottom: 2px solid #e5e7eb;
                margin-bottom: 15px;
            }
            
            .tab-btn {
                flex: 1;
                padding: 10px 15px;
                background: transparent;
                border: none;
                border-bottom: 3px solid transparent;
                cursor: pointer;
                font-size: 14px;
                font-weight: 500;
                color: #6b7280;
                transition: all 0.3s ease;
            }
            
            .tab-btn:hover {
                color: #2563eb;
                background: #f3f4f6;
            }
            
            .tab-btn.active {
                color: #2563eb;
                border-bottom-color: #2563eb;
                background: #eff6ff;
            }
            
            .tab-content {
                display: none;
            }
            
            .tab-content.active {
                display: block;
            }
            
            .recent-list {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            
            .recent-list li {
                padding: 12px 0;
                border-bottom: 1px solid #f3f4f6;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 10px;
            }
            
            .recent-list li:last-child {
                border-bottom: none;
            }
            
            .recent-list a {
                color: #1f2937;
                text-decoration: none;
                font-weight: 500;
                flex: 1;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            
            .recent-list a:hover {
                color: #2563eb;
            }
            
            .meta {
                font-size: 12px;
                color: #9ca3af;
                flex-shrink: 0;
            }
            
            .empty-state {
                text-align: center;
                color: #9ca3af;
                padding: 30px;
                font-style: italic;
            }
        </style>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const widget = document.querySelector('.widget-recent-content');
            if (!widget) return;
            
            const tabBtns = widget.querySelectorAll('.tab-btn');
            const tabContents = widget.querySelectorAll('.tab-content');
            
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    
                    // Rimuovi active da tutti
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    
                    // Aggiungi active al selezionato
                    this.classList.add('active');
                    document.getElementById(targetTab).classList.add('active');
                });
            });
        });
        </script>
        <?php
    }
}
?>
