<?php
     
    require 'database.php';
 
    if ( !empty($_POST)) {
        // keep track post values
		$tag = $_POST['tag'];
        $name = $_POST['name'];
		$enrollment = $_POST['enrollment'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        
		// insert data
        $pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO users (tag, name, enrollment, email, phone) values(?, ?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($tag,$name,$enrollment,$email,$phone));
		Database::disconnect();
		header("Location: ../users.php");
    }
?>