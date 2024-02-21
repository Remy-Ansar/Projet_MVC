<?php

namespace App\Core;

use ReflectionMethod;

class Main
{
    public function __construct(
        private Routeur $routeur = new Routeur()
    ) {
    }

    public function start(): void
    {
        session_start();

        $uri = $_GET['q'];

        // On vérifie le trailing /
        if ($uri != '' && $uri[-1] === '/') {
            // On enlève le dernier caractère de l'url (donc le /)
            $uri = substr($uri, 0, -1);

            http_response_code(302);
            header("Location: /$uri");
            exit();
        }

        $this->initRouter();

        $this->routeur->handleRequest($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    }

    private function initRouter(): void
    {
        // On récupère dynamiquement tous les fichiers dans le dossier
        // Controllers
        $files = glob(ROOT . '/Controllers/*.php');

        $filesSubFolder = glob(ROOT . '/Controllers/**/*.php');

        $files = array_merge_recursive($files, $filesSubFolder);

        // On boucle sur le tableau de fichier
        foreach ($files as $file) {
            // On retire le 1er /
            $file = substr($file, 1);

            // On remplacer app App
            $file = ucfirst($file);

            // On remplace les / par des \
            $file = str_replace('/', '\\', $file);

            // On enlève l'extension du fichier
            $file = substr($file, 0, -4);

            $classes[] = $file;
        }

        foreach ($classes as $class) {
            // On récupère les méthodes de la classe dans un tableau
            $methodes = get_class_methods($class);

            // On boucle sur toutes les méthodes de la class
            foreach ($methodes as $methode) {
                // On récupère les attributs PHP 8 pour chaque méthode dans un tableau
                $attributs = (new \ReflectionMethod($class, $methode))->getAttributes(Route::class);

                foreach ($attributs as $attribut) {
                    // On cée une instance da la classe Route avec les informations de l'attribut PHP 8
                    $route = $attribut->newInstance();

                    // On définit le controller pour la nouvelle route
                    $route->setController($class);

                    // On définit l'action de la route (La méthode à executer dans le controller)
                    $route->setAction($methode);

                    // On ajoute la route dans le tableau de route
                    $this->routeur->addRoute([
                        'name' => $route->getName(),
                        'url' => $route->getUrl(),
                        'methods' => $route->getMethods(),
                        'controller' => $route->getController(),
                        'action' => $route->getAction(),
                    ]);
                }
            }
        }
    }
}
