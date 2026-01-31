<?php
/**
 * FILE: /admin/views/site-analytics.php
 * Dashboard statistiche interne del sito
 */

// Verifica se la tabella esiste
require_once __DIR__ . '/../analytics-installer.php';
$installer = new AnalyticsInstaller();
$tableExists = $installer->tableExists();

if (!$tableExists) {
    echo '<div class="warning-box">
        <h2>⚠️ Sistema Statistiche Non Installato</h2>
        <p>La tabella per le statistiche non è ancora stata creata.</p>
        <button onclick="installAnalytics()" class="btn-primary">Installa Sistema Statistiche</button>
    </div>';
    ?>
    <script>
    function installAnalytics() {
        if (confirm('Vuoi creare la tabella per le statistiche?')) {
            fetch('/api/analytics/install', { method: 'POST' })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Sistema installato con successo!');
                        location.reload();
                    } else {
                        alert('Errore: ' + (data.error || data.message));
                    }
                })
                .catch(err => {
                    alert('Errore di connessione: ' + err.message);
                });
        }
    }
    </script>
    <style>
    .warning-box {
        background: #fff3cd;
        border: 2px solid #ffc107;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        margin: 40px auto;
        max-width: 600px;
    }
    .btn-primary {
        background: #667eea;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 20px;
    }
    .btn-primary:hover {
        background: #5568d3;
    }
    </style>
    <?php
    return;
}

// Tabella esiste, mostra statistiche
$stats = $installer->getTableStats();
?>

<div class="analytics-dashboard">
    <div class="header-section">
        <h1>📊 Statistiche Sito Interno</h1>
        <button onclick="refreshStats()" class="btn-refresh">
            <span id="refresh-icon">🔄</span> Aggiorna
        </button>
    </div>

    <!-- Carte riepilogative -->
    <div class="stats-cards">
        <div class="stat-card">
            <h3>Totale Visite</h3>
            <p class="stat-number" id="total-visits"><?php echo number_format($stats['total_visits'] ?? 0); ?></p>
            <small>Dall'inizio del tracciamento</small>
        </div>
        
        <div class="stat-card">
            <h3>Visite Oggi</h3>
            <p class="stat-number" id="today-visits"><?php echo number_format($stats['today_visits'] ?? 0); ?></p>
            <small><?php echo date('d/m/Y'); ?></small>
        </div>
        
    </div>
    
    <?php if ($stats['total_visits'] == 0): ?>
    <div class="info-message">
        ℹ️ <strong>Sistema attivo e in attesa di dati</strong><br>
        Il tracking è stato installato correttamente. I grafici si popoleranno automaticamente quando le pagine del sito riceveranno visite.
        Assicurati che il file <code>/themes/aurora/js/analytics.js</code> sia incluso nel footer del tema.
    </div>
    <?php endif; ?>

    <!-- Grafico Visualizzazioni giornaliere -->
    <div class="chart-container">
        <h2>Visite Giornaliere (Ultimi 30 Giorni)</h2>
        <canvas id="visitsChart"></canvas>
    </div>

    <!-- Top 20 Pagine di Oggi -->
    <div class="table-container">
        <h2>Top 20 Pagine Più Viste di Oggi</h2>
        <div id="top-pages-content">
            <div class="loading">Caricamento...</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
<script>
let visitsChart;
let refreshInterval;

async function loadStats() {
    try {
        const response = await fetch('/api/analytics/stats?days=30');
        const data = await response.json();
        
        if (data.success) {
            updateChart(data.daily_visits || []);
            updateTopPages(data.top_pages_today || []);
            await updateSummaryCards(data.summary); // Aggiunto await
        } else {
            console.error('Errore API:', data.error);
        }
    } catch (error) {
        console.error('Errore caricamento statistiche:', error);
    }
}


