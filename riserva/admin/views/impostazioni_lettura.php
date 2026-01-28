<?php 
$success = isset($_GET['saved']) ? 'Impostazioni Lettura salvate!' : ''; 
?>
<h1>Impostazioni Lettura</h1>

<div class="one-column-layout">
    <div class="column">
        <?php if ($success): ?>
            <div class="success-message">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="index.php?action=save_lettura">
            
            
            <div class="form-group">
                <label>Articoli per pagina <span class="text-danger">*</span></label>
                <input type="number" name="posts_per_page" class="form-control" 
                       value="<?php echo htmlspecialchars($settingsLettura['posts_per_page']); ?>" 
                       min="1" max="50" required>
                <small>Numero massimo di articoli visualizzati nelle pagine del blog (default: 10)</small>
            </div>

            <div class="form-group">
                <label>Visibilità ai Motori di Ricerca</label>
                <label class="form-check-label">
                    <input type="checkbox" name="search_engine_visibility" value="1" 
                           <?php echo $settingsLettura['search_engine_visibility'] === '1' ? 'checked' : ''; ?>>
                    <strong>Discoraggia</strong> i motori di ricerca (noindex)
                </label>
                <small>
                    Indica se i motori di ricerca possono o no indicizzare il sito.<br/>
                    Selezionato vuol dire ai motori di ricerca di indicizzare il sito.<br/>
                    Se deselezionato aggiunge <code>&lt;meta name="robots" content="noindex,nofollow"&gt;</code> 
                    a tutte le pagine del sito.
                </small>
            </div>

            <div>
                <button type="submit" class="btn">
                    <i class="fas fa-save"></i> Salva Impostazioni
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.text-danger { color: #dc3545; }
.form-check-label input[type="checkbox"] { margin-right: 8px; transform: scale(1.2); }
.badge { padding: 4px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; }
.badge-success { background: #d4edda; color: #155724; }
.badge-warning { background: #fff3cd; color: #856404; }
code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; font-family: monospace; }
</style>
