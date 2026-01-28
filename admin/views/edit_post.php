<h1><?php echo $post ? 'Modifica Post' : 'Nuovo Post'; ?></h1>

<form method="POST" action="index.php?action=save_post" enctype="multipart/form-data">
    <?php if ($post): ?>
        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
    <?php endif; ?>
    
    <div class="form-group">
        <label>Titolo:</label>
        <input type="text" name="title" id="post-title" value="<?php echo $post ? htmlspecialchars($post['title']) : ''; ?>" required>
    </div>
    
    <div class="form-group">
        <label>Slug:</label>
        <input type="text" name="slug" id="post-slug" value="<?php echo $post ? htmlspecialchars($post['slug']) : ''; ?>" required>
        <small>Generato automaticamente dal titolo, modificabile manualmente</small>
    </div>
    
    <div class="form-group">
        <label>Estratto:</label>
        <textarea name="excerpt" rows="3"><?php echo $post ? htmlspecialchars($post['excerpt']) : ''; ?></textarea>
        <small>Breve descrizione del post (opzionale)</small>
    </div>
    
    <div class="form-group">
    <label>Immagine in evidenza:</label>
    <div class="featured-image-options" style="margin-bottom: 10px;">
        <button type="button" id="btn-upload-image" class="btn btn-secondary">Carica Nuova Immagine</button>
        <button type="button" id="btn-select-existing" class="btn btn-secondary">Seleziona da Libreria</button>
    </div>
    
    <!-- Campo upload nascosto di default -->
    <div id="upload-section" style="display:none; margin-top:10px;">
        <input type="file" name="featured_image" id="featured-image" accept="image/*">
    </div>
    
    <!-- Modal per selezione immagini esistenti -->
    <div id="image-library-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; overflow:auto;">
        <div style="background:white; margin:50px auto; padding:20px; max-width:800px; border-radius:8px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h3 style="margin:0;">Seleziona Immagine dalla Libreria</h3>
                <button type="button" id="close-modal" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
            </div>
            <div id="images-grid" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(150px, 1fr)); gap:15px; max-height:500px; overflow-y:auto;">
                <!-- Le immagini verranno caricate qui via AJAX -->
            </div>
        </div>
    </div>
    
    <div id="featured-image-preview" style="margin-top:10px;">
        <?php if ($post && $post['featured_image']): ?>
            <div style="position:relative; display:inline-block;">
                <img src="/uploads/<?php echo htmlspecialchars($post['featured_image']); ?>" style="max-width:200px;">
                <button type="button" class="remove-featured-image" style="position:absolute; top:5px; right:5px; background:red; color:white; border:none; border-radius:50%; width:25px; height:25px; cursor:pointer;">×</button>
            </div>
            <input type="hidden" name="existing_featured_image" value="<?php echo htmlspecialchars($post['featured_image']); ?>">
        <?php endif; ?>
    </div>
</div>

    
    <div class="form-group">
        <label>Categorie:</label>
        <select name="categories[]" id="post-categories" multiple style="width:100%;">
            <?php 
            $allCategories = $this->db->getAllCategories();
            $postCategories = $post ? array_column($this->db->getPostCategories($post['id']), 'id') : [];
            foreach ($allCategories as $category): ?>
                <option value="<?php echo $category['id']; ?>" 
                        <?php echo in_array($category['id'], $postCategories) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <small>Seleziona una o più categorie per questo post</small>
    </div>
    
    <div class="form-group">
        <label>Contenuto:</label>
        <textarea name="content" id="post-content"><?php echo $post ? $post['content'] : ''; ?></textarea>
    </div>
    
    <div class="form-group">
        <label>Stato:</label>
        <select name="status" id="post-status" required>
            <option value="bozza" <?php echo ($post && $post['status'] === 'bozza') ? 'selected' : ''; ?>>Bozza</option>
            <option value="pubblicato" <?php echo ($post && $post['status'] === 'pubblicato') ? 'selected' : ''; ?>>Pubblicato</option>
            <option value="programmato" <?php echo ($post && $post['status'] === 'programmato') ? 'selected' : ''; ?>>Programmato</option>
        </select>
    </div>
    
    <div class="form-group" id="scheduled-publish-group" style="display:none;">
    <label>Data e ora di pubblicazione:</label>
    <input type="text" name="scheduled_at" id="scheduled-at" placeholder="Seleziona data e ora..." readonly>
    <small>Clicca per selezionare quando vuoi che il post venga pubblicato automaticamente</small>
</div>

    
    <button type="submit" class="btn">Salva Post</button>
    <a href="index.php?action=posts" class="btn btn-secondary">Annulla</a>
</form>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.5/jodit.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.5/jodit.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/it.js"></script>

<script>

// Inizializza Flatpickr per la data programmata
const scheduledPicker = flatpickr("#scheduled-at", {
    enableTime: true,
    dateFormat: "Y-m-d H:i:S",
    time_24hr: true,
    locale: "it",
    minDate: "today",
    minuteIncrement: 1,
    defaultDate: null,
    onChange: function(selectedDates, dateStr, instance) {
        // Assicura che la data sia futura
        const now = new Date();
        if (selectedDates[0] <= now) {
            Swal.fire({
                icon: 'warning',
                title: 'Attenzione',
                text: 'La data deve essere futura!'
            });
            instance.clear();
        }
    }
});

