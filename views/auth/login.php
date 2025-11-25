<?php
$titre = "Connexion - DungeonXplorer";
ob_start();
?>

<div class="row justify-content-center align-items-center h-100">
    <div class="col-lg-6">

        <div class="text-center mb-4 icone-ambiance">
            <i class="fa-solid fa-key fa-4x"></i>
        </div>

        <h1 class="display-5 mb-4 text-center titre-principal">
            Reprendre l'Aventure
        </h1>

        <div class="card panneau-sombre shadow-lg">
            <div class="card-body p-4">

                <p class="lead texte-intro text-center mb-4">
                    Entrez vos informations pour retrouver votre héros.
                </p>

                <form action="index.php?route=login" method="POST">

                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="exemple@royaume.com"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="mot de passe"
                            required
                        >
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn bouton-action-principal btn-lg py-3">
                            <i class="fa-solid fa-door-open me-2"></i>
                            Entrer dans le Val Perdu
                        </button>
                    </div>

                </form>

                <hr class="separateur-dore my-4">

                <p class="text-center mb-0">
                    Pas encore de héros ?
                    <a href="index.php?route=register" class="lien-dore">
                        Créer un personnage
                    </a>
                </p>

            </div>
        </div>

    </div>
</div>

<?php
$contenu = ob_get_clean();
require 'views/layout.php';

?>
