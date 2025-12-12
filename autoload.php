<?php

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