<?php
$titre = 'Liste des items';
$root = dirname($_SERVER['SCRIPT_NAME']);
$root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/');
ob_start();
?>
<div class="liste-admin">
    <h1>Liste des items</h1>
    <a href="<?= $root ?>/admin/items/add" class="btn-ajouter">Ajouter un item</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['description']) ?></td>
                    <td><?= htmlspecialchars($item['item_type']) ?></td>
                    <td class="actions">
                        <a href="<?= $root ?>/admin/items/edit/<?= $item['id'] ?>">Modifier</a>
                        <a href="<?= $root ?>/admin/items/delete/<?= $item['id'] ?>" onclick="return confirm('Supprimer cet item ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$contenu = ob_get_clean();
require __DIR__ . '/../../views/layout.php';
?>
