<?php

namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Route;
use App\Form\CategorieForm;
use App\Models\Categorie;
use DateTime;

class CategorieController extends Controller
{

    
    #[Route('admin.categories.index', '/admin/categories', ['GET'])]
    public function index(): void
    {
        $this->isAdmin();
        $_SESSION['token'] = bin2hex(random_bytes(50));

        $this->render('Backend/Categories/index.php', [
            'categories' =>(new Categorie)->findAll(),
            'meta' => [
                'title' => "Administration des catégories",
                'scripts' => [
                    '/js/switchVisibilityCategorie.js'
                ]
            ]
        ]);
    }


    #[Route('admin.categories.create', '/admin/categories/create', ['GET', 'POST'])]
    public function create():void
    {
        $this->isAdmin();

        $form = new CategorieForm($_SERVER['REQUEST_URI']);


        if ($form->validate(['titre'], $_POST)) {
            $titre = strip_tags($_POST['titre']);
            $actif = isset($_POST['actif']) ? true : false;
           

            $categorie = (new Categorie())->findOneByTitre($titre);

            if (!$categorie) {
                (new Categorie)
                    ->setTitre($titre)
                    ->setActif($actif)
                    ->create();

                $_SESSION['messages']['success'] = "Catégorie créé avec succès";
                http_response_code(302);
                header('Location: /admin/categories');
                exit();
            } else {
                $_SESSION['messages']['danger'] = "Cette catégorie a déjà été créé";
            }
        }

        $this->render('Backend/Categories/create.php', [
            'meta' => [
                'title' => 'Création d\'une catégorie',
            ],
            'form' => $form->createForm()
        ]);
    }


    
    #[Route('admin.categories.edit', '/admin/categories/([0-9]+)/edit', ['GET', 'POST'])]
    public function edit(int $id): void
    {
        $this->isAdmin();

        $categorie = (new Categorie)->find($id);

        if (!$categorie) {
            $_SESSION['messages']['danger'] = "Catégorie non trouvée";

            http_response_code(302);
            header('Location: /admin/categories');
            exit();
        }
        $form = new CategorieForm($_SERVER['REQUEST_URI'], $categorie);

        if ($form->validate(['titre'], $_POST)) {
            $titre = strip_tags($_POST['titre']);
            $actif = isset($_POST['actif']) ? true : false;
            

            $oldCategorie = $categorie->getTitre();

            if ($oldCategorie !== $categorie && (new Categorie())->findOneByTitre($titre)) {
                $_SESSION['messages']['danger'] = "Cette catégorie a déjà été créé";
            } else {
                $categorie
                    ->setTitre($titre)
                    ->setActif($actif)
                    ->setUpdatedAt(New DateTime())
                    ->update();

                $_SESSION['messages']['success'] = "La catégorie a été renommée avec succès";

                http_response_code(302);
                header('Location: /admin/categories');
                exit();
            }
        }

        $this->render('Backend/categories/edit.php', [
            'meta' => [
                'title' => 'Modification d\'une catégorie',
            ],
            'form' => $form->createForm()
        ]);
    }
    
    #[Route('admin.categories.delete', '/admin/categories/([0-9]+)/delete', ['POST'])]
    public function delete(int $id): void
    {
        $this->isAdmin();

        $categorie = (new Categorie)->find($id);

        if ($categorie) {
            if (hash_equals($_SESSION['token'], $_POST['token'])) {
                $categorie->delete();

                $_SESSION['messages']['success'] = "Catégorie supprimée avec succès";
            } else {
                $_SESSION['messages']['danger'] = "Token CSRF invalide";
            }
        } else {
            $_SESSION['messages']['danger'] = "Catégorie non trouvé";
        }

        http_response_code(302);
        header('Location: /admin/categories');
        exit();
    }

    #[Route('admin.categories.switch', '/admin/categories/([0-9]+)/switch', ['GET'])]
    public function switch(int $id): void
    {
        $categorie = (new Categorie())->find($id);

        header('Content-Type: application/json');

        if ($categorie) {
            $categorie
                ->setActif(!$categorie->getActif())
                ->update();

            http_response_code(201);

            echo json_encode([
                'status' => 'success',
                'visibility' => $categorie->getActif()
            ]);
            exit();
        } else {
            http_response_code(404);

            echo json_encode([
                'status' => 'error',
                'message' => 'Article non trouvé',
            ]);
            exit();
        }
    }

}