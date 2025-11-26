<?php

/*Gère inscription/connexion/déconnexion des utilisateurs : 
Il reçoit les données du formulaire de connexion ($_POST)
Il vérifie si le mot de passe correspond au hash dans la BDD
Il crée la $_SESSION si c'est bon ou renvoie une erreur si c'est faux
*/

    class AuthController {

        // Affiche le formulaire
        public function register() {
            require __DIR__ . '/../views/auth/register.php';
        }

        public function register($username, $password, $email) { // Logique d'inscription utilisation de la base de donnee 
            // ici je recupere les donnees du formulaire que l'utilisateur a rempli
            $username = htmlspecialchars($_POST['username']);
            $email = filter_var($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // je fais des verifications des données 

            if (!$email || empty($username) || empty($password)) {
                $_SESSION['message_erreur'] = "Tous les champs sont obligatoires";
                header('Location: index.php?route=register');
                exit;
            }

            if ($password !== $confirm_password) {
                $_SESSION['message_erreur'] = "Le mot de passe ne correspond pas.";
                header('Location: index.php?route=register');
                exit;
            }

            // hachage du mot de passe
            
        }
        
        public function login($username, $password) {
            require __DIR__ . '/../views/auth/login.php';
        }

        public function logout() {
            require __DIR__ . '/../views/auth/logout.php';
        }

    }
?> 