<?php
/**
 * View: Form aggiungi/modifica feed (integrata nella dashboard)
 */
if (!defined('DB_PREFIX')) {
    die('Accesso diretto non consentito');
}
?>

<style>
    .form-container {
        max-width: 800px;
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .form-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e1e4e8;
    }
    
    .form-header h2 {
        margin: 0;
        font-size: 24px;
        color: #24292e;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #24292e;
        font-size: 14px;
    }
    
    .form-group input[type="text"],
    .form-group input[type="url"],
    .form-group input[type="number"],
    .form-group select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #d1d5da;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
        transition: border-color 0.2s;
    }
    
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #0366d6;
        box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.1);
    }
    
    .help-text {
        font-size: 13px;
        color: #586069;
        margin-top: 6px;
        line-height: 1.5;
    }
    
    .required-mark {
        color: #dc3545;
    }
    
    .btn-group {
        display: flex;
        gap: 10px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e1e4e8;
    }
    
    .alert-errors {
        background: #f8d7da;
        color: #721c24;
        padding: 20px;
        margin-bottom: 25px;
        border-radius: 6px;
        border-left: 4px solid #dc3545;
    }
    
    .alert-errors strong {
        display: block;
        margin-bottom: 10px;
        font-size: 16px;
    }
    
    .alert-errors ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .alert-errors li {
        margin: 5px 0;
    }
    
    .btn {
        padding: 12px 24px;
        text-decoration: none;
        border-radius: 6px;
        display: inline-block;
        cursor: pointer;
        border: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background: #0366d6;
        color: white;
    }
    
    .btn-primary:hover {
        background: #0256c7;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
    }
    
    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #0366d6;
        padding: 15px;
        margin-bottom: 25px;
        border-radius: 6px;
    }
    
    .info-box strong {
        display: block;
        margin-bottom: 8px;
        color: #0256c7;
    }
    
    .info-box ul {
        margin: 0;
        padding-left: 20px;
        font-size: 14px;
        color: #24292e;
    }
</style>

<div class="form-container">
    <div class="form-header">
        <h2>
            <?php echo $feedId ? '✏️ Modifica Feed RSS' : '➕ Nuovo Feed RSS'; ?>
        </h2>
    </div>
    
    <?php if (!empty($errori)): ?>
        <div class="alert-errors">
            <strong>⚠️ Errori nel form:</strong>
            <ul>
                <?php foreach ($errori as $errore): ?>
                    <li><?php echo htmlspecialchars($errore); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if (!$feedId): ?>
        <div class="info-box">
            <strong>ℹ️ Come funziona:</strong>
            <ul>
                <li>Ogni feed RSS verrà controllato automaticamente secondo la frequenza impostata</li>
                <li>I nuovi elementi saranno pubblicati come post di tipo "news"</li>
                <li>Le immagini vengono incluse come URL esterni (non scaricate)</li>
                <li>Ogni post includerà un link all'articolo originale</li>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="post">
        <div class="form-group">
            <label for="nome">
                Nome Feed <span class="required-mark">*</span>
            </label>
            <input type="text" 
                   id="nome" 
                   name="nome" 
                   value="<?php echo $feed ? htmlspecialchars($feed['nome']) : ''; ?>" 
                   placeholder="Es: TechCrunch, Il Post, Repubblica Tecnologia"
                   required>
            <div class="help-text">
                Nome identificativo del feed per riconoscerlo facilmente nell'admin
            </div>
        </div>
        
        <div class="form-group">
            <label for="url">
                URL Feed RSS <span class="required-mark">*</span>
            </label>
            <input type="url" 
                   id="url" 
                   name="url" 
                   value="<?php echo $feed ? htmlspecialchars($feed['url']) : ''; ?>" 
                   placeholder="https://example.com/feed.xml"
                   required>
            <div class="help-text">
                URL completo del feed RSS o Atom. Assicurati che sia accessibile pubblicamente.
            </div>
        </div>
        
        <div class="form-group">
            <label for="frequenza">
                Frequenza Scansione (ore) <span class="required-mark">*</span>
            </label>
            <input type="number" 
                   id="frequenza" 
                   name="frequenza" 
                   min="1" 
                   max="168" 
                   value="<?php echo $feed ? round($feed['frequenza'] / 3600, 1) : 3; ?>" 
                   step="0.5"
                   required>
            <div class="help-text">
                Ogni quante ore controllare il feed per nuovi contenuti (min: 1 ora, max: 168 ore/1 settimana).
                Valori consigliati: 1-3 ore per news frequenti, 6-12 ore per blog, 24 ore per contenuti occasionali.
            </div>
        </div>
        
        <div class="form-group">
            <label for="stato">
                Stato Feed
            </label>
            <select id="stato" name="stato">
                <option value="attivo" <?php echo (!$feed || $feed['stato'] === 'attivo') ? 'selected' : ''; ?>>
                    ✓ Attivo (importazione automatica)
                </option>
                <option value="pausa" <?php echo ($feed && $feed['stato'] === 'pausa') ? 'selected' : ''; ?>>
                    ⏸ In Pausa (nessuna importazione)
                </option>
            </select>
            <div class="help-text">
                Puoi mettere in pausa un feed temporaneamente senza eliminarlo
            </div>
        </div>
        
        <div class="btn-group">
            <button type="submit" class="btn btn-primary">
                <?php echo $feedId ? '💾 Salva Modifiche' : '➕ Crea Feed'; ?>
            </button>
            <a href="/admin/index.php?action=plugin-page&page=rss-feeds" class="btn btn-secondary">
                ← Torna alla Lista
            </a>
        </div>
    </form>
</div>
