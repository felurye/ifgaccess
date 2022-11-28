<?php
$Write = "<?php $" . "UIDresult='" . $UIDresult . "'; " . "$" . "room='" . $room . "'; " . "echo $" . "room;" . "echo $" . "UIDresult;" . " ?>";
file_put_contents('UIDContainer.php', $Write);
?>

<!DOCTYPE html>
<html lang="pt-br">
<html>

<head>
	<?php include_once 'inc/head.html'; ?>
	<script>
		$(document).ready(function() {
			$("#getUID").load("UIDContainer.php");
			setInterval(function() {
				$("#getUID").load("UIDContainer.php");
			}, 500);
		});
	</script>
	<title>Cadastrar : IFGAccess</title>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class=" container">
			<a class="navbar-brand" href="index.php">
				<img src="img/white-logo.png" alt="Logo" width="100" class="d-inline-block align-text-top">
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav">
					<li class="nav-item"><a class="nav-link" aria-current="page" href="index.php">Home</a></li>
					<li class="nav-item"><a class="nav-link" href="users.php">Usuários</a></li>
					<li class="nav-item"><a class="nav-link" href="access.php">Acessos</a></li>
					<li class="nav-item"><a class="nav-link active" href="registration.php">Cadastrar</a></li>
					<li class="nav-item"><a class="nav-link" href="readTag.php">Consultar</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<br>
	<div align="center" class="container-fluid">
		<div class="card" style="width: 60rem;">
			<div class="card-body">

				<div class="row">
					<h3 align="center">Cadastro de Usuário</h3>
				</div>
				<br>

				<form action="func/createUser.php" method="post">
					<div align="left" class="mb-3">
						<label for="getUID" class="form-label">Tag</label>
						<textarea name="tag" id="getUID" class="form-control" placeholder="Por favor, aproxime sua tag/cartão do leitor" rows="1" maxlength="8" minlength="8"></textarea>
					</div>

					<div align="left" class="mb-3">
						<label class="form-label">Nome</label>
						<input name="name" type="text" class="form-control" required>
					</div>

					<div align="left" class="mb-3">
						<label class="form-label">Matrícula</label>
						<input name="enrollment" type="text" class="form-control" maxlength="14" required>
					</div>

					<div align="left" class="mb-3">
						<label class="form-label">E-mail</label>
						<input name="email" type="email" class="form-control" required>
					</div>

					<div align="left" class="mb-3">
						<label class="form-label">Telefone</label>
						<input name="phone" type="tel" class="form-control" maxlength="11" minlength="10" required>
					</div>

					<div>
						<button type="submit" class="btn btn-success">Cadastrar</button>
					</div>
				</form>
			</div>
		</div>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>