<?php

require 'database.php';
date_default_timezone_set('America/Sao_Paulo');

$t = time();
$dateNow = date("Y-m-d H:i:s", $t);

$tag = null;
if (!empty($_POST['tagResult'])) {
	$tag = $_REQUEST['tagResult'];
	$room = $_REQUEST['room'];
}

$Write = "<?php $" . "tagResult='" . $tag . "'; " . "echo  $" . "tagResult;" . " ?>";
file_put_contents('../tagContainer.php', $Write);

Database::connect();
$userResult = Database::query("SELECT * FROM users where tag = '$tag'");

// A tag não possui cadastro
if (empty($userResult)) {
	echo "NOK";
	Database::disconnect();
	return;
}

$userId = $userResult[0]['id'];

$checkinAnotherUserResult = Database::query("SELECT * FROM access where room = '$room' AND user_id != '$userId' AND checkout is NULL ORDER BY checkin DESC");
// Já possui um checkin de outro usuário
if (!empty($checkinAnotherUserResult)) {
	echo "Wait for another user to checkout";
	return;
}

$checkinResult = Database::query("SELECT * FROM access where user_id = '$userId' AND checkout is NULL ORDER BY checkin DESC");

// A tag possui cadastro e não possui checkin pedente de checkout
if (empty($checkinResult)) {
	echo "Checkin";

	$dataCheckIn = [
		'user_id' => $userId,
		'checkin' => $dateNow,
		'room' => $room,
	];

	Database::create("access", $dataCheckIn);
	Database::disconnect();
	return;
}

// A tag possui cadastro e checkin pedente de checkout
echo "Checkout";
$dataCheckout = [
	'checkout' => $dateNow,
];

Database::update("access", $dataCheckout, ['id', $checkinResult[0]['id']]);
Database::disconnect();
