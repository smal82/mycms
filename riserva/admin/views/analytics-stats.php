<h1>📊 Statistiche Google Analytics</h1>

<?php if (isset($error)): ?>
    <div class="error-message">
        Errore: <?php echo htmlspecialchars($error); ?>
    </div>
<?php else: ?>

    <?php
    // Estrai totali (gestisci caso 0 righe)
    $totals = $visitors['rows'][0]['metricValues'] ?? null;
    $activeUsers = $totals ? ($totals[0]['value'] ?? 0) : 0;
    $sessions = $totals ? ($totals[1]['value'] ?? 0) : 0;
    $screenPageViews = $totals ? ($totals[2]['value'] ?? 0) : 0;
    
    // Prepara dati per il grafico (anche se vuoti)
    $chartLabels = [];
    $chartValues = [];
    
    if (!empty($pageViews['rows'])) {
        foreach ($pageViews['rows'] as $row) {
            $date = $row['dimensionValues'][0]['value'];
            $chartLabels[] = substr($date, 4, 2) . '/' . substr($date, 6, 2);
            $chartValues[] = (int)$row['metricValues'][0]['value'];
        }
    } else {
        // Genera etichette vuote per gli ultimi 30 giorni
        for ($i = 29; $i >= 0; $i--) {
            $date = date('m/d', strtotime("-$i days"));
            $chartLabels[] = $date;
            $chartValues[] = 0;
        }
    }
    ?>

    <!-- Carte riepilogative -->
    <div class="stats-cards">
        <div class="stat-card">
            <h3>Utenti Attivi</h3>
            <p class="stat-number"><?php echo number_format($activeUsers); ?></p>
            <small>Ultimi 30 giorni</small>
        </div>
        
        <div class="stat-card">
            <h3>Sessioni</h3>
            <p class="stat-number"><?php echo number_format($sessions); ?></p>
            <small>Ultimi 30 giorni</small>
        </div>
        
        <div class="stat-card">
            <h3>Visualizzazioni</h3>
            <p class="stat-number"><?php echo number_format($screenPageViews); ?></p>
            <small>Ultimi 30 giorni</small>
        </div>
    </div>

    <?php if (empty($pageViews['rows'])): ?>
        <div class="info-message">
            ℹ️ <strong>In attesa dei primi dati</strong><br>
            Google Analytics elabora i dati entro 24-48 ore dall'attivazione del tracking. 
            I grafici si popoleranno automaticamente non appena i dati saranno disponibili.
        </div>
    <?php endif; ?>

    <!-- Grafico Visualizzazioni nel tempo (sempre visibile) -->
    <div class="chart-container">
        <h2>Visualizzazioni Giornaliere</h2>
        <canvas id="pageViewsChart"></canvas>
    </div>

    <!-- Tabella pagine più visitate -->
    <div class="table-container">
        <h2>Pagine Più Visitate</h2>
        <?php if (!empty($topPages['rows'])): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Pagina</th>
                        <th>Visualizzazioni</th>
                        <th>Utenti</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topPages['rows'] as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['dimensionValues'][0]['value']); ?></td>
                            <td><?php echo number_format($row['metricValues'][0]['value']); ?></td>
                            <td><?php echo number_format($row['metricValues'][1]['value']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align:center; color:#718096; padding:40px;">
                Nessuna pagina visualizzata negli ultimi 30 giorni
            </p>
        <?php endif; ?>
    </div>

<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dati per il grafico
const labels = <?php echo json_encode($chartLabels); ?>;
const values = <?php echo json_encode($chartValues); ?>;

// Crea grafico
const ctx = document.getElementById('pageViewsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Visualizzazioni',
            data: values,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true
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
                        return 'Visualizzazioni: ' + context.parsed.y;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});
</script>

<style>
.stats-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin: 30px 0;
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
}

.stat-number {
    font-size: 42px;
    font-weight: bold;
    color: #667eea;
    margin: 10px 0;
}

.stat-card small {
    color: #a0aec0;
    font-size: 12px;
}

.chart-container {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 40px;
    height: 400px;
}

.chart-container h2 {
    margin: 0 0 20px 0;
    font-size: 18px;
    color: #2d3748;
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
}

.admin-table td {
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
}

.admin-table tr:hover {
    background: #f7fafc;
}

.info-message {
    background: #e3f2fd;
    border: 1px solid #2196f3;
    color: #0d47a1;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
}

@media (max-width: 768px) {
    .stats-cards {
        grid-template-columns: 1fr;
    }
}
</style>
