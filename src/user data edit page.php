<?php
require 'database.php';
$id = null;
if (!empty($_GET['id'])) {
	$id = $_REQUEST['id'];
}

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM teste where id = ?";
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

	<h2 align="center">IFGAccess</h2>

	<div class="container">

		<div class="center" style="margin: 0 auto; width:495px; border-style: solid; border-color: #f2f2f2;">
			<div class="row">
				<h3 align="center">Edit User Data</h3>
				<p id="defaultGender" hidden><?php echo $data['gender']; ?></p>
			</div>

			<form class="form-horizontal" action="user data edit tb.php?id=<?php echo $id ?>" method="post">
				<div class="control-group">
					<label class="control-label">ID</label>
					<div class="controls">
						<input name="id" type="text" placeholder="" value="<?php echo $data['id']; ?>" readonly>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Name</label>
					<div class="controls">
						<input name="name" type="text" placeholder="" value="<?php echo $data['name']; ?>" required>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Gender</label>
					<div class="controls">
						<select name="gender" id="mySelect">
							<option value="Male">Male</option>
							<option value="Female">Female</option>
						</select>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Email Address</label>
					<div class="controls">
						<input name="email" type="text" placeholder="" value="<?php echo $data['email']; ?>" required>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Mobile Number</label>
					<div class="controls">
						<input name="mobile" type="text" placeholder="" value="<?php echo $data['mobile']; ?>" required>
					</div>
				</div>

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Update</button>
					<a class="btn" href="user data.php">Back</a>
				</div>
			</form>
		</div>
	</div> <!-- /container -->

	<script>
		var g = document.getElementById("defaultGender").innerHTML;
		if (g == "Male") {
			document.getElementById("mySelect").selectedIndex = "0";
		} else {
			document.getElementById("mySelect").selectedIndex = "1";
		}
	</script>
</body>

</html>