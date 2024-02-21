<?php

namespace App\Core;

abstract class Controller
{
    protected function render(string $path, array $data = []): void
    {
        extract($data);

        ob_start();

        require_once ROOT . '/Views/' .  $path;

        $content = ob_get_clean();

        require_once ROOT . '/Views/base.php';
    }

    protected function isAdmin(): void
    {
        if (
            empty($_SESSION['LOGGED_USER']) ||
            !in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])
        ) {
            $_SESSION['messages']['danger'] = "Vous n'avez pas les droits pour cette page";

            http_response_code(302);
            header('Location: /login');
            exit();
        }
    }
}
