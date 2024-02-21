<section class="mt-4 container">
    <h1 class="text-center">Administration des articles</h1>
    <a href="/admin/articles/create" class="btn btn-primary">Créer un article</a>
    <div class="mt-2 row gy-3">
        <?php foreach ($articles as $article) : ?>
            <div class="col-md-4">
                <div class="card border-<?= $article->getActif() ? 'success' : 'danger'; ?>">
                    <?php if ($article->getImageName()) : ?>
                        <img src="/images/articles/<?= $article->getImageName() ?>" class="img-fluid rounded-top" loading="lazy" alt="<?= $article->getTitre() ?>">
                    <?php endif; ?>

                    <div class="card-body">
                        <h2 class="card-header"><?= $article->getTitre() ?></h2>
                        <em class="card-text"><?= $article->getCreatedAt()->format('d-m-Y'); ?></em>
                        <p class="card-text"><?= $article->getAuthor(); ?> </p>
                        <p class="card-text"><?= $article->getDescription(); ?></p>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switch-enable-article-<?= $article->getId(); ?>" <?= $article->getActif() ? 'checked' : null; ?> data-switch-article-id="<?= $article->getId(); ?>" />
                            <label class="form-check-label" for="switch-enable-article-<?= $article->getId(); ?>">Actif</label>
                        </div>

                        <div class="d-flex justify-content-between flex-wrap">
                            <a href="/admin/articles/<?= $article->getid(); ?>/edit" class="btn btn-warning">Modifier</a>
                            <form action="/admin/articles/<?= $article->getid(); ?>/delete" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article?')">
                                <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                                <input type="hidden" name="id" value="<?= $article->getid(); ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
</section>