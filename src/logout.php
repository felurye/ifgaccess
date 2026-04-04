<?php
require_once 'func/auth.php';
logout();
header('Location: login.php');
exit;
