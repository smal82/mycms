<?php
define('INSTALLING', true);
session_start();

// Carica bootstrap per verificare se già installato
require_once 'core/bootstrap.php';

// Se già installato (DB_NAME non vuoto), redirect alla home
if (!empty(DB_NAME)) {
    header('Location: /');
    exit;
}

$step = $_GET['step'] ?? 1;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installazione CMS</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .installer { background: white; max-width: 700px; width: 100%; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); overflow: hidden; }
        .installer-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; text-align: center; }
        .installer-header h1 { font-size: 2.5em; margin-bottom: 10px; }
        .installer-header p { opacity: 0.9; }
        .installer-body { padding: 40px; }
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 15px; transition: all 0.3s; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #667eea; }
        .form-group small { display: block; margin-top: 5px; color: #666; font-size: 13px; }
        .btn { padding: 15px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 50px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(102,126,234,0.4); }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn-secondary { background: #6c757d; margin-left: 10px; }
        .progress-container { margin: 30px 0; }
        .progress-bar { width: 100%; height: 30px; background: #e0e0e0; border-radius: 15px; overflow: hidden; position: relative; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); width: 0%; transition: width 0.5s ease; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px; }
        #install-log { background: #f8f9fa; border: 2px solid #e0e0e0; border-radius: 8px; padding: 20px; max-height: 400px; overflow-y: auto; font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.6; }
        .log-entry { padding: 5px 0; }
        .log-success { color: #28a745; }
        .log-error { color: #dc3545; font-weight: bold; }
        .log-info { color: #17a2b8; }
        .final-buttons { display: flex; gap: 15px; justify-content: center; margin-top: 30px; }
        .hidden { display: none; }
        .success-icon { font-size: 80px; text-align: center; margin: 30px 0; }
    </style>
</head>
<body>
    <div class="installer">
        <div class="installer-header">
            <h1>🚀 Installazione CMS</h1>
            <p>Configuriamo il tuo nuovo sito web</p>
        </div>
        
        <div class="installer-body">
            <!-- Step 1: Benvenuto -->
            <div id="step-1" class="step">
                <h2>Benvenuto!</h2>
                <p style="margin: 20px 0; line-height: 1.8; color: #666;">
                    Questo wizard ti guiderà attraverso l'installazione del CMS.<br>
                    Avrai bisogno di:
                </p>
                <ul style="margin: 20px 0 30px 30px; color: #666; line-height: 2;">
                    <li>Un database MySQL già creato</li>
                    <li>Le credenziali di accesso al database</li>
                    <li>5 minuti del tuo tempo</li>
                </ul>
                <button class="btn" onclick="goToStep(2)">Inizia Installazione</button>
            </div>
            
            <!-- Step 2: Database -->
            <div id="step-2" class="step hidden">
                <h2>Configurazione Database</h2>
                <p style="margin-bottom: 20px; color: #666;">Inserisci i dati di connessione al tuo database MySQL</p>
                
                <form id="db-form">
                    <div class="form-group">
                        <label>Host Database:</label>
                        <input type="text" name="db_host" value="localhost" required>
                    </div>
                    <div class="form-group">
                        <label>Nome Database:</label>
                        <input type="text" name="db_name" placeholder="mio_cms" required>
                    </div>
                    <div class="form-group">
                        <label>Username Database:</label>
                        <input type="text" name="db_user" placeholder="root" required>
                    </div>
                    <div class="form-group">
                        <label>Password Database:</label>
                        <input type="password" name="db_pass" placeholder="Lascia vuoto se non hai password">
                    </div>
                    <div class="form-group">
                        <label>Prefisso Tabelle:</label>
                        <input type="text" name="db_prefix" value="" placeholder="cms_">
                        <small>Prefisso opzionale per i nomi delle tabelle (es: cms_users, cms_posts).</small>
                    </div>
                    
                    <button type="submit" class="btn" id="test-db-btn">Verifica Connessione</button>
                    <button type="button" class="btn btn-secondary" onclick="goToStep(1)">Indietro</button>
                </form>
                
                <div id="db-test-result" style="margin-top: 20px;"></div>
            </div>
            
            <!-- Step 3: Informazioni Sito -->
            <div id="step-3" class="step hidden">
                <h2>Informazioni Sito</h2>
                
                <form id="site-form">
                    <div class="form-group">
                        <label>Titolo del Sito:</label>
                        <input type="text" name="site_title" placeholder="Il mio CMS" required>
                    </div>
                    <div class="form-group">
                        <label>Descrizione Sito:</label>
                        <textarea name="site_description" rows="3" placeholder="Un CMS moderno e potente"></textarea>
                    </div>
                    
                    <h3 style="margin: 30px 0 20px;">Amministratore</h3>
                    
                    <div class="form-group">
                        <label>Nome Amministratore:</label>
                        <input type="text" name="admin_name" placeholder="Admin" required>
                    </div>
                    <div class="form-group">
                        <label>Email Amministratore:</label>
                        <input type="email" name="admin_email" placeholder="admin@example.com" required>
                    </div>
                    <div class="form-group">
                        <label>Password Amministratore:</label>
                        <input type="password" name="admin_password" placeholder="Minimo 6 caratteri" required minlength="6">
                    </div>
                    
                    <button type="submit" class="btn">Installa CMS</button>
                    <button type="button" class="btn btn-secondary" onclick="goToStep(2)">Indietro</button>
                </form>
            </div>
            
            <!-- Step 4: Installazione -->
            <div id="step-4" class="step hidden">
                <h2>Installazione in corso...</h2>
                
                <div class="progress-container">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill">0%</div>
                    </div>
                </div>
                
                <div id="install-log"></div>
                
                <div id="final-step" class="hidden">
                    <div class="success-icon">✅</div>
                    <h2 style="text-align: center; color: #28a745;">Installazione Completata!</h2>
                    <p style="text-align: center; margin: 20px 0; color: #666;">
                        Il tuo CMS è stato installato con successo.
                    </p>
                    
                    <div class="final-buttons">
                        <a href="/" class="btn">Visualizza Sito</a>
                        <a href="/admin/" class="btn btn-secondary">Pannello Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    let dbConfig = {};
    
    function goToStep(step) {
        $('.step').addClass('hidden');
        $('#step-' + step).removeClass('hidden');
    }
    
    function addLog(message, type = 'info') {
        const logClass = type === 'success' ? 'log-success' : (type === 'error' ? 'log-error' : 'log-info');
        const icon = type === 'success' ? '✓' : (type === 'error' ? '✗' : 'ℹ');
        $('#install-log').append(`<div class="log-entry ${logClass}">${icon} ${message}</div>`);
        $('#install-log').scrollTop($('#install-log')[0].scrollHeight);
    }
    
    function updateProgress(percent, text) {
        $('#progress-fill').css('width', percent + '%').text(text || percent + '%');
    }
    
    // Test connessione database
    $('#db-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        $('#test-db-btn').prop('disabled', true).text('Verifica in corso...');
        $('#db-test-result').html('');
        
        $.ajax({
            url: 'install-ajax.php?action=test_db',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    dbConfig = data.db_config;
                    $('#db-test-result').html('<div class="log-success">✓ Connessione riuscita!</div>');
                    setTimeout(() => goToStep(3), 1000);
                } else {
                    $('#db-test-result').html('<div class="log-error">✗ ' + data.error + '</div>');
                    $('#test-db-btn').prop('disabled', false).text('Verifica Connessione');
                }
            },
            error: function(xhr, status, error) {
                console.error('Errore AJAX:', xhr.responseText);
                $('#db-test-result').html('<div class="log-error">✗ Errore di comunicazione: ' + error + '<br>Controlla la console per dettagli.</div>');
                $('#test-db-btn').prop('disabled', false).text('Verifica Connessione');
            }
        });
    });
    
    // Installa CMS
    $('#site-form').on('submit', function(e) {
        e.preventDefault();
        
        const siteData = {
            db: dbConfig,
            site_title: $('[name="site_title"]').val(),
            site_description: $('[name="site_description"]').val(),
            admin_name: $('[name="admin_name"]').val(),
            admin_email: $('[name="admin_email"]').val(),
            admin_password: $('[name="admin_password"]').val()
        };
        
        goToStep(4);
        performInstallation(siteData);
    });
    
    function performInstallation(data) {
        updateProgress(10, 'Salvataggio configurazione...');
        addLog('Inizio installazione', 'info');
        
        // Step 1: Salva config in bootstrap.php
        $.ajax({
            url: 'install-ajax.php?action=save_config',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function(result) {
                if (result.success) {
                    addLog('Configurazione salvata in bootstrap.php', 'success');
                    updateProgress(25, '25%');
                    createTables(data);
                } else {
                    addLog('Errore salvataggio configurazione: ' + result.error, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Errore save_config:', xhr.responseText);
                addLog('Errore comunicazione: ' + error, 'error');
            }
        });
    }
    
    function createTables(data) {
        updateProgress(30, 'Creazione tabelle...');
        addLog('Creazione struttura database...', 'info');
        
        $.ajax({
            url: 'install-ajax.php?action=create_tables',
            type: 'POST',
            dataType: 'json',
            success: function(result) {
                if (result.success) {
                    addLog('Tabelle create con successo', 'success');
                    updateProgress(60, '60%');
                    insertData(data);
                } else {
                    addLog('Errore creazione tabelle: ' + result.error, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Errore create_tables:', xhr.responseText);
                addLog('Errore comunicazione: ' + error, 'error');
            }
        });
    }
    
    function insertData(data) {
        updateProgress(70, 'Inserimento dati...');
        addLog('Inserimento dati iniziali...', 'info');
        
        $.ajax({
            url: 'install-ajax.php?action=insert_data',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function(result) {
                if (result.success) {
                    addLog('Dati iniziali inseriti', 'success');
                    addLog('Utente admin creato', 'success');
                    addLog('Categorie create', 'success');
                    addLog('Contenuti di esempio creati', 'success');
                    addLog('Email di riepilogo inviata', 'success');
                    updateProgress(100, 'Completato!');
                    
                    setTimeout(() => {
                        $('#final-step').removeClass('hidden');
                    }, 500);
                } else {
                    addLog('Errore inserimento dati: ' + result.error, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Errore insert_data:', xhr);
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response:', xhr.responseText);
                
                // Prova a fare il parse del JSON anche in caso di errore
                try {
                    var errData = JSON.parse(xhr.responseText);
                    addLog('Errore: ' + errData.error, 'error');
                } catch(e) {
                    // Non è JSON valido, mostra la risposta raw
                    addLog('Errore comunicazione: ' + (xhr.responseText || error), 'error');
                    if (xhr.responseText) {
                        addLog('Dettagli: ' + xhr.responseText.substring(0, 200), 'error');
                    }
                }
            }
        });
    }
    </script>
</body>
</html>