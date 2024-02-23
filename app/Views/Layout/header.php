<header class="navbar-sticky">
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">My first app MVC</a>
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation"></button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/articles">Articles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/produits">Produits</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if (!empty($_SESSION['LOGGED_USER'])) : ?>
                        <?php if (in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])) : ?>
                            <li class="nav-item me-2">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="triggerId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Admin
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="triggerId">
                                        <a class="dropdown-item" href="/admin/users">Users</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="/admin/articles">Articles</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="/admin/categories">Catégories</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="/admin/produits">Produits</a>
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a href="/logout" class="btn btn-danger">Deconnexion</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a href="/register" class="btn btn-outline-light">Inscription</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a href="/login" class="btn btn-light">Connexion</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>