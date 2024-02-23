<?php 

namespace App\Controllers\Backend;

use DateTime;
use App\Core\Route;
use App\Models\Produit;
use App\Core\Controller;
use App\Form\ProduitForm;
use App\Models\Categorie;

class ProduitController extends Controller 
{
    #[Route('admin.produits.index', '/admin/produits', ['GET'])]
    public function index(): void
    {
        $this->isAdmin();

        $_SESSION['token'] = bin2hex(random_bytes(50));

        $this->render('Backend/Produits/index.php', [
            'produits' => (New Produit)->findAll(),
            'meta' => [
                'title' => 'Administration des produits',
                'scripts' => [
                    '/js/switchVisibilityProduit.js'
                ]
                ],
            ]);
    }

    #[Route('admin.produits.create', '/admin/produits/create', ['GET', 'POST'])]
    public function create(): void
    {
        $this->isAdmin();

        $form = new ProduitForm($_SERVER['REQUEST_URI']);

        if ($form->validate(['titre', 'description'], $_POST)) {
            $titre = strip_tags($_POST['titre']);
            $description = strip_tags($_POST['description']);
            $actif = isset($_POST['actif']) ? true : false;
            $imageName = !empty($_FILES['image']) ? (new produit)->uploadImage($_FILES['image']) : null;
            $categoriesId = (int) $_POST['categories'] > 0 ? (int) $_POST['categories'] : null;
            $TVA = filter_input(INPUT_POST,'TVA', FILTER_VALIDATE_FLOAT);
            $prixHT = filter_input(INPUT_POST,'prixht', FILTER_SANITIZE_NUMBER_FLOAT);


            $produit = (new Produit)->findOneByTitre($titre);

            if (!$produit) {
                (new Produit)
                    ->setTitre($titre)
                    ->setDescription($description)
                    ->setActif($actif)
                    ->setImageName($imageName)
                    ->setCategoriesId($categoriesId)
                    ->setTVA($TVA)
                    ->setprixHT($prixHT)
                    ->create();

                $_SESSION['messages']['success'] = "Produit créé avec succès";
                http_response_code(302);
                header('Location: /admin/produits');
                exit();
            } else {
                $_SESSION['messages']['danger'] = "Le titre est déjà utilisé par un autre produit";
            }
        }

        $this->render('Backend/Produits/create.php', [
            'meta' => [
                'title' => 'Création d\'un produit',
            ],
            'form' => $form->createForm()
        ]);
    }

    #[Route('admin.produits.edit', '/admin/produits/([0-9]+)/edit', ['GET', 'POST'])]
    public function edit(int $id): void
    {
        $this->isAdmin();

        $produit = (new Produit)->find($id);

        if (!$produit) {
            $_SESSION['messages']['danger'] = "Produit non trouvé";

            http_response_code(302);
            header('Location: /admin/produits');
            exit();
        }

         $form = new ProduitForm($_SERVER['REQUEST_URI']);

        if ($form->validate(['titre', 'description'], $_POST)) {
            $titre = strip_tags($_POST['titre']);
            $description = strip_tags($_POST['description']);
            $actif = isset($_POST['actif']) ? true : false;
            $imageName = !empty($_FILES['image']) ? (new produit)->uploadImage($_FILES['image']) : null;
            $categoriesId = (int) $_POST['categories'] > 0 ? (int) $_POST['categories'] : null;
            $TVA = (float) $_POST['TVA'];
            $prixHT = filter_input((float)$_POST['prixht'], FILTER_SANITIZE_NUMBER_FLOAT);

            $produit = (new Produit)->findOneByTitre($titre);

            $oldTitre = $produit->getTitre();

            if ($oldTitre !== $titre && (new Produit)->findOneByTitre($titre)) {
                $_SESSION['messages']['danger'] = "Le titre est déjà utilisé par un autre produit";
            } else {
                $produit
                    ->setTitre($titre)
                    ->setDescription($description)
                    ->setActif($actif)
                    ->setImageName($imageName)
                    ->setUpdatedAt(new DateTime())
                    ->setCategoriesId($categoriesId)
                    ->setTVA($TVA)
                    ->setprixHT($prixHT)
                    ->update();

                $_SESSION['messages']['success'] = "Produit modifié avec succès";

                http_response_code(302);
                header('Location: /admin/produits');
                exit();
            }
        }

        $this->render('Backend/Produits/edit.php', [
            'meta' => [
                'title' => 'Modification d\'un produit',
            ],
            'form' => $form->createForm()
        ]);
    }

    #[Route('admin.produits.delete', '/admin/produits/([0-9]+)/delete', ['POST'])]
    public function delete(int $id): void
    {
        $this->isAdmin();

        $article = (new Produit())->find($id);

        if ($article) {
            if (hash_equals($_SESSION['token'], $_POST['token'])) {
                $article->delete();

                $_SESSION['messages']['success'] = "Produit supprimé avec succès";
            } else {
                $_SESSION['messages']['danger'] = "Token CSRF invalide";
            }
        } else {
            $_SESSION['messages']['danger'] = "Produit non trouvé";
        }

        http_response_code(302);
        header('Location: /admin/produits');
        exit();
    }

    #[Route('admin.produits.switch', '/admin/produits/([0-9]+)/switch', ['GET'])]
    public function switch(int $id): void
    {
        $produit = (new Produit())->find($id);

        header('Content-Type: application/json');

        if ($produit) {
            $produit
                ->setActif(!$produit->getActif())
                ->update();

            http_response_code(201);

            echo json_encode([
                'status' => 'success',
                'visibility' => $produit->getActif()
            ]);
            exit();
        } else {
            http_response_code(404);

            echo json_encode([
                'status' => 'error',
                'message' => 'Produit non trouvé',
            ]);
            exit();
        }
    }
}

