<?php

require 'database.php';

date_default_timezone_set('America/Sao_Paulo');

$tag = null;
if (!empty($_GET['UIDresult'])) {
	$tag = $_REQUEST['UIDresult'];
	$room = $_REQUEST['room'];
}

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM users where tag = ?";
$q = $pdo->prepare($sql);
$q->execute(array($tag));
$data = $q->fetch(PDO::FETCH_ASSOC);


if (empty($data)) {
	echo "A tag não possui vinculo com nenhum usuário.";
	Database::disconnect();
	return;
}


$userId = $data['id'];
echo "Tag cadastrada. UserId= " . $userId . "\n";

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM access where user_id = ? AND checkout is NULL ORDER BY checkin DESC";
$q = $pdo->prepare($sql);
$q->execute(array($userId));
$dataAccess = $q->fetch(PDO::FETCH_ASSOC);

$t = time();
$dateNow = date("Y-m-d H:i:s", $t);

if (empty($dataAccess)) {
	echo "Registrando novo acesso.\n";
	$sql = "INSERT INTO access (user_id, checkin, room) values(?, ?, ?)";
	$q = $pdo->prepare($sql);
	$q->execute(array($userId, $dateNow, $room));
	Database::disconnect();
	return;
}

echo "Um acesso em aberto foi encontrado. Registrando checkout. AccessId = " . $dataAccess['id'] . ".\n";
$sql = "UPDATE access set checkout = ? where id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($dateNow, $dataAccess['id']));
Database::disconnect();