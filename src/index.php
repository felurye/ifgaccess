<?php
require_once 'lib/Auth.php';
requireAuth();

$activePage = 'home';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include_once 'inc/head.html'; ?>
    <title>Home : IFGAccess</title>
</head>
<body>
    <?php include_once 'inc/navbar.php'; ?>

    <br>
    <h3 class="text-center">Bem-vindo ao IFGAccess</h3>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
