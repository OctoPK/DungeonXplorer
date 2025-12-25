<?php
$titre = 'Dashboard Admin';
$root = dirname($_SERVER['SCRIPT_NAME']); 
$root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/');
ob_start();
?>

<div class="dashboard-admin">
    <h1>Dashboard Admin</h1>

    <h2>Chapitres <a href="<?= $root ?>/admin/chapitres/add" class="ajouter">[Ajouter]</a></h2>
    <table>
        <thead>
            <tr>
                <th>ID</th> <!-- la balise th est pour les titre des colonne -->
                <th>Contenu</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody> <!-- la balise tbody permet de regrouper le contenu du tableau -->
            <?php foreach ($chapters as $chapter): ?>
                <tr>
                    <td><?= htmlspecialchars($chapter['id']) ?></td> <!-- la balise td est pour les donnees des colonne -->
                    <td><?= htmlspecialchars(mb_strimwidth($chapter['content'], 0, 60, '...')) ?></td>
                    <td><?= htmlspecialchars($chapter['image']) ?></td>
                    <td class="actions">
                        <?php $root = dirname($_SERVER['SCRIPT_NAME']); $root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/'); ?>
                        <a href="<?= $root ?>/admin/chapitres/edit/<?= $chapter['id'] ?>">Modifier</a>
                        <a href="<?= $root ?>/admin/chapitres/delete/<?= $chapter['id'] ?>" onclick="return confirm('Supprimer ce chapitre ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Items <a href="<?= $root ?>/admin/items/add" class="ajouter">[Ajouter]</a></h2>
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
                    <td><?= htmlspecialchars($item['id']) ?></td>
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

    <h2>Monstres <a href="<?= $root ?>/admin/monstres/add" class="ajouter">[Ajouter]</a></h2>
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