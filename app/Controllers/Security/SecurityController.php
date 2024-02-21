<?php

namespace App\Controllers\Security;

use App\Core\Controller;
use App\Core\Route;
use App\Form\LoginForm;
use App\Form\UserForm;
use App\Models\User;

class SecurityController extends Controller
{
    #[Route('app.login', '/login', ['GET', 'POST'])]
    public function login(): void
    {
        $form = new LoginForm();

        if ($form->validate(['email', 'password'], $_POST)) {
            $user = (new User())->findOneByEmail($_POST['email']);

            if ($user && password_verify($_POST['password'], $user->getPassword())) {
                $user->connect();

                http_response_code(302);
                header("Location: /");
                exit();
            } else {
                $_SESSION['messages']['danger'] = "Identifiants invalides";
            }
        }

        $this->render('Security/login.php', [
            'form' => $form->createForm(),
        ]);
    }

    #[Route('app.logout', '/logout', ['GET'])]
    public function logout(): void
    {
        unset($_SESSION['LOGGED_USER']);

        http_response_code(302);
        header("Location: /");
        exit();
    }

    #[Route('app.register', '/register', ['GET', 'POST'])]
    public function register(): void
    {
        $form = new UserForm('/register');

        if ($form->validate(['nom', 'prenom', 'email', 'password'], $_POST)) {
            $nom = strip_tags($_POST['nom']);
            $prenom = strip_tags($_POST['prenom']);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = password_hash($_POST['password'], PASSWORD_ARGON2I);

            if ($email) {
                $user = (new User)->findOneByEmail($email);

                if (!$user) {
                    $user = new User;

                    $user
                        ->setPrenom($prenom)
                        ->setNom($nom)
                        ->setEmail($email)
                        ->setPassword($password)
                        ->create();

                    $_SESSION['messages']['success'] = "Vous êtes inscrit à notre application";

                    http_response_code(302);
                    header('Location: /login');
                    exit();
                } else {
                    $_SESSION['messages']['danger'] = "L'email est déjà utilisé par un autre compte";
                }
            } else {
                $_SESSION['messages']['danger'] = "Veuillez renseignez un email valide";
            }
        }

        $this->render('Security/register.php', [
            'meta' => [
                'title' => 'S\'inscrire',
            ],
            'form' => $form->createForm(),
        ]);
    }
}
