<section class="container mt-4">
    <h1 class="text-center">Liste des produits</h1>
    <div class="mt-2 row gy-3">
        <?php foreach ($produits as $produit) : ?>
            <div class="col-md-4">
                <div class="card">
                    <?php if ($produit->getImageName()) : ?>
                        <img src="/images/produits/<?= $produit->getImageName() ?>" class="img-fluid rounded-top" loading="lazy" alt="<?= $produit->getTitre(); ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h2 class="card-text"><?= $produit->getTitre(); ?></h2>
                        <em class="card-text"><?= $produit->getCreatedAt()->format('Y/m/d'); ?></em>
                        <p class="card-text"><?= $produit->getDescription(); ?></p>
                        <a href="/produits/details/<?= $produit->getId(); ?>" class="btn btn-primary">En savoir plus</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>