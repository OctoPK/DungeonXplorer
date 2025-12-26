<?php
class CombatEngine
{
    const DEFAULT_POTION_HP = 30;
    const DEFAULT_POTION_MANA = 20;

    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function fight(array $hero, array $monster)
    {
        $log = [];
        $h = $hero;
        $m = $monster;
        $h['pv'] = isset($h['pv']) ? $h['pv'] : 0;
        $h['mana'] = isset($h['mana']) ? $h['mana'] : 0;
        $h['strength'] = isset($h['strength']) ? $h['strength'] : 0;
        $h['initiative'] = isset($h['initiative']) ? $h['initiative'] : 0;
        $h['bonus_weapon'] = 0;
        $h['bonus_defense'] = 0;
        $this->loadEquipmentBonuses($h);

        $m['pv'] = isset($m['pv']) ? $m['pv'] : 0;
        $m['mana'] = isset($m['mana']) ? $m['mana'] : null;
        $m['strength'] = isset($m['strength']) ? $m['strength'] : 0;
        $m['initiative'] = isset($m['initiative']) ? $m['initiative'] : 0;

        $log[] = "Combat démarré : {$h['name']} (PV={$h['pv']}, Mana={$h['mana']}) vs {$m['name']} (PV={$m['pv']})";

        $round = 1;
        $resultat = null;
        while ($h['pv'] > 0 && $m['pv'] > 0) {
            $heroAction = $this->decideHeroAction($h, $m);
            $res = $this->processTurn($h, $m, $heroAction);

            if (!empty($res['log'])) {
                foreach ($res['log'] as $line) $log[] = $line;
            }
            $h = $res['hero'];
            $m = $res['monster'];

            if (!empty($res['resultat'])) {
                $resultat = $res['resultat'];
                break;
            }

            $round++;
        }

        if ($resultat === null) $resultat = 'draw';

        return ['log' => $log, 'hero' => $h, 'monster' => $m, 'resultat' => $resultat];
    }

