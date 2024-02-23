<section class="container mt-4">
    <h1 class="text-center">Liste des articles</h1>
    <div class="mt-2 row gy-3">
        <?php foreach ($articles as $article) : ?>
            <div class="col-md-4">
                <div class="card">
                    <?php if ($article->getImageName()) : ?>
                        <img src="/images/articles/<?= $article->getImageName() ?>" class="img-fluid rounded-top" loading="lazy" alt="<?= $article->getTitre(); ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h2 class="card-text"><?= $article->getTitre(); ?></h2>
                        <em class="card-text"><?= $article->getCreatedAt()->format('Y/m/d'); ?></em>
                        <p class="card-text"><?= $article->getAuthor(); ?></p>
                        <p class="card-text"><?= $article->getDescription(); ?></p>
                        <a href="/articles/details/<?= $article->getId(); ?>" class="btn btn-primary">En savoir plus</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>