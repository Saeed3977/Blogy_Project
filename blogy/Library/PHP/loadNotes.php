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
	
	//Build UI
echo "
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<title>$profileFirst's notes</title>
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
					$('#datepicker').datepicker();
				});
			</script>
		</head>
		<body>
";
	include "loadMenu.php";
	include 'loadSuggestedBlogers.php';
echo "
			<div id='sub-logo'>
				<h1>Notes</h1>
			</div>
			<div id='notesOptions'>
				<button type='button' onclick='showNoteBuilder()' title='Pin a note'><img src='https://cdn1.iconfinder.com/data/icons/mirrored-twins-icon-set-hollow/512/PixelKit_point_marker_icon.png' title='Pin a note' /></button>
			</div>
			<div id='noteBuilder'>
				<button class='hideButton' onclick='hideNoteBuilder()'></button>
				<form id='noteForm' method='post'>
					<input type='text' id='noteTitle' name='noteTitle' placeholder='Give title of your note'>
					<input type='text' id='datepicker' name='noteDate' placeholder='Date of your note'>
					<textarea id='noteContent' name='noteContent' placeholder='What you have to do ?'></textarea>
					<button type='button' onclick='pinNote(0, \"\", \"\")'>Pin note</button>
				</form>
			</div>
			<div id='body'>
				<div id='notesContainer'>
";
	
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
		$sql = "CREATE TABLE notesOf$sender (ID int NOT NULL AUTO_INCREMENT, NOTEID LONGTEXT, NOTETEXT LONGTEXT, NOTEDATE LONGTEXT, PRIMARY KEY (ID))";
		if ($conn->query($sql) === TRUE) {}
		else {
			//Get content
			$sql = "SELECT NOTEID, NOTETEXT, NOTEDATE FROM notesOf$sender ORDER BY ID DESC";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$getTitle = $row['NOTEID'];
					$getContent = $row['NOTETEXT'];
					$getDate = $row['NOTEDATE'];
					
					echo "
						<div id='noteView'>
							<button type='button' title='Preview $getTitle' onclick='previewNote(\"$getTitle\", \"$getDate\")'>
								$getTitle - $getDate
							</button>
							<div id='noteOptions'>
								<button type='button' title='Delete note' onclick='deleteNote(\"$getTitle\")'>
									<img src='https://cdn3.iconfinder.com/data/icons/sympletts-free-sampler/128/circle-close-512.png' />
								</button>
							</div>
						</div>
					";
				}
			}
		}
	}
	$conn->close();
	
echo "
				</div>
			</div>
		</body>
	</html>
";
?>