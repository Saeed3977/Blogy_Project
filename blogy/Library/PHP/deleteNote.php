<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$noteId = $_COOKIE['noteId'];
	if (!isset($noteId)) {
		echo "<script>window.location='loadNotes.php'</script>";
	}
	
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
		$sql = "DELETE FROM notesOf$sender WHERE NOTEID='$noteId'";
		$conn->query($sql);
	}
	$conn->close();
	
	echo "<script>document.cookie = 'noteId=; expires=Thu, 01 Jan 1970 00:00:00 UTC';</script>";
	echo "<script>window.location='loadNotes.php'</script>";
?>