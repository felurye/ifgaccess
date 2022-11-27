<?php
require 'database.php';
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
    $sql = "DELETE FROM teste  WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    Database::disconnect();
    header("Location: user data.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php include_once 'inc/head.html'; ?>
    <title>Delete : IFGAccess</title>
</head>

<body>
    <h2 align="center">IFGAccess</h2>

    <div class="container">

        <div class="span10 offset1">
            <div class="row">
                <h3 align="center">Delete User</h3>
            </div>

            <form class="form-horizontal" action="user data delete page.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <p class="alert alert-error">Are you sure to delete ?</p>
                <div class="form-actions">
                    <button type="submit" class="btn btn-danger">Yes</button>
                    <a class="btn" href="user data.php">No</a>
                </div>
            </form>
        </div>

    </div> <!-- /container -->
</body>

</html>