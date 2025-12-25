<?php
$titre = 'Modifier le chapitre';
$root = dirname($_SERVER['SCRIPT_NAME']);
$root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/');
ob_start();
?>
<div class="formulaire-admin">
    <h1>Modifier le chapitre</h1>
    <form method="post" action="<?= $root ?>/admin/chapitres/update/<?= $chapter['id'] ?>">
        <div class="form-group">
            <label for="content">Contenu :</label>
            <textarea name="content" id="content" required><?= htmlspecialchars($chapter['content']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image (nom du fichier) :</label>
            <input type="text" name="image" id="image" value="<?= htmlspecialchars($chapter['image']) ?>">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-soumettre">Mettre à jour</button>
            <a href="<?= $root ?>/admin/chapitres" class="btn-annuler">Annuler</a>
        </div>
    </form>
</div>

<?php
$contenu = ob_get_clean();
require __DIR__ . '/../../views/layout.php';
?>
