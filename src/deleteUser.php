<?php
require_once 'func/auth.php';
require_once 'func/database.php';
requireAuth();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!empty($_POST)) {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id > 0) {
        Database::query('DELETE FROM users WHERE id = ?', [$id]);
    }
    header('Location: users.php');
    exit;
}

if ($id <= 0) {
    header('Location: users.php');
    exit;
}

$rows = Database::query('SELECT name FROM users WHERE id = ?', [$id]);
if (empty($rows)) {
    header('Location: users.php');
    exit;
}
$userName = $rows[0]['name'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include_once 'inc/head.html'; ?>
    <title>Excluir : IFGAccess</title>
</head>
<body>
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="users.php">Usuários</a></li>
                    <li class="nav-item"><a class="nav-link" href="access.php">Acessos</a></li>
                    <li class="nav-item"><a class="nav-link" href="createUser.php">Cadastrar</a></li>
                    <li class="nav-item"><a class="nav-link" href="readTag.php">Consultar</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <br>

    <div class="container-fluid d-flex justify-content-center">
        <div class="card" style="width: 30rem;">
            <div class="card-body text-center">
                <h3>Excluir Usuário</h3>
                <br>
                <p class="alert alert-warning">
                    Deseja excluir o usuário <strong><?php echo e($userName); ?></strong>?<br>
                    Esta ação não pode ser desfeita.
                </p>
                <form action="deleteUser.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <button type="submit" class="btn btn-danger">Sim, excluir</button>
                    <a class="btn btn-secondary" href="users.php">Cancelar</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
