<?php
class MainController {
    
    public function index() {
        
        if (file_exists('views/home/index.php')) {
            require 'views/home/index.php';
        } elseif (file_exists('views/home.php')) {
            require 'views/home.php';
        } else {
            echo "Erreur : La vue 'home.php' est introuvable dans le dossier views.";
        }
    }
}