<?php
$titre = "Création de personnage - DungeonXplorer";
ob_start();
?>

<div class="row justify-content-center align-items-center h-100">
    <div class="col-lg-6">

        <div class="text-center mb-4 icone-ambiance">
            <i class="fa-solid fa-hat-wizard fa-4x"></i>
        </div>

        <h1 class="display-5 mb-4 text-center titre-principal">
            Forger Votre Héros
        </h1>

        <div class="card panneau-sombre shadow-lg">
            <div class="card-body p-4">

                <!-- AFFICHAGE DES ERREURS -->
                <?php if(isset($_SESSION['message_erreur'])): ?>
                    <div class="alert alert-danger text-center">
                        <?= $_SESSION['message_erreur']; unset($_SESSION['message_erreur']); ?>
                    </div>
                <?php endif; ?>

                <p class="lead texte-intro text-center mb-4">
                    Inscrivez-vous pour commencer votre quête dans le Val Perdu.
                </p>

                <!-- CORRECTION ICI : action="register" -->
                <form action="register" method="POST">

                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input
                            type="text"
                            class="form-control"
                            id="username"
                            name="username"
                            placeholder="Aldric le Brave"
                            required
                        >
                    </div>

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

                    <div class="mb-3">
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

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                        <!-- Attention : le name doit être "confirm_password" pour correspondre à AuthController -->
                        <input
                            type="password"
                            class="form-control"
                            id="confirm_password"
                            name="confirm_password"
                            placeholder="confirmation"
                            required
                        >
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn bouton-action-principal btn-lg py-3">
                            <i class="fa-solid fa-scroll me-2"></i>
                            S'inscrire
                        </button>
                    </div>
                </form>

                <hr class="separateur-dore my-4">

                <p class="text-center mb-0">
                    Déjà un aventurier ?
                    <!-- CORRECTION DU LIEN : vers "login" -->
                    <a href="login" class="lien-dore">
                        Reprendre l'Aventure
                    </a>
                </p>

            </div>
        </div>

    </div>
</div>

<?php
$contenu = ob_get_clean();
// On remonte d'un niveau (../) car on est dans views/auth/
require __DIR__ . '/../../views/layout.php';
?>