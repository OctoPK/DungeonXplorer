<?php
/**
 * AUTOLOADER (Le Chargeur Magique)
 * * Son rôle : Dès que tu utilises un mot clé comme "new GameController()",
 * PHP va lancer cette fonction pour trouver le fichier correspondant.
 * Plus besoin de faire des "require" partout !
 */
spl_autoload_register(function ($class_name) {
    
    $dossiers = [
        'controllers/', 
        'config/',      
        'models/'        
    ];
    foreach ($dossiers as $dossier) {
        $chemin_fichier = $dossier . $class_name . '.php';
        
        if (file_exists($chemin_fichier)) {
            require_once $chemin_fichier;
            return; 
        }
    }
});
?>