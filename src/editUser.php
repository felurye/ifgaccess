<?php
require 'func/database.php';
$id = null;
if (!empty($_GET['id'])) {
	$id = $_REQUEST['id'];
}

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM users where id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($id));
$data = $q->fetch(PDO::FETCH_ASSOC);
Database::disconnect();
?>

<!DOCTYPE html>
<html lang="pt-br">
<html>

<head>
	<?php include_once 'inc/head.html'; ?>
	<title>Edit : IFGAccess</title>
</head>

<body>
	<br>
	<div align="center" class="container-fluid">
		<div class="card" style="width: 40rem;">
			<div class="card-body">
				<div class="row">
					<h3 align="center">Editar Usuário</h3>
					<p hidden><?php echo "user id = " . $data['id']?></p>
				</div>

				<form action="func/editUser.php?id=<?php echo $id ?>" method="post">
					<div align="left" class="mb-3">
						<label class="form-label">Tag</label>
						<input name="tag" type="text" class="form-control" placeholder="" value="<?php echo $data['tag']; ?>" maxlength="8" minlength="8" disabled>
					</div>

					<div align="left" class="mb-3">
						<label class="form-label">Name</label>
						<input name="name" type="text" class="form-control" placeholder="" value="<?php echo $data['name']; ?>" required>

					</div>

					<div align="left" class="mb-3">
						<label class="form-label">Matrícula</label>
						<input name="enrollment" type="text" class="form-control" placeholder="" value="<?php echo $data['enrollment']; ?>" maxlength="14" required>
					</div>

					<div align="left" class="mb-3">
						<label class="form-label">Email Address</label>
						<input name="email" type="email" class="form-control" placeholder="" value="<?php echo $data['email']; ?>" required>
					</div>

					<div align="left" class="mb-3">
						<label class="form-label">Telefone</label>
						<input name="phone" type="tel" class="form-control" placeholder="" value="<?php echo $data['phone']; ?>"  maxlength="11" minlength="10" required>
					</div>

					<div>
						<button type="submit" class="btn btn-success">Atualizar</button>
						<a class="btn" href="users.php">Voltar</a>
					</div>
				</form>
			</div>
		</div>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>