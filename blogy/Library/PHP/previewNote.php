<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];
	
	$getId = $_COOKIE['noteId'];
	if (!isset($getId)) {
		echo "<script>window.location='loadNotes.php';</script>";
		echo "<script>document.cookie = 'noteDate=; expires=Thu, 01 Jan 1970 00:00:00 UTC';</script>";
	}
	$getDate = $_COOKIE['noteDate'];
	
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
		$sql = "SELECT NOTEID, NOTETEXT, NOTEDATE FROM notesOf$sender";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					if ($row['NOTEID'] == $getId && $row['NOTEDATE']) {
						$getContent = $row['NOTETEXT'];
					}
				}
			}
	}
	$conn->close();
	
	//Build UI
echo "
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<title>Edit: $getId</title>
			<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../fonts.css' rel='stylesheet' type='text/css'>		
			<script type='text/javascript' src='../../java.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
			
			<link href='../../LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
			<script src='../../LightBox/js/jquery-1.11.0.min.js'></script>
			<script src='../../LightBox/js/lightbox.min.js'></script>
				
			<link rel='stylesheet' href='//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css'>
			<script src='//code.jquery.com/jquery-1.10.2.js'></script>
			<script src='//code.jquery.com/ui/1.11.4/jquery-ui.js'></script>
			<link rel='stylesheet' href='/resources/demos/style.css'>
			<script>
				$(function() {
					$( '#datepicker' ).datepicker();
				});
			</script>
		</head>
		<body>
";
	include "loadMenu.php";
	include 'loadSuggestedBlogers.php';
echo "
	<div id='notePreview'>
		<form id='noteForm' method='post'>
			<input type='text' id='noteTitle' name='noteTitle' placeholder='Give title of your note' value='$getId'>
			<input type='text' id='datepicker' name='noteDate' placeholder='Date of your note' value='$getDate'>
			<textarea id='noteContent' name='noteContent' placeholder='What you have to do ?' value='$getContent'>$getContent</textarea>
			<button type='button' onclick='pinNote(1, \"$getId\", \"$getDate\")'>Update note</button>
		</form>
	</div>
";

	echo "<script>document.cookie = 'noteId=; expires=Thu, 01 Jan 1970 00:00:00 UTC';</script>";
	echo "<script>document.cookie = 'noteDate=; expires=Thu, 01 Jan 1970 00:00:00 UTC';</script>";
?>