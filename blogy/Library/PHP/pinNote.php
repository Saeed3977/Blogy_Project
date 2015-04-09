<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$getTitle = $_POST['noteTitle'];
	$getDate = $_POST['noteDate'];
	$getContent = trim($_POST['noteContent']);
	
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$stackOrder = array();
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "INSERT INTO notesOf$sender (NOTEID, NOTETEXT, NOTEDATE) VALUES ('$getTitle', '$getContent', '$getDate')";
		$conn->query($sql);
	}
	
	$conn->close();
	header('Location: loadNotes.php');
?>