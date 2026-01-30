<?php 
$success = isset($_GET['saved']) ? 'Impostazioni salvate con successo!' : ''; 
?>
<h1>Impostazioni Generali</h1>

<div class="one-column-layout">
    <div class="column">
        
        <?php if ($success): ?>
            <div class="success-message">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="index.php?action=save_impostazioni" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label>Titolo del Sito:</label>
                    <input type="text" name="site_title" value="<?php echo htmlspecialchars($this->db->getSetting('site_title', 'Il mio CMS')); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Descrizione del Sito:</label>
                    <textarea name="site_description" rows="3"><?php echo htmlspecialchars($this->db->getSetting('site_description', '')); ?></textarea>
                    <small>Breve descrizione del tuo sito</small>
                </div>
                
                <div class="form-group">
                <label>Attiva registrazioni utenti</label>
                <label class="form-check-label">
                    <input type="checkbox" name="registrazioni_attive" value="1" 
                           <?php echo $settings['registrazioni_attive'] === '1' ? 'checked' : ''; ?>>
                    Consenti nuove registrazioni frontend
                </label>
                <small>Disabilita per chiudere registrazioni</small>
            </div>
                
                <div class="form-group">
                    <label>Logo del Sito:</label>
                    <input type="file" name="site_logo" accept="image/*" id="logo-upload">
                    <?php $currentLogo = $this->db->getSetting('site_logo'); ?>
                    <div id="logo-preview" class="image-preview">
                        <?php if ($currentLogo): ?>
                            <img src="/uploads/<?php echo htmlspecialchars($currentLogo); ?>" style="max-width:200px; margin-top:10px;">
                            <input type="hidden" name="current_logo" value="<?php echo htmlspecialchars($currentLogo); ?>">
                        <?php else: ?>
                            <p style="color:#999; margin-top:10px;">Nessun logo caricato</p>
                        <?php endif; ?>
                    </div>
                    <small>Dimensioni consigliate: 200x50px</small>
                </div>
                
                <div class="form-group">
                    <label>Favicon:</label>
                    <input type="file" name="site_favicon" accept="image/*" id="favicon-upload">
                    <?php $currentFavicon = $this->db->getSetting('site_favicon'); ?>
                    <div id="favicon-preview" class="image-preview">
                        <?php if ($currentFavicon): ?>
                            <img src="/uploads/<?php echo htmlspecialchars($currentFavicon); ?>" style="max-width:32px; margin-top:10px;">
                            <input type="hidden" name="current_favicon" value="<?php echo htmlspecialchars($currentFavicon); ?>">
                        <?php else: ?>
                            <p style="color:#999; margin-top:10px;">Nessuna favicon caricata</p>
                        <?php endif; ?>
                    </div>
                    <small>Dimensioni: 32x32px o 64x64px (formato .ico, .png)</small>
                </div>
                
                <button type="submit" class="btn">Salva Modifiche</button>
            </form>
    </div>
</div>

<style>
.text-danger { color: #dc3545; }
.form-check-label input[type="checkbox"] { margin-right: 8px; transform: scale(1.2); }
img { vertical-align: middle; }
</style>
