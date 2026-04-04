<?php
require_once 'lib/Auth.php';
require_once 'lib/Database.php';
requireAuth();

$rows = Database::query('SELECT * FROM users ORDER BY name ASC');

$activePage = 'users';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include_once 'inc/head.html'; ?>
    <title>Usuários : IFGAccess</title>
</head>
<body>
    <?php include_once 'inc/navbar.php'; ?>

    <br>

    <div class="container-fluid">
        <div class="row">
            <h3 class="text-center">Lista de Usuários</h3>
        </div>
        <div class="row">
            <table class="table table-striped table-bordered">
                <thead style="background-color: #157347; color: #fff;">
                    <tr>
                        <th>Nome</th>
                        <th>Tag</th>
                        <th>Matrícula</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo e($row['name']); ?></td>
                            <td><?php echo e($row['tag']); ?></td>
                            <td><?php echo e($row['enrollment']); ?></td>
                            <td><?php echo e($row['email']); ?></td>
                            <td><?php echo e($row['phone']); ?></td>
                            <td>
                                <a class="btn btn-success btn-sm" href="edit-user.php?id=<?php echo (int) $row['id']; ?>">Editar</a>
                                <a class="btn btn-danger btn-sm" href="delete-user.php?id=<?php echo (int) $row['id']; ?>">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
