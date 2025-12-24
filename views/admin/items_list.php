<?php
// Liste des items
?>
<h1>Liste des items</h1>
<a href="/admin/items/add">Ajouter un item</a>
<table styleborder="1" cellpadding="5">
    <tr><th>ID</th><th>Nom</th><th>Description</th><th>Type</th><th>Actions</th></tr>
    <?php foreach ($items as $item): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= htmlspecialchars($item['description']) ?></td>
            <td><?= htmlspecialchars($item['item_type']) ?></td>
            <td>
                <a href="/admin/items/edit/<?= $item['id'] ?>">Modifier</a> |
                <a href="/admin/items/delete/<?= $item['id'] ?>" onclick="return confirm('Supprimer cet item ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
