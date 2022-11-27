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

$msg = null;
if (null == $data['name']) {
	$msg = "The ID of your Card / KeyChain is not registered !!!";
	$data['id'] = $id;
	$data['name'] = "--------";
	$data['gender'] = "--------";
	$data['email'] = "--------";
	$data['mobile'] = "--------";
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
			<table width="452" border="1" bordercolor="#10a0c5" align="center" cellpadding="0" cellspacing="1" bgcolor="#000" style="padding: 2px">
				<tr>
					<td height="40" align="center" bgcolor="#10a0c5">
						<font color="#FFFFFF">
							<b>User Data</b>
						</font>
					</td>
				</tr>
				<tr>
					<td bgcolor="#f9f9f9">
						<table width="452" border="0" align="center" cellpadding="5" cellspacing="0">
							<tr>
								<td width="113" align="left" class="lf">ID</td>
								<td style="font-weight:bold">:</td>
								<td align="left"><?php echo $data['id']; ?></td>
							</tr>
							<tr bgcolor="#f2f2f2">
								<td align="left" class="lf">Name</td>
								<td style="font-weight:bold">:</td>
								<td align="left"><?php echo $data['name']; ?></td>
							</tr>
							<tr>
								<td align="left" class="lf">Gender</td>
								<td style="font-weight:bold">:</td>
								<td align="left"><?php echo $data['gender']; ?></td>
							</tr>
							<tr bgcolor="#f2f2f2">
								<td align="left" class="lf">Email</td>
								<td style="font-weight:bold">:</td>
								<td align="left"><?php echo $data['email']; ?></td>
							</tr>
							<tr>
								<td align="left" class="lf">Mobile Number</td>
								<td style="font-weight:bold">:</td>
								<td align="left"><?php echo $data['mobile']; ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<p align="center" style="color:red;"><?php echo $msg; ?></p>
</body>

</html>