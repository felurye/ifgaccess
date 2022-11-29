<?php
$tagResult = $_POST['tagResult'];

$Write = "<?php $" . "tagResult='" . $tagResult . "'; " . "echo  $" . "tagResult;" . " ?>";
file_put_contents('../tagContainer.php', $Write);
