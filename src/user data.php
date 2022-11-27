<?php
$Write = "<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
file_put_contents('UIDContainer.php', $Write);
?>

<!DOCTYPE html>
<html lang="pt-br">
<html>

<head>
	<?php include_once 'inc/head.html'; ?>
	<title>User Data : IFGAccess</title>
</head>

<body>
	<h2 align="center">IFGAccess</h2>
	<ul class="topnav">
		<li><a href="index.php">Home</a></li>
		<li><a class="active" href="user data.php">User Data</a></li>
		<li><a href="registration.php">Registration</a></li>
		<li><a href="read tag.php">Read Tag ID</a></li>
	</ul>
	<br>
	<div class="container">
		<div class="row">
			<h3 align="center">User Data Table</h3>
		</div>
		<div class="row">
			<table class="table table-striped table-bordered">
				<thead>
					<tr bgcolor="#10a0c5" color="#FFFFFF">
						<th>Name</th>
						<th>ID</th>
						<th>Gender</th>
						<th>Email</th>
						<th>Mobile Number</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					include 'database.php';
					$pdo = Database::connect();
					$sql = 'SELECT * FROM teste ORDER BY name ASC';
					foreach ($pdo->query($sql) as $row) {
						echo '<tr>';
						echo '<td>' . $row['name'] . '</td>';
						echo '<td>' . $row['id'] . '</td>';
						echo '<td>' . $row['gender'] . '</td>';
						echo '<td>' . $row['email'] . '</td>';
						echo '<td>' . $row['mobile'] . '</td>';
						echo '<td><a class="btn btn-success" href="user data edit page.php?id=' . $row['id'] . '">Edit</a>';
						echo ' ';
						echo '<a class="btn btn-danger" href="user data delete page.php?id=' . $row['id'] . '">Delete</a>';
						echo '</td>';
						echo '</tr>';
					}
					Database::disconnect();
					?>
				</tbody>
			</table>
		</div>
	</div>
</body>

</html>