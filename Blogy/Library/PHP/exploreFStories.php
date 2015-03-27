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
	
	//Pull notifications
	$pullNotifications = fopen("../Authors/$sender/Messages/Notification.txt", "r") or die("Unable to pull.");
	$countNotifications = fread($pullNotifications, filesize("../Authors/$sender/Messages/Notification.txt"));
	fclose($pullNotifications);	
		
echo "
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<title>Stories of your friends</title>
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
				document.getElementById('reSend').action = '../PHP/exploreFStories.php';
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

	$followingCount = 0;
	$allPosts = array();
	$loadFollowing = fopen("../Authors/$sender/Following.txt", "r") or die("Unable to load Following.");
	while (!feof($loadFollowing)) {
		$line = trim(fgets($loadFollowing));
		if ($line != "") {
			$followingCount++;
			$postAuthor = $line;

			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			} else {
				$sql = "SELECT DATE, STACK, PUBLICVIEW FROM stack$postAuthor";
				$pick = $conn->query($sql);
				if ($pick->num_rows > 0) {
					while ($row = $pick->fetch_assoc()) {
						$postBuild = html_entity_decode($row['PUBLICVIEW']);
						$buildDate = $row['DATE'];
						
						$allPosts = array_merge($allPosts, array("$postBuild" => "$buildDate"));
					}
				}
			}
		}
	}
	fclose($loadFollowing);

	$conn->close();
	
	$storiesBack = (int)$_GET['storiesBack'];
	if ($followingCount == 0) {
		echo "<h1>You don't follow anybody :(<h1>";
	} else {
		arsort($allPosts);
		$postsToday = 0;
		if ($storiesBack == 0) {
			$storiesBack = 5;
		}
		
		$exploreStories = 0;
		
		foreach($allPosts as $key => $value) {
			if ($storiesBack <= 0) {
				break;
			}
			
			echo "$key";
			$storiesBack--;
		}
		
		/*
		$daysBack =$_GET['daysBack'];
		if (!isset($daysBack)) {
			$daysBack = 0;
		}
		foreach($allPosts as $key => $value) {
			$splitValue = explode(" ", $value);
			if ($splitValue[0] >= date("Y-m-d", strtotime("-$daysBack day"))) {
				buildPosts($key);
				$postsToday++;
			}
		}
		*/
		
		$scrollPos = $_GET['scrollPos'];
		if (!isset($scrollPos)) {
			$scrollPos = 0;
		}
		echo "
			<script>
				$(window).scrollTop($scrollPos);
			</script>
		";
	}
	
echo "</table>";

	if ((int)$_GET['storiesBack'] < count($allPosts)) {
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
/*
	if ($followingCount > 0) {
		$minDate = min(array_values($allPosts));
		$daysBack++;
		if ($followingCount > 0 && date("Y-m-d", strtotime("-$daysBack day")) > explode(" ", $minDate)[0]) {
			echo "<script>alert('1');</script>";
			if ($postsToday == 0) {
				echo "<h1>No body posts today :'(</h1>";
			}
			
			";
			
			if ($daysBack == 1) {
				$toDisplay = "day";
			} else {
				$toDisplay = "days";
			}
				
			echo "
						Show stories from $daysBack $toDisplay back
					</button>
					<form id='reSend' method='get' style='display: none;'>
						<input type='text' id='daysBack' name='daysBack' value='$daysBack'>
						<input type='text' id='scrollPos' name='scrollPos'>
					</form>
				</div>
			";
		}
	}
*/
	
echo "
			</div>
		</div>
	</body>
";
?>