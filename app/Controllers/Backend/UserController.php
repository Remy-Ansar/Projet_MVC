<?php

namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Route;
use App\Form\UserForm;
use App\Models\User;

class UserController extends Controller
{
    #[Route('admin.users.index', '/admin/users', ['GET'])]
    public function index(): void
    {
        $this->isAdmin();

        $users = (new User)->findAll();

        $_SESSION['token'] = bin2hex(random_bytes(50));

        $this->render('Backend/Users/index.php', [
            'meta' => [
                'title' => 'Administration des users',
            ],
            'users' => $users,
        ]);
    }
    #[Route('admin.users.edit', '/admin/users/([0-9]+)/edit', ['GET', 'POST'])]
    public function edit(int $id): void
    {
        $this->isAdmin();
        /**
         * @var User|bool $user
         */
      $user = (new User)->find($id);

      if(!$user) {
        $_SESSION['messages']['danger'] = "Utilisateur non trouvé";

        http_response_code(302);
        header('Location: /admin/users');
        exit();
      }

      $form = new UserForm($_SERVER['REQUEST_URI'], $user);

      if($form->validate(['nom','prenom','email'], $_POST)) {
        $nom = strip_tags($_POST['nom']);
        $prenom = strip_tags($_POST['prenom']);
        $email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL);
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_ARGON2I) :null;
        $roles = $_POST['roles'];

        if($email) {
            $oldEmail = $user->getEmail();

            if ($oldEmail !== $email && !(new User)->findOneByEmail($email)) {
                $_SESSION['messages']['danger'] = "Cet email est déjà utilisé par un autre compte";
            } else {
                $user
                ->setPrenom($prenom)
                ->setNom($nom)
                ->setEmail($email)
                ->setPassword($password)
                ->setRoles($roles)
                ->update();

            $_SESSION['messages']['success'] = "Utilisateur mis à jour avec succès";

            http_response_code(302);
            header('Location: /admin/users');
            exit();
            }

        } else {
            $_SESSION['messages']['danger'] = "Veuillez renseigner un email valide";
        }
      }

      $this->render('Backend/Users/edit.php', [
        'meta' => [
            'title' => 'Modification d\'un user'
        ],
        'form' => $form->createForm(),
        ]);
    }


     #[Route('Admin.users.delete', '/admin/users/([0-9]+)/delete', ['POST'])]
    public function delete(int $id): void 
    {
        $this->isAdmin();

        /**
         * @var User|bool $user
         */
      $user = (new User)->find($id);

      if ($user) {
        if(hash_equals($_SESSION['token'], $_POST['token'])) {
            $user->delete();

            $_SESSION['messages']['success'] = "Utilisateur supprimé avec succès";
        } else {
            $_SESSION['messages']['danger'] = "Token CSRF invalide";
        }
      } else {
        $_SESSION['messages']['danger'] = "Utilisateur non trouvé";
      }

      http_response_code(302);
      header('Location: /admin/users');
      exit();
    }
}
