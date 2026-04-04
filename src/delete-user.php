<?php
require_once 'lib/Auth.php';
require_once 'lib/Database.php';
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

$activePage = 'users';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include_once 'inc/head.html'; ?>
    <title>Excluir : IFGAccess</title>
</head>
<body>
    <?php include_once 'inc/navbar.php'; ?>

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
                <form method="post">
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
