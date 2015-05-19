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
	
	//Pull notifications
	$pullNotifications = fopen("../Authors/$sender/Messages/Notification.txt", "r") or die("Unable to pull.");
	$countNotifications = fread($pullNotifications, filesize("../Authors/$sender/Messages/Notification.txt"));
	fclose($pullNotifications);	

	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";

	include "loadStories.php";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

	$followingCount = 0;
	$allPosts = array();
	$loadFollowing = fopen("../Authors/$sender/Following.txt", "r") or die("Unable to load Following.");
	while (!feof($loadFollowing)) {
		$line = trim(fgets($loadFollowing));
		if ($line != "") {
			$followingCount++;
			$postAuthor = $line;

			$lineCount = 0;
			$pullInfo = fopen("../Authors/$postAuthor/config.txt", "r") or die("Fatal: Could not load.");
			while (!feof($pullInfo)) {
				$line = trim(fgets($pullInfo));
				if ($line != "") {
					if ($lineCount == 0) {
						$authorImg = $line;
					}
					else
					if ($lineCount == 1) {
						$authorHref = $line;
					}
					else
					if ($lineCount == 3) {
						$authorFN = $line;
					}
					else
					if ($lineCount == 4) {
						$authorLN = $line;
						break;
					}
				}
				$lineCount++;
			}
			fclose($pullInfo);

			$author = array("$postAuthor", "$authorImg", "$authorHref", "$authorFN", "$authorLN");
			$author = implode("#", $author);

			$sql = "SELECT ID, DATETIME, STORYTITLE, STORYLINK, STORYCONTENT FROM stack$postAuthor";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$getId = $row["ID"];
					$getDate = $row["DATETIME"];

					$getBuild = "$getId@$author";
					$allPosts = array_merge($allPosts, array("$getBuild" => "$getDate"));
				}
			}
		}
	}
	fclose($loadFollowing);

	$conn->close();

	if ($followingCount == 0) {
		$printMessage = "<h1>You don't follow anybody :(<h1>";
	} else {
		arsort($allPosts);
		$allPosts = array_reverse($allPosts);
		$reCatchAllPosts = array();
		$countStories = 0;
		foreach ($allPosts as $key => $value) {
			$countStories++;
			array_push($reCatchAllPosts, $key);
		}
		$reCatchAllPosts = implode(",", $reCatchAllPosts);
	}
		
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
	
		<script>
			var container = [];
			var loops = parseInt('$countStories');
			var dinamic = loops;
			
			function callBack() {
				dinamic -= 5;
				loops--;
				loadExplorerStories(\"$reCatchAllPosts\", \"0\");
			}

			var flag = 0;
		</script>
	</head>
	<body onload='loadExplorerStories(\"$reCatchAllPosts\", \"0\");' onscroll='checkPos()'>
		
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "	
		<div id='sub-menu'>
			<div id='currentLeft'>
				<a href='exploreFStories.php'>Following</a>
			</div>
			<div id='otherOption' class='right'>
				<a href='exploreStories.php'>Worldwide</a>
			</div>
		</div>
		<div id='body'>
			<table id='main-table'>
				$printMessage
			</table>
		</div>
	</body>
";

?>