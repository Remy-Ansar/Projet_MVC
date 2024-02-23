<?php

namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Core\Route;
use App\Models\Article;

class ArticleController extends Controller
{
    #[Route('app.articles.index', '/articles', ['GET'])]
    public function index(): void
    {
        $articles = (new Article)->findLatestByActif();

        $this->render('Frontend/Articles/index.php', [
            'articles' => $articles,
            'meta' => [
                'title' => 'Liste des articles',
            ]
        ]);
    }

    #[Route('app.article.show', '/articles/details/([0-9]+)', ['GET'])]
    public function show(int $id): void
    {
        $article = (new Article)->findOneActifById($id);

        if(!$article) {
            $_SESSION['messages']['danger'] = "Article non trouvé";

            http_response_code(302);
            header('Location: /articles');
            exit();
        }

        $this->render('Frontend/Articles/show.php', [
            'article' => $article,
            'meta' => [
                'title' => $article->getTitre(),
                'css' => [
                    '/css/showArticle.css',
                ]
            ]
        ]);
    }
}
