<?php
if (!isset($log) || !is_array($log)) {
    echo '<p>Journal de combat introuvable.</p>';
    return;
}

$titre = 'Combat';
ob_start();

$imageUrl = null;
if (!empty($chapter['image'])) {
    $imageUrl = '/DungeonXplorer/public/images/chapters/' . $chapter['image'];
}
?>

<div class="combat">

        <h2><?= htmlspecialchars($titre) ?></h2>
    <div class="combat-description">

        <div class="chapter-image">
            <?php if ($imageUrl): ?>
                <img src="<?= htmlspecialchars($imageUrl) ?>" alt="Image du chapitre" />
            <?php else: ?>
                <div>Aucune image</div>
            <?php endif; ?>
        </div>

        <?php if (!empty($inventory) && is_array($inventory)): ?>
            <aside class="combat-inventory">
                <h4>Inventaire</h4>
                <ul>
                    <?php foreach ($inventory as $it): ?>
                        <li><?= htmlspecialchars($it['name']) ?> x<?= (int)$it['quantity'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </aside>
        <?php endif; ?>

        <div class="stats-combat">
            <div class="chapter-choices">
            <h3>Héros</h3>
            <p>Nom: <?= htmlspecialchars($heroAfter['name'] ?? '') ?> — Classe: <?= htmlspecialchars($heroAfter['class_name'] ?? '') ?></p>
            <p>PV: <?= (int)($heroAfter['pv'] ?? 0) ?></p>
            <p>Mana: <?= (int)($heroAfter['mana'] ?? 0) ?></p>
            <p>Force: <?= (int)($heroAfter['strength'] ?? 0) ?></p>
            <p>Initiative: <?= (int)($heroAfter['initiative'] ?? 0) ?></p>
            </div>
            <div class="chapter-choices">
            <h3>Monstre</h3>
            <p>Nom: <?= htmlspecialchars($monsterAfter['name'] ?? '') ?></p>
            <p>PV: <?= (int)($monsterAfter['pv'] ?? 0) ?></p>
            <p>Mana: <?= $monsterAfter['mana'] === null ? '—' : (int)$monsterAfter['mana'] ?></p>
            <p>Force: <?= (int)($monsterAfter['strength'] ?? 0) ?></p>
            <p>Initiative: <?= (int)($monsterAfter['initiative'] ?? 0) ?></p>
            </div>
        </div>
    </div>

    <div class="chapter-choices">
        <h3>Journal</h3>
        <ul>
            <?php foreach ($log as $line): ?>
                <li><?= htmlspecialchars($line) ?></li>
            <?php endforeach; ?>
        </ul>

        <?php if (!isset($resultat)): ?>
            <form method="post">
                <button type="submit" name="action" value="physical">Attaque physique</button>
                <?php if (isset($heroAfter['class_name']) && strtolower($heroAfter['class_name']) === 'magicien' && (int)($heroAfter['mana'] ?? 0) >= 5): ?>
                    <button type="submit" name="action" value="magic">Attaque magique</button>
                <?php endif; ?>
                <button type="submit" name="action" value="potion_hp" <?= empty($hasPotionHP) ? 'disabled' : '' ?>><?= empty($hasPotionHP) ? 'Potion PV (0)' : 'Utiliser Potion PV' ?></button>
                <button type="submit" name="action" value="potion_mana" <?= empty($hasPotionMana) ? 'disabled' : '' ?>><?= empty($hasPotionMana) ? 'Potion Mana (0)' : 'Utiliser Potion Mana' ?></button>
            </form>
        <?php else: ?>
            <p>Résultat final: <?php if ($resultat === 'hero_victory'): ?>Victoire<?php elseif ($resultat === 'hero_defeat'): ?>Défaite<?php else: ?>Match nul<?php endif; ?></p>
            <p><a href="/DungeonXplorer/chapter/<?= $continueTarget ?>">Continuer</a></p>
        <?php endif; ?>
    </div>

</div>

<?php $contenu = ob_get_clean(); require __DIR__ . "/../layout.php"; ?>