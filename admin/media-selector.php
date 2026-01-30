<?php
session_start();
require_once '../core/bootstrap.php';

// Verifica login
if (!isset($_SESSION['user_id'])) {
    die('Non autenticato');
}

$uploadDir = '../uploads/';
$media = [];

if (is_dir($uploadDir)) {
    $files = array_diff(scandir($uploadDir), ['.', '..', '.htaccess']);
    
    foreach ($files as $file) {
        $filepath = $uploadDir . $file;
        if (is_file($filepath) && strpos(mime_content_type($filepath), 'image/') === 0) {
            $media[] = [
                'filename' => $file,
                'url' => '/uploads/' . $file,
                'size' => filesize($filepath),
                'modified' => filemtime($filepath)
            ];
        }
    }
    
    // Ordina per data modifica (più recenti prima)
    usort($media, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleziona Media</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header h2 {
            margin: 0;
            color: #333;
        }
        
        .btn-upload {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }
        
        .btn-upload:hover {
            background: #0056b3;
        }
        
        .search-box {
            margin-bottom: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .search-box input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .media-item {
            background: white;
            border: 2px solid transparent;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .media-item:hover {
            border-color: #007bff;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .media-item.selected {
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);
        }
        
        .media-thumbnail {
            width: 100%;
            height: 150px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        
        .media-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .selected-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #28a745;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .media-item.selected .selected-badge {
            display: flex;
        }
        
        .media-info {
            padding: 10px;
            font-size: 12px;
            color: #666;
            text-align: center;
            border-top: 1px solid #eee;
        }
        
        .media-filename {
            font-weight: 500;
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 4px;
        }
        
        .no-media {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: #666;
            background: white;
            border-radius: 8px;
        }
        
        .actions {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 15px 20px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .btn-insert {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }
        
        .btn-insert:hover {
            background: #218838;
        }
        
        .btn-insert:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }
        
        .btn-cancel:hover {
            background: #5a6268;
        }
        
        .selected-info {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>🖼️ Seleziona Media</h2>
        <button class="btn-upload" onclick="document.getElementById('file-upload').click()">
            ➕ Carica Nuovo
        </button>
        <input type="file" id="file-upload" multiple accept="image/*" style="display:none;">
    </div>
    
    <div class="search-box">
        <input type="text" id="search" placeholder="🔍 Cerca immagine..." onkeyup="filterMedia()">
    </div>
    
    <div class="media-grid" id="media-grid">
        <?php if (empty($media)): ?>
            <div class="no-media">
                <p style="font-size: 48px; margin-bottom: 10px;">📭</p>
                <p>Nessuna immagine disponibile</p>
                <p style="margin-top: 10px;"><small>Carica nuove immagini per iniziare</small></p>
            </div>
        <?php else: ?>
            <?php foreach ($media as $item): ?>
                <div class="media-item" 
                     data-url="<?php echo htmlspecialchars($item['url']); ?>"
                     data-filename="<?php echo htmlspecialchars($item['filename']); ?>"
                     onclick="toggleSelect(this)">
                    <div class="media-thumbnail">
                        <img src="<?php echo htmlspecialchars($item['url']); ?>" 
                             alt="<?php echo htmlspecialchars($item['filename']); ?>">
                        <div class="selected-badge">✓</div>
                    </div>
                    <div class="media-info">
                        <div class="media-filename" title="<?php echo htmlspecialchars($item['filename']); ?>">
                            <?php echo htmlspecialchars($item['filename']); ?>
                        </div>
                        <div>
                            <?php echo $item['size'] > 1024*1024 ? round($item['size']/(1024*1024), 2) . ' MB' : round($item['size']/1024, 2) . ' KB'; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="actions">
        <div class="selected-info">
            <span id="selected-count">Nessuna selezione</span>
        </div>
        <div>
            <button class="btn-cancel" onclick="window.parent.closeMediaSelector()">Annulla</button>
            <button class="btn-insert" id="btn-insert" onclick="insertSelected()" disabled>Inserisci</button>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let selectedItems = [];
        
        function toggleSelect(element) {
            const url = element.dataset.url;
            const index = selectedItems.indexOf(url);
            
            if (index > -1) {
                selectedItems.splice(index, 1);
                element.classList.remove('selected');
            } else {
                // Per ora permetti solo una selezione
                document.querySelectorAll('.media-item.selected').forEach(item => {
                    item.classList.remove('selected');
                });
                selectedItems = [url];
                element.classList.add('selected');
            }
            
            updateSelectedInfo();
        }
        
        function updateSelectedInfo() {
            const count = selectedItems.length;
            const countEl = document.getElementById('selected-count');
            const btnInsert = document.getElementById('btn-insert');
            
            if (count === 0) {
                countEl.textContent = 'Nessuna selezione';
                btnInsert.disabled = true;
            } else if (count === 1) {
                countEl.textContent = '1 immagine selezionata';
                btnInsert.disabled = false;
            } else {
                countEl.textContent = count + ' immagini selezionate';
                btnInsert.disabled = false;
            }
        }
        
        function insertSelected() {
            if (selectedItems.length === 0) return;
            
            // Invia le URL selezionate al parent
            window.parent.insertMediaFromSelector(selectedItems);
        }
        
        function filterMedia() {
            const search = document.getElementById('search').value.toLowerCase();
            const items = document.querySelectorAll('.media-item');
            
            items.forEach(item => {
                const filename = item.dataset.filename.toLowerCase();
                item.style.display = filename.includes(search) ? 'block' : 'none';
            });
        }
        
        // Upload nuovo file
        document.getElementById('file-upload').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            if (files.length === 0) return;
            
            Swal.fire({
                title: 'Caricamento in corso...',
                html: 'Caricamento file: <b>0</b> / <b>' + files.length + '</b>',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            let uploaded = 0;
            let errors = [];
            
            files.forEach(file => {
                const formData = new FormData();
                formData.append('file', file);
                
                fetch('upload.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    uploaded++;
                    
                    Swal.update({
                        html: 'Caricamento file: <b>' + uploaded + '</b> / <b>' + files.length + '</b>'
                    });
                    
                    if (!data.success) {
                        errors.push(file.name + ': ' + data.error);
                    }
                    
                    if (uploaded === files.length) {
                        if (errors.length > 0) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Upload completato con errori',
                                html: '<div style="text-align:left;">' + errors.join('<br>') + '</div>',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Upload completato!',
                                text: files.length + ' file caricati con successo',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    }
                })
                .catch(error => {
                    uploaded++;
                    errors.push(file.name + ': Errore di rete');
                    
                    if (uploaded === files.length) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Errore durante l\'upload',
                            html: '<div style="text-align:left;">' + errors.join('<br>') + '</div>',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