// Mostra/nascondi campo data programmazione
$('#post-status').on('change', function() {
    if ($(this).val() === 'programmato') {
        $('#scheduled-publish-group').show();
        
        // Imposta data suggerita tra 2 minuti
        if (!$('#scheduled-at').val()) {
            const now = new Date();
            now.setMinutes(now.getMinutes() + 2);
            scheduledPicker.setDate(now);
        }
    } else {
        $('#scheduled-publish-group').hide();
    }
});

// Trigger al caricamento pagina
$('#post-status').trigger('change');


// Select2 per categorie
$('#post-categories').select2({
    placeholder: 'Seleziona categorie',
    allowClear: true
});

// Mostra/nascondi campo data programmazione
$('#post-status').on('change', function() {
    if ($(this).val() === 'programmato') {
        $('#scheduled-publish-group').show();
        
        // Imposta data minima (ora + 1 minuto)
        const now = new Date();
        now.setMinutes(now.getMinutes() + 1);
        const minDateTime = now.toISOString().slice(0, 16);
        $('#scheduled-at').attr('min', minDateTime);
        
        // Se non c'è un valore, imposta tra 5 minuti come suggerimento
        if (!$('#scheduled-at').val()) {
            now.setMinutes(now.getMinutes() + 4);
            $('#scheduled-at').val(now.toISOString().slice(0, 16));
        }
    } else {
        $('#scheduled-publish-group').hide();
    }
});

// Trigger al caricamento pagina
$('#post-status').trigger('change');

// Genera slug automaticamente
$('#post-title').on('input', function() {
    if (!$('#post-slug').data('manual')) {
        var slug = $(this).val()
            .toLowerCase()
            .replace(/[àáâãäå]/g, 'a')
            .replace(/[èéêë]/g, 'e')
            .replace(/[ìíîï]/g, 'i')
            .replace(/[òóôõö]/g, 'o')
            .replace(/[ùúûü]/g, 'u')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        $('#post-slug').val(slug);
    }
});

$('#post-slug').on('input', function() {
    $(this).data('manual', true);
});

// Jodit Editor
const editor = Jodit.make('#post-content', {
    height: 500,
    language: 'it',
    toolbarButtonSize: 'middle',
    buttons: 'source,|,bold,italic,underline,strikethrough,|,ul,ol,|,outdent,indent,|,font,fontsize,brush,paragraph,|,image,file,video,table,link,|,align,undo,redo,|,hr,eraser,copyformat,|,symbol,fullsize,preview',
    useImageEditor: true,
    uploader: {
        url: 'upload.php',
        format: 'json',
        filesVariableName: function(t) {
            return 'file';
        },
        isSuccess: function (resp) {
            return resp.success;
        },
        getMessage: function (resp) {
            return resp.error || 'Upload fallito';
        },
        process: function (resp) {
            return {
                files: resp.files || [resp.url],
                path: resp.path || '',
                baseurl: resp.baseurl || '',
                error: resp.error || 0,
                msg: resp.msg || ''
            };
        },
        defaultHandlerSuccess: function (data) {
            const files = data.files || [];
            if (files.length) {
                const tagName = 'img';
                const elm = editor.createInside.element(tagName);
                elm.setAttribute('src', files[0]);
                editor.s.insertImage(elm, null, editor.o.imageDefaultWidth);
            }
        },
        error: function (e) {
            editor.e.fire('errorMessage', e.message, 'error', 4000);
        }
    },
    filebrowser: {
        ajax: {
            url: 'filebrowser.php'
        },
        uploader: {
            url: 'upload.php',
            format: 'json',
            filesVariableName: 'file',
            isSuccess: function (resp) {
                return resp.success;
            },
            getMessage: function (resp) {
                return resp.error;
            },
            process: function (resp) {
                return {
                    files: [resp.url],
                    path: '',
                    baseurl: '',
                    error: resp.error ? 1 : 0,
                    msg: resp.error || ''
                };
            }
        },
        createNewButton: false,
        deleteButton: false,
        renameButton: false,
        moveFolder: false,
        moveFile: false,
        showFoldersPanel: false
    }
});

