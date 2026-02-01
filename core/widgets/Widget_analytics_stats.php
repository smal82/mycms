<?php
class Widget_analytics_stats {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function render($config = []) {
        $days = isset($config['days']) ? (int)$config['days'] : 7;
        
        // Verifica configurazione Google Analytics
        $propertyId = $this->db->getSetting('ga_property_id');
        $serviceAccountJson = base64_decode($this->db->getSetting('ga_service_account_json'));
        
        if (!$propertyId || !$serviceAccountJson) {
            ?>
            <div class="dashboard-widget widget-analytics">
                <h3>📈 Statistiche Google Analytics</h3>
                <div class="empty-state">
                    <p>Google Analytics non configurato</p>
                    <a href="index.php?action=customizer" class="btn-small">Configura ora</a>
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
        
        // Carica dati Analytics
        try {
            require_once __DIR__ . '/../../core/GoogleAnalyticsAPI.php';
            $analytics = new GoogleAnalyticsAPI($serviceAccountJson, $propertyId);
            
            // Visite giornaliere ultimi 7 giorni
            $pageViews = $analytics->getPageViews($days);
            
            // Pagine più viste ultimi 7 giorni
            $topPages = $analytics->getTopPages($days, 5);
            
            // Prepara dati grafico
            $chartLabels = [];
            $chartValues = [];
            $totalViews = 0;
            
            if (!empty($pageViews['rows'])) {
                foreach ($pageViews['rows'] as $row) {
                    $date = $row['dimensionValues'][0]['value'];
                    $chartLabels[] = substr($date, 6, 2) . '/' . substr($date, 4, 2);
                    $value = (int)$row['metricValues'][0]['value'];
                    $chartValues[] = $value;
                    $totalViews += $value;
                }
            } else {
                // Dati vuoti
                for ($i = $days - 1; $i >= 0; $i--) {
                    $date = date('d/m', strtotime("-$i days"));
                    $chartLabels[] = $date;
                    $chartValues[] = 0;
                }
            }
            
        } catch (Exception $e) {
            ?>
            <div class="dashboard-widget widget-analytics">
                <h3>📈 Statistiche Google Analytics</h3>
                <div class="error-state">
                    <p>Errore nel caricamento dei dati</p>
                    <small><?php echo htmlspecialchars($e->getMessage()); ?></small>
                </div>
            </div>
            <?php
            return;
        }
        
        $widgetId = 'analytics-widget-' . uniqid();
        ?>
        
        <div class="dashboard-widget widget-analytics">
            <h3>📈 Analytics (ultimi <?php echo $days; ?> giorni)</h3>
            
            <!-- Tab Navigation -->
            <div class="widget-tabs">
                <button class="tab-btn active" data-tab="views-tab-<?php echo $widgetId; ?>">
                    Visite giornaliere (<?php echo number_format($totalViews); ?>)
                </button>
                <button class="tab-btn" data-tab="pages-tab-<?php echo $widgetId; ?>">
                    Pagine Top (<?php echo count($topPages['rows'] ?? []); ?>)
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
    <?php if (!empty($topPages['rows'])): ?>
        <?php
        // Raggruppa per pagePath e somma visualizzazioni
        $groupedPages = [];
        
        foreach ($topPages['rows'] as $page) {
            $pagePath = $page['dimensionValues'][0]['value'];
            $views = (int)$page['metricValues'][0]['value'];
            
            if (!isset($groupedPages[$pagePath])) {
                $groupedPages[$pagePath] = 0;
            }
            
            $groupedPages[$pagePath] += $views;
        }
        
        // Ordina per visualizzazioni (dal pi� alto al pi� basso)
        arsort($groupedPages);
        
        // Limita a 5 pagine
        $groupedPages = array_slice($groupedPages, 0, 5, true);
        ?>
        
        <ul class="analytics-list">
            <?php foreach ($groupedPages as $pagePath => $views): ?>
            <li>
                <div class="page-info">
                    <span class="page-path"><?php echo htmlspecialchars($pagePath); ?></span>
                    <span class="page-views"><?php echo number_format($views); ?> visite</span>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="empty-state">Nessun dato disponibile per questo periodo.</p>
    <?php endif; ?>
</div>

            
            <div class="widget-footer">
                <a href="index.php?action=analytics_stats">Vedi tutte le statistiche →</a>
            </div>
        </div>
        
        <style>
            .widget-analytics {
                position: relative;
            }
            
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
                font-size: 13px;
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
            
            .mini-chart-container {
                height: 200px;
                padding: 10px 0;
            }
            
            .analytics-list {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            
            .analytics-list li {
                padding: 12px 0;
                border-bottom: 1px solid #f3f4f6;
            }
            
            .analytics-list li:last-child {
                border-bottom: none;
            }
            
            .page-info {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 15px;
            }
            
            .page-path {
                color: #1f2937;
                font-size: 13px;
                flex: 1;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            
            .page-views {
                font-size: 12px;
                color: #2563eb;
                font-weight: 600;
                flex-shrink: 0;
            }
            
            .widget-footer {
                margin-top: 15px;
                padding-top: 15px;
                border-top: 1px solid #e5e7eb;
                text-align: center;
            }
            
            .widget-footer a {
                color: #2563eb;
                text-decoration: none;
                font-size: 13px;
                font-weight: 500;
            }
            
            .widget-footer a:hover {
                text-decoration: underline;
            }
            
            .empty-state {
                text-align: center;
                color: #9ca3af;
                padding: 30px;
                font-style: italic;
                font-size: 13px;
            }
            
            .error-state {
                text-align: center;
                color: #dc2626;
                padding: 20px;
            }
            
            .error-state small {
                display: block;
                margin-top: 8px;
                color: #6b7280;
                font-size: 12px;
            }
        </style>
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestione tab
            const widget = document.querySelector('.widget-analytics');
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
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
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
}
?>
