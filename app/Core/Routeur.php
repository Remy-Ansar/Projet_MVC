<?php

namespace App\Core;

class Routeur
{
    public  function __construct(
        private array $routes = [],
    ) {
    }

    public function addRoute(array $route): self
    {
        $this->routes[] = $route;

        return $this;
    }

    public function handleRequest(string $url, string $method): void
    {
        // On boucle sur toutes les routes de notre application
        foreach ($this->routes as $route) {
            // On vérifie que l'url du client correspond à l'url de la route
            // Et que la méthode HTTP du client correspond à celle de la route
            if (preg_match("#^" . $route['url'] . "$#", $url, $matches) && in_array($method, $route['methods'])) {
                // On récupère le nom du controller
                $controller = $route['controller'];

                // On récupère le nom de la méthode à exécuter dans le controller
                $action = $route['action'];

                // On instancie le controller
                $controller = new $controller();

                // On récupère les paramètres d'url
                $params = array_slice($matches, 1);

                // On execute la méthode dans le controller
                $controller->$action(...$params);

                return;
            }
        }

        // Si la boucle ne trouve pas de route, c'est une page 404
        http_response_code(404);
        echo "Page not found";
    }
}
