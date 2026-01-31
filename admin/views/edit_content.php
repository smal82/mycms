<h1><?php echo $content ? 'Modifica Contenuto' : 'Nuovo Contenuto'; ?></h1>
<form method="POST" action="index.php?action=save_content">
    <?php if ($content): ?>
        <input type="hidden" name="id" value="<?php echo $content['id']; ?>">
    <?php endif; ?>
    
    <div class="form-group">
        <label>Tipo:</label>
        <select name="type" required>
            <option value="page" <?php echo ($content && $content['type'] === 'page') ? 'selected' : ''; ?>>Pagina</option>
            <option value="post" <?php echo ($content && $content['type'] === 'post') ? 'selected' : ''; ?>>Post</option>
        </select>
    </div>
    
    <div class="form-group">
        <label>Titolo:</label>
        <input type="text" name="title" value="<?php echo $content ? htmlspecialchars($content['title']) : ''; ?>" required>
    </div>
    
    <div class="form-group">
        <label>Slug:</label>
        <input type="text" name="slug" value="<?php echo $content ? htmlspecialchars($content['slug']) : ''; ?>" required>
    </div>
        <div class="form-group">
        <label>Contenuto:</label>
        <textarea name="content" rows="15" id="mytextarea"><?php echo $content ? htmlspecialchars($content['content']) : ''; ?></textarea>
    </div>
    
    <button type="submit" class="btn">Salva</button>
    <a href="index.php?action=contents" class="btn btn-secondary">Annulla</a>
</form>