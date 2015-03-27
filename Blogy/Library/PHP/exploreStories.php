<?php
	error_reporting(0);

	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];

echo "
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<title>Worldwide stories</title>
		<link href='../../style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../fonts.css' rel='stylesheet' type='text/css'>
		<script type='text/javascript' src='../../java.js'></script>
		<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
		
		<link href='../../LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
		<script src='../../LightBox/js/jquery-1.11.0.min.js'></script>
		<script src='../../LightBox/js/lightbox.min.js'></script>
		
		<script type = 'text/javascript'> 			
			function loadAll() {		
				document.getElementById('scrollPos').value = $(window).scrollTop();
				document.getElementById('reSend').action = '../PHP/exploreStories.php';
				document.forms['reSend'].submit();
			}
		</script>
	</head>
	<body>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "	
		<div id='sub-logo'>
			<a href='exploreFStories.php'>Following</a>
			or
			<a href='exploreStories.php'>Worldwide</a>
		</div>
		<div id='body'>
			<table id='main-table'>
			<br>
";

	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$storiesBack = (int)$_GET['storiesBack'];
	if ($storiesBack == 0 || !isset($_GET['storiesBack'])) {
		$storiesBack = 5;
	}
	
	$allPosts = array();
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "SELECT POST FROM worldStories ORDER BY ID DESC";
		$result = mysqli_query($conn, $sql);
		$ROWS = mysqli_num_rows($result);
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				if ($storiesBack > 0) {
					array_push($allPosts, html_entity_decode($row['POST']));
					$storiesBack--;
				}
			}
		}
	}
	$conn->close();
	
	foreach ($allPosts as $post) {
		echo $post;
	}
	
	$scrollPos = $_GET['scrollPos'];
	if (!isset($scrollPos)) {
		$scrollPos = 0;
	}
	echo "
		<script>
			$(window).scrollTop($scrollPos);
		</script>
	";
	
echo "</table>";

	if ((int)$_GET['storiesBack'] < $ROWS) {
		if ((int)$_GET['storiesBack'] > 0) {
			$storiesBack = (int)$_GET['storiesBack'];
		} else {
			$storiesBack = 5;
		}
		$storiesBack += 5;
		echo "
			<div id='moreStories'>
				<button type='button' onclick='loadAll()'>	
					Show more stories
				</button>
				<form id='reSend' method='get' style='display: none;'>
					<input type='text' id='daysBack' name='storiesBack' value='$storiesBack'>
					<input type='text' id='scrollPos' name='scrollPos'>
				</form>
			</div>
		";
	}

echo "
		</div>
	</body>
";
?>