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

    <div class="chapter-description">
        <h2><?= htmlspecialchars($titre) ?></h2>
        <div class="chapter-image">
            <img src="<?= htmlspecialchars($imageUrl) ?>" alt="Image du chapitre"/>
        </div>

        <div class="chapter-content">
            <?= $contenu ?>
        </div>
        <?php if (!empty($inventory) && is_array($inventory)): ?>
            <div class="chapter-inventory">
                <h4>Inventaire</h4>
                <ul>
                    <?php foreach ($inventory as $it): ?>
                        <li><?= htmlspecialchars($it['name']) ?> x<?= (int)$it['quantity'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <div class="chapter-choices">
        <h3>Choix :</h3>
        <ul>
            <?php foreach ($links as $link): ?>
                <li>
                    <?php if (!empty($link['disabled'])): ?>
                        <span class="disabled"><?= htmlspecialchars($link['description']) ?> (verrouillé)</span>
                    <?php else: ?>
                        <a href="/DungeonXplorer/chapter/<?= (int)$link['next_chapter_id'] ?>"><?= htmlspecialchars($link['description']) ?></a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

</div>

<?php $contenu = ob_get_clean(); require __DIR__ . "/../layout.php"; ?>