    public function processTurn(array $hero, array $monster, $heroAction)
    {
        $log = [];
        $hero['pv'] = isset($hero['pv']) ? $hero['pv'] : 0;
        $hero['mana'] = isset($hero['mana']) ? $hero['mana'] : 0;
        $hero['strength'] = isset($hero['strength']) ? $hero['strength'] : 0;
        $hero['initiative'] = isset($hero['initiative']) ? $hero['initiative'] : 0;
        $hero['bonus_weapon'] = isset($hero['bonus_weapon']) ? $hero['bonus_weapon'] : 0;
        $hero['bonus_defense'] = isset($hero['bonus_defense']) ? $hero['bonus_defense'] : 0;
        $monster['pv'] = isset($monster['pv']) ? $monster['pv'] : 0;
        $monster['mana'] = isset($monster['mana']) ? $monster['mana'] : null;
        $monster['strength'] = isset($monster['strength']) ? $monster['strength'] : 0;
        $monster['initiative'] = isset($monster['initiative']) ? $monster['initiative'] : 0;


        $log[] = "Nouveau tour : {$hero['name']} ({$hero['pv']} PV) vs {$monster['name']} ({$monster['pv']} PV)";
        $initHero = $this->roll(1,6) + $hero['initiative'];
        $initMonster = $this->roll(1,6) + $monster['initiative'];
        $log[] = "Initiative : {$hero['name']}={$initHero} vs {$monster['name']}={$initMonster}";

        $heroFirst = false;
        if ($initHero > $initMonster) {
            $heroFirst = true;
            $log[] = "{$hero['name']}, vous agissez en premier";
        } elseif ($initHero < $initMonster) {
            $heroFirst = false;
            $log[] = "{$monster['name']} agit en premier";
        } else {
            $heroClass = isset($hero['class_name']) ? strtolower($hero['class_name']) : '';
            if ($heroClass === 'voleur') {
                $heroFirst = true;
                $log[] = "Égalité d'initiative — avantage Voleur : le héros agit en premier";
            } else {
                $heroFirst = false;
                $log[] = "Égalité d'initiative — le monstre agit en premier";
            }
        }

        $actions = $heroFirst ? ['hero','monster'] : ['monster','hero'];

        foreach ($actions as $actor) {
            if ($hero['pv'] <= 0 || $monster['pv'] <= 0) break;
            if ($actor === 'hero') {
                if ($heroAction === 'potion_hp') {
                    if ($this->hasPotion($hero['id'], 'Potion PV')) {
                        $amount = self::DEFAULT_POTION_HP;
                        $hero['pv'] = $hero['pv'] + $amount;
                        $this->consumePotion($hero['id'], 'Potion PV', 1);
                        $log[] = "{$hero['name']} boit une Potion PV et récupère {$amount} PV (PV={$hero['pv']})";
                            // Persist immediate change to hero PV after potion
                            if (isset($hero['id'])) $this->persistHeroState($hero);
                    } else {
                        $log[] = "{$hero['name']} n'a pas de Potion PV";
                    }
                } elseif ($heroAction === 'potion_mana') {
                    if ($this->hasPotion($hero['id'], 'Potion Mana')) {
                        $amount = self::DEFAULT_POTION_MANA;
                        $hero['mana'] = $hero['mana'] + $amount;
                        $this->consumePotion($hero['id'], 'Potion Mana', 1);
                        $log[] = "{$hero['name']} boit une Potion Mana et récupère {$amount} mana (Mana={$hero['mana']})";
                            // Persist immediate change to hero mana after potion
                            if (isset($hero['id'])) $this->persistHeroState($hero);
                    } else {
                        $log[] = "{$hero['name']} n'a pas de Potion Mana";
                    }
                } elseif ($heroAction === 'magic') {
                    $cost = 5;
                    if ((isset($hero['class_name']) && strtolower($hero['class_name']) === 'magicien') && $hero['mana'] >= $cost) {
                        $atk = $this->magicAttack($hero, $cost);
                        $def = $this->calculateDefense($monster, false);
                        $dmg = max(0, $atk - $def);
                        $monster['pv'] -= $dmg;
                        $hero['mana'] -= $cost;
                        $log[] = "{$hero['name']} lance un sort de dégat ({$atk}) et {$monster['name']} se défend de ({$def}) dégats → vous infligez {$dmg} dégats ({$monster['name']} à {$monster['pv']} PV)";
                    } else {
                        $log[] = "{$hero['name']} ne peut pas lancer de sort (classe ou mana insuffisant)";
                    }
                } else {
                    // physical
                    $atk = $this->physicalAttack($hero);
                    $def = $this->calculateDefense($monster, false);
                    $dmg = max(0, $atk - $def);
                    $monster['pv'] -= $dmg;
                    $log[] = "{$hero['name']} attaque à hauteur de ({$atk}) dégats et {$monster['name']} se défend de ({$def}) dégats → vous infligez {$dmg} dégats ({$monster['name']} à {$monster['pv']} PV)";
                }
            } else {
                if ($monster['mana'] !== null && $monster['mana'] > 0 && mt_rand(1,100) <= 20) {
                    $cost = 3;
                    if ($monster['mana'] >= $cost) {
                        $atk = $this->magicAttack($monster, $cost);
                        $def = $this->calculateDefense($hero, true);
                        $dmg = max(0, $atk - $def);
                        $hero['pv'] -= $dmg;
                        $monster['mana'] -= $cost;
                        $log[] = "{$monster['name']} lance un sort de ({$atk}) dégats et vous vous défendez de ({$def}) → vous subissez {$dmg} dégats (vous êtes à {$hero['pv']} PV)";
                        if (isset($hero['id'])) $this->persistHeroState($hero);
                    }
                } else {
                    $atk = $this->physicalAttack($monster);
                    $def = $this->calculateDefense($hero, true);
                    $dmg = max(0, $atk - $def);
                    $hero['pv'] -= $dmg;
                    $log[] = "{$monster['name']} attaque à hauteur de ({$atk}) dégats et vous vous défendez de ({$def}) → vous subissez {$dmg} dégats (vous êtes à {$hero['pv']} PV)";
                    if (isset($hero['id'])) $this->persistHeroState($hero);
                }
            }
        }

        $resultat = null;
        if ($monster['pv'] <= 0 && $hero['pv'] > 0) {
            $log[] = "{$hero['name']} a vaincu {$monster['name']} !";
            $resultat = 'hero_victory';
            $this->donnerRewards($hero, $monster, $log);
        } elseif ($hero['pv'] <= 0) {
            $log[] = "{$hero['name']} est mort...";
            $resultat = 'hero_defeat';
            $this->persistHeroState($hero);
        }
        if (isset($hero['id'])) {
            $this->persistHeroState($hero);
        }

        return ['log' => $log, 'hero' => $hero, 'monster' => $monster, 'resultat' => $resultat];
    }

