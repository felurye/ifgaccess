<!DOCTYPE html>
<html lang="pt-br">
<html>

<head>
	<?php include_once 'inc/head.html'; ?>
	<title>Acessos : IFGAccess</title>
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
					<li class="nav-item"><a class="nav-link active" href="access.php">Acessos</a></li>
					<li class="nav-item"><a class="nav-link" href="createUser.php">Cadastrar</a></li>
					<li class="nav-item"><a class="nav-link" href="readTag.php">Consultar</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<br>

	<div class="container-fluid">
		<div class="row">
			<h3 align="center">Lista de Usuários</h3>
		</div>
		<div class="row">
			<table class="table table-striped table-bordered">
				<thead>
					<tr bgcolor="#157347" color="#FFFFFF">
						<th>Nome</th>
						<th>Matrícula</th>
						<th>Sala</th>
						<th>Checkin</th>
						<th>Checkout</th>
					</tr>
				</thead>
				<tbody>
					<?php
					include 'func/database.php';
					$pdo = Database::connect();
					$sql = 'SELECT * FROM access A INNER JOIN users U ON U.id = A.user_id ORDER BY A.checkin DESC';
					foreach ($pdo->query($sql) as $row) {
						echo '<tr>';
						echo '<td>' . $row['name'] . '</td>';
						echo '<td>' . $row['enrollment'] . '</td>';
						echo '<td>' . $row['room'] . '</td>';
						echo '<td>' . $row['checkin'] . '</td>';
						echo '<td>' . $row['checkout'] . '</td>';
						echo '</tr>';
					}
					Database::disconnect();
					?>
				</tbody>
			</table>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>