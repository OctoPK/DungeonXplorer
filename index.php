<?php
session_start();

$route = isset($_GET['route']) ? $_GET['route'] : 'home';

switch ($route) {
    case 'home':
        require 'views/home.php';
        break;

    case 'login':
        require 'views/auth/login.php';
        break;

    case 'register':
        echo "Page d'inscription (à venir)";
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?route=home');
        exit();
        break;

    default:
        require 'views/home.php';
        break;
}