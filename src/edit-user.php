<?php
require_once 'lib/Auth.php';
require_once 'lib/Database.php';
requireAuth();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: users.php');
    exit;
}

$rows = Database::query('SELECT * FROM users WHERE id = ?', [$id]);
if (empty($rows)) {
    header('Location: users.php');
    exit;
}
$data = $rows[0];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim($_POST['name']       ?? '');
    $enrollment = trim($_POST['enrollment'] ?? '');
    $email      = trim($_POST['email']      ?? '');
    $phone      = trim($_POST['phone']      ?? '');

    $data = array_merge($data, compact('name', 'enrollment', 'email', 'phone'));

    if ($name === '' || mb_strlen($name) > 80) {
        $errors[] = 'Nome inválido. Campo obrigatório, máximo 80 caracteres.';
    }
    if ($enrollment === '' || mb_strlen($enrollment) > 14) {
        $errors[] = 'Matrícula inválida. Campo obrigatório, máximo 14 caracteres.';
    }
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'E-mail inválido.';
    }
    if ($phone !== '' && !preg_match('/^\d{10,11}$/', $phone)) {
        $errors[] = 'Telefone inválido. Informe entre 10 e 11 dígitos numéricos.';
    }

    if (empty($errors)) {
        Database::update('users', [
            'name'       => $name,
            'enrollment' => $enrollment,
            'email'      => $email,
            'phone'      => $phone,
        ], ['id', $id]);
        header('Location: users.php');
        exit;
    }
}

$activePage = 'users';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include_once 'inc/head.html'; ?>
    <title>Editar : IFGAccess</title>
</head>
<body>
    <?php include_once 'inc/navbar.php'; ?>

    <br>

    <div class="container-fluid d-flex justify-content-center">
        <div class="card" style="width: 40rem;">
            <div class="card-body">
                <h3 class="text-center">Editar Usuário</h3>
                <br>

                <?php foreach ($errors as $msg): ?>
                    <div class="alert alert-danger"><?php echo e($msg); ?></div>
                <?php endforeach; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Tag</label>
                        <input type="text" class="form-control" value="<?php echo e($data['tag']); ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input name="name" type="text" class="form-control" value="<?php echo e($data['name']); ?>" maxlength="80" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Matrícula</label>
                        <input name="enrollment" type="text" class="form-control" value="<?php echo e($data['enrollment']); ?>" maxlength="14" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input name="email" type="email" class="form-control" value="<?php echo e($data['email'] ?? ''); ?>" maxlength="45">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input name="phone" type="tel" class="form-control" value="<?php echo e($data['phone'] ?? ''); ?>" maxlength="11" minlength="10">
                    </div>
                    <button type="submit" class="btn btn-success">Atualizar</button>
                    <a class="btn btn-secondary" href="users.php">Voltar</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
