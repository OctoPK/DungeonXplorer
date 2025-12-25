<?php
$titre = 'Ajouter un item';
$root = dirname($_SERVER['SCRIPT_NAME']);
$root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/');
ob_start();
?>
<div class="formulaire-admin">
    <h1>Ajouter un item</h1>
    <form method="post" action="<?= $root ?>/admin/items/store">
        <div class="form-group">
            <label for="name">Nom :</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description :</label>
            <textarea name="description" id="description"></textarea>
        </div>
        <div class="form-group">
            <label for="item_type">Type :</label>
            <input type="text" name="item_type" id="item_type" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-soumettre">Enregistrer</button>
            <a href="<?= $root ?>/admin/items" class="btn-annuler">Annuler</a>
        </div>
    </form>
</div>

<?php
$contenu = ob_get_clean();
require __DIR__ . '/../../views/layout.php';
?>
