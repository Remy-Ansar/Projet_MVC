<?php

namespace App\Controllers\Backend;

use App\Core\Route;
use App\Models\Article;
use App\Form\ArticleForm;
use App\Core\Controller;
use DateTime;

class ArticleController extends Controller
{
    #[Route('app.articles.create', '/admin/articles/create', ['GET', 'POST'])]
    public function create(): void
    {
        $this->isAdmin();

        $form = new ArticleForm($_SERVER['REQUEST_URI']);

        if ($form->validate(['titre', 'description'], $_POST)) {
            $titre = strip_tags($_POST['titre']);
            $description = strip_tags($_POST['description']);
            $actif = isset($_POST['actif']) ? true : false;

            $article = (new Article)->findOneByTitre($titre);

            if (!$article) {
                (new Article)
                    ->setTitre($titre)
                    ->setDescription($description)
                    ->setActif($actif)
                    ->setUserId($_SESSION['LOGGED_USER']['id'])
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
                'title' => 'Création d\un article',
            ],
            'form' => $form->createForm()
        ]);
    }

    #[Route('admin.articles.index', '/admin/articles', ['GET'])]
    public function index(): void
    {
        $this->isAdmin();



        $_SESSION['token'] = bin2hex(random_bytes(50));

        $this->render('Backend/articles/index.php', [
            'meta' => [
                'title' => 'Administration des articles',
            ],
            'articles' => (new article)->findAll(),
        ]);
    }

    #[Route('admin.articles.edit', '/admin/articles/([0-9]+)/edit', ['GET', 'POST'])]
    public function edit(int $id): void
    {
        $this->isAdmin();

        $article = (new Article)->find($id);

        if (!$article) {
            $_SESSION['messages']['danger'] = "article non trouvé";

            http_response_code(302);
            header('Location: /admin/articles');
            exit();
        }

        $form = new ArticleForm($_SERVER['REQUEST_URI'], $article);

        if ($form->validate(['titre', 'description'], $_POST)) {
            $titre = strip_tags($_POST['titre']);
            $description = strip_tags($_POST['description']);
            $actif = isset($_POST['actif']) ? true : false;         

            $oldTitre = $article->getTitre();

                if ($oldTitre !== $titre && (new article)->findOneByTitre($titre)) {
                    $_SESSION['messages']['danger'] = "Le titre est déjà utilisé par un autre article";
                } else {
                    $article
                        ->setTitre($titre)
                        ->setDescription($description)
                        ->setActif($actif)
                        ->setUpdatedAt(new DateTime())
                        ->update();

                    $_SESSION['messages']['success'] = "Article mis à jour avec succès";

                    http_response_code(302);
                    header('Location: /admin/articles');
                    exit();
                }
            }
        
        $this->render('Backend/articles/edit.php', [
            'meta' => [
                'title' => 'Modification d\'un article'
            ],
            'form' => $form->createForm(),
            ]);
        
    }

    #[Route('Admin.articles.delete', '/admin/articles/([0-9]+)/delete', ['POST'])]
    public function delete(int $id): void
    {
        $this->isAdmin();

        $article = (new Article)->find($id);

        if ($article) {
            if(hash_equals($_SESSION['token'], $_POST['token'])) {
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
}
