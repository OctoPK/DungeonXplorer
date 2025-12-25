<?php
$titre = 'Liste des monstres';
$root = dirname($_SERVER['SCRIPT_NAME']);
$root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/');
ob_start();
?>
<div class="liste-admin">
    <h1>Liste des monstres</h1>
    <a href="<?= $root ?>/admin/monstres/add" class="btn-ajouter">Ajouter un monstre</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>PV</th>
                <th>Mana</th>
                <th>Initiative</th>
                <th>Force</th>
                <th>XP</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($monsters as $monster): ?>
                <tr>
                    <td><?= htmlspecialchars($monster['id']) ?></td>
                    <td><?= htmlspecialchars($monster['name']) ?></td>
                    <td><?= htmlspecialchars($monster['pv']) ?></td>
                    <td><?= htmlspecialchars($monster['mana']) ?></td>
                    <td><?= htmlspecialchars($monster['initiative']) ?></td>
                    <td><?= htmlspecialchars($monster['strength']) ?></td>
                    <td><?= htmlspecialchars($monster['xp']) ?></td>
                    <td class="actions">
                        <a href="<?= $root ?>/admin/monstres/edit/<?= $monster['id'] ?>">Modifier</a>
                        <a href="<?= $root ?>/admin/monstres/delete/<?= $monster['id'] ?>" onclick="return confirm('Supprimer ce monstre ?')">Supprimer</a>
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
