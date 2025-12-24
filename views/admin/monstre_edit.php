<?php
// Formulaire d'édition de monstre
?>
<h1>Modifier le monstre</h1>
<form method="post" action="/admin/monstres/update/<?= $monster['id'] ?>">
    <label>Nom :</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($monster['name']) ?>" required><br><br>
    <label>PV :</label><br>
    <input type="number" name="pv" value="<?= htmlspecialchars($monster['pv']) ?>" required><br><br>
    <label>Mana :</label><br>
    <input type="number" name="mana" value="<?= htmlspecialchars($monster['mana']) ?>"><br><br>
    <label>Initiative :</label><br>
    <input type="number" name="initiative" value="<?= htmlspecialchars($monster['initiative']) ?>" required><br><br>
    <label>Force :</label><br>
    <input type="number" name="strength" value="<?= htmlspecialchars($monster['strength']) ?>" required><br><br>
    <label>Attaque :</label><br>
    <input type="text" name="attack" value="<?= htmlspecialchars($monster['attack']) ?>"><br><br>
    <label>XP :</label><br>
    <input type="number" name="xp" value="<?= htmlspecialchars($monster['xp']) ?>" required><br><br>
    <button type="submit">Mettre à jour</button>
    <a href="/admin/monstres">Annuler</a>
</form>
