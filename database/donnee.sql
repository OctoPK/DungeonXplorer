-- 1. Nettoyage et Initialisation de la base de données
DROP DATABASE IF EXISTS DungeonXplorer;
CREATE DATABASE DungeonXplorer CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE DungeonXplorer; -- IMPORTANT : On sélectionne la base qu'on vient de créer

-- 2. Création de la table Class (Classe des personnages)
CREATE TABLE Class (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    base_pv INT NOT NULL,
    base_mana INT NOT NULL, 
    strength INT NOT NULL,
    initiative INT NOT NULL,
    max_items INT NOT NULL
);

-- 3. Création de la table Items (Objets disponibles dans le jeu)
CREATE TABLE Items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    item_type VARCHAR(50) NOT NULL, -- Ex: 'Arme', 'Armure', 'Potion',
    bonus INT DEFAULT NULL
);

-- 4. Création de la table Monster (Monstres)
CREATE TABLE Monster (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    pv INT NOT NULL,
    mana INT,
    initiative INT NOT NULL,
    strength INT NOT NULL,
    attack TEXT,
    xp INT NOT NULL,
    image VARCHAR(255)
);

-- 5. Table intermédiaire Monster_Loot (Liaison Monstre - Objets)
CREATE TABLE Monster_Loot (
    id INT AUTO_INCREMENT PRIMARY KEY,
    monster_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    drop_rate DECIMAL(5, 2) DEFAULT 1.0, 
    FOREIGN KEY (monster_id) REFERENCES Monster(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES Items(id) ON DELETE CASCADE,
    UNIQUE (monster_id, item_id)
);

-- 6. Création de la table Hero (Personnage principal)
CREATE TABLE Hero (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    class_id INT,
    image VARCHAR(255),
    biography TEXT,
    pv INT NOT NULL,
    mana INT NOT NULL,
    strength INT NOT NULL,
    initiative INT NOT NULL,
    
    -- Équipement actuel
    armor_item_id INT,
    primary_weapon_item_id INT,
    secondary_weapon_item_id INT,
    shield_item_id INT,
    
    spell_list TEXT,
    xp INT NOT NULL DEFAULT 0,
    current_level INT DEFAULT 1,
    
    FOREIGN KEY (class_id) REFERENCES Class(id) ON DELETE SET NULL,
    FOREIGN KEY (armor_item_id) REFERENCES Items(id) ON DELETE SET NULL,
    FOREIGN KEY (primary_weapon_item_id) REFERENCES Items(id) ON DELETE SET NULL,
    FOREIGN KEY (secondary_weapon_item_id) REFERENCES Items(id) ON DELETE SET NULL,
    FOREIGN KEY (shield_item_id) REFERENCES Items(id) ON DELETE SET NULL    
);

-- 7. Création de la table Level (Progression)
CREATE TABLE Level (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    level INT NOT NULL,
    required_xp INT NOT NULL,
    pv_bonus INT NOT NULL,
    mana_bonus INT NOT NULL,
    strength_bonus INT NOT NULL,
    initiative_bonus INT NOT NULL,
    FOREIGN KEY (class_id) REFERENCES Class(id) ON DELETE CASCADE
);

-- 8. Création de la table Chapter (Chapitres)
CREATE TABLE Chapter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    image VARCHAR(255)
);

-- 9. Table intermédiaire Chapter_Treasure (Trésors dans les chapitres)
CREATE TABLE Chapter_Treasure (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chapter_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (chapter_id) REFERENCES Chapter(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES Items(id) ON DELETE CASCADE,
    UNIQUE (chapter_id, item_id)
);

-- 10. Création de la table Encounter (Rencontres Monstres/Chapitres)
CREATE TABLE Encounter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chapter_id INT NOT NULL,
    monster_id INT NOT NULL,
    FOREIGN KEY (chapter_id) REFERENCES Chapter(id) ON DELETE CASCADE,
    FOREIGN KEY (monster_id) REFERENCES Monster(id) ON DELETE CASCADE
);

-- 11. Table intermédiaire Inventory (Sac à dos du Héros)
CREATE TABLE Inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hero_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (hero_id) REFERENCES Hero(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES Items(id) ON DELETE CASCADE,
    UNIQUE (hero_id, item_id)
);

-- 12. Création de la table Links (Liens entre chapitres)
CREATE TABLE Links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chapter_id INT NOT NULL,
    next_chapter_id INT NOT NULL,
    description TEXT,
    FOREIGN KEY (chapter_id) REFERENCES Chapter(id) ON DELETE CASCADE,
    FOREIGN KEY (next_chapter_id) REFERENCES Chapter(id) ON DELETE CASCADE
);

-- 13. Table intermédiaire Hero_Progress (Sauvegarde progression)
CREATE TABLE Hero_Progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hero_id INT NOT NULL,
    chapter_id INT NOT NULL,
    status VARCHAR(20) DEFAULT 'Completed',
    completion_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hero_id) REFERENCES Hero(id) ON DELETE CASCADE,
    FOREIGN KEY (chapter_id) REFERENCES Chapter(id) ON DELETE CASCADE
);

-- 14. Table Users (Utilisateurs réels)
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('admin', 'player') DEFAULT 'player',
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 15. Table User_Heroes (Lien Joueur - Héros)
CREATE TABLE User_Heroes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    hero_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (hero_id) REFERENCES Hero(id) ON DELETE CASCADE
);