<?php
$Write = "<?php $" . "UIDresult='" . $UIDresult . "'; " . "$" . "room='" . $room . "'; " . "echo $" . "room;" . "echo $" . "UIDresult;" . " ?>";
file_put_contents('UIDContainer.php', $Write);
?>

<!DOCTYPE html>
<html lang="pt-br">
<html>

<head>
	<?php include_once 'inc/head.html'; ?>
	<title>Home : IFGAccess</title>
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
				<li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
				<li class="nav-item"><a class="nav-link" href="users.php">Usuários</a></li>
				<li class="nav-item"><a class="nav-link" href="registration.php">Cadastrar</a></li>
				<li class="nav-item"><a class="nav-link" href="readTag.php">Consultar</a></li>
			</ul>
		</div>
		</div>
	</nav>

	<br>
	<h3 align="center">Welcome to IFGAccess</h3>

	<img src="home ok ok.jpg" alt="" style="width:55%;">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>