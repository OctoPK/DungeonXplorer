<?php
$root = dirname($_SERVER['SCRIPT_NAME']);
$root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/');
// Formulaire d'ajout d'item
?>
<h1>Ajouter un item</h1>
<form method="post" action="<?= $root ?>/admin/items/store">
    <label>Nom :</label><br>
    <input type="text" name="name" required><br><br>
    <label>Description :</label><br>
    <textarea name="description" rows="4" cols="50"></textarea><br><br>
    <label>Type :</label><br>
    <input type="text" name="item_type" required><br><br>
    <button type="submit">Enregistrer</button>
    <a href="<?= $root ?>/admin/items">Annuler</a>
</form>
