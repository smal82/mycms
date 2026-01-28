<?php
/**
 * FILE: /admin/views/backup.php
 * Vista Backup System con Sincronizzazione GitHub
 */

// Genera token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<h1>🔧 Strumenti - Backup</h1>
<!-- Sistema Tab con 3 tab -->
<div class="backup-tabs-container">
    <div class="backup-tabs-nav">
        <button class="backup-tab-btn active" data-tab="backup-tab">Backup</button>
        <button class="backup-tab-btn" data-tab="settings-tab">Impostazioni</button>
    </div>
    
    <!-- TAB 1: BACKUP -->
    <div id="backup-tab" class="backup-tab-content active">
        <div class="two-column-layout" style="grid-template-columns: 1fr;">
            <div class="column">
                <h2>Crea Nuovo Backup</h2>
                <p style="margin-bottom: 20px; color: #666;">Crea un backup completo di file e database. Il backup includerà tutti i file del CMS e tutte le tabelle del database.</p>
                <button id="start-backup-btn" class="btn">
                    <span class="icon">💾</span> Avvia Backup
                </button>
                
                <!-- Barra di avanzamento -->
                <div id="progress-container" style="display: none; margin-top: 20px;">
                    <div class="progress-bar-wrapper">
                        <div id="progress-bar" class="progress-bar-fill">
                            <span id="progress-text">0%</span>
                        </div>
                    </div>
                    <div id="progress-message" style="margin-top: 10px; font-size: 0.9em; color: #666;"></div>
                </div>
                
                <!-- Risultato -->
                <div id="backup-result" style="display: none; margin-top: 20px;"></div>
            </div>
        </div>
        
        <!-- Lista Backup Esistenti -->
        <div class="two-column-layout" style="grid-template-columns: 1fr; margin-top: 20px;">
            <div class="column">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="margin: 0;">Backup Esistenti</h2>
                    <?php if (count($backups) > 0): ?>
                    <button id="delete-all-btn" class="btn-delete">
                        <span class="icon">🗑️</span> Elimina Tutti
                    </button>
                    <?php endif; ?>
                </div>
                
                <?php if (count($backups) > 0): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nome File</th>
                            <th>Data Creazione</th>
                            <th>Dimensione</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody id="backups-list">
                        <?php foreach ($backups as $backup): ?>
                        <tr data-filename="<?php echo htmlspecialchars($backup['filename']); ?>">
                            <td><?php echo htmlspecialchars($backup['filename']); ?></td>
                            <?php /* <td><?php echo date('d/m/Y H:i:s', $backup['date']); ?></td>*/ ?>
                            <td><?php 
    $dt = new DateTime();
    $dt->setTimestamp($backup['date']);
    $dt->setTimezone(new DateTimeZone('Europe/Rome'));
    echo $dt->format('d/m/Y H:i:s'); 
?></td>

                            <td><?php echo number_format($backup['size'] / 1048576, 2); ?> MB</td>
                            <td>
                                <a href="../backups/<?php echo htmlspecialchars($backup['filename']); ?>" 
                                   class="btn-edit" download style="margin-right: 5px;">
                                    ⬇️ Scarica
                                </a>
                                <button class="btn-delete delete-backup-btn" 
                                        data-filename="<?php echo htmlspecialchars($backup['filename']); ?>">
                                    🗑️ Elimina
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="text-align: center; color: #999; padding: 40px 0;">Nessun backup disponibile</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- TAB 2: IMPOSTAZIONI -->
    <div id="settings-tab" class="backup-tab-content">
        <div class="two-column-layout" style="grid-template-columns: 1fr;">
            <div class="column">
                <h2>Impostazioni Backup</h2>
                <form id="settings-form">
                    <div class="form-group">
                        <label for="max-backups">Numero massimo di backup da conservare:</label>
                        <input type="number" id="max-backups" name="max_backups" 
                               value="<?php echo htmlspecialchars($maxBackups); ?>" 
                               min="1" max="100" style="max-width: 200px;">
                        <small>Quando viene creato un nuovo backup, i file più vecchi oltre questo limite verranno automaticamente eliminati.</small>
                    </div>
                    <button type="submit" class="btn">
                        💾 Salva Impostazioni
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
/* Sistema Tab - Stili personalizzati solo per questa funzionalità */
.backup-tabs-container {
    margin-top: 20px;
}

.backup-tabs-nav {
    display: flex;
    border-bottom: 2px solid #ddd;
    margin-bottom: 20px;
    background: white;
    border-radius: 8px 8px 0 0;
    overflow: hidden;
}

.backup-tab-btn {
    padding: 15px 30px;
    background: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    color: #666;
    border-bottom: 3px solid transparent;
    transition: all 0.3s;
}

.backup-tab-btn:hover {
    color: #333;
    background: #f8f9fa;
}

.backup-tab-btn.active {
    color: #0066cc;
    border-bottom-color: #0066cc;
    font-weight: bold;
    background: white;
}

.backup-tab-content {
    display: none;
}

