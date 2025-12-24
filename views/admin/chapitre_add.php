<?php
//Formulaire d'ajout de chapitre
?>
<h1>Ajouter un chapitre</h1>
<form method="post" action="/admin/chapitres/store">
    <label>Contenu :</label><br>
    <textarea name="content" rows="6" cols="60" required></textarea><br><br>
    <label>Image (nom du fichier) :</label><br>
    <input type="text" name="image"><br><br>
    <button type="submit">Enregistrer</button>
    <a href="/admin/chapitres">Annuler</a>
</form>
