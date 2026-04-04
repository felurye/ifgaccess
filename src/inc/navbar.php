<?php // $activePage deve ser definido pelo arquivo que inclui este componente ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="img/white-logo.png" alt="Logo" width="100" class="d-inline-block align-text-top">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link<?= ($activePage ?? '') === 'home' ? ' active' : '' ?>" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link<?= ($activePage ?? '') === 'users' ? ' active' : '' ?>" href="users.php">Usuários</a></li>
                <li class="nav-item"><a class="nav-link<?= ($activePage ?? '') === 'access' ? ' active' : '' ?>" href="access.php">Acessos</a></li>
                <li class="nav-item"><a class="nav-link<?= ($activePage ?? '') === 'create-user' ? ' active' : '' ?>" href="create-user.php">Cadastrar</a></li>
                <li class="nav-item"><a class="nav-link<?= ($activePage ?? '') === 'read-tag' ? ' active' : '' ?>" href="read-tag.php">Consultar</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
            </ul>
        </div>
    </div>
</nav>
