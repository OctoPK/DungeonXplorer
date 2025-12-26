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
            $stmtLastProgress = $db->prepare(
                "SELECT chapter_id
                 FROM Hero_Progress hp
                 JOIN User_Heroes uh ON hp.hero_id = uh.hero_id
                 WHERE uh.user_id = ?
                 ORDER BY hp.completion_date DESC
                 LIMIT 1"
            );
            $stmtLastProgress->execute([$userId]);
            $lastProgress = $stmtLastProgress->fetch(PDO::FETCH_ASSOC);

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
        if (isset($_SESSION['user_id'])) { //on récupère l'id du héro pour ajouter les items rencontrés dans les chapitres
            $userId = $_SESSION['user_id'];
            $stmtHero = $db->prepare("SELECT hero_id FROM User_Heroes WHERE user_id = ?");
            $stmtHero->execute([$userId]);
            $heroData = $stmtHero->fetch(PDO::FETCH_ASSOC);


            if ($heroData) {
                $heroId = $heroData['hero_id'];
                //on met à joue la progression, d'abord on umet les chapitres précédents en complété et ensuite on met le nouveau chapitre traveré
                $stmtUpdtatePrevioursChapter = $db->prepare("UPDATE Hero_Progress SET status='Completed', completion_date=NOW() WHERE hero_id = ? AND chapter_id <> ? AND status='InProgress'");
                $stmtUpdtatePrevioursChapter->execute([$heroId, $chapterId]);
                $stmtProgressChapter = $db->prepare("INSERT INTO Hero_Progress (hero_id, chapter_id, status, completion_date) values (?, ?, 'InProgress', NOW()) ON DUPLICATE KEY UPDATE status='InProgress', completion_date=NOW()");
                $stmtProgressChapter->execute([$heroId, $chapterId]);
                //on récupère l'équipement du héro pour savoir où mettre les items trouvés sur le chapitre
                $stmtHeroEquip = $db->prepare("SELECT primary_weapon_item_id, secondary_weapon_item_id, shield_item_id FROM Hero WHERE id = ?");
                $stmtHeroEquip->execute([$heroId]);
                $heroEquip = $stmtHeroEquip->fetch(PDO::FETCH_ASSOC);
                //on récupère les trésors du chapitre (on joint la table item pour connaitre le type et insérer au bon endroit)
                $stmtTreasure = $db->prepare("SELECT ct.item_id, ct.quantity, i.item_type FROM Chapter_Treasure ct JOIN Items i ON ct.item_id = i.id WHERE ct.chapter_id = ?");
                $stmtTreasure->execute([$chapterId]);
                $treasures = $stmtTreasure->fetchAll(PDO::FETCH_ASSOC);
                foreach ($treasures as $treasure) {
                    $itemId = $treasure['item_id'];
                    $quantity = $treasure['quantity'];
                    $itemType = $treasure['item_type'];

                    if ($itemType === 'Arme') { //si c'est une arme ou un bouclier, on met dans la colonne adaptée dans la table héro vu qu'elle a une clonne poour
                        if ($heroEquip['primary_weapon_item_id'] === null) {
                            $stmtUpdate = $db->prepare("UPDATE Hero SET primary_weapon_item_id = ? WHERE id = ?");
                            $stmtUpdate->execute([$itemId, $heroId]);
                            $heroEquip['primary_weapon_item_id'] = $itemId;
                        } elseif ($heroEquip['secondary_weapon_item_id'] === null) {
                            $stmtUpdate = $db->prepare("UPDATE Hero SET secondary_weapon_item_id = ? WHERE id = ?");
                            $stmtUpdate->execute([$itemId, $heroId]);
                            $heroEquip['secondary_weapon_item_id'] = $itemId;
                        } //si les deux emplacements sont occupés, on ne remplit pas
                    } elseif ($itemType === 'Bouclier') {
                        if ($heroEquip['shield_item_id'] === null) {
                            $stmtUpdate = $db->prepare("UPDATE Hero SET shield_item_id = ? WHERE id = ?");
                            $stmtUpdate->execute([$itemId, $heroId]);
                            $heroEquip['shield_item_id'] = $itemId;
                        }
                    } else { //sinon si c'est d'un autre type on met dans l'inventaire
                        $stmtCheck = $db->prepare("SELECT id, quantity FROM Inventory WHERE hero_id = ? AND item_id = ?");
                        $stmtCheck->execute([$heroId, $itemId]);
                        $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);
                        if ($existing) { //si l'item est déjà présent dans l'inventaire on met juste qté + 1
                            $newQuantity = $existing['quantity'] + $quantity;
                            $stmtUpdate = $db->prepare("UPDATE Inventory SET quantity = ? WHERE id = ?");
                            $stmtUpdate->execute([$newQuantity, $existing['id']]);
                        } else { //sinon on l'ajoute
                            $stmtInsert = $db->prepare("INSERT INTO Inventory (hero_id, item_id, quantity) VALUES (?, ?, ?)");
                            $stmtInsert->execute([$heroId, $itemId, $quantity]);
                        }
                    }
                }
            }

            $stmtInv = $db->prepare("SELECT i.id, i.name, i.item_type, inv.quantity FROM Inventory inv JOIN Items i ON inv.item_id = i.id WHERE inv.hero_id = ?");
            $stmtInv->execute([$heroId]);
            $inventory = $stmtInv->fetchAll(PDO::FETCH_ASSOC);

            if ((int)$chapterId === 36) {
                $stmtKey = $db->prepare("SELECT Inventory.quantity FROM Inventory JOIN Items ON Inventory.item_id = Items.id WHERE Inventory.hero_id = ? AND Items.name like(?) AND Inventory.quantity > 0 LIMIT 1");
                $stmtKey->execute([$heroId, '%Clé%']);
                $hasKey = (bool)$stmtKey->fetchColumn();
                if (!$hasKey) {
                    foreach ($links as &$link) {
                        if ((int)$link['next_chapter_id'] === 40) {
                            $link['disabled'] = true;
                        }
                    }
                    unset($link);
                }
            }
        }

        $stmtCombat = $db->prepare(
            "select monster_id from Encounter where chapter_id = ?"
        );
        $stmtCombat->execute([$chapterId]);
        $combat = $stmtCombat->fetchAll(PDO::FETCH_ASSOC);

        if ($combat) {
                $encounter = $combat[0];
            $monsterId = (int)$encounter['monster_id'];
            $stmtMon = $db->prepare("SELECT * FROM Monster WHERE id = ?");
            $stmtMon->execute([$monsterId]);
            $monster = $stmtMon->fetch(PDO::FETCH_ASSOC);
            $stmtHeroFull = $db->prepare("SELECT Hero.*, Class.name AS class_name FROM Hero LEFT JOIN Class ON Hero.class_id = Class.id WHERE Hero.id = ?");
            $stmtHeroFull->execute([$heroId]);
            $heroFull = $stmtHeroFull->fetch(PDO::FETCH_ASSOC);
            if (!$heroFull || !$monster) {
                require 'views/game/chapitre.php';
                return;
            }
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            if (!isset($_SESSION['combat']) || !isset($_SESSION['combat']['chapter_id']) || $_SESSION['combat']['chapter_id'] != $chapterId) {
                $_SESSION['combat'] = [
                    'chapter_id' => $chapterId,
                    'monster' => $monster
                ];
            }
            $monsterState = $_SESSION['combat']['monster'];

            require_once __DIR__ . "/../public/class/combat.php";
            $engine = new CombatEngine($db);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $action = isset($_POST['action']) ? $_POST['action'] : 'physical';
                $stmtHeroFull->execute([$heroId]);
                $heroFull = $stmtHeroFull->fetch(PDO::FETCH_ASSOC);
                $result = $engine->processTurn($heroFull, $monsterState, $action);
                $_SESSION['combat']['monster'] = $result['monster'];

                $log = $result['log'];
                $resultat = $result['resultat'];
                $combatEnded = false;
                $continueTarget = null;

                if ($resultat === 'hero_victory' || $resultat === 'hero_defeat') {
                    unset($_SESSION['combat']);
                    $combatEnded = true;

                    if ($resultat === 'hero_defeat') {
                        $continueTarget = 10; // rediriger vers chapitre 10 en cas de défaite
                    } else {
                        // en cas de victoire, récupérer le lien dont next_chapter_id != 10
                        $stmtNext = $db->prepare("SELECT next_chapter_id FROM Links WHERE chapter_id = ? AND next_chapter_id <> 10 LIMIT 1");
                        $stmtNext->execute([$chapterId]);
                        $rowNext = $stmtNext->fetch(PDO::FETCH_ASSOC);
                        $continueTarget = $rowNext['next_chapter_id'];
                    }
                }

                $heroAfter = $result['hero'];
                $monsterAfter = $result['monster'];
                $stmtPot = $db->prepare("SELECT SUM(Inventory.quantity) as q FROM Inventory JOIN Items ON Inventory.item_id = Items.id WHERE Inventory.hero_id = ? AND Items.item_type = ? AND Items.name LIKE ?");
                $stmtPot->execute([$heroId, 'Potion', '%Soin%']);
                $hasPotionHP = (int)$stmtPot->fetchColumn() > 0;
                $stmtPot->execute([$heroId, 'Potion', '%Mana%']);
                $hasPotionMana = (int)$stmtPot->fetchColumn() > 0;
                $stmtInv = $db->prepare("SELECT i.id, i.name, i.item_type, inv.quantity FROM Inventory inv JOIN Items i ON inv.item_id = i.id WHERE inv.hero_id = ?");
                $stmtInv->execute([$heroId]);
                $inventory = $stmtInv->fetchAll(PDO::FETCH_ASSOC);

                require 'views/game/combat.php';
                return;
            }

            $log = ["Combat préparé : vous pouvez choisir une action."];
            $heroAfter = $heroFull;
            $monsterAfter = $monsterState;
            $stmtPot = $db->prepare("SELECT SUM(Inventory.quantity) as q FROM Inventory JOIN Items ON Inventory.item_id = Items.id WHERE Inventory.hero_id = ? AND Items.item_type = ? AND Items.name LIKE ?");
            $stmtPot->execute([$heroId, 'Potion', '%Soin%']);
            $hasPotionHP = (int)$stmtPot->fetchColumn() > 0;
            $stmtPot->execute([$heroId, 'Potion', '%Mana%']);
            $hasPotionMana = (int)$stmtPot->fetchColumn() > 0;

                if (!isset($inventory)) {
                    $stmtInv = $db->prepare("SELECT i.id, i.name, i.item_type, inv.quantity FROM Inventory inv JOIN Items i ON inv.item_id = i.id WHERE inv.hero_id = ?");
                    $stmtInv->execute([$heroId]);
                    $inventory = $stmtInv->fetchAll(PDO::FETCH_ASSOC);
                }

            require 'views/game/combat.php';
        } else {
            require 'views/game/chapitre.php';
        }
    }
}
?>
