<?php
// Formulaire d'ajout d'item
?>
<h1>Ajouter un item</h1>
<form method="post" action="/admin/items/store">
    <label>Nom :</label><br>
    <input type="text" name="name" required><br><br>
    <label>Description :</label><br>
    <textarea name="description" rows="4" cols="50"></textarea><br><br>
    <label>Type :</label><br>
    <input type="text" name="item_type" required><br><br>
    <button type="submit">Enregistrer</button>
    <a href="/admin/items">Annuler</a>
</form>
