<?php 
// On définit le titre de la page pour le layout
$titre = "Accueil - DungeonXplorer";

// On démarre la "mémoire tampon" (output buffering)
// Tout ce qui est écrit en dessous ne s'affiche pas, mais est stocké en mémoire
ob_start(); 
?>

<div class="row justify-content-center align-items-center h-100">
    <div class="col-lg-8 text-center">
        
        <!-- Icône Dragon animée -->
        <div class="mb-4 icone-ambiance">
            <i class="fa-solid fa-dragon fa-5x"></i>
        </div>
        
        <h1 class="display-3 mb-4 titre-principal">
            Bienvenue au Val Perdu
        </h1>
        
        <div class="card panneau-sombre mb-5">
            <div class="card-body p-4 text-start">
                <p class="lead texte-intro">
                    Bienvenue sur <strong>DungeonXplorer</strong>, l'univers de dark fantasy où se mêlent aventure, stratégie et immersion totale dans les récits interactifs.
                </p>
                <hr class="separateur-dore">
                <p>
                    Ce projet est né de la volonté de l'association <em>Les Aventuriers du Val Perdu</em> de raviver l'expérience unique des <strong>livres dont vous êtes le héros</strong>.
                </p>
                <p>
                    Dans cette première version, incarnez un <span class="badge classe-guerrier">Guerrier</span>, un <span class="badge classe-voleur">Voleur</span> ou un <span class="badge classe-mage">Magicien</span> et plongez au cœur des mystères !
                </p>
                <p class="text-end fst-italic signature-equipe">- L'équipe DungeonXplorer</p>
            </div>
        </div>

        <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
            <!-- Note les liens : ils pointent vers index.php avec un paramètre ?route=... -->
            <a href="index.php?route=register" class="btn bouton-action-principal btn-lg px-5 py-3">
                <i class="fa-solid fa-scroll me-2"></i> Créer mon Héros
            </a>
            
            <a href="index.php?route=login" class="btn bouton-action-secondaire btn-lg px-5 py-3">
                <i class="fa-solid fa-key me-2"></i> Reprendre l'aventure
            </a>
        </div>

    </div>
</div>

<?php 
// On récupère tout ce qui a été généré ci-dessus et on le met dans la variable $contenu
$contenu = ob_get_clean();

// Enfin, on appelle le squelette (layout) qui va afficher $contenu au bon endroit
require 'views/layout.php';
?>