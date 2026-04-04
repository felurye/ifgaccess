<?php
require_once 'lib/Auth.php';
logout();
header('Location: login.php');
exit;
