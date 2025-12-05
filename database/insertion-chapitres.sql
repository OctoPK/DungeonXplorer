-- ============================================================
-- 0. NETTOYAGE (VERSION CORRIGÉE "DELETE")
-- ============================================================
SET FOREIGN_KEY_CHECKS = 0;

-- On utilise DELETE à la place de TRUNCATE pour éviter l'erreur #1701
DELETE FROM Links;
ALTER TABLE Links AUTO_INCREMENT = 1; -- On remet le compteur à zéro

DELETE FROM Encounter;
ALTER TABLE Encounter AUTO_INCREMENT = 1;

DELETE FROM Monster_Loot;
ALTER TABLE Monster_Loot AUTO_INCREMENT = 1;

DELETE FROM Chapter_Treasure;
ALTER TABLE Chapter_Treasure AUTO_INCREMENT = 1;

DELETE FROM Hero_Progress;
ALTER TABLE Hero_Progress AUTO_INCREMENT = 1;

DELETE FROM Inventory;
ALTER TABLE Inventory AUTO_INCREMENT = 1;

DELETE FROM Monster;
ALTER TABLE Monster AUTO_INCREMENT = 1;

DELETE FROM Items;
ALTER TABLE Items AUTO_INCREMENT = 1;

DELETE FROM Chapter;
ALTER TABLE Chapter AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- 1. ITEMS & MONSTRES
-- ============================================================
INSERT INTO Items (id, name, description, item_type) VALUES
(1, 'Potion de Soin', 'Rend 10 PV.', 'Potion'),
(2, 'Potion de Mana', 'Rend 5 Mana.', 'Potion'),
(3, 'Épée Rouillée', 'Dégâts +1.', 'Arme'),
(4, 'Épée Bâtarde', 'Dégâts +3.', 'Arme'),
(5, 'Bouclier du Garde', 'Défense +2.', 'Bouclier'),
(6, 'Clé de Fer Noir', 'Ouvre la tour du sorcier.', 'Clé'),
(7, 'Grimoire Ancien', 'Une source de savoir magique.', 'Tresor');

INSERT INTO Monster (id, name, pv, mana, initiative, strength, attack, xp) VALUES
(1, 'Sanglier Enragé', 15, 0, 4, 3, 'Charge', 10),
(2, 'Loup Noir', 12, 0, 6, 4, 'Morsure', 10),
(3, 'Squelette Garde', 10, 0, 2, 3, 'Coup de lance', 8),
(4, 'Rat Géant', 18, 0, 5, 4, 'Morsure infectieuse', 12),
(5, 'Armure Animée', 25, 0, 1, 5, 'Épée lourde', 20),
(6, 'Ogre Geôlier', 40, 0, 2, 6, 'Coup de massue', 50),
(7, 'Slime Acide', 20, 0, 3, 3, 'Projection acide', 15),
(8, 'Gargouille', 30, 0, 6, 5, 'Griffes de pierre', 25),
(9, 'Sorcier Malakor', 50, 30, 8, 7, 'Éclair noir', 100);

