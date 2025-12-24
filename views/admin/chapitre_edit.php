<?php
$root = dirname($_SERVER['SCRIPT_NAME']);
$root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/');
// Vue : Formulaire d'édition de chapitre
?>
<h1>Modifier le chapitre</h1>
<form method="post" action="<?= $root ?>/admin/chapitres/update/<?= $chapter['id'] ?>">
    <label>Contenu :</label><br>
    <textarea name="content" rows="6" cols="60" required><?= htmlspecialchars($chapter['content']) ?></textarea><br><br>
    <label>Image (nom du fichier) :</label><br>
    <input type="text" name="image" value="<?= htmlspecialchars($chapter['image']) ?>"><br><br>
    <button type="submit">Mettre à jour</button>
    <a href="<?= $root ?>/admin/chapitres">Annuler</a>
</form>
