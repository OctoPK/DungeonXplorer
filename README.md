# DungeonXplorer 🏰
Bienvenue sur le repo de notre projet de groupe.
C'est une application web de type "Livre dont vous êtes le héros" réalisée dans le cadre de notre formation.

## Le contexte
Le but était de reprendre un projet "abandonné" pour l'association *Les Aventuriers du Val Perdu*. 
L'idée est simple : on propose une aventure textuelle interactive (Dark Fantasy) où le joueur doit faire des choix, gérer son inventaire et combattre des monstres.

## Techno utilisées
**Back :** PHP 8 (Architecture MVC faite maison).
**Base de données :** MySQL (avec PDO).
**Front :** HTML, CSS, un peu de Bootstrap
**Outils :** VS Code, Git/GitHub.

## Ce qui fonctionne (Fonctionnalités)
Pour cette V1, on a mis en place :

**Côté Joueur :**
* Création de compte et connexion.
* Création de personnage : choix entre Guerrier, Voleur ou Magicien (chacun a ses stats).
* Système de jeu : lecture des chapitres et choix qui modifient la suite de l'histoire.
* Combats : système au tour par tour.
* Reprise de la partie là où on s'est arrêté (sauvegarde).

**Côté Admin (Back-office) :**
* CRUD complet : on peut créer/modifier/supprimer des chapitres, des monstres et des objets.
* Gestion des utilisateurs.

## Comment installer le projet chez vous

1.  Clonez ce repo.
2. Copiez le fichier **.env.example**, renommez le par **.env**
3. Importez les 2 fichiers `.sql` qui sont dans le dossier `database` (ou `sql`) dans votre outil SGBD (phpMyAdmin, etc.).
4. Configurez vos accès BDD (user/password) dans le fichier de connexion (souvent dans `models/` ou `config/`).
5.  Modifiez les valeurs du fichier .env en fonction de votre base de données
6.  Lancez votre serveur local (Wamp/Xampp/Mamp) et c'est parti.

## L'équipe
Projet réalisé par :
* <https://github.com/Juniorr27>
* <https://github.com/Hichem1914>
* <https://github.com/marinlrdl>
* <https://github.com/OctoPK>
