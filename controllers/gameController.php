<?php
// PAS DE REQUIRE ICI ! L'autoload.php s'occupe de charger 'models/Database.php' tout seul.

class GameController {

    public function index() {
        // On s'assure que la session est démarrée
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        // Simulation utilisateur connecté (A retirer quand le login marchera)
        if (!isset($_SESSION['user_id'])) { $_SESSION['user_id'] = 1; }
        $userId = $_SESSION['user_id'];

        // L'autoloader va trouver la classe Database dans models/Database.php
        $db = Database::getConnection();

        // Requête pour récupérer le héros
        $sql = "SELECT Hero.*, Class.name AS class_name 
                FROM Hero 
                JOIN User_Heroes ON Hero.id = User_Heroes.hero_id
                JOIN Class ON Hero.class_id = Class.id
                WHERE User_Heroes.user_id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        $hero = $stmt->fetch();

        // Affichage de la vue
        if ($hero) {
            require 'views/game/profile.php';
        } else {
            // Si pas de héros, on redirige vers la création
            // Attention : on utilise un chemin relatif adapté au nouveau routeur
            header('Location: game/create'); 
            exit();
        }
    }

    public function create() {
        $db = Database::getConnection();
        
        $requete = $db->query("SELECT * FROM Class"); 
        $classes = $requete->fetchAll();
        
        require 'views/game/create_heros.php';
    }

    public function store() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getConnection();

            try {
                // SÉCURITÉ : Vérifier si les champs existent
                if (!isset($_POST['hero_name']) || !isset($_POST['class_id'])) {
                    die("Erreur : Vous devez choisir un nom ET une classe ! <a href='create'>Retour</a>");
                }
                $nom = htmlspecialchars($_POST['hero_name']);
                $classId = (int)$_POST['class_id'];
                $userId = $_SESSION['user_id'];

                $stmt = $db->prepare("SELECT * FROM Class WHERE id = ?");
                $stmt->execute([$classId]);
                $classData = $stmt->fetch();

                if ($classData) {
                    $db->beginTransaction();

                    $sqlHero = "INSERT INTO Hero (name, class_id, pv, mana, strength, initiative) 
                                VALUES (:name, :class_id, :pv, :mana, :strength, :initiative)";
                    
                    $stmtHero = $db->prepare($sqlHero);
                    $stmtHero->execute([
                        ':name' => $nom,
                        ':class_id' => $classId,
                        ':pv' => $classData['base_pv'],
                        ':mana' => $classData['base_mana'],
                        ':strength' => $classData['strength'],
                        ':initiative' => $classData['initiative']
                    ]);

                    $nouveauHeroId = $db->lastInsertId();

                    $sqlLink = "INSERT INTO User_Heroes (user_id, hero_id) VALUES (:uid, :hid)";
                    $stmtLink = $db->prepare($sqlLink);
                    $stmtLink->execute([
                        ':uid' => $userId,
                        ':hid' => $nouveauHeroId
                    ]);

                    $db->commit();

                    // Redirection vers le profil (route 'game')
                    // '../game' permet de remonter d'un cran pour revenir à la racine du routeur
                    header('Location: ../game'); 
                    exit();
                }
            } catch (PDOException $e) {
                $db->rollBack();
                die("Erreur : " . $e->getMessage());
            }
        }
    }
    
    public function play($id) {
        echo "On joue le chapitre $id";
    }
}
?>