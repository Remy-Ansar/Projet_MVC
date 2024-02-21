<section class="mt-4 container">
    <h1 class="text-center">Administration des utilisateurs</h1>
<a href="/register" class="btn btn-primary">Créer un utilisateur</a>
    <div class="row gy-3">
        <?php foreach ($users as $user) : ?>
            <div class="col-4">
                <div class="card">
                    <h2 class="card-header"><?= $user->getNom() . ' ' . $user->getPrenom(); ?></h2>
                    <div class="card-body">
                        <p class="card-text"><?= $user->getEmail(); ?></p>
                        <p class="card-text"><?= implode(', ',$user->getRoles())  ?></p>
                        <div class="d-flex justify-content-between flex-wrap">
                            <a href="/admin/users/<?= $user->getid(); ?>/edit" class="btn btn-warning">Modifier</a>
                            <form action="/admin/users/<?= $user->getid(); ?>/delete" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">
                                <input type="hidden" name="id" value="<?= $user->getid(); ?>">
                                <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>