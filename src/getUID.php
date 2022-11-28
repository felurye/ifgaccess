<?php
$UIDresult = $_POST['UIDresult'];
$room = $_POST['room'];

$Write = "<?php $" . "UIDresult='" . $UIDresult . "'; " . "$" . "room='" . $room . "'; " . "echo  $" . "UIDresult;" . " ?>";
file_put_contents('UIDContainer.php', $Write);
