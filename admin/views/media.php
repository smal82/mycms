<h1>Gestione Media</h1>

<?php if (isset($_GET['uploaded'])): ?>
    <div class="success-message">File caricato con successo!</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="success-message">File eliminato con successo!</div>
<?php endif; ?>
<?php if (isset($_GET['trashed'])): ?>
    <div class="success-message">File spostato nel cestino!</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="error-message" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        <strong>Errore:</strong> 
        <?php 
        switch($_GET['error']) {
            case 'invalid_type': echo 'Tipo file non permesso'; break;
            case 'save_failed': echo 'Impossibile salvare il file - verifica i permessi della cartella uploads/'; break;
            case 'db_error': echo 'Errore salvataggio nel database'; break;
            case 'permission_denied': echo 'La cartella uploads/ non è scrivibile'; break;
            case 'upload_error': echo 'Errore durante l\'upload del file'; break;
            default: echo 'Errore sconosciuto durante upload';
        }
        ?>
    </div>
<?php endif; ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div>
        <button onclick="document.getElementById('uploadForm').style.display='block'" class="btn">📤 Carica File</button>
        <a href="index.php?action=trash_media" class="btn btn-secondary" style="background-color: #6c757d;">
            🗑️ Cestino Media <?php 
            $trashedCount = count($this->db->getTrashedMedia());
            if ($trashedCount > 0) echo "($trashedCount)";
            ?>
        </a>
    </div>
</div>

<!-- Form Upload Semplice (Standard HTML) -->
<div id="uploadForm" style="display: none; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 5px;">
    <h3>Carica Nuovo File</h3>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" accept="image/*" required style="margin-bottom: 10px;">
        <!-- Campo hidden per redirect -->
        <input type="hidden" name="redirect" value="media">
        
        <button type="submit" class="btn">Carica</button>
        <button type="button" onclick="document.getElementById('uploadForm').style.display='none'" class="btn btn-secondary">Annulla</button>
    </form>
</div>



<?php if (empty($media)): ?>
    <div class="empty-state">
        <div class="empty-state-icon">🖼️</div>
        <h2>Nessun file caricato</h2>
        <p>Carica il tuo primo file per iniziare.</p>
    </div>
<?php else: ?>
    <div class="media-grid">
        <?php foreach ($media as $item): ?>
            <div class="media-item">
                <?php if (in_array(strtolower(pathinfo($item['filename'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])): ?>
                    <img src="/uploads/<?php echo htmlspecialchars($item['filename']); ?>" alt="<?php echo htmlspecialchars($item['original_name']); ?>">
                <?php else: ?>
                    <div class="media-icon">📄</div>
                <?php endif; ?>
                
                <div class="media-info">
                    <div class="media-name" title="<?php echo htmlspecialchars($item['original_name']); ?>">
                        <?php echo htmlspecialchars($item['original_name']); ?>
                    </div>
                    <div class="media-meta">
                        <?php echo number_format($item['size'] / 1024, 2); ?> KB
                    </div>
                    <div class="media-actions">
                        <button onclick="copyToClipboard('/uploads/<?php echo htmlspecialchars($item['filename']); ?>')" 
                                class="btn btn-sm" 
                                title="Copia URL">
                            📋
                        </button>
                        <form method="POST" action="index.php?action=trash_media" style="display:inline;" class="trash-form" data-filename="<?php echo htmlspecialchars($item['original_name']); ?>">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <button type="button" class="btn btn-sm btn-danger trash-btn" title="Cestina">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border-radius: 8px;
    margin: 40px 0;
}

.empty-state-icon {
    font-size: 72px;
    margin-bottom: 20px;
    opacity: 0.3;
}

.empty-state h2 {
    color: #6c757d;
    margin-bottom: 10px;
}

.empty-state p {
    color: #adb5bd;
}

.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.media-item {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}

.media-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.media-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
}

.media-icon {
    width: 100%;
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 72px;
    background: #f8f9fa;
}

.media-info {
    padding: 15px;
}

.media-name {
    font-weight: 600;
    margin-bottom: 5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 14px;
}

.media-meta {
    color: #6c757d;
    font-size: 12px;
    margin-bottom: 10px;
}

.media-actions {
    display: flex;
    gap: 5px;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 14px;
    min-width: auto;
}
</style>

<script>
function copyToClipboard(url) {
    const fullUrl = window.location.origin + url;
    navigator.clipboard.writeText(fullUrl).then(function() {
        Swal.fire({
            title: 'Copiato!',
            text: 'URL copiato negli appunti',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    }, function() {
        Swal.fire({
            title: 'Errore',
            text: 'Impossibile copiare negli appunti',
            icon: 'error'
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Gestione cestinamento con SweetAlert2
    document.querySelectorAll('.trash-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.trash-form');
            const filename = form.dataset.filename;
            
            Swal.fire({
                title: 'Spostare nel cestino?',
                html: `Vuoi spostare il file <strong>"${filename}"</strong> nel cestino?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🗑️ Sì, sposta nel cestino',
                cancelButtonText: 'Annulla',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
