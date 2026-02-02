<h1>📊 Configurazione Google Analytics - Guida</h1>

<div class="guide-container">
    <h2>Passo 1: Crea Service Account</h2>
    <ol>
        <li>Vai su <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a></li>
        <li>Seleziona o crea un progetto</li>
        <li>Vai su <strong>API e servizi</strong> → <strong>Abilita API e servizi</strong></li>
        <li>Cerca e abilita <strong>Google Analytics Data API</strong></li>
        <li>Vai su <strong>Credenziali</strong> → <strong>Crea credenziali</strong> → <strong>Account di servizio</strong></li>
        <li>Inserisci un nome (es: "analytics-reader")</li>
        <li>Clicca su <strong>Crea e continua</strong></li>
        <li>Salta gli step opzionali e clicca <strong>Fine</strong></li>
        <li>Clicca sull'account appena creato</li>
        <li>Vai su tab <strong>Chiavi</strong> → <strong>Aggiungi chiave</strong> → <strong>Crea nuova chiave</strong></li>
        <li>Scegli tipo <strong>JSON</strong> → scarica il file</li>
    </ol>

    <h2>Passo 2: Dai accesso al Service Account su Google Analytics</h2>
    <ol>
        <li>Apri il file JSON scaricato e copia l'email del service account (es: <code>analytics-reader@progetto.iam.gserviceaccount.com</code>)</li>
        <li>Vai su <a href="https://analytics.google.com/" target="_blank">Google Analytics</a></li>
        <li>Vai su <strong>Admin</strong> (in basso a sinistra)</li>
        <li>Nella colonna <strong>Account</strong>, clicca su <strong>Gestione accessi account</strong></li>
        <li>Clicca <strong>+</strong> (Aggiungi utenti)</li>
        <li>Incolla l'email del service account</li>
        <li>Seleziona ruolo <strong>Visualizzatore</strong></li>
        <li>Clicca <strong>Aggiungi</strong></li>
    </ol>

    <h2>Passo 3: Carica il file JSON nel CMS</h2>
    
    <form method="POST" action="index.php?action=upload_ga_credentials" enctype="multipart/form-data" style="margin-top: 20px;">
        <div class="form-group">
            <label>File JSON Service Account:</label>
            <input type="file" name="ga_json_file" accept=".json" required>
            <small>Seleziona il file scaricato da Google Cloud Console</small>
        </div>
        <button type="submit" class="btn">Carica Credenziali</button>
        <a href="index.php?action=customizer" class="btn" style="background: #ccc; margin-left: 10px;">Annulla</a>
    </form>
</div>

<style>
.guide-container {
    max-width: 900px;
    background: white;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.guide-container h2 {
    color: #2d3748;
    margin-top: 40px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #667eea;
}

.guide-container ol {
    line-height: 2;
    padding-left: 25px;
}

.guide-container ol li {
    margin-bottom: 10px;
}

.guide-container code {
    background: #f7fafc;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 13px;
    color: #e53e3e;
}

.guide-container a {
    color: #667eea;
    text-decoration: none;
}

.guide-container a:hover {
    text-decoration: underline;
}
</style>
