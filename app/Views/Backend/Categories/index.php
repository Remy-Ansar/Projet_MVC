<section class="container mt-4">
    <h1 class="text-center">Administration des catégories</h1>
    <a href="/admin/categories/create" class="btn btn-primary">Créer une catégorie</a>
    <div class="mt-2 row gy-3">
        <?php foreach ($categories as $categorie) : ?>
            <div class="col-md-4">
                <div class="card border-<?= $categorie->getActif() ? 'success' : 'danger'; ?>">
                    
                    <div class="card-body">
                        <h2 class="card-text"><?= $categorie->getTitre(); ?></h2>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switch-enable-categorie-<?= $categorie->getId(); ?>" <?= $categorie->getActif() ? 'checked' : null; ?> data-switch-categorie-id="<?= $categorie->getId(); ?>" />
                            <label class="form-check-label" for="switch-enable-categorie-<?= $categorie->getId(); ?>">Actif</label>
                        </div>

                        <div class="d-flex justify-content-between flex-wrap">
                            <a href="/admin/categories/<?= $categorie->getId(); ?>/edit" class="btn btn-warning">Modifier</a>
                            <form action="/admin/categories/<?= $categorie->getId(); ?>/delete" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie')">
                                <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                                <input type="hidden" name="id" value="<?= $categorie->getId(); ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>