// Aggiungi gestore doppio click sull'immagine per linkare
editor.e.on('afterInit', function() {
    editor.e.on(editor.editor, 'dblclick', function(e) {
        const img = e.target;
        if (img.tagName === 'IMG') {
            const currentLink = img.parentElement.tagName === 'A' ? img.parentElement.getAttribute('href') : '';
            const currentTarget = img.parentElement.tagName === 'A' ? img.parentElement.getAttribute('target') : '';
            
            Swal.fire({
                title: 'Link Immagine',
                html: `
                    <div style="text-align: left;">
                        <label style="display: block; margin-bottom: 5px;">URL di destinazione:</label>
                        <input type="text" id="img-link-url" class="swal2-input" value="${currentLink || ''}" placeholder="https://esempio.com">
                        
                        <label style="display: block; margin-top: 15px;">
                            <input type="checkbox" id="img-link-target" ${currentTarget === '_blank' ? 'checked' : ''}>
                            Apri in nuova finestra
                        </label>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: currentLink ? 'Aggiorna Link' : 'Aggiungi Link',
                cancelButtonText: 'Annulla',
                showDenyButton: currentLink ? true : false,
                denyButtonText: 'Rimuovi Link',
                focusConfirm: false,
                preConfirm: () => {
                    const url = document.getElementById('img-link-url').value;
                    const target = document.getElementById('img-link-target').checked;
                    return { url, target };
                }
            }).then((result) => {
                if (result.isConfirmed && result.value.url) {
                    if (img.parentElement.tagName === 'A') {
                        img.parentElement.setAttribute('href', result.value.url);
                        if (result.value.target) {
                            img.parentElement.setAttribute('target', '_blank');
                        } else {
                            img.parentElement.removeAttribute('target');
                        }
                    } else {
                        const link = editor.createInside.element('a');
                        link.setAttribute('href', result.value.url);
                        if (result.value.target) {
                            link.setAttribute('target', '_blank');
                        }
                        img.parentNode.insertBefore(link, img);
                        link.appendChild(img);
                    }
                    editor.synchronizeValues();
                } else if (result.isDenied) {
                    if (img.parentElement.tagName === 'A') {
                        const link = img.parentElement;
                        link.parentNode.insertBefore(img, link);
                        link.parentNode.removeChild(link);
                        editor.synchronizeValues();
                    }
                }
            });
        }
    });
});

// ===== GESTIONE IMMAGINE IN EVIDENZA =====

// Gestione pulsanti selezione metodo immagine
$('#btn-upload-image').on('click', function() {
    $('#upload-section').show();
    $('#featured-image').click();
});

$('#btn-select-existing').on('click', function() {
    // Carica le immagini dalla libreria
    loadImageLibrary();
    $('#image-library-modal').fadeIn();
});

$('#close-modal, #image-library-modal').on('click', function(e) {
    if (e.target === this) {
        $('#image-library-modal').fadeOut();
    }
});

// Funzione per caricare la libreria immagini
function loadImageLibrary() {
    $.ajax({
        url: 'get_images.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success && response.images.length > 0) {
                let html = '';
                response.images.forEach(function(image) {
                    html += `
                        <div class="image-item" style="cursor:pointer; border:2px solid transparent; padding:5px; transition:border 0.3s;" data-filename="${image}">
                            <img src="/uploads/${image}" style="width:100%; height:150px; object-fit:cover; border-radius:4px;">
                            <p style="font-size:11px; margin:5px 0 0; word-break:break-all;">${image}</p>
                        </div>
                    `;
                });
                $('#images-grid').html(html);
                
                // Gestione click su immagine
                $('.image-item').on('click', function() {
                    const filename = $(this).data('filename');
                    selectFeaturedImage(filename);
                    $('#image-library-modal').fadeOut();
                });
                
                // Hover effect
                $('.image-item').hover(
                    function() { $(this).css('border-color', '#0366d6'); },
                    function() { $(this).css('border-color', 'transparent'); }
                );
            } else {
                $('#images-grid').html('<p style="text-align:center; padding:20px;">Nessuna immagine disponibile nella libreria.</p>');
            }
        },
        error: function() {
            $('#images-grid').html('<p style="text-align:center; padding:20px; color:red;">Errore nel caricamento delle immagini.</p>');
        }
    });
}

// Funzione per impostare l'immagine in evidenza selezionata
function selectFeaturedImage(filename) {
    $('#featured-image-preview').html(
        '<div style="position:relative; display:inline-block;">' +
            '<img src="/uploads/' + filename + '" style="max-width:200px;">' +
            '<button type="button" class="remove-featured-image" style="position:absolute; top:5px; right:5px; background:red; color:white; border:none; border-radius:50%; width:25px; height:25px; cursor:pointer; font-size:18px; line-height:1;">×</button>' +
        '</div>' +
        '<input type="hidden" name="featured_image" value="' + filename + '">'
    );
    
    // Riattiva il gestore per il pulsante rimuovi
    attachRemoveHandler();
}

// Gestore per rimuovere l'immagine in evidenza
function attachRemoveHandler() {
    $('.remove-featured-image').off('click').on('click', function() {
        $('#featured-image-preview').html('');
        $('#featured-image').val('');
    });
}

// Attiva il gestore al caricamento
$(document).ready(function() {
    attachRemoveHandler();
});

// Upload immagine in evidenza (UNICO gestore per l'evento change)
$('#featured-image').on('change', function() {
    var file = this.files[0];
    if (file) {
        var formData = new FormData();
        formData.append('file', file);
        
        $.ajax({
            url: 'upload.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    // Usa la stessa funzione per uniformare il comportamento
                    selectFeaturedImage(data.filename);
                    // Nascondi la sezione upload dopo aver caricato
                    $('#upload-section').hide();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore upload',
                        text: data.error || 'Sconosciuto'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore upload',
                    text: 'Errore durante il caricamento del file'
                });
            }
        });
    }
});
</script>
