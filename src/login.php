<?php
require_once 'lib/Auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['authenticated'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    if (login($user, $pass)) {
        header('Location: index.php');
        exit;
    }
    $error = 'Usuário ou senha inválidos.';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include_once 'inc/head.html'; ?>
    <title>Login : IFGAccess</title>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card" style="width: 24rem;">
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="img/dark-logo.png" alt="IFGAccess" width="120">
                </div>
                <h5 class="card-title text-center mb-3">Entrar</h5>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo e($error); ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Usuário</label>
                        <input name="username" type="text" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <input name="password" type="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
