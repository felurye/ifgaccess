<?php
require_once 'lib/Auth.php';
require_once 'lib/Database.php';
requireAuth();

$rows = Database::query(
    'SELECT A.*, U.name, U.enrollment
     FROM access A
     INNER JOIN users U ON U.id = A.user_id
     ORDER BY A.checkin DESC'
);

$activePage = 'access';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include_once 'inc/head.html'; ?>
    <title>Acessos : IFGAccess</title>
</head>
<body>
    <?php include_once 'inc/navbar.php'; ?>

    <br>

    <div class="container-fluid">
        <div class="row">
            <h3 class="text-center">Histórico de Acessos</h3>
        </div>
        <div class="row">
            <table class="table table-striped table-bordered">
                <thead style="background-color: #157347; color: #fff;">
                    <tr>
                        <th>Nome</th>
                        <th>Matrícula</th>
                        <th>Sala</th>
                        <th>Checkin</th>
                        <th>Checkout</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo e($row['name']); ?></td>
                            <td><?php echo e($row['enrollment']); ?></td>
                            <td><?php echo e($row['room']); ?></td>
                            <td><?php echo e($row['checkin']); ?></td>
                            <td><?php echo e($row['checkout'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