// Aggiorna il grafico
function updateChart(dailyVisits) {
    const ctx = document.getElementById('visitsChart').getContext('2d');
    
    let labels, data;
    
    // Se non ci sono dati, crea un grafico vuoto con gli ultimi 30 giorni
    if (dailyVisits.length === 0) {
        labels = [];
        data = [];
        
        // Genera etichette per gli ultimi 30 giorni
        for (let i = 29; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            labels.push(date.toLocaleDateString('it-IT', { day: '2-digit', month: '2-digit' }));
            data.push(0);
        }
    } else {
        // Crea una mappa dei dati esistenti
        const visitsMap = {};
        dailyVisits.forEach(d => {
            visitsMap[d.visit_date] = parseInt(d.visits);
        });
        
        labels = [];
        data = [];
        
        // Genera tutti gli ultimi 30 giorni
        for (let i = 29; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            const dateStr = date.toISOString().split('T')[0];
            
            labels.push(date.toLocaleDateString('it-IT', { day: '2-digit', month: '2-digit' }));
            data.push(visitsMap[dateStr] || 0);
        }
    }
    
    if (visitsChart) {
        visitsChart.destroy();
    }
    
    visitsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Visite',
                data: data,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: true,
                    labels: {
                        usePointStyle: true,
                        padding: 15
                    }
                },
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
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Aggiorna tabella top pagine
function updateTopPages(topPages) {
    const container = document.getElementById('top-pages-content');
    
    if (topPages.length === 0) {
        container.innerHTML = '<p class="no-data">📭 Nessuna visita registrata oggi. Il sistema è attivo e traccia automaticamente le visite alle pagine.</p>';
        return;
    }
    
    let html = `
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>URL Pagina</th>
                    <th>Titolo</th>
                    <th style="width: 100px; text-align: center;">Visite</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    topPages.forEach((page, index) => {
        html += `
            <tr>
                <td><strong>${index + 1}</strong></td>
                <td><code>${escapeHtml(page.page_url)}</code></td>
                <td>${escapeHtml(page.page_title || '-')}</td>
                <td style="text-align: center;"><span class="badge">${page.views}</span></td>
            </tr>
        `;
    });
    
    html += '</tbody></table>';
    container.innerHTML = html;
}

// Aggiorna le card riepilogative
async function updateSummaryCards(summary) {
    if (summary) {
        document.getElementById('today-visits').textContent = 
            new Intl.NumberFormat('it-IT').format(summary.today_visits || 0);
    }
    
    // Aggiorna anche il totale
    try {
        const response = await fetch('/api/analytics/status');
        const data = await response.json();
        
        if (data.success && data.stats) {
            document.getElementById('total-visits').textContent = 
                new Intl.NumberFormat('it-IT').format(data.stats.total_visits || 0);
        }
    } catch (error) {
        console.error('Errore aggiornamento totale:', error);
    }
}


// Funzione di refresh
async function refreshStats() {
    const icon = document.getElementById('refresh-icon');
    icon.style.animation = 'spin 1s linear';
    
    await Promise.all([
        loadStats(),
        updateAllCards()
    ]);
    
    setTimeout(() => {
        icon.style.animation = '';
    }, 1000);
}



// Helper per escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Carica dati iniziali
loadStats();

// Auto-refresh ogni 30 secondi
refreshInterval = setInterval(loadStats, 30000);

// Cleanup quando si lascia la pagina
window.addEventListener('beforeunload', () => {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});
</script>

<style>
.analytics-dashboard {
    padding: 20px;
}

.header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.header-section h1 {
    margin: 0;
    font-size: 28px;
    color: #2d3748;
}

.btn-refresh {
    background: white;
    border: 1px solid #e2e8f0;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.btn-refresh:hover {
    background: #f7fafc;
    border-color: #cbd5e0;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    text-align: center;
}

.stat-card h3 {
    color: #718096;
    font-size: 14px;
    margin-bottom: 10px;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.stat-number {
    font-size: 42px;
    font-weight: bold;
    color: #667eea;
    margin: 10px 0;
}

.stat-date {
    font-size: 28px;
}

.text-muted {
    color: #a0aec0;
    font-size: 20px;
    font-weight: normal;
}

.stat-card small {
    color: #a0aec0;
    font-size: 12px;
}

.info-message {
    background: #e3f2fd;
    border-left: 4px solid #2196f3;
    color: #0d47a1;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
    line-height: 1.6;
}

.info-message code {
    background: #bbdefb;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: monospace;
}

.chart-container {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    height: 450px;
}

.chart-container h2 {
    margin: 0 0 20px 0;
    font-size: 18px;
    color: #2d3748;
    font-weight: 600;
}

.table-container {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.table-container h2 {
    margin: 0 0 20px 0;
    font-size: 18px;
    color: #2d3748;
    font-weight: 600;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th {
    background: #f7fafc;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: #2d3748;
    border-bottom: 2px solid #e2e8f0;
    font-size: 14px;
}

.admin-table td {
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 14px;
}

.admin-table tr:hover {
    background: #f7fafc;
}

.admin-table code {
    background: #edf2f7;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 13px;
    color: #2d3748;
}

.badge {
    background: #667eea;
    color: white;
    padding: 4px 12px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 13px;
}

.loading {
    text-align: center;
    padding: 40px;
    color: #a0aec0;
}

.no-data {
    text-align: center;
    padding: 40px;
    color: #718096;
    font-size: 15px;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .stats-cards {
        grid-template-columns: 1fr;
    }
    
    .header-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
}
</style>
