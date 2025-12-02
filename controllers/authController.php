<?php

require_once "../config/db.php";
require "../config/PasswordOption.php";

/*Gère inscription/connexion/déconnexion des utilisateurs :
Il reçoit les données du formulaire de connexion ($_POST)
Il vérifie si le mot de passe correspond au hash dans la BDD
Il crée la $_SESSION si c'est bon ou renvoie une erreur si c'est faux
*/


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

        $stmt = $db->prepare("insert into users (username, password_hash, email) values (:user, :passwd, :email)");
        $stmt->execute(array("user" => $username, "passwd" => $hash, "email" => $email));


    }

    public function login($username, $password) {
        $stmt = $db->query("select count(*) as exist from users where username = :user and password_hash = :passwd");
        $row = $stmt->fetch();
        if ($row['exist'] == 0) {
            $_SESSION['message_erreur'] = "mot de passe ou tuilisateur inconnus";
        }
    }

    public function logout() {
        require __DIR__ . '/../views/auth/logout.php';
    }

}
?>