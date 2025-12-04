<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?= $titre ?? 'DungeonXplorer' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pirata+One&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">

        <!-- Le slash / au début renvoie à la racine du serveur (localhost) -->
    <!-- Adapte "DungeonXplorer" si ton dossier s'appelle autrement -->
    <link rel="stylesheet" href="/DungeonXplorer/public/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100 fond-site">

    <!-- HEADER / NAVIGATION -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark barre-navigation">
            <div class="container">
                <a class="navbar-brand titre-site" href="index.php">
                    <i class="fa-solid fa-dungeon"></i> DungeonXplorer
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="menuPrincipal">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link lien-nav" href="index.php?route=home">Accueil</a>
                        </li>

                        <!-- LOGIQUE PHP : Affichage conditionnel selon la connexion -->
                        <?php if (isset($_SESSION['utilisateur'])): ?>
                            <!-- MENU CONNECTÉ -->
                            <li class="nav-item dropdown ms-2">
                                <a class="nav-link dropdown-toggle bouton-compte" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-user-hood"></i> Mon Héros
                                </a>
                                <ul class="dropdown-menu menu-deroulant-sombre">
                                    <li><a class="dropdown-item" href="index.php?route=profil">Profil & Stats</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="index.php?route=logout">Se déconnecter</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <!-- MENU VISITEUR -->
                            <li class="nav-item ms-3">
                                <a href="index.php?route=login" class="btn bouton-connexion">
                                    Connexion
                                </a>
                            </li>
                            <li class="nav-item ms-2">
                                <a href="index.php?route=register" class="btn bouton-inscription">
                                    Créer un compte
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- CONTENU PRINCIPAL -->
    <main class="container my-5 flex-grow-1 contenu-page">
        
        <?php if(isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-info">
                <?= $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <?= $contenu ?? '<p>Erreur : Aucun contenu à afficher.</p>' ?>
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

    <!-- Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>