.backup-tab-content.active {
    display: block;
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Barra di avanzamento */
.progress-bar-wrapper {
    width: 100%;
    height: 35px;
    background: #f0f0f0;
    border-radius: 17px;
    overflow: hidden;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
    border: 1px solid #ddd;
}

.progress-bar-fill {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #0066cc, #0052a3);
    border-radius: 17px;
    transition: width 0.4s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 14px;
    position: relative;
}

.progress-bar-fill::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background: linear-gradient(90deg, 
        transparent, 
        rgba(255,255,255,0.3), 
        transparent
    );
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Alert boxes */
.alert-success-backup {
    background: #d4edda;
    color: #155724;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #c3e6cb;
}

.alert-error-backup {
    background: #f8d7da;
    color: #721c24;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #f5c6cb;
}

.alert-success-backup strong,
.alert-error-backup strong {
    display: block;
    margin-bottom: 10px;
    font-size: 16px;
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
const CSRF_TOKEN = '<?php echo $_SESSION['csrf_token']; ?>';

$(document).ready(function() {
    // ========== SISTEMA TAB ==========
    $('.backup-tab-btn').click(function() {
        const tabId = $(this).data('tab');
        
        $('.backup-tab-btn').removeClass('active');
        $(this).addClass('active');
        
        $('.backup-tab-content').removeClass('active');
        $('#' + tabId).addClass('active');
    });
    
    // ========== TAB 1: BACKUP ==========
    $('#start-backup-btn').click(function() {
        const btn = $(this);
        btn.prop('disabled', true).text('⏳ Backup in corso...');
        
        $('#progress-container').show();
        $('#backup-result').hide();
        $('#progress-bar').css('width', '0%');
        $('#progress-text').text('0%');
        $('#progress-message').text('Inizializzazione...');
        
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'backup-process.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        let lastPosition = 0;
        
        xhr.onprogress = function() {
            const response = xhr.responseText.substring(lastPosition);
            lastPosition = xhr.responseText.length;
            
            const lines = response.split('\n');
            lines.forEach(function(line) {
                if (line.trim()) {
                    try {
                        const data = JSON.parse(line);
                        updateProgress(data);
                    } catch(e) {}
                }
            });
        };
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                const lines = xhr.responseText.split('\n');
                const lastLine = lines[lines.length - 2] || lines[lines.length - 1];
                
                try {
                    const data = JSON.parse(lastLine);
                    if (data.completed) {
                        $('#backup-result').html(
                            '<div class="alert-success-backup">' +
                            '<strong>✓ Backup completato con successo!</strong>' +
                            'File: ' + data.filename + '<br>' +
                            'Dimensione: ' + (data.size / 1048576).toFixed(2) + ' MB<br>' +
                            '<a href="../backups/' + encodeURIComponent(data.filename) + '" class="btn-edit" style="margin-top: 15px; display: inline-block;">' +
                            '⬇️ Scarica Backup</a>' +
                            '</div>'
                        ).show();
                        
                        setTimeout(function() { location.reload(); }, 2000);
                    }
                } catch(e) {
                    showError('Errore nel parsing della risposta');
                }
            } else {
                showError('Errore nella richiesta: ' + xhr.status);
            }
            
            btn.prop('disabled', false).html('<span class="icon">💾</span> Avvia Backup');
        };
        
        xhr.onerror = function() {
            showError('Errore di connessione');
            btn.prop('disabled', false).html('<span class="icon">💾</span> Avvia Backup');
        };
        
        xhr.send('csrf_token=' + CSRF_TOKEN);
    });
    
    function updateProgress(data) {
        if (data.progress !== undefined) {
            $('#progress-bar').css('width', data.progress + '%');
            $('#progress-text').text(data.progress + '%');
        }
        if (data.message) {
            $('#progress-message').text(data.message);
        }
        if (data.error) {
            showError(data.error);
        }
    }
    
    function showError(message) {
        $('#backup-result').html(
            '<div class="alert-error-backup">' +
            '<strong>✗ Errore durante il backup!</strong>' + message +
            '</div>'
        ).show();
    }
    
    // Elimina singolo backup
    $(document).on('click', '.delete-backup-btn', function() {
        const filename = $(this).data('filename');
        const row = $(this).closest('tr');
        
        if (!confirm('Sei sicuro di voler eliminare questo backup?')) {
            return;
        }
        
        $.post('index.php?action=delete_backup', {
            filename: filename,
            csrf_token: CSRF_TOKEN
        }, function(response) {
            if (response.success) {
                row.fadeOut(300, function() {
                    $(this).remove();
                    if ($('#backups-list tr').length === 0) {
                        location.reload();
                    }
                });
                Swal.fire('Successo!', response.message, 'success');
            } else {
                Swal.fire('Errore!', response.message, 'error');
            }
        }, 'json');
    });
    
    // Elimina tutti i backup
    $('#delete-all-btn').click(function() {
        Swal.fire({
            title: 'Sei sicuro?',
            text: "Verranno eliminati tutti i backup esistenti!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#666',
            confirmButtonText: 'Sì, elimina tutti',
            cancelButtonText: 'Annulla'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('index.php?action=delete_all_backups', {
                    csrf_token: CSRF_TOKEN
                }, function(response) {
                    if (response.success) {
                        Swal.fire('Eliminati!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Errore!', response.message, 'error');
                    }
                }, 'json');
            }
        });
    });
    
    // ========== TAB 2: IMPOSTAZIONI ==========
    $('#settings-form').submit(function(e) {
        e.preventDefault();
        
        $.post('index.php?action=save_backup_settings', {
            max_backups: $('#max-backups').val(),
            csrf_token: CSRF_TOKEN
        }, function(response) {
            if (response.success) {
                Swal.fire('Successo!', response.message, 'success');
            } else {
                Swal.fire('Errore!', response.message, 'error');
            }
        }, 'json');
    });
});
</script>
