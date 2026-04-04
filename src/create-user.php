<?php
require_once 'lib/Auth.php';
require_once 'lib/Database.php';
requireAuth();

$errors = [];
$formData = ['tag' => '', 'name' => '', 'enrollment' => '', 'email' => '', 'phone' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tag        = trim($_POST['tag']        ?? '');
    $name       = trim($_POST['name']       ?? '');
    $enrollment = trim($_POST['enrollment'] ?? '');
    $email      = trim($_POST['email']      ?? '');
    $phone      = trim($_POST['phone']      ?? '');

    $formData = compact('tag', 'name', 'enrollment', 'email', 'phone');

    if (!preg_match('/^[0-9A-Fa-f]{8}$/', $tag)) {
        $errors[] = 'Tag inválida. O formato esperado é 8 caracteres hexadecimais (ex: A1B2C3D4).';
    }
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
        $existing = Database::query('SELECT id FROM users WHERE tag = ?', [strtoupper($tag)]);
        if (!empty($existing)) {
            $errors[] = 'Esta tag já está cadastrada para outro usuário.';
        }
    }

    if (empty($errors)) {
        Database::create('users', [
            'tag'        => strtoupper($tag),
            'name'       => $name,
            'enrollment' => $enrollment,
            'email'      => $email,
            'phone'      => $phone,
        ]);
        header('Location: users.php');
        exit;
    }
}

$activePage = 'create-user';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include_once 'inc/head.html'; ?>
    <script>
        $(document).ready(function () {
            function pollTag() {
                $.get('api/last-tag.php', function (data) {
                    var tag = data.trim();
                    if (tag !== '') {
                        $('#getTag').val(tag);
                    }
                });
            }
            pollTag();
            setInterval(pollTag, 500);
        });
    </script>
    <title>Cadastrar : IFGAccess</title>
</head>
<body>
    <?php include_once 'inc/navbar.php'; ?>

    <br>

    <div class="container-fluid d-flex justify-content-center">
        <div class="card" style="width: 60rem;">
            <div class="card-body">
                <h3 class="text-center">Cadastro de Usuário</h3>
                <br>

                <?php foreach ($errors as $msg): ?>
                    <div class="alert alert-danger"><?php echo e($msg); ?></div>
                <?php endforeach; ?>

                <form method="post">
                    <div class="mb-3">
                        <label for="getTag" class="form-label">Tag</label>
                        <input name="tag" id="getTag" type="text" class="form-control"
                               placeholder="Aproxime a tag/cartão do leitor" maxlength="8" minlength="8"
                               value="<?php echo e($formData['tag']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input name="name" type="text" class="form-control" maxlength="80"
                               value="<?php echo e($formData['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Matrícula</label>
                        <input name="enrollment" type="text" class="form-control" maxlength="14"
                               value="<?php echo e($formData['enrollment']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input name="email" type="email" class="form-control" maxlength="45"
                               value="<?php echo e($formData['email']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input name="phone" type="tel" class="form-control" maxlength="11" minlength="10"
                               value="<?php echo e($formData['phone']); ?>">
                    </div>
                    <button type="submit" class="btn btn-success">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
