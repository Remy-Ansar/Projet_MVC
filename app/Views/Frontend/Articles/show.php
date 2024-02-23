<section class="banner-hero" style="background: center / cover url('/images/articles/<?= $article->getImageName(); ?>')">
    <div class="banner-title">
        <h1><?= $article->getTitre(); ?></h1>
    </div>
</section>
<section class="container mt-4">
    <div class="row">
        <aside class="col-md-3">
            <h2 class="mb-3"><?= $article->getTitre(); ?></h2>
            <p><strong>Date:</strong> <?= $article->getCreatedAt()->format('Y/m/d'); ?></p>
        </aside>
        <div class="col-md-9">
            <p><?= nl2br( $article->getDescription()); ?></p>
        </div>
    </div>
</section>