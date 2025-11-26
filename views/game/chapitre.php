<?php
// affichage d'un chapitre de l'histoire

$titre = "Chapitre " . $chapitre->getNumero() . " : " . htmlspecialchars($chapitre->getTitre());
// pour l'instant la variable chapitre n'existe pas, ca doit etre fait dans le controller 
ob_start();

?>
<div class="chapitre">
    <h2><?= $titre ?></h2>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . "/../../layout.php";
?>