<?php
//Liste des chapitres
?>
<h1>Liste des chapitres</h1>
<a href="/admin/chapitres/add">Ajouter un chapitre</a>
<table styleborder="1" cellpadding="5">
    <tr><th>ID</th><th>Contenu</th><th>Image</th><th>Actions</th></tr>
    <?php foreach ($chapters as $chapter): ?>
        <tr>
            <td><?= $chapter['id'] ?></td>
            <td><?= htmlspecialchars(mb_strimwidth($chapter['content'], 0, 60, '...')) ?></td>
            <td><?= htmlspecialchars($chapter['image']) ?></td>
            <td>
                <a href="/admin/chapitres/edit/<?= $chapter['id'] ?>">Modifier</a> |
                <a href="/admin/chapitres/delete/<?= $chapter['id'] ?>" onclick="return confirm('Supprimer ce chapitre ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
