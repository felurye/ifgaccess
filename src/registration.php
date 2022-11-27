<?php
$Write = "<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
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
	<title>Registration : IFGAccess</title>
</head>

<body>

	<h2 align="center">IFGAccess</h2>
	<ul class="topnav">
		<li><a href="index.php">Home</a></li>
		<li><a href="user data.php">User Data</a></li>
		<li><a class="active" href="registration.php">Registration</a></li>
		<li><a href="read tag.php">Read Tag ID</a></li>
	</ul>

	<div class="container">
		<br>
		<div class="center" style="margin: 0 auto; width:495px; border-style: solid; border-color: #f2f2f2;">
			<div class="row">
				<h3 align="center">Registration Form</h3>
			</div>
			<br>
			<form class="form-horizontal" action="insertDB.php" method="post">
				<div class="control-group">
					<label class="control-label">ID</label>
					<div class="controls">
						<textarea name="id" id="getUID" placeholder="Please Tag your Card / Key Chain to display ID" rows="1" cols="1" required></textarea>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Name</label>
					<div class="controls">
						<input id="div_refresh" name="name" type="text" placeholder="" required>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Gender</label>
					<div class="controls">
						<select name="gender">
							<option value="Male">Male</option>
							<option value="Female">Female</option>
						</select>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Email Address</label>
					<div class="controls">
						<input name="email" type="text" placeholder="" required>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Mobile Number</label>
					<div class="controls">
						<input name="mobile" type="text" placeholder="" required>
					</div>
				</div>

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Save</button>
				</div>
			</form>

		</div>
	</div> <!-- /container -->
</body>

</html>