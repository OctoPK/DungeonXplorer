<?php

class AuthController {

    // ---------------------------------------------------------
    // GESTION DE L'INSCRIPTION
    // ---------------------------------------------------------
    public function register() {
        // Si le formulaire est envoyé (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. Connexion BDD
            $db = Database::getConnection();

            // 2. Nettoyage des entrées
            $username = htmlspecialchars($_POST['username']);
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // 3. Vérifications
            if (!$email || empty($username) || empty($password)) {
                $_SESSION['message_erreur'] = "Tous les champs sont obligatoires.";
                header('Location: register'); // On recharge la page
                exit;
            }

            if ($password !== $confirm_password) {
                $_SESSION['message_erreur'] = "Les mots de passe ne correspondent pas.";
                header('Location: register');
                exit;
            }

            // 4. Vérifier si l'email existe déjà
            $check = $db->prepare("SELECT id FROM Users WHERE email = ?");
            $check->execute([$email]);
            if ($check->rowCount() > 0) {
                $_SESSION['message_erreur'] = "Cet email est déjà utilisé.";
                header('Location: register');
                exit;
            }

            // 5. Hachage et Insertion
            // PASSWORD_DEFAULT est suffisant et sécurisé (utilise souvent Argon2 ou Bcrypt)
            $hash = password_hash($password, PASSWORD_DEFAULT);

            try {
                $stmt = $db->prepare("INSERT INTO Users (username, password_hash, email, role) VALUES (:user, :passwd, :email, 'player')");
                $stmt->execute([
                    "user" => $username, 
                    "passwd" => $hash, 
                    "email" => $email
                ]);

                // Succès : On connecte directement l'utilisateur ou on le renvoie au login
                $_SESSION['flash_message'] = "Compte créé ! Connectez-vous.";
                header('Location: login');
                exit;

            } catch (PDOException $e) {
                die("Erreur technique : " . $e->getMessage());
            }

        } 
        
        // Si on est en GET (affichage simple), on montre la vue
        require 'views/auth/register.php';
    }

    // ---------------------------------------------------------
    // GESTION DE LA CONNEXION
    // ---------------------------------------------------------
    public function login() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getConnection();
            
            $email = $_POST['email']; // On se connecte souvent avec l'email
            $password = $_POST['password'];

            // 1. On cherche l'utilisateur par son email
            $stmt = $db->prepare("SELECT * FROM Users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            // 2. Vérification
            // Si l'utilisateur existe ET que le mot de passe correspond au hash
            if ($user && password_verify($password, $user['password_hash'])) {
                
                // C'est gagné ! On remplit la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                $_SESSION['flash_message'] = "Bon retour parmi nous, " . $user['username'] . " !";
                
                // Redirection vers le profil
                header('Location: game'); 
                exit;

            } else {
                $_SESSION['message_erreur'] = "Email ou mot de passe incorrect.";
                header('Location: login'); // On recharge pour afficher l'erreur
                exit;
            }
        }

        // Affichage de la vue login
        require 'views/auth/login.php';
    }

    // ---------------------------------------------------------
    // DÉCONNEXION
    // ---------------------------------------------------------
    public function logout() {
        session_destroy(); // On détruit la session
        header('Location: home'); // Retour accueil
        exit;
    }
}
?>