<?php

namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Route;
use App\Form\ArticleForm;
use App\Models\Article;
use App\Models\Categorie;
use DateTime;

class ArticleController extends Controller
{
    #[Route('admin.articles.index', '/admin/articles', ['GET'])]
    public function index(): void
    {
        $this->isAdmin();

        $_SESSION['token'] = bin2hex(random_bytes(50));

        $this->render('Backend/Articles/index.php', [
            'articles' => (new Article)->findAll(),
            'meta' => [
                'title' => "Administration des articles",
                'scripts' => [
                    '/js/switchVisibilityArticle.js',
                ]
            ],
        ]);
    }

    #[Route('admin.articles.create', '/admin/articles/create', ['GET', 'POST'])]
    public function create(): void
    {
        $this->isAdmin();

        $form = new ArticleForm($_SERVER['REQUEST_URI']);

        if ($form->validate(['titre', 'description'], $_POST)) {
            $titre = strip_tags($_POST['titre']);
            $description = strip_tags($_POST['description']);
            $actif = isset($_POST['actif']) ? true : false;
            $imageName = !empty($_FILES['image']) ? (new Article)->uploadImage($_FILES['image']) : null;
            $categoriesId = $_POST['categoriesId'];

            $article = (new Article)->findOneByTitre($titre);

            if (!$article) {
                (new Article)
                    ->setTitre($titre)
                    ->setDescription($description)
                    ->setActif($actif)
                    ->setUserId($_SESSION['LOGGED_USER']['id'])
                    ->setImageName($imageName)
                    ->setCategoriesId($categoriesId)
                    ->create();

                $_SESSION['messages']['success'] = "Article créé avec succès";
                http_response_code(302);
                header('Location: /admin/articles');
                exit();
            } else {
                $_SESSION['messages']['danger'] = "Le titre est déjà utilisé par un autre article";
            }
        }

        $this->render('Backend/Articles/create.php', [
            'meta' => [
                'title' => 'Création d\'un article',
            ],
            'form' => $form->createForm()
        ]);
    }

    #[Route('admin.articles.edit', '/admin/articles/([0-9]+)/edit', ['GET', 'POST'])]
    public function edit(int $id): void
    {
        $this->isAdmin();

        $article = (new Article)->find($id);

        if (!$article) {
            $_SESSION['messages']['danger'] = "Article non trouvé";

            http_response_code(302);
            header('Location: /admin/articles');
            exit();
        }

        $form = new ArticleForm($_SERVER['REQUEST_URI'], $article);

        if ($form->validate(['titre', 'description'], $_POST)) {
            $titre = strip_tags($_POST['titre']);
            $description = strip_tags($_POST['description']);
            $actif = isset($_POST['actif']) ? true : false;
            $imageName = !empty($_FILES['image']) ? (new Article)->uploadImage($_FILES['image']) : null;
            $categoriesId = $_POST['categoriesId'];
            
            $oldTitre = $article->getTitre();

            if ($oldTitre !== $titre && (new Article)->findOneByTitre($titre)) {
                $_SESSION['messages']['danger'] = "Le titre est déjà utilisé par un autre article";
            } else {
                $article
                    ->setTitre($titre)
                    ->setDescription($description)
                    ->setActif($actif)
                    ->setUpdatedAt(new DateTime())
                    ->setImageName($imageName)
                    ->setCategoriesId($categoriesId)
                    ->update();

                $_SESSION['messages']['success'] = "Article mis à jour avec succès";

                http_response_code(302);
                header('Location: /admin/articles');
                exit();
            }
        }

        $this->render('Backend/Articles/edit.php', [
            'meta' => [
                'title' => 'Modification d\'un article',
            ],
            'form' => $form->createForm()
        ]);
    }

    #[Route('admin.articles.delete', '/admin/articles/([0-9]+)/delete', ['POST'])]
    public function delete(int $id): void
    {
        $this->isAdmin();

        $article = (new Article)->find($id);

        if ($article) {
            if (hash_equals($_SESSION['token'], $_POST['token'])) {
                $article->delete();

                $_SESSION['messages']['success'] = "Article supprimé avec succès";
            } else {
                $_SESSION['messages']['danger'] = "Token CSRF invalide";
            }
        } else {
            $_SESSION['messages']['danger'] = "Article non trouvé";
        }

        http_response_code(302);
        header('Location: /admin/articles');
        exit();
    }

    #[Route('admin.articles.switch', '/admin/articles/([0-9]+)/switch', ['GET'])]
    public function switch(int $id): void
    {
        $article = (new Article())->find($id);

        header('Content-Type: application/json');

        if ($article) {
            $article
                ->setActif(!$article->getActif())
                ->update();

            http_response_code(201);

            echo json_encode([
                'status' => 'success',
                'visibility' => $article->getActif()
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
