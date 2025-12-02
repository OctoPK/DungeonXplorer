<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require 'autoload.php'; // parce que on a rajouter la fonction autoload.php


define('DB_HOST', 'localhost');
define('DB_NAME', 'dungeonxplorer');
define('DB_USER', 'root');
define('DB_PASS', '');

class Router
{
    private $routes = [];
    private $prefix;

    public function __construct($prefix = '')
    {
        $this->prefix = trim($prefix, '/');
    }

    public function addRoute($uri, $controllerMethod)
    {
        $this->routes[trim($uri, '/')] = $controllerMethod;
    }

    public function route($url)
    {
        $url = parse_url($url, PHP_URL_PATH);

        // Enlève le préfixe du debut de l'URL 
        if ($this->prefix && strpos($url, '/' . $this->prefix) === 0) {
            $url = substr($url, strlen($this->prefix) + 1);
        }

        // Enlève les barres obliques en trop
        $url = trim($url, '/');

        // Vérification de la correspondance de l'URL à une route définie
        foreach ($this->routes as $route => $controllerMethod) {
            $routeParts = explode('/', $route);
            $urlParts = explode('/', $url);

            if (count($routeParts) === count($urlParts)) {
                $params = [];
                $isMatch = true;

                foreach ($routeParts as $index => $part) {
                    if (preg_match('/^{\w+}$/', $part)) {
                        $params[] = $urlParts[$index];
                    } elseif ($part !== $urlParts[$index]) {
                        $isMatch = false;
                        break;
                    }
                }

                if ($isMatch) {
                    list($controllerName, $methodName) = explode('@', $controllerMethod);
                    if (class_exists($controllerName)) {
                        $controller = new $controllerName();
                        
                        if (method_exists($controller, $methodName)) {
                            call_user_func_array([$controller, $methodName], $params);
                            return;
                        } else {
                            die("Erreur : La méthode $methodName n'existe pas dans $controllerName");
                        }
                    } else {
                        die("Erreur : Le contrôleur $controllerName n'existe pas.");
                    }
                }

                // require 'views/404.php';
            }
        }
    }
}

// config des routes 


$router = new Router('DungeonXplorer');

$router->addRoute('', 'MainController@index');      
$router->addRoute('home', 'MainController@index');  

$router->addRoute('login', 'AuthController@login');
$router->addRoute('register', 'AuthController@register');
$router->addRoute('logout', 'AuthController@logout');

$router->addRoute('game', 'GameController@index');          
$router->addRoute('game/create', 'GameController@create');
$router->addRoute('chapter/{id}', 'GameController@play');   

// on appele la methode route
$router->route(trim($_SERVER['REQUEST_URI'], '/'));