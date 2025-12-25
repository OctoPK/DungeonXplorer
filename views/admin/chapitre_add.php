<?php
$titre = 'Ajouter un chapitre';
$root = dirname($_SERVER['SCRIPT_NAME']);
$root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/');
ob_start();
?>
<div class="formulaire-admin">
    <h1>Ajouter un chapitre</h1>
    <form method="post" action="<?= $root ?>/admin/chapitres/store">
        <div class="form-group">
            <label for="content">Contenu :</label>
            <textarea name="content" id="content" required></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image (nom du fichier) :</label>
            <input type="text" name="image" id="image">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-soumettre">Enregistrer</button>
            <a href="<?= $root ?>/admin/chapitres" class="btn-annuler">Annuler</a>
        </div>
    </form>
</div>

<?php
$contenu = ob_get_clean();
require __DIR__ . '/../../views/layout.php';
?>