    protected function roll($min, $max)
    {
        return mt_rand($min, $max);
    }

    protected function loadEquipmentBonuses(array &$hero)
    {
        $weaponBonus = 0;
        $defenseBonus = 0;

        $ids = [];
        if (!empty($hero['primary_weapon_item_id'])) $ids[] = $hero['primary_weapon_item_id'];
        if (!empty($hero['secondary_weapon_item_id'])) $ids[] = $hero['secondary_weapon_item_id'];
        if (!empty($hero['armor_item_id'])) $ids[] = $hero['armor_item_id'];
        if (!empty($hero['shield_item_id'])) $ids[] = $hero['shield_item_id'];

        if (empty($ids)) {
            $hero['bonus_weapon'] = 0;
            $hero['bonus_defense'] = 0;
            return;
        }

        $rows = [];
        $stmt = $this->db->prepare("SELECT id, item_type, bonus FROM Items WHERE id = ?");
        foreach ($ids as $id) {
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) $rows[] = $row;
        }

        foreach ($rows as $row) {
            $type = strtolower($row['item_type']);
            $bonus = isset($row['bonus']) ? $row['bonus'] : 0;
            if ($type === 'arme') {
                $weaponBonus += $bonus;
            } elseif ($type === 'bouclier' || $type === 'armure') {
                $defenseBonus += $bonus;
            }
        }

