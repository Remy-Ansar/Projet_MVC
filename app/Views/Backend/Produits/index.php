<section class="container mt-4">
    <h1 class="text-center">Administration des produits</h1>
    <a href="/admin/produits/create" class="btn btn-primary">Créer un produit</a>
    <div class="mt-2 row gy-3">
        <?php foreach ($produits as $produit) : ?>
            <div class="col-md-4">
                <div class="card border-<?= $produit->getActif() ? 'success' : 'danger'; ?>">
                    <?php if ($produit->getImageName()) : ?>
                        <img src="/images/produits/<?= $produit->getImageName() ?>" class="img-fluid rounded-top" loading="lazy" alt="<?= $produit->getTitre(); ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h2 class="card-text"><?= $produit->getTitre(); ?></h2>
                        <em class="card-text"><?= $produit->getCreatedAt()->format('Y/m/d'); ?></em>
                        <p class="card-text"><?= $produit->getDescription(); ?></p>
                        <p class="card-text"><?= ($produit->getPrixHT())*(1 + $produit->getTVA()); ?> € (TTC)</p>
                        <p class="card-text <?= $produit->getCategorie() ? 'badge bg-primary' : null ?>"><?= $produit->getCategorie(); ?></p>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switch-enable-produit-<?= $produit->getId(); ?>" <?= $produit->getActif() ? 'checked' : null; ?> data-switch-produit-id="<?= $produit->getId(); ?>" />
                            <label class="form-check-label" for="switch-enable-produit-<?= $produit->getId(); ?>">Actif</label>
                        </div>

                        <div class="d-flex justify-content-between flex-wrap">
                            <a href="/admin/produits/<?= $produit->getId(); ?>/edit" class="btn btn-warning">Modifier</a>
                            <form action="/admin/produits/<?= $produit->getId(); ?>/delete" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?')">
                                <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                                <input type="hidden" name="id" value="<?= $produit->getId(); ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>