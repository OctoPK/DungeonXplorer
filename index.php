<?php
// 1. Démarrage de la session (OBLIGATOIRE tout en haut)
// Cela permet de savoir si l'utilisateur est connecté sur toutes les pages
session_start();

// 2. Inclusion de la configuration (si tu as un fichier de base de données db.php, c'est ici qu'on l'appelle)
// require_once 'config/db.php';

// 3. Le Routeur (Le système d'aiguillage)
// On regarde s'il y a un paramètre "route" dans l'URL (ex: index.php?route=login)
$route = isset($_GET['route']) ? $_GET['route'] : 'home';

// 4. Switch pour charger la bonne vue
switch ($route) {
    case 'home':
        // Si la route est 'home', on charge la vue de l'accueil
        require 'views/home.php';
        break;

    case 'login':
        // Pour l'instant, on n'a pas encore créé login.php, donc on redirige vers l'accueil
        // Plus tard, tu mettras : require 'views/login.php';
        echo "Page de connexion (à venir)";
        break;

    case 'register':
        // Plus tard : require 'views/register.php';
        echo "Page d'inscription (à venir)";
        break;

    case 'logout':
        // Logique de déconnexion simple
        session_destroy();
        header('Location: index.php?route=home');
        exit();
        break;

    default:
        // Si la route n'existe pas (404), on renvoie vers l'accueil
        require 'views/home.php';
        break;
}