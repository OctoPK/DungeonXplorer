<?php

class AuthController {

   
    public function register() {
       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            
            $db = Database::getConnection();

           
            $username = htmlspecialchars($_POST['username']);
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

          
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

           
            $check = $db->prepare("SELECT id FROM Users WHERE email = ?");
            $check->execute([$email]);
            if ($check->rowCount() > 0) {
                $_SESSION['message_erreur'] = "Cet email est déjà utilisé.";
                header('Location: register');
                exit;
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);

            try {
                $stmt = $db->prepare("INSERT INTO Users (username, password_hash, email, role) VALUES (:user, :passwd, :email, 'player')");
                $stmt->execute([
                    "user" => $username, 
                    "passwd" => $hash, 
                    "email" => $email
                ]);

                
                $_SESSION['flash_message'] = "Compte créé ! Connectez-vous.";
                header('Location: login');
                exit;

            } catch (PDOException $e) {
                die("Erreur technique : " . $e->getMessage());
            }

        } 
        
       
        require 'views/auth/register.php';
    }

    public function login() {
        $db = Database::getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email']; 
            $password = $_POST['password'];

            
            $stmt = $db->prepare("SELECT * FROM Users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                
               
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                $_SESSION['flash_message'] = "Bon retour parmi nous, " . $user['username'] . " !";
                
               $_SESSION['role'] === 'admin' ? header('Location: admin') : header('Location: game'); 
                exit;

            } else {
                $_SESSION['message_erreur'] = "Email ou mot de passe incorrect.";
                header('Location: login'); 
                exit;
            }
        }

        $stmtLastProgress = $db->prepare(
            "SELECT chapter_id
             FROM Hero_Progress hp
             JOIN User_Heroes uh ON hp.hero_id = uh.hero_id
             WHERE uh.user_id = ?
             ORDER BY hp.completion_date DESC
             LIMIT 1"
        );
        $lastProgress = null;
        if (isset($_SESSION['user_id'])) {
            $stmtLastProgress->execute([$_SESSION['user_id']]);
            $lastProgress = $stmtLastProgress->fetch(PDO::FETCH_ASSOC);
        }
      
        require 'views/auth/login.php';
    }

    
    public function logout() {
        session_destroy(); 
        header('Location: home'); 
        exit;
    }
}