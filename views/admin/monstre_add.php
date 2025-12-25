<?php
$titre = 'Ajouter un monstre';
$root = dirname($_SERVER['SCRIPT_NAME']);
$root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/');
ob_start();
?>
<div class="formulaire-admin">
    <h1>Ajouter un monstre</h1>
    <form method="post" action="<?= $root ?>/admin/monstres/store">
        <div class="form-group">
            <label for="name">Nom :</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div class="form-group">
            <label for="pv">PV :</label>
            <input type="number" name="pv" id="pv" required>
        </div>
        <div class="form-group">
            <label for="mana">Mana :</label>
            <input type="number" name="mana" id="mana">
        </div>
        <div class="form-group">
            <label for="initiative">Initiative :</label>
            <input type="number" name="initiative" id="initiative" required>
        </div>
        <div class="form-group">
            <label for="strength">Force :</label>
            <input type="number" name="strength" id="strength" required>
        </div>
        <div class="form-group">
            <label for="attack">Attaque :</label>
            <input type="text" name="attack" id="attack">
        </div>
        <div class="form-group">
            <label for="xp">XP :</label>
            <input type="number" name="xp" id="xp" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-soumettre">Enregistrer</button>
            <a href="<?= $root ?>/admin/monstres" class="btn-annuler">Annuler</a>
        </div>
    </form>
</div>

<?php
$contenu = ob_get_clean();
require __DIR__ . '/../../views/layout.php';
?>
