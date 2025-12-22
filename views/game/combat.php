<?php
if (!isset($chapter) || !is_array($chapter)) {
    echo '<p>Chapitre introuvable.</p>';
    return;
}

$titre = 'Chapitre ' . (int)$chapter['id'];

$contenu = nl2br(htmlspecialchars($chapter['content']));

$imageUrl = null;
if (!empty($chapter['image'])) {
    $imageUrl = '/DungeonXplorer/public/images/chapters/' . $chapter['image']   ;
}

ob_start();
?>

<div class="chapitre">
    <h2><?= htmlspecialchars($titre) ?></h2>

        <div class="chapter-image">
            <img src="<?= htmlspecialchars($imageUrl) ?>" alt="Image du chapitre"/>
        </div>

    <div class="chapter-content">
        <?= $contenu ?>
    </div>
</div>

<?php $contenu = ob_get_clean(); ?>