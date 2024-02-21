<?php

namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Core\Route;
use App\Models\Article;

class ArticleController extends Controller
{
    #[Route('app.article.show', '/articles/details/([0-9]+)', ['GET'])]
    public function show(int $id): void
    {
        $article = (new Article)->find($id);

        $this->render('Frontend/Articles/show.php', [
            'article' => $article,
            'meta' => [
                'title' => $article['titre'],
            ]
        ]);
    }
}
