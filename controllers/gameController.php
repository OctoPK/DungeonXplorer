<?php


class GameController {

    public function index() {
        
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        
        if (!isset($_SESSION['user_id'])) { $_SESSION['user_id'] = 1; }
        $userId = $_SESSION['user_id'];

      
        $db = Database::getConnection();

        $sql = "SELECT Hero.*, Class.name AS class_name 
                FROM Hero 
                JOIN User_Heroes ON Hero.id = User_Heroes.hero_id
                JOIN Class ON Hero.class_id = Class.id
                WHERE User_Heroes.user_id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        $hero = $stmt->fetch();

        
        if ($hero) {
            require 'views/game/profile.php';
        } else {
           
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
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $db = Database::getConnection();
        $chapterId = (int) $id;

        $stmt = $db->prepare("SELECT * FROM Chapter WHERE id = ?");
        $stmt->execute([$chapterId]);
        $chapter = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$chapter) {
            header('Location: /');
            exit();
        }

        $stmtLinks = $db->prepare(
            "SELECT Links.id, Links.next_chapter_id, Links.description
             FROM Links
             WHERE Links.chapter_id = ?"
        );
        $stmtLinks->execute([$chapterId]);
        $links = $stmtLinks->fetchAll(PDO::FETCH_ASSOC);

        require 'views/game/chapitre.php';
    }
}
?>