-- Loot (L'Ogre donne la clé indispensable)
INSERT INTO Monster_Loot (monster_id, item_id, drop_rate) VALUES
(6, 6, 1.00), 
(6, 1, 0.50); -- 50% de chance d'avoir une potion en plus

-- ============================================================
-- 2. CHAPITRES (HISTOIRE + INTERMÉDIAIRES DE COMBAT)
-- ============================================================
INSERT INTO Chapter (id, content, image) VALUES
-- --- INTRO & FORÊT (1-11) ---
(1, 'Le ciel est lourd ce soir sur le village du Val Perdu. Le bourgmestre s''approche de vous : "Ma fille... elle a disparu dans la forêt. On raconte qu''un sorcier vit dans un château en ruines. J''ai besoin de vous.', 'intro.jpg'),
(2, 'Vous franchissez la lisière des arbres. Un vent froid glisse entre les troncs. Deux chemins s''offrent à vous : l''un sinueux, l''autre envahi par des ronces', 'foret.jpg'),
(3, 'Votre choix vous mène devant un vieux chêne aux branches tordues, grouillant de corbeaux. Soudain, un bruit de pas feutrés se fait entendre', 'corbeaux.jpg'),
(4, 'En progressant, le calme est brisé par un grognement. Un énorme sanglier aux yeux injectés de sang fonce sur vous !', 'sanglier.jpg'),
(5, 'Vous tombez sur un vieux paysan. "Attention étranger, la nuit, des créatures rôdent..." Il vous indique un chemin plus sûr', 'paysan.jpg'),
(6, 'Une silhouette sombre s''élance : un loup noir aux yeux perçants. Le combat est inévitable.', 'loup.jpg'),
(7, 'Vous atteignez une clairière étrange avec des pierres dressées. Une brume rampe au sol. Deux cheminssont possibles. Un sentier couvert de mousse, ou un chemin tortueux.', 'clairiere.jpg'),
(8, 'Vous arrivez près d''un ruisseau. Des inscriptions anciennes sont gravées sur une pierre moussue.', 'ruisseau.jpg'),
(9, 'La forêt se disperse. Devant vous se dresse la colline et le château en ruines. L''aventure commence vraiment ici.', 'chateau.jpg'),
(10, 'Le monde se dérobe sous vos pieds... Vous êtes mort. Une lueur apparaît : "Une seconde chance est accordée.', 'death.jpg'),
(11, 'La curiosité est un vilain défaut. Il vaut parfois mieux rester dans l''inconnu... Le piège se referme et vous mourrez.', 'trap.jpg'),

-- --- ZONE 1 : INFILTRATION (12-19) ---
(12, 'Le chemin monte vers une herse rouillée. Deux squelettes montent la garde. Ils tournent leurs orbites vides vers vous.', 'porte.jpg'),
(13, 'Vous glissez dans les douves asséchées. L''odeur est atroce. Une grille d''égout est entrouverte.', 'douves.jpg'),
(14, 'Le tunnel est sombre. Une Mère des Rats, grosse comme un chien, protège sa progéniture et vous attaque !', 'rat.jpg'),
(15, 'La herse franchie, vous êtes dans la cour basse. Un champ de ruines. Un puits luit au centre.', 'cour.jpg'),
(16, 'Vous tentez d''escalader la muraille. La pierre est glissante...', 'mur.jpg'),
(17, 'Vous émergez dans les cuisines abandonnées. Une potion fume encore sur la table ', 'cuisine.jpg'),
(18, 'Le puits émet une voix spectrale.', 'puits.jpg'),
(19, 'Dans l''écurie, une armure de chevalier se redresse toute seule !', 'armure.jpg'),

-- --- ZONE 2 : DONJON (20-29) ---
(20, 'Vous entrez dans le Grand Hall du donjon. Majestueux mais décrépi. Des escaliers montent et descendent.', 'hall.jpg'),
(21, 'Vous tombez dans l''armurerie. C''est poussiéreux, mais vous trouvez un bouclier intact.', 'loot_room.jpg'),
(23, 'Les geôles sont glaciales. Au fond du couloir, une silhouette massive garde une porte blindée.', 'geoles.jpg'),
(24, 'Un prisonnier squelettique vous appelle : "La clé... l''Ogre la porte à sa ceinture !"', 'prisonnier.jpg'),
(25, 'L''Ogre Geôlier se retourne en grognant. Il lève une massue énorme. Préparez-vous !', 'boss_ogre.jpg'),
(26, 'L''Ogre s''effondre. Vous récupérez la Clé de Fer Noir sur son cadavre.', 'win_ogre.jpg'),

-- --- ZONE 3 : TOUR (30-40) ---
(30, 'Vous montez l''escalier en colimaçon vers la haute tour. Deux portes : Bibliothèque ou Laboratoire ?', 'stairs.jpg'),
(31, 'La bibliothèque. Des livres volent tout seuls. Un grimoire semble important.', 'lib.jpg'),
(32, 'Vous déchiffrez le grimoire. Une connaissance arcanique pénètre votre esprit.', 'book.jpg'),
(33, 'Le laboratoire d''alchimie. Un Slime acide s''échappe d''un bocal brisé !', 'slime.jpg'),
(34, 'Le Slime est vaincu. Vous récupérez des potions de mana.', 'labo_win.jpg'),
(35, 'Vous arrivez sur un balcon battu par les vents. Une Gargouille de pierre se détache du mur !', 'gargoyle.jpg'),
(36, 'La Gargouille est en miettes. Devant vous, la porte du sommet. Elle a une serrure en fer noir.', 'door_locked.jpg'),

-- --- ZONE 4 : FINAL (41-50) ---
(40, 'La clé tourne dans la serrure. La porte s''ouvre sur une antichambre', 'antichambre.jpg'),
(41, 'Malakor le Sorcier est là, devant un autel. La fille du maire flotte dans un champ de force.', 'boss_final.jpg'),
(45, 'Malakor hurle et se désintègre. Le château commence à trembler. La fille est libre !', 'win_game.jpg'),
(46, 'Tout s''effondre ! Vous devez fuir avec la jeune fille. Par l''escalier ou par la fenêtre ?', 'run.jpg'),
(47, 'L''escalier s''effondre devant vous ! Vous êtes piégés...', 'stairs_collapse.jpg'),
(48, 'Vous sautez dans le vide ! L''eau des douves amortit votre chute. Vous nagez vers la rive.', 'jump.jpg'),
(50, 'Le village vous accueille en héros. Le bourgmestre pleure de joie. Votre légende est née.', 'end.jpg'),

-- --- CHAPITRES TECHNIQUES (COMBATS) ---
-- Ces chapitres servent uniquement à déclencher le moteur de combat
(51, 'COMBAT : Sanglier Enragé', 'combat.jpg'),
(52, 'COMBAT : Loup Noir', 'combat.jpg'),
(53, 'COMBAT : Gardes Squelettes', 'combat.jpg'),
(54, 'COMBAT : Rat Géant', 'combat.jpg'),
(55, 'COMBAT : Armure Animée', 'combat.jpg'),
(56, 'COMBAT : Ogre Geôlier', 'combat.jpg'),
(57, 'COMBAT : Slime Acide', 'combat.jpg'),
(58, 'COMBAT : Gargouille', 'combat.jpg'),
(59, 'COMBAT FINAL : Sorcier Malakor', 'combat.jpg');

-- ============================================================
-- 3. LIENS (NAVIGATION)
-- ============================================================
INSERT INTO Links (chapter_id, next_chapter_id, description) VALUES
-- Intro
(1, 2, 'Aller vers la forêt'),

(2, 3, 'Chemin sinueux'),
(2, 4, 'Chemin de ronces'),

(3, 5, 'Rester prudent'),
(3, 6, 'Ignorer les bruits et avancer'),

-- Rencontres Forêt (Vers Chapitres de Combat)
(4, 51, 'Combattre le Sanglier'), -- Vers Combat Sanglier
(51, 8, 'Victoire ! Continuer'),  -- Victoire -> Suite
(51, 10, 'Défaite...'),

(5, 7, 'Continuer'),

(6, 52, 'Combattre le Loup'),     -- Vers Combat Loup
(52, 7, 'Victoire ! Continuer'),
(52, 10, 'Défaite...'),

(7, 8, 'Sentier moussu'),
(7, 9, 'Chemin tortueux'),

(8, 11, 'Toucher la pierre (Piège)'),
(8, 9, 'Ignorer et avancer'),

-- Château : Choix tactiques
(9, 12, 'Approcher la Porte (Squelettes)'),
(9, 13, 'Passer par les Douves'),

(10, 1, 'Nouvelle Partie'),

(11, 10, 'Vous êtes mort'),

-- Porte (Chap 12) -> Combat ou Fuite
(12, 53, 'Attaquer les Gardes'),    -- Vers Combat Squelettes
(12, 13, 'Fuir vers les douves'),   -- Fuite/Contournement

(53, 15, 'Victoire ! Entrer dans la cour'),    -- Victoire
(53, 10, 'Défaite...'),

-- Douves (Chap 13)
(13, 14, 'Entrer dans les égouts'),
(13, 16, 'Escalader le mur'),

-- Égouts (Chap 14) -> Combat Obligatoire ici
(14, 54, 'Se défendre contre le Rat'), -- Vers Combat Rat
(54, 17, 'Le rat est mort, avancer'),
(54, 10, 'Défaite...'),

-- Cour (Chap 15)
(15, 18, 'Voir le Puits'),
(15, 19, 'Fouiller l''écurie'),
(15, 20, 'Entrer au Donjon'),

(16, 21, 'Continuer l''escalade'),

(17, 20, 'Prendre la potion et avancer'),

(18, 20, 'Quitter le puits'),

-- Écurie (Chap 19) -> Combat ou Fuite
(19, 55, 'Combattre l''Armure'),   -- Vers Combat Armure
(19, 15, 'S''enfuir en courant'),  -- Fuite vers la cour
(55, 15, 'Victoire (Retour cour)'),
(55, 10, 'Défaite...'),

-- Donjon & Geôles
(20, 23, 'Descendre aux geôles'),
(20, 30, 'Monter à la tour'),

(21, 20, 'Retour au Grand Hall'),

(23, 24, 'Parler au prisonnier'),
(23, 25, 'Approcher l''Ogre'),

(24, 25, 'Aller voir l''Ogre'),

-- Boss Ogre (Chap 25) -> Combat
(25, 56, 'COMBAT : Ogre Geôlier'),
(56, 26, 'Victoire ! (Prendre la clé)'),
(56, 10, 'Défaite...'),

(26, 30, 'Monter à la tour'),

-- Tour
(30, 33, 'Laboratoire'),
(30, 31, 'Bibliothèque'),

(31, 32, 'Lire le grimoire'),
(31, 35, 'Sortir au balcon'),

(32, 35, 'Retourner à l''escalier'),

-- Labo (Chap 33) -> Combat
(33, 57, 'Attaquer le Slime'),
(33, 30, 'Fuir dans l''escalier'), -- Fuite

(57, 34, 'Victoire (Loot)'),

(34, 35, 'Sortir au balcon'),

-- Balcon (Chap 35) -> Combat
(35, 58, 'Défendre sa vie (Gargouille)'),
(58, 36, 'Victoire (Porte Sommet)'),
(58, 10, 'Défaite...'),

-- Porte Finale (Chap 36)
(36, 40, 'Ouvrir avec la Clé'),
(36, 23, 'Retourner chercher la clé (Geôles)'),

-- Boss Final (Chap 41)
(40, 41, 'Entrer'),

(41, 59, 'AFFRONTER MALAKOR'),
(59, 45, 'VICTOIRE FINALE'),
(59, 10, 'Défaite...'),

-- Fin
(45, 46, 'Fuir !'),

(46, 47, 'Prendre l''escalier'),
(46, 48, 'Sauter !'),

(48, 50, 'Nager vers la rive'),
(50, 1, 'Recommencer');

-- ============================================================
-- 4. RENCONTRES (Liaison Chapitre Technique -> Monstre)
-- ============================================================
-- C'est ici que la magie opère : Le code PHP regarde si le chapitre
-- actuel est dans cette table. Si oui -> Mode Combat.

INSERT INTO Encounter (chapter_id, monster_id) VALUES
(51, 1), -- Chap 51 = Sanglier
(52, 2), -- Chap 52 = Loup
(53, 3), -- Chap 53 = Squelettes
(54, 4), -- Chap 54 = Rat
(55, 5), -- Chap 55 = Armure
(56, 6), -- Chap 56 = Ogre
(57, 7), -- Chap 57 = Slime
(58, 8), -- Chap 58 = Gargouille
(59, 9); -- Chap 59 = Malakor

-- ============================================================
-- 5. TRESORS (Exploration)
-- ============================================================
INSERT INTO Chapter_Treasure (chapter_id, item_id) VALUES
(21, 5), -- Bouclier (Armurerie)
(17, 1), -- Potion Soin (Cuisine)
(32, 7), -- Grimoire (Bibliothèque)
(34, 2); -- Potion Mana (Labo après victoire)