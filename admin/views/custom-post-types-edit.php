<?php
$isEdit = isset($cpt) && $cpt;
?>

<div class="admin-content">
    <div class="page-header">
        <h1><?= $isEdit ? 'Modifica' : 'Nuovo' ?> Custom Post Type</h1>
        <a href="index.php?action=custom_post_types" class="btn">← Indietro</a>
    </div>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <?php if ($_GET['error'] === 'no_permission'): ?>
                Non hai i permessi per questa operazione.
            <?php else: ?>
                Errore nel salvataggio. Verifica che il nome non sia già in uso.
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="index.php?action=save_custom_post_type" class="form-horizontal">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= $cpt['id'] ?>">
        <?php endif; ?>
        
        <div class="card">
            <h2>Informazioni Base</h2>
            
            <div class="form-group">
                <label for="name">Nome Tecnico *</label>
                <input type="text" id="name" name="name" 
                       value="<?= htmlspecialchars($cpt['name'] ?? '') ?>"
                       <?= $isEdit ? 'readonly' : 'required' ?>
                       pattern="[a-z0-9_]+" 
                       placeholder="portfolio">
                <small>Solo lettere minuscole, numeri e underscore. Non modificabile dopo la creazione.</small>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="singular_label">Label Singolare *</label>
                    <input type="text" id="singular_label" name="singular_label" 
                           value="<?= htmlspecialchars($cpt['singular_label'] ?? '') ?>"
                           required placeholder="Progetto">
                </div>
                
                <div class="form-group">
                    <label for="plural_label">Label Plurale *</label>
                    <input type="text" id="plural_label" name="plural_label" 
                           value="<?= htmlspecialchars($cpt['plural_label'] ?? '') ?>"
                           required placeholder="Progetti">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="slug">Slug URL *</label>
                    <input type="text" id="slug" name="slug" 
                           value="<?= htmlspecialchars($cpt['slug'] ?? '') ?>"
                           required pattern="[a-z0-9-]+" placeholder="portfolio">
                    <small>Usato negli URL: /portfolio/nome-progetto</small>
                </div>
                
                <div class="form-group">
                    <label for="rewrite_slug">Slug Personalizzato</label>
                    <input type="text" id="rewrite_slug" name="rewrite_slug" 
                           value="<?= htmlspecialchars($cpt['rewrite_slug'] ?? $cpt['slug'] ?? '') ?>"
                           placeholder="lascia vuoto per usare lo slug">
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">Descrizione</label>
                <textarea id="description" name="description" rows="3"><?= htmlspecialchars($cpt['description'] ?? '') ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="icon">Icona</label>
                    <input type="text" id="icon" name="icon" 
                           value="<?= htmlspecialchars($cpt['icon'] ?? 'document') ?>"
                           placeholder="document">
                    <small>Icona da mostrare nel menu (es: 📁, 🎨, 🛍️)</small>
                </div>
                
                <div class="form-group">
                    <label for="menu_position">Posizione Menu</label>
                    <input type="number" id="menu_position" name="menu_position" 
                           value="<?= $cpt['menu_position'] ?? 5 ?>" min="1" max="100">
                </div>
            </div>
        </div>
        
        <div class="card">
            <h2>Funzionalità Supportate</h2>
            
            <div class="checkbox-group">
                <?php 
                $allSupports = ['title', 'content', 'excerpt', 'featured_image', 'categories', 'author'];
                $currentSupports = $cpt['supports'] ?? ['title', 'content'];
                ?>
                
                <?php foreach ($allSupports as $support): ?>
                    <label class="checkbox-label">
                        <input type="checkbox" name="supports[]" value="<?= $support ?>"
                               <?= in_array($support, $currentSupports) ? 'checked' : '' ?>>
                        <span><?= ucfirst(str_replace('_', ' ', $support)) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="card">
            <h2>Opzioni Avanzate</h2>
            
            <div class="checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="public" value="1"
                           <?= ($cpt['public'] ?? 1) ? 'checked' : '' ?>>
                    <span>Pubblico (visibile nel frontend)</span>
                </label>
                
                <label class="checkbox-label">
                    <input type="checkbox" name="show_in_menu" value="1"
                           <?= ($cpt['show_in_menu'] ?? 1) ? 'checked' : '' ?>>
                    <span>Mostra nel menu admin</span>
                </label>
                
                <label class="checkbox-label">
                    <input type="checkbox" name="has_archive" value="1"
                           <?= ($cpt['has_archive'] ?? 1) ? 'checked' : '' ?>>
                    <span>Ha pagina archivio</span>
                </label>
                
                <label class="checkbox-label">
                    <input type="checkbox" name="hierarchical" value="1"
                           <?= ($cpt['hierarchical'] ?? 0) ? 'checked' : '' ?>>
                    <span>Gerarchico (come pagine)</span>
                </label>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= $isEdit ? 'Aggiorna' : 'Crea' ?> Custom Post Type
            </button>
            <a href="index.php?action=custom_post_types" class="btn">Annulla</a>
        </div>
    </form>
</div>

<style>
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.checkbox-group {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s;
}

.checkbox-label:hover {
    background: #e9ecef;
}

.checkbox-label input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
