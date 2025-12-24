<?php
require_once __DIR__ . '/../../models/Database.php';
$db = Database::getConnection();

// Récupérer les chapitres, items et monstres
$chapters = $db->query('SELECT * FROM Chapter')->fetchAll();
$items = $db->query('SELECT * FROM Items')->fetchAll();
$monsters = $db->query('SELECT * FROM Monster')->fetchAll();
?>

<h1>Dashboard Admin</h1>

<h2>Chapitres <a href="?action=add_chapter">[Ajouter]</a></h2>
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
                <td>
                    <a href="?action=edit_chapter&id=<?= $chapter['id'] ?>">Modifier</a> |
                    <a href="?action=delete_chapter&id=<?= $chapter['id'] ?>" onclick="return confirm('Supprimer ce chapitre ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Items <a href="?action=add_item">[Ajouter]</a></h2>
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
                <td>
                    <a href="?action=edit_item&id=<?= $item['id'] ?>">Modifier</a> |
                    <a href="?action=delete_item&id=<?= $item['id'] ?>" onclick="return confirm('Supprimer cet item ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Monstres <a href="?action=add_monster">[Ajouter]</a></h2>
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
                <td>
                    <a href="?action=edit_monster&id=<?= $monster['id'] ?>">Modifier</a> |
                    <a href="?action=delete_monster&id=<?= $monster['id'] ?>" onclick="return confirm('Supprimer ce monstre ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>