<?php

/*Gère inscription/connexion/déconnexion des utilisateurs :
Il reçoit les données du formulaire de connexion ($_POST)
Il vérifie si le mot de passe correspond au hash dans la BDD
Il crée la $_SESSION si c'est bon ou renvoie une erreur si c'est faux
*/
$options = [
    'memory_cost' => 1 <<17,
    'time_cost' => 4,
    'threads' => 2,
];


class AuthController {

    public function register() {
        require __DIR__ . '/../views/auth/register.php';
    }

    public function register($username, $password, $email) {
        $username = htmlspecialchars($_POST['username']);
        $email = filter_var($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];


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

        $hash = password_hash($password, PASSWORD_ARGON2ID, $options);

    }

    public function login($username, $password) {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout() {
        require __DIR__ . '/../views/auth/logout.php';
    }

}
?>