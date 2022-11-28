<?php
$Write = "<?php $" . "UIDresult='" . $UIDresult . "'; " . "$" . "room='" . $room . "'; " . "echo $" . "room;" . "echo $" . "UIDresult;" . " ?>";
file_put_contents('UIDContainer.php', $Write);
?>

<!DOCTYPE html>
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
	<title>Read Tag : IFGAccess</title>
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
					<li class="nav-item"><a class="nav-link" href="registration.php">Cadastrar</a></li>
					<li class="nav-item"><a class="nav-link active" href="readTag.php">Consultar</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<br>

	<h3 align="center" id="blink">Por favor, aproxime a tag/cartão do leitor</h3>

	<p id="getUID" hidden></p>

	<br>

	<div id="show_user_data">
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
								<td align="left">--------</td>
							</tr>
							<tr bgcolor="#f2f2f2">
								<td align="left" class="lf">Name</td>
								<td style="font-weight:bold">:</td>
								<td align="left">--------</td>
							</tr>
							<tr>
								<td align="left" class="lf">Matrícula</td>
								<td style="font-weight:bold">:</td>
								<td align="left">--------</td>
							</tr>
							<tr bgcolor="#f2f2f2">
								<td align="left" class="lf">Email</td>
								<td style="font-weight:bold">:</td>
								<td align="left">--------</td>
							</tr>
							<tr>
								<td align="left" class="lf">Telefone</td>
								<td style="font-weight:bold">:</td>
								<td align="left">--------</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	</div>

	<script>
		var myVar = setInterval(myTimer, 1000);
		var myVar1 = setInterval(myTimer1, 1000);
		var oldID = "";
		clearInterval(myVar1);

		function myTimer() {
			var getID = document.getElementById("getUID").innerHTML;
			oldID = getID;
			if (getID != "") {
				myVar1 = setInterval(myTimer1, 500);
				showUser(getID);
				clearInterval(myVar);
			}
		}

		function myTimer1() {
			var getID = document.getElementById("getUID").innerHTML;
			if (oldID != getID) {
				myVar = setInterval(myTimer, 500);
				clearInterval(myVar1);
			}
		}

		function showUser(str) {
			if (str == "") {
				document.getElementById("show_user_data").innerHTML = "";
				return;
			} else {
				if (window.XMLHttpRequest) {
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("show_user_data").innerHTML = this.responseText;
					}
				};
				xmlhttp.open("GET", "readTagData.php?tag=" + str, true);
				xmlhttp.send();
			}
		}

		var blink = document.getElementById('blink');
		setInterval(function() {
			blink.style.opacity = (blink.style.opacity == 0 ? 1 : 0);
		}, 750);
	</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>