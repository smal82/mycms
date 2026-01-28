<?php
$isEdit = isset($post) && $post;
?>

<div class="admin-content">
    <div class="page-header">
        <h1><?= $isEdit ? 'Modifica' : 'Nuovo' ?> <?= htmlspecialchars($cpt['singular_label']) ?></h1>
        <a href="index.php?action=custom_posts_list&type=<?= $postType ?>" class="btn">← Indietro</a>
    </div>
    
    <form method="POST" action="index.php?action=save_custom_post" class="post-form">
        <input type="hidden" name="post_type" value="<?= $postType ?>">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= $post['id'] ?>">
        <?php endif; ?>
        
        <div class="main-column">
            <?php if (in_array('title', $supports)): ?>
                <div class="card">
                    <div class="form-group">
                        <label for="title">Titolo *</label>
                        <input type="text" id="title" name="title" 
                               value="<?= htmlspecialchars($post['title'] ?? '') ?>"
                               required class="title-input">
                    </div>
                    
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" id="slug" name="slug" 
                               value="<?= htmlspecialchars($post['slug'] ?? '') ?>"
                               required>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (in_array('content', $supports)): ?>
                <div class="card">
                    <div class="form-group">
                        <label for="content">Contenuto</label>
                        <textarea id="content" name="content" rows="15"><?= htmlspecialchars($post['content'] ?? '') ?></textarea>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (in_array('excerpt', $supports)): ?>
                <div class="card">
                    <div class="form-group">
                        <label for="excerpt">Estratto</label>
                        <textarea id="excerpt" name="excerpt" rows="3"><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Metadati Custom -->
            <div class="card">
                <h3>Campi Personalizzati</h3>
                <div id="custom-fields">
                    <?php if (!empty($post['meta'])): ?>
                        <?php foreach ($post['meta'] as $key => $value): ?>
                            <div class="meta-field">
                                <input type="text" class="meta-key" value="<?= htmlspecialchars($key) ?>" readonly>
                                <input type="text" name="meta[<?= htmlspecialchars($key) ?>]" 
                                       value="<?= htmlspecialchars($value) ?>" 
                                       placeholder="Valore" class="meta-value">
                                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">×</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="btn btn-secondary" onclick="addMetaField()">+ Aggiungi Campo</button>
            </div>
        </div>
        
        <div class="sidebar-column">
            <!-- Pubblica -->
            <div class="card">
                <h3>Pubblica</h3>
                
                <div class="form-group">
                    <label for="status">Stato</label>
                    <select id="status" name="status">
                        <option value="bozza" <?= ($post['status'] ?? 'bozza') === 'bozza' ? 'selected' : '' ?>>
                            Bozza
                        </option>
                        <option value="pubblicato" <?= ($post['status'] ?? '') === 'pubblicato' ? 'selected' : '' ?>>
                            Pubblicato
                        </option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <?= $isEdit ? 'Aggiorna' : 'Pubblica' ?>
                </button>
            </div>
            
            <?php if (in_array('featured_image', $supports)): ?>
                <!-- Immagine in evidenza -->
                <div class="card">
                    <h3>Immagine in Evidenza</h3>
                    
                    <div id="featured-image-preview" class="image-preview">
                        <?php if (!empty($post['featured_image'])): ?>
                            <img src="/uploads/<?= htmlspecialchars($post['featured_image']) ?>" alt="">
                        <?php endif; ?>
                    </div>
                    
                    <input type="hidden" id="featured_image" name="featured_image" 
                           value="<?= htmlspecialchars($post['featured_image'] ?? '') ?>">
                    
                    <button type="button" class="btn btn-secondary btn-block" 
                            onclick="openMediaLibrary('featured')">
                        Seleziona Immagine
                    </button>
                    
                    <?php if (!empty($post['featured_image'])): ?>
                        <button type="button" class="btn btn-danger btn-block" 
                                onclick="removeFeaturedImage()">
                            Rimuovi Immagine
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php if (in_array('categories', $supports) && !empty($categories)): ?>
                <!-- Categorie -->
                <div class="card">
                    <h3>Categorie</h3>
                    
                    <div class="categories-list">
                        <?php foreach ($categories as $cat): ?>
                            <label class="checkbox-label">
                                <input type="checkbox" name="categories[]" value="<?= $cat['id'] ?>"
                                       <?= in_array($cat['id'], $post['categories'] ?? []) ? 'checked' : '' ?>>
                                <span><?= htmlspecialchars($cat['name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>

<style>
.post-form {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 20px;
}

.title-input {
    font-size: 24px;
    font-weight: 600;
}

.meta-field {
    display: grid;
    grid-template-columns: 1fr 2fr auto;
    gap: 10px;
    margin-bottom: 10px;
}

.meta-key, .meta-value {
    padding: 8px 12px;
}

.btn-remove {
    width: 40px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 20px;
    font-weight: bold;
}

.image-preview {
    margin-bottom: 15px;
    min-height: 150px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.image-preview img {
    max-width: 100%;
    height: auto;
}

.categories-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn-block {
    width: 100%;
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .post-form {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Auto-genera slug dal titolo
document.getElementById('title')?.addEventListener('input', function(e) {
    const slugField = document.getElementById('slug');
    if (!slugField.value || slugField.dataset.manual !== 'true') {
        slugField.value = e.target.value
            .toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-|-$/g, '');
    }
});

document.getElementById('slug')?.addEventListener('input', function() {
    this.dataset.manual = 'true';
});

// Aggiungi campo meta
function addMetaField() {
    const container = document.getElementById('custom-fields');
    const key = prompt('Nome del campo:');
    if (!key) return;
    
    const div = document.createElement('div');
    div.className = 'meta-field';
    div.innerHTML = `
        <input type="text" class="meta-key" value="${key}" readonly>
        <input type="text" name="meta[${key}]" placeholder="Valore" class="meta-value">
        <button type="button" class="btn-remove" onclick="this.parentElement.remove()">×</button>
    `;
    container.appendChild(div);
}

// Media Library (placeholder - da implementare)
function openMediaLibrary(target) {
    alert('Funzionalità Media Library da implementare. Per ora inserisci manualmente il nome file.');
    const filename = prompt('Inserisci il nome del file (es: immagine.jpg):');
    if (filename) {
        document.getElementById('featured_image').value = filename;
        document.getElementById('featured-image-preview').innerHTML = 
            '<img src="/uploads/' + filename + '" alt="">';
    }
}

function removeFeaturedImage() {
    document.getElementById('featured_image').value = '';
    document.getElementById('featured-image-preview').innerHTML = '';
}
</script>
