<div class="admin-content">
    <div class="page-header">
        <div>
            <h1><?= htmlspecialchars($cpt['plural_label']) ?></h1>
            <p class="subtitle"><?= htmlspecialchars($cpt['description']) ?></p>
        </div>
        <div>
            <a href="index.php?action=custom_posts_edit&type=<?= $postType ?>" class="btn btn-primary">
                + Nuovo <?= htmlspecialchars($cpt['singular_label']) ?>
            </a>
            <a href="index.php?action=custom_post_types" class="btn">← Torna ai CPT</a>
        </div>
    </div>
    
    <?php if (isset($_GET['saved'])): ?>
        <div class="alert alert-success">Contenuto salvato con successo!</div>
    <?php endif; ?>
    
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">Contenuto eliminato con successo!</div>
    <?php endif; ?>
    
    <div class="card">
        <table class="data-table">
            <thead>
                <tr>
                    <?php 
                    $supports = json_decode($cpt['supports'], true) ?? [];
                    if (in_array('featured_image', $supports)): 
                    ?>
                        <th style="width: 80px;">Immagine</th>
                    <?php endif; ?>
                    <th>Titolo</th>
                    <?php if (in_array('excerpt', $supports)): ?>
                        <th>Estratto</th>
                    <?php endif; ?>
                    <?php if (in_array('author', $supports)): ?>
                        <th>Autore</th>
                    <?php endif; ?>
                    <th>Stato</th>
                    <th>Data</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px;">
                            <p>Nessun contenuto ancora.</p>
                            <a href="index.php?action=custom_posts_edit&type=<?= $postType ?>" class="btn btn-primary">
                                Crea il primo <?= htmlspecialchars($cpt['singular_label']) ?>
                            </a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <?php if (in_array('featured_image', $supports)): ?>
                                <td>
                                    <?php if ($post['featured_image']): ?>
                                        <img src="/uploads/<?= htmlspecialchars($post['featured_image']) ?>" 
                                             alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                        <div style="width: 60px; height: 60px; background: #e9ecef; border-radius: 4px;"></div>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                            
                            <td>
                                <strong><?= htmlspecialchars($post['title']) ?></strong><br>
                                <small style="color: #6c757d;">Slug: <?= htmlspecialchars($post['slug']) ?></small>
                            </td>
                            
                            <?php if (in_array('excerpt', $supports)): ?>
                                <td>
                                    <small><?= htmlspecialchars(substr($post['excerpt'] ?? '', 0, 80)) ?>...</small>
                                </td>
                            <?php endif; ?>
                            
                            <?php if (in_array('author', $supports)): ?>
                                <td><?= htmlspecialchars($post['author_name'] ?? 'N/A') ?></td>
                            <?php endif; ?>
                            
                            <td>
                                <span class="badge badge-<?= $post['status'] === 'pubblicato' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($post['status']) ?>
                                </span>
                            </td>
                            
                            <td><?= date('d/m/Y', strtotime($post['created_at'])) ?></td>
                            
                            <td>
                                <a href="index.php?action=custom_posts_edit&type=<?= $postType ?>&id=<?= $post['id'] ?>" 
                                   class="btn-small">Modifica</a>
                                <form method="POST" action="index.php?action=delete_custom_post" style="display:inline;"
                                      onsubmit="return confirm('Eliminare questo contenuto?')">
                                    <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                    <input type="hidden" name="post_type" value="<?= $postType ?>">
                                    <button type="submit" class="btn-small btn-danger">Elimina</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.subtitle {
    color: #6c757d;
    margin-top: 5px;
}

.badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-warning {
    background: #fff3cd;
    color: #856404;
}
</style>
