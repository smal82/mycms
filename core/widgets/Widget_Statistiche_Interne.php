<?php
/**
 * Widget per statistiche interne del CMS
 * Mostra visite giornaliere e pagine più viste
 */
class Widget_Statistiche_Interne {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function render($config = []) {
        $days = isset($config['days']) ? (int)$config['days'] : 7;
        $range = $days - 1;
        
        // Verifica che la tabella esista
        if (!$this->tableExists()) {
            ?>
            <div class="dashboard-widget widget-internal-analytics">
                <h3>ðŸ“Š Statistiche Interne</h3>
                <div class="empty-state">
                    <p>Sistema di statistiche non installato</p>
                    <a href="index.php?action=analytics_install" class="btn-small">Installa ora</a>
                </div>
            </div>
            <style>
                .btn-small {
                    display: inline-block;
                    padding: 8px 16px;
                    background: #2563eb;
                    color: white;
                    text-decoration: none;
                    border-radius: 6px;
                    font-size: 13px;
                    margin-top: 10px;
                }
                .btn-small:hover {
                    background: #1d4ed8;
                }
            </style>
            <?php
            return;
        }
        
        try {
            $prefix = DB_PREFIX;
            
            // Visite giornaliere ultimi N giorni
            $stmt = $this->db->pdo->prepare("
                SELECT visit_date, COUNT(*) as visits
                FROM `{$prefix}page_visits`
                WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                GROUP BY visit_date
                ORDER BY visit_date ASC
            ");
            $stmt->execute([$range]);
            $dailyVisits = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Pagine più viste negli ultimi N giorni
            $stmt = $this->db->pdo->prepare("
                SELECT 
                    CASE 
                        WHEN page_url = '' THEN '/'
                        ELSE page_url 
                    END as page_url,
                    page_title,
                    COUNT(*) as views
                FROM `{$prefix}page_visits`
                WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                GROUP BY page_url, page_title
                ORDER BY views DESC
                LIMIT 10
            ");
            $stmt->execute([$range]);
            $topPages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Prepara dati grafico
            $chartLabels = [];
            $chartValues = [];
            $totalViews = 0;
            
            // Crea array con tutti i giorni del periodo
            $dateMap = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $dateMap[$date] = 0;
            }
            
            // Popola con i dati reali
            foreach ($dailyVisits as $row) {
                $dateMap[$row['visit_date']] = (int)$row['visits'];
            }
            
            // Prepara dati per il grafico
            foreach ($dateMap as $date => $views) {
                $chartLabels[] = date('d/m', strtotime($date));
                $chartValues[] = $views;
                $totalViews += $views;
            }
            
        } catch (Exception $e) {
            ?>
            <div class="dashboard-widget widget-internal-analytics">
                <h3>📊  Statistiche Interne</h3>
                <div class="error-state">
                    <p>Errore nel caricamento dei dati</p>
                    <small><?php echo htmlspecialchars($e->getMessage()); ?></small>
                </div>
            </div>
            <?php
            return;
        }
        
        $widgetId = 'internal-analytics-widget-' . uniqid();
        ?>
        
        <div class="dashboard-widget widget-internal-analytics">
            <h3>📊  Statistiche Interne (ultimi <?php echo $days; ?> giorni)</h3>
            
            <!-- Tab Navigation -->
            <div class="widget-tabs">
                <button class="tab-btn active" data-tab="views-tab-<?php echo $widgetId; ?>">
                    Visite giornaliere (<?php echo number_format($totalViews); ?>)
                </button>
                <button class="tab-btn" data-tab="pages-tab-<?php echo $widgetId; ?>">
                    Pagine Top (<?php echo count($topPages); ?>)
                </button>
            </div>
            
            <!-- Tab Content: Visite giornaliere -->
            <div id="views-tab-<?php echo $widgetId; ?>" class="tab-content active">
                <div class="mini-chart-container">
                    <canvas id="chart-<?php echo $widgetId; ?>"></canvas>
                </div>
            </div>
            
            <!-- Tab Content: Pagine Top -->
            <div id="pages-tab-<?php echo $widgetId; ?>" class="tab-content">
                <?php if (!empty($topPages)): ?>
                    <ul class="analytics-list">
                        <?php foreach ($topPages as $page): ?>
                        <li>
                            <div class="page-info">
                                <span class="page-path" title="<?php echo htmlspecialchars($page['page_url']); ?>">
                                    <?php echo htmlspecialchars($page['page_title'] ?: $page['page_url']); ?>
                                </span>
                                <span class="page-views"><?php echo number_format($page['views']); ?> visite</span>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="empty-state">Nessun dato disponibile per questo periodo.</p>
                <?php endif; ?>
            </div>
            
            <div class="widget-footer">
                <a href="index.php?action=site_analytics">Vedi tutte le statistiche →</a>
            </div>
        </div>
        
        <style>
            .widget-internal-analytics {
                position: relative;
            }
        </style>
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestione tab
            const widget = document.querySelector('.widget-internal-analytics');
            if (!widget) return;
            
            const tabBtns = widget.querySelectorAll('.tab-btn');
            const tabContents = widget.querySelectorAll('.tab-content');
            
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    
                    this.classList.add('active');
                    document.getElementById(targetTab).classList.add('active');
                });
            });
            
            // Crea grafico
            const ctx = document.getElementById('chart-<?php echo $widgetId; ?>');
            if (ctx) {
                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode($chartLabels); ?>,
                        datasets: [{
                            label: 'Visite',
                            data: <?php echo json_encode($chartValues); ?>,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 3,
                            pointHoverRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Visite: ' + context.parsed.y;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    font: { size: 11 }
                                }
                            },
                            x: {
                                ticks: {
                                    font: { size: 11 }
                                }
                            }
                        }
                    }
                });
            }
        });
        </script>
        <?php
    }
    
    /**
     * Verifica se la tabella statistiche esiste
     */
    private function tableExists() {
        try {
            $prefix = DB_PREFIX;
            $stmt = $this->db->pdo->prepare("SHOW TABLES LIKE '{$prefix}page_visits'");
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
