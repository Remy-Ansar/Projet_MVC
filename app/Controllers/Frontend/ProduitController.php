<?php

namespace App\Controllers\Frontend;

use App\Core\Route;
use App\Models\Produit;
use App\Core\Controller;

class ProduitController extends Controller
{
    #[Route('app.produits.index', '/produits', ['GET'])]
    public function index(): void
    {
        $produits = (new Produit)->findLatestByActif();

        $this->render('Frontend/Produits/index.php', [
            'produits' => $produits,
            'meta' => [
                'title' => 'Liste des produits',
            ]
        ]);
    }

    #[Route('app.produit.show', '/produits/details/([0-9]+)', ['GET'])]
    public function show(int $id): void
    {
        $produit = (new Produit)->findOneActifById($id);

        if(!$produit) {
            $_SESSION['messages']['danger'] = "produit non trouvé";

            http_response_code(302);
            header('Location: /produits');
            exit();
        }

        $this->render('Frontend/Produits/show.php', [
            'produit' => $produit,
            'meta' => [
                'title' => $produit->getTitre(),
                'css' => [
                    '/css/showArticle.css',
                ]
            ]
        ]);
    }
}
