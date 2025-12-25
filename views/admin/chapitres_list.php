<?php
$titre = 'Liste des chapitres';
$root = dirname($_SERVER['SCRIPT_NAME']);
$root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/');
ob_start();
?>
<div class="liste-admin">
    <h1>Liste des chapitres</h1>
    <a href="<?= $root ?>/admin/chapitres/add" class="btn-ajouter">Ajouter un chapitre</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Contenu</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chapters as $chapter): ?>
                <tr>
                    <td><?= $chapter['id'] ?></td>
                    <td><?= htmlspecialchars(mb_strimwidth($chapter['content'], 0, 60, '...')) ?></td>
                    <td><?= htmlspecialchars($chapter['image']) ?></td>
                    <td class="actions">
                        <a href="<?= $root ?>/admin/chapitres/edit/<?= $chapter['id'] ?>">Modifier</a>
                        <a href="<?= $root ?>/admin/chapitres/delete/<?= $chapter['id'] ?>" onclick="return confirm('Supprimer ce chapitre ?')">Supprimer</a>
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
