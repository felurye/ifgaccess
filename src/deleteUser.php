<?php
require 'func/database.php';
$id = 0;

if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (!empty($_POST)) {
    // keep track post values
    $id = $_POST['id'];

    // delete data
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM users WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    Database::disconnect();
    header("Location: users.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php include_once 'inc/head.html'; ?>
    <title>Delete : IFGAccess</title>
</head>

<body>

    <div class="container-fluid">
        <br>
        <div align="center" class="span10 offset1">
            <div class="row">
                <h3 >Deletar Usuário</h3>
            </div>

            <form class="form-horizontal" action="deleteUser.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <p class="alert alert-error">Are you sure to delete?</p>
                <div class="form-actions">
                    <button type="submit" class="btn btn-danger">Sim, deletar</button>
                    <a class="btn" href="users.php">Não</a>
                </div>
            </form>
        </div>

    </div> <!-- /container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>