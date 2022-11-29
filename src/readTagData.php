<?php
require 'func/database.php';
$tag = null;
if (!empty($_GET['tag'])) {
	$tag = $_REQUEST['tag'];
}

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM users where tag = ?";
$q = $pdo->prepare($sql);
$q->execute(array($tag));
$data = $q->fetch(PDO::FETCH_ASSOC);
Database::disconnect();

$msg = null;
if (null == $data['name']) {
	$msg = "A tag aproximada não está cadastrada!!!";
	$data['tag'] = $tag;
	$data['name'] = "--------";
	$data['enrollment'] = "--------";
	$data['email'] = "--------";
	$data['phone'] = "--------";
} else {
	$msg = null;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<?php include_once 'inc/head.html'; ?>
</head>

<body>
	<div>
		<form>
			<table width="452" border="1" bordercolor="#157347" align="center" cellpadding="0" cellspacing="1" bgcolor="#000" style="padding: 2px">
				<tr>
					<td height="40" align="center" bgcolor="#157347">
						<font color="#FFFFFF">
							<b>User Data</b>
						</font>
					</td>
				</tr>
				<tr>
					<td bgcolor="#f9f9f9">
						<table width="452" border="0" align="center" cellpadding="5" cellspacing="0">
							<tr>
								<td width="113" align="left" class="lf">Tag</td>
								<td style="font-weight:bold">:</td>
								<td align="left"><?php echo $data['tag']; ?></td>
							</tr>
							<tr bgcolor="#f2f2f2">
								<td align="left" class="lf">Name</td>
								<td style="font-weight:bold">:</td>
								<td align="left"><?php echo $data['name']; ?></td>
							</tr>
							<tr>
								<td align="left" class="lf">Matrícula</td>
								<td style="font-weight:bold">:</td>
								<td align="left"><?php echo $data['enrollment']; ?></td>
							</tr>
							<tr bgcolor="#f2f2f2">
								<td align="left" class="lf">Email</td>
								<td style="font-weight:bold">:</td>
								<td align="left"><?php echo $data['email']; ?></td>
							</tr>
							<tr>
								<td align="left" class="lf">Telefone</td>
								<td style="font-weight:bold">:</td>
								<td align="left"><?php echo $data['phone']; ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<p align="center" style="color:red;"><?php echo $msg; ?></p>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>