<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$getOldId = $_COOKIE['oldNoteId'];
	if (!isset($getOldId)) {
		echo "<script>window.location='loadNotes.php';</script>";
		echo "<script>document.cookie = 'oldNoteDate=; expires=Thu, 01 Jan 1970 00:00:00 UTC';</script>";
	}
	$getOldDate = $_COOKIE['oldNoteDate'];
	
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
		$sql = "UPDATE notesOf$sender SET NOTEID='$getTitle', NOTETEXT='$getContent', NOTEDATE='$getDate' WHERE NOTEID='$getOldId' AND NOTEDATE='$getOldDate'";
		$conn->query($sql);
	}
	$conn->close();
	
	echo "<script>document.cookie = 'oldNoteId=; expires=Thu, 01 Jan 1970 00:00:00 UTC';</script>";
	echo "<script>document.cookie = 'oldNoteDate=; expires=Thu, 01 Jan 1970 00:00:00 UTC';</script>";
	echo "<script>window.location='loadNotes.php';</script>";
?>