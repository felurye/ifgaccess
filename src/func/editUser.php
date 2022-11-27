<?php
    require 'database.php';
 
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    if ( !empty($_POST)) {
        // keep track post values
        $name = $_POST['name'];
        $tag = $_POST['tag'];
		$enrollment = $_POST['enrollment'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
         
        $pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "UPDATE users set name = ?, enrollment =?, email =?, phone =? WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($name,$enrollment,$email,$phone,$id));
		Database::disconnect();
		header("Location: ../users.php");
    }
