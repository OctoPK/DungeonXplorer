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

        $chemin_lcfirst = $dossier . lcfirst($class_name) . '.php';
        if (file_exists($chemin_lcfirst)) {
            require_once $chemin_lcfirst;
            return;
        }
        if (is_dir($dossier)) {
            $files = scandir($dossier);
            foreach ($files as $f) {
                if (strcasecmp($f, $class_name . '.php') === 0) {
                    require_once $dossier . $f;
                    return;
                }
            }
        }
    }
});
?>