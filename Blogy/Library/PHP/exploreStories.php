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

	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	include "loadStories.php";

	$countStories = 0;
	$storePosts = array();
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "SELECT ID, AuthorTitle, LINK, POST FROM worldStories ORDER BY ID";
		$result = mysqli_query($conn, $sql);
		$ROWS = mysqli_num_rows($result);
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getId = $row["ID"];
				array_push($storePosts, $getId);

				$countStories++;
			}
		}
	}
	$conn->close();

	$storePosts = implode(",", $storePosts);

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

		<script src='../../jQuery/lib/noframework.waypoints.js'></script>
		<script src='../../jQuery/lib/noframework.waypoints.min.js'></script>
		<script>
			var waypoint = new Waypoint({
			element: document.getElementById('moreStories'),
			handler: function() {
					alert(1)
				}
			})
		</script>
		
		<script type = 'text/javascript'> 			
			function loadAll() {		
				document.cookie='scrollToPos='+$(window).scrollTop();
				document.getElementById('reSend').action = '../PHP/exploreStories.php';
				document.forms['reSend'].submit();
			}
		</script>

		<script>
			var container = [];
			var loops = parseInt('$countStories');
			var dinamic = loops;

			function callBack() { //Loads stories for explorer
				dinamic -= 5;
				loops--;
				loadExplorerStories(\"$storePosts\", \"1\");
			}

			var flag = 0;
		</script>
	</head>
	<body onload='loadExplorerStories(\"$storePosts\", \"1\");' onscroll='checkPos()'>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "	
		<div id='sub-menu'>
			<div id='otherOption' class='left'>
				<a href='exploreFStories.php'>Following</a>
			</div>
			<div id='currentRight'>
				<a href='exploreStories.php'>Worldwide</a>
			</div>
		</div>
		<div id='body'>
			<table id='main-table'>
			</table>
		</div>
	</body>
";

?>