        $hero['bonus_weapon'] = $weaponBonus;
        $hero['bonus_defense'] = $defenseBonus;
    }

    protected function physicalAttack(array $entity)
    {
        $weaponBonus = isset($entity['bonus_weapon']) ? $entity['bonus_weapon'] : 0;
        return $this->roll(1,6) + $entity['strength'] + $weaponBonus;
    }

    protected function magicAttack(array $entity, $cost)
    {
        return $this->roll(1,6) + $this->roll(1,6) + $cost;
    }

    protected function calculateDefense(array $defender, $defenderIsHero)
    {
        $base = $this->roll(1,6);
        if ($defenderIsHero && isset($defender['class_name']) && strtolower($defender['class_name']) === 'voleur') {
            $stat = floor($defender['initiative'] / 2);
        } else {
            $stat = floor($defender['strength'] / 2);
        }
        $bonusDefense = isset($defender['bonus_defense']) ? $defender['bonus_defense'] : 0;
        return $base + $stat + $bonusDefense;
    }

    protected function decideHeroAction(array $h, array $m)
    {
        if ($h['pv'] <= 10) {
            if ($this->hasPotion($h['id'], 'Potion PV')) {
                return 'potion_hp';
            }
        }
        if (isset($h['class_name']) && strtolower($h['class_name']) === 'magicien' && $h['mana'] <= 5) {
            if ($this->hasPotion($h['id'], 'Potion Mana')) {
                return 'potion_mana';
            }
        }
        if (isset($h['class_name']) && strtolower($h['class_name']) === 'magicien' && $h['mana'] >= 5) {
            if (mt_rand(1,100) <= 40) return 'magic';
        }
        return 'physical';
    }

    protected function hasPotion($heroId, $type)
    {
        if ($type === 'Potion PV') {
            $nameLike = '%Soin%';
        } elseif ($type === 'Potion Mana') {
            $nameLike = '%Mana%';
        } else {
            $nameLike = '%Potion%';
        }
        $stmt = $this->db->prepare("SELECT Inventory.quantity FROM Inventory JOIN Items ON Inventory.item_id = Items.id WHERE Inventory.hero_id = ? AND Items.item_type = ? AND Items.name LIKE ? AND Inventory.quantity > 0 LIMIT 1");
        $stmt->execute([$heroId, 'Potion', $nameLike]);
        return (bool)$stmt->fetchColumn();
    }

    protected function consumePotion($heroId, $type, $qty = 1)
    {
        if ($type === 'Potion PV') {
            $nameLike = '%Soin%';
        } elseif ($type === 'Potion Mana') {
            $nameLike = '%Mana%';
        } else {
            $nameLike = '%Potion%';
        }
        $stmt = $this->db->prepare("SELECT Inventory.id, Inventory.quantity, Items.id AS item_id FROM Inventory JOIN Items ON Inventory.item_id = Items.id WHERE Inventory.hero_id = ? AND Items.item_type = ? AND Items.name LIKE ? LIMIT 1");
        $stmt->execute([$heroId, 'Potion', $nameLike]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $newQ = max(0, $row['quantity'] - $qty);
            $upd = $this->db->prepare("UPDATE Inventory SET quantity = ? WHERE id = ?");
            $upd->execute([$newQ, $row['id']]);
        }
    }

    protected function persistHeroState(array $hero)
    {
        try {
            $pv = isset($hero['pv']) ? $hero['pv'] : 0;
            $mana = isset($hero['mana']) ? $hero['mana'] : 0;
            $stmt = $this->db->prepare("UPDATE Hero SET pv = ?, mana = ? WHERE id = ?");
            $stmt->execute([$pv, $mana, $hero['id']]);
        } catch (PDOException $e) {
        }
    }

    protected function donnerRewards(array &$h, array $m, array &$log)
    {
        try {
            $this->db->beginTransaction();
            $xpGain = isset($m['xp']) ? $m['xp'] : 0;
            $h['xp'] = (isset($h['xp']) ? $h['xp'] : 0) + $xpGain;
            $stmt = $this->db->prepare("UPDATE Hero SET xp = ?, pv = ? , mana = ? WHERE id = ?");
            $stmt->execute([$h['xp'], $h['pv'], $h['mana'], $h['id']]);
            $log[] = "{$h['name']} gagne {$xpGain} XP (total {$h['xp']})";

            $nextLevel = (isset($h['current_level']) ? $h['current_level'] + 1 : 2);
            $stmt = $this->db->prepare("SELECT required_xp, pv_bonus, mana_bonus, strength_bonus, initiative_bonus FROM Level WHERE class_id = ? AND level = ?");
            $stmt->execute([$h['class_id'], $nextLevel]);
            $lvl = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($lvl && $h['xp'] >= $lvl['required_xp']) {
                $h['pv'] += $lvl['pv_bonus'];
                $h['mana'] += $lvl['mana_bonus'];
                $h['strength'] += $lvl['strength_bonus'];
                $h['initiative'] += $lvl['initiative_bonus'];
                $h['current_level'] = $nextLevel;
                $upd = $this->db->prepare("UPDATE Hero SET pv = ?, mana = ?, strength = ?, initiative = ?, current_level = ? WHERE id = ?");
                $upd->execute([$h['pv'], $h['mana'], $h['strength'], $h['initiative'], $h['current_level'], $h['id']]);
                $log[] = "Niveau supérieur atteint ! Niveau {$nextLevel} — bonus appliqués";
            }

            $stmtLoot = $this->db->prepare("SELECT Monster_Loot.item_id, Monster_Loot.quantity, Monster_Loot.drop_rate FROM Monster_Loot WHERE Monster_Loot.monster_id = ?");
            $stmtLoot->execute([$m['id']]);
            $loots = $stmtLoot->fetchAll(PDO::FETCH_ASSOC);
            foreach ($loots as $loot) {
                $chance = (float)$loot['drop_rate'];
                $roll = mt_rand(0,10000)/10000;
                if ($roll <= $chance) {
                    $ins = $this->db->prepare("INSERT INTO Inventory (hero_id, item_id, quantity) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + ?");
                    $ins->execute([$h['id'], $loot['item_id'], $loot['quantity'], $loot['quantity']]);
                    $stmtName = $this->db->prepare("SELECT name FROM Items WHERE id = ? LIMIT 1");
                    $stmtName->execute([$loot['item_id']]);
                    $iname = $stmtName->fetchColumn();
                    $iname = $iname ?: ('item#' . $loot['item_id']);
                    $log[] = "Butin obtenu : {$iname} x{$loot['quantity']}";
                }
            }

            $specialItems = [1, 2];
            foreach ($specialItems as $sId) {
                $insS = $this->db->prepare("INSERT INTO Inventory (hero_id, item_id, quantity) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE quantity = quantity + 1");
                $insS->execute([$h['id'], $sId]);
                $stmtName = $this->db->prepare("SELECT name FROM Items WHERE id = ? LIMIT 1");
                $stmtName->execute([$sId]);
                $sname = $stmtName->fetchColumn();
                $sname = $sname ?: ('item#' . $sId);
                $log[] = "Butin spécial ajouté : {$sname} x1";
            }

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            $log[] = "Erreur lors de l'attribution des récompenses : " . $e->getMessage();
        }
    }
}