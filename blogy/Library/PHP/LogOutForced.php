<?php
	session_start();
	$sender = $_POST['sender'];

	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//Delate old TABLE
		$sql = "DROP TABLE $sender";
		$conn->query($sql);
	}
	$conn->close();
?>