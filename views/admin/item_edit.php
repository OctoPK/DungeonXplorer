<?php
$root = dirname($_SERVER['SCRIPT_NAME']);
$root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/');
// Formulaire d'édition d'item
?>
<h1>Modifier l'item</h1>
<form method="post" action="<?= $root ?>/admin/items/update/<?= $item['id'] ?>">
    <label>Nom :</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required><br><br>
    <label>Description :</label><br>
    <textarea name="description" rows="4" cols="50"><?= htmlspecialchars($item['description']) ?></textarea><br><br>
    <label>Type :</label><br>
    <input type="text" name="item_type" value="<?= htmlspecialchars($item['item_type']) ?>" required><br><br>
    <button type="submit">Mettre à jour</button>
    <a href="<?= $root ?>/admin/items">Annuler</a>
</form>
