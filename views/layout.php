<?php
// --- ASTUCE POUR LES LIENS ---
// On calcule la racine du site (ex: /DungeonXplorer) pour que les liens marchent partout
$root = dirname($_SERVER['SCRIPT_NAME']); 
// Correction pour Windows (remplace les antislashs \ par des / et enlève le slash final)
$root = ($root === '/' || $root === '\\') ? '' : rtrim(str_replace('\\', '/', $root), '/');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre ?? 'DungeonXplorer' ?></title>
    
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pirata+One&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    
    <!-- CSS Perso (Avec chemin absolu grâce à $root) -->
    <link rel="stylesheet" href="<?= $root ?>/public/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100 fond-site">

    <!-- BARRE DE NAVIGATION -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark barre-navigation">
            <div class="container">
                <!-- Logo (Retour Accueil) -->
                <a class="navbar-brand titre-site" href="<?= $root ?>/home">
                    <i class="fa-solid fa-dungeon"></i> DungeonXplorer
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="menuPrincipal">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link lien-nav" href="<?= $root ?>/home">Accueil</a>
                        </li>

                        <!-- ==================================================== -->
                        <!-- LOGIQUE D'AFFICHAGE INTELLIGENTE (Connecté ou Pas ?) -->
                        <!-- ==================================================== -->
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            
                            <!-- CAS 1 : JOUEUR CONNECTÉ (On cache Inscription/Connexion) -->
                            <li class="nav-item">
                                <a class="nav-link lien-nav" href="<?= $root ?>/game">
                                    <i class="fa-solid fa-scroll"></i> Jouer
                                </a>
                            </li>
                            <li class="nav-item dropdown ms-2">
                                <a class="nav-link dropdown-toggle bouton-compte" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-user-hood"></i> <?= htmlspecialchars($_SESSION['username'] ?? 'Héros') ?>
                                </a>
                                <ul class="dropdown-menu menu-deroulant-sombre">
                                    <li><a class="dropdown-item" href="<?= $root ?>/game">Mon Profil</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="<?= $root ?>/logout">Se déconnecter</a></li>
                                </ul>
                            </li>

                        <?php else: ?>
                            
                            <!-- CAS 2 : VISITEUR (On affiche Inscription/Connexion) -->
                            <li class="nav-item ms-3">
                                <a href="<?= $root ?>/login" class="btn bouton-connexion">
                                    <i class="fa-solid fa-key me-1"></i> Connexion
                                </a>
                            </li>
                            <li class="nav-item ms-2">
                                <a href="<?= $root ?>/register" class="btn bouton-inscription">
                                    <i class="fa-solid fa-user-plus me-1"></i> Inscription
                                </a>
                            </li>

                        <?php endif; ?>
                        <!-- ==================================================== -->

                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- CONTENU DE LA PAGE -->
    <main class="container my-5 flex-grow-1 contenu-page">
        <!-- Zone pour afficher les messages de succès ou d'erreur -->
        <?php if(isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-success text-center">
                <?= $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['message_erreur'])): ?>
            <div class="alert alert-danger text-center">
                <?= $_SESSION['message_erreur']; unset($_SESSION['message_erreur']); ?>
            </div>
        <?php endif; ?>

        <!-- Ici s'affichera le contenu de tes vues (login, register, home...) -->
        <?= $contenu ?? '<p>Erreur de chargement du contenu.</p>' ?>
    </main>

    <!-- PIED DE PAGE -->
    <footer class="pied-de-page py-4 mt-auto">
        <div class="container text-center">
            <div class="mb-2">
                <i class="fa-brands fa-github fa-lg mx-2"></i>
                <i class="fa-brands fa-discord fa-lg mx-2"></i>
            </div>
            <p class="mb-0 texte-gris">
                &copy; 2025 Les Aventuriers du Val Perdu.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>