<?php
require_once 'config/Database.php'; // On charge notre outil de connexion

class GameController {

    // Cette fonction remplace le case 'game': ou 'profile':
    public function index() {
        // Vérification session
        if (!isset($_SESSION['user_id'])) { $_SESSION['user_id'] = 1; }
        $userId = $_SESSION['user_id'];

        $db = Database::getConnection();

        // Ta requête SQL pour le profil
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
            // Si pas de héros, on redirige vers la création (attention à la nouvelle route)
            header('Location: game/create');
            exit();
        }
    }

    // Cette fonction remplace le case 'create_heros':
    public function create() {
        $db = Database::getConnection();
        
        $requete = $db->query("SELECT * FROM Class"); 
        $classes = $requete->fetchAll();
        
        require 'views/game/create_heros.php';
    }

    // Cette fonction remplace le case 'create_heros_action':
    public function store() {
        if (!isset($_SESSION['user_id'])) { $_SESSION['user_id'] = 1; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getConnection();

            try {
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

                    // Redirection vers la route 'game' (qui affiche le profil)
                    // Attention : avec ce nouveau routeur, on redirige vers l'URL propre
                    header('Location: ../game'); 
                    exit();
                }
            } catch (PDOException $e) {
                $db->rollBack();
                die("Erreur : " . $e->getMessage());
            }
        }
    }
    
    // Pour le chapitre (déjà prévu par ton collègue)
    public function play($id) {
        echo "On joue le chapitre $id";
    }
}
?>
```

### Étape 4 : Mettre à jour `index.php` (Ajouter la route manquante)

Ton collègue a oublié une route ! Il a mis la route pour *voir* le formulaire (`game/create`), mais pas celle pour *recevoir* le formulaire (l'action).

Dans le fichier `index.php` de ton collègue, ajoute cette ligne juste en dessous de `game/create` :

```php
// ...
$router->addRoute('game', 'GameController@index');          
$router->addRoute('game/create', 'GameController@create');
// AJOUTE CETTE LIGNE :
$router->addRoute('game/store', 'GameController@store'); 
// ...
```

### Étape 5 : Mettre à jour ton Formulaire (Vue)

Enfin, il faut dire à ton formulaire HTML d'envoyer les données vers cette nouvelle route "propre" (`game/store`) et non plus vers `index.php?route=...`.

Ouvre **`views/game/create_heros.php`** et modifie la balise `<form>` :
<!-- On change l'action pour correspondre à la route du nouveau Router 
<form action="game/store" method="POST">-->

```php
