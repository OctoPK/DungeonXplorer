<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'autoload.php'; 

if(!file_exists(__DIR__ . '/.env')) {
    die("Le fichier .env est manquant. Veuillez le créer à partir de .env.example et le configurer.");
}

$env = parse_ini_file(__DIR__ . '/.env');

define('DB_HOST', $env['DB_HOST']);
define('DB_NAME', $env['DB_NAME']);
define('DB_USER', $env['DB_USER']);
define('DB_PASS', $env['DB_PASS']);


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

        
        if ($this->prefix && strpos($url, '/' . $this->prefix) === 0) {
            $url = substr($url, strlen($this->prefix) + 1);
        }

        $url = trim($url, '/');

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
                            die("Erreur : Méthode $methodName introuvable dans $controllerName");
                        }
                    } else {
                        die("Erreur : Contrôleur $controllerName introuvable (Vérifie le nom du fichier et la Majuscule !)");
                    }
                }
            }
        }
        
        // SI ON ARRIVE ICI, C'EST QU'AUCUNE ROUTE N'A MATCHÉ
        echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";
        echo "<h1 style='color:red;'>⚠️ Erreur 404 : Route non trouvée</h1>";
        echo "<p>Le routeur a reçu l'URL : <strong>/$url</strong></p>";
        echo "<p>Il n'a trouvé aucune correspondance dans la liste des routes.</p>";
        echo "</div>";
    }
}

// On remplace les anti-slashs (\) par des slashs (/)
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$dossier_projet = dirname($scriptName);

$router = new Router($dossier_projet);

$router->addRoute('', 'MainController@index');      
$router->addRoute('home', 'MainController@index');  


$router->addRoute('login', 'AuthController@login');
$router->addRoute('register', 'AuthController@register');
$router->addRoute('logout', 'AuthController@logout');


$router->addRoute('game', 'GameController@index');          
$router->addRoute('game/create', 'GameController@create');
$router->addRoute('game/store', 'GameController@store'); 
$router->addRoute('chapter/{id}', 'GameController@play');   

$router->route($_SERVER['REQUEST_URI']);
?>