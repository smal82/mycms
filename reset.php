<?php
define('INSTALLING', true);
session_start();

$ajax = isset($_GET['ajax']);

if ($ajax) {
    header('Content-Type: application/json');

    require_once __DIR__ . '/core/bootstrap.php';

    $log = [];

    function addLog(&$log, $msg, $type = 'info') {
        $log[] = [
            'message' => $msg,
            'type'    => $type
        ];
    }

    // Tabelle create dall'installer
    $tables = [
        'users',
        'pages',
        'categories',
        'posts',
        'post_categories',
        'uploads',
        'contents',
        'settings',
        'active_plugins',
        'menus',
        'menu_items',
        'dashboard_widgets',
        'theme_widget_areas'
    ];

    $prefix = DB_PREFIX ?? '';

    // 1. Connessione DB
    if (!empty(DB_NAME) && !empty(DB_USER)) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            addLog($log, 'Connessione al database riuscita', 'success');
            
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
            addLog($log, 'Foreign key disabilitate', 'info');

            foreach ($tables as $table) {
                $full = $prefix . $table;
                try {
                    $pdo->exec("DROP TABLE IF EXISTS {$full}");
                    addLog($log, "Tabella {$full} eliminata", 'success');
                } catch (PDOException $e) {
                    addLog($log, "Errore eliminando {$full}: " . $e->getMessage(), 'error');
                }
            }
            
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
            addLog($log, 'Foreign key ripristinate', 'info');

        } catch (PDOException $e) {
            addLog($log, 'Errore connessione DB: ' . $e->getMessage(), 'error');
        }
    } else {
        addLog($log, 'Configurazione database vuota, salto eliminazione tabelle', 'info');
    }

    // 2. Reset bootstrap.php
    $bootstrapPath = __DIR__ . '/core/bootstrap.php';

    if (!file_exists($bootstrapPath)) {
        addLog($log, 'bootstrap.php non trovato', 'error');
    } else {
        $content = file_get_contents($bootstrapPath);

        $map = [
            'DB_NAME',
            'DB_USER',
            'DB_PASS',
            'DB_PREFIX'
        ];

        foreach ($map as $key) {
            $content = preg_replace(
                "/define\('{$key}',\s*'[^']*'\);/",
                "define('{$key}', '');",
                $content
            );
            addLog($log, "Reset definizione {$key}", 'success');
        }

        if (file_put_contents($bootstrapPath, $content) === false) {
            addLog($log, 'Errore scrittura bootstrap.php', 'error');
        } else {
            addLog($log, 'bootstrap.php aggiornato correttamente', 'success');
        }
    }

    unset($_SESSION['install_data']);
    addLog($log, 'Sessione installazione pulita', 'success');

    echo json_encode([
        'success' => true,
        'log'     => $log
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Reset CMS</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
body {
    font-family: Segoe UI, sans-serif;
    background: linear-gradient(135deg,#667eea 0%,#764ba2 100%);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}
.box {
    background:#fff;
    width:700px;
    border-radius:20px;
    box-shadow:0 20px 60px rgba(0,0,0,.3);
    overflow:hidden;
}
.header {
    background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
    color:#fff;
    padding:30px;
    text-align:center;
}
.body {
    padding:30px;
}
button {
    padding:15px 40px;
    border:none;
    border-radius:50px;
    background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
    color:#fff;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
}
button:disabled {
    opacity:.5;
}
#log {
    margin-top:30px;
    background:#f8f9fa;
    border:2px solid #e0e0e0;
    border-radius:8px;
    padding:20px;
    max-height:350px;
    overflow-y:auto;
    font-family:Courier New, monospace;
    font-size:13px;
}
.log-success { color:#28a745; }
.log-error { color:#dc3545; font-weight:bold; }
.log-info { color:#17a2b8; }
</style>
</head>
<body>

<div class="box">
    <div class="header">
        <h1>Reset CMS</h1>
        <p>Ripristino completo installazione</p>
    </div>
    <div class="body">
        <p>
            Questa operazione eliminerà <strong>tutte le tabelle del CMS</strong>
            e ripristinerà la configurazione iniziale.
        </p>

        <button id="reset-btn">Esegui Reset</button>

        <div id="log"></div>
    </div>
</div>

<script>
$('#reset-btn').on('click', function () {
    if (!confirm('Sei sicuro di voler eseguire il reset completo?')) return;

    const btn = $(this);
    btn.prop('disabled', true).text('Reset in corso...');

    $('#log').html('');

    $.getJSON('reset.php?ajax=1', function (res) {
        res.log.forEach(function (entry) {
            $('#log').append(
                `<div class="log-${entry.type}">• ${entry.message}</div>`
            );
        });

        btn.text('Reset completato');
    }).fail(function (xhr) {
        $('#log').append('<div class="log-error">Errore AJAX</div>');
        btn.prop('disabled', false).text('Esegui Reset');
    });
});
</script>

</body>
</html>
