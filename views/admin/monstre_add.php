<?php
$root = dirname($_SERVER['SCRIPT_NAME']);
$root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/');
// Formulaire d'ajout de monstre
?>
<h1>Ajouter un monstre</h1>
<form method="post" action="<?= $root ?>/admin/monstres/store">
    <label>Nom :</label><br>
    <input type="text" name="name" required><br><br>
    <label>PV :</label><br>
    <input type="number" name="pv" required><br><br>
    <label>Mana :</label><br>
    <input type="number" name="mana"><br><br>
    <label>Initiative :</label><br>
    <input type="number" name="initiative" required><br><br>
    <label>Force :</label><br>
    <input type="number" name="strength" required><br><br>
    <label>Attaque :</label><br>
    <input type="text" name="attack"><br><br>
    <label>XP :</label><br>
    <input type="number" name="xp" required><br><br>
    <button type="submit">Enregistrer</button>
    <a href="<?= $root ?>/admin/monstres">Annuler</a>
    <a href="/admin/monstres">Annuler</a>
</form>
