<?php 

spl_autoload_register(function ($class_name) {
    $directories = array(
        'models/',
        'controllers/'
    );

    foreach ($directories as $directory) {
        $filePath = $directory . $class_name . '.php';

        if (file_exists($filePath)) {
            require_once $filePath;
            return;
        }
    }
});