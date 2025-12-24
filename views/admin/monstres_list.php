<?php
// Liste des monstres
?>
<h1>Liste des monstres</h1>
<?php $root = dirname($_SERVER['SCRIPT_NAME']); $root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/'); ?>
<a href="<?= $root ?>/admin/monstres/add">Ajouter un monstre</a>
<table border="1" cellpadding="5">
    <tr><th>ID</th><th>Nom</th><th>PV</th><th>Mana</th><th>Initiative</th><th>Force</th><th>XP</th><th>Actions</th></tr>
    <?php foreach ($monsters as $monster): ?>
        <tr>
            <td><?= htmlspecialchars($monster['id']) ?></td>
            <td><?= htmlspecialchars($monster['name']) ?></td>
            <td><?= htmlspecialchars($monster['pv']) ?></td>
            <td><?= htmlspecialchars($monster['mana']) ?></td>
            <td><?= htmlspecialchars($monster['initiative']) ?></td>
            <td><?= htmlspecialchars($monster['strength']) ?></td>
            <td><?= htmlspecialchars($monster['xp']) ?></td>
            <td>
                <a href="<?= $root ?>/admin/monstres/edit/<?= $monster['id'] ?>">Modifier</a> |
                <a href="<?= $root ?>/admin/monstres/delete/<?= $monster['id'] ?>" onclick="return confirm('Supprimer ce monstre ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
