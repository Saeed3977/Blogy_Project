<?php
	session_start();
	$sender = $_SESSION['sender'];
	
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "DROP TABLE pushTable$sender";
		$conn->query($sql);
		
		$pageId = $_COOKIE['pageId'];
		header("Location: $pageId");
	}
?>