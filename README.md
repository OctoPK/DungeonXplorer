# DungeonXplorer 🏰
Bienvenue sur le repo de notre projet de groupe.
C'est une application web de type "Livre dont vous êtes le héros" réalisée dans le cadre de notre formation.

## Le contexte
Le but était de reprendre un projet "abandonné" pour l'association *Les Aventuriers du Val Perdu*. 
L'idée est simple : on propose une aventure textuelle interactive (Dark Fantasy) où le joueur doit faire des choix, gérer son inventaire et combattre des monstres.

## Techno utilisées
Comme demandé dans le sujet, on a tout codé "from scratch" (sans gros framework type Symfony) pour montrer qu'on maîtrise les bases.
**Back :** PHP 8 (Architecture MVC faite maison).
**Base de données :** MySQL (avec PDO)].
**Front :** HTML, CSS, un peu de JS et Bootstrap pour le design
**Outils :** VS Code, Git/GitHub.

## Ce qui fonctionne (Fonctionnalités)
Pour cette V1, on a mis en place :

**Côté Joueur :**
* Création de compte et connexion.
* Création de personnage : choix entre Guerrier, Voleur ou Magicien (chacun a ses stats).
* Système de jeu : lecture des chapitres et choix qui modifient la suite de l'histoire.
* Combats : système au tour par tour avec lancers de dés (gestion de l'initiative, attaque, défense).
* Reprise de la partie là où on s'est arrêté (sauvegarde).

**Côté Admin (Back-office) :**
* [CRUD complet : on peut créer/modifier/supprimer des chapitres, des monstres et des objets.
* Gestion des utilisateurs.

## Comment installer le projet chez vous

1.  Clonez ce repo.
2.  Importez le fichier `.sql` qui est dans le dossier `database` (ou `sql`) dans votre outil SGBD (phpMyAdmin, etc.).
3.  Configurez vos accès BDD (user/password) dans le fichier de connexion (souvent dans `models/` ou `config/`).
4.  Lancez votre serveur local (Wamp/Xampp/Mamp) et c'est parti.

## L'équipe
Projet réalisé par :
* [Junior]
* [Hichem]
* [Marin]
* [Arman]


---
*Basé sur le sujet DungeonXplorer de Christophe Vallot.* 
