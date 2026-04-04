<?php
require_once 'func/auth.php';
requireAuth();

$errorMessages = [
    'tag_invalida'       => 'Tag inválida. O formato esperado é 8 caracteres hexadecimais (ex: A1B2C3D4).',
    'tag_existente'      => 'Esta tag já está cadastrada para outro usuário.',
    'nome_invalido'      => 'Nome inválido. Campo obrigatório, máximo 80 caracteres.',
    'matricula_invalida' => 'Matrícula inválida. Campo obrigatório, máximo 14 caracteres.',
    'email_invalido'     => 'E-mail inválido.',
    'telefone_invalido'  => 'Telefone inválido. Informe entre 10 e 11 dígitos numéricos.',
];

$errors = [];
if (!empty($_GET['error'])) {
    foreach (explode(',', $_GET['error']) as $code) {
        $code = trim($code);
        if (isset($errorMessages[$code])) {
            $errors[] = $errorMessages[$code];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include_once 'inc/head.html'; ?>
    <script>
        $(document).ready(function () {
            function pollTag() {
                $.get('getLastTag.php', function (data) {
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
                    <li class="nav-item"><a class="nav-link" href="users.php">Usuários</a></li>
                    <li class="nav-item"><a class="nav-link" href="access.php">Acessos</a></li>
                    <li class="nav-item"><a class="nav-link active" href="createUser.php">Cadastrar</a></li>
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
        <div class="card" style="width: 60rem;">
            <div class="card-body">
                <h3 class="text-center">Cadastro de Usuário</h3>
                <br>

                <?php foreach ($errors as $msg): ?>
                    <div class="alert alert-danger"><?php echo e($msg); ?></div>
                <?php endforeach; ?>

                <form action="func/createUser.php" method="post">
                    <div class="mb-3">
                        <label for="getTag" class="form-label">Tag</label>
                        <input name="tag" id="getTag" type="text" class="form-control"
                               placeholder="Aproxime a tag/cartão do leitor" maxlength="8" minlength="8" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input name="name" type="text" class="form-control" maxlength="80" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Matrícula</label>
                        <input name="enrollment" type="text" class="form-control" maxlength="14" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input name="email" type="email" class="form-control" maxlength="45">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input name="phone" type="tel" class="form-control" maxlength="11" minlength="10">
                    </div>
                    <button type="submit" class="btn btn-success">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
