<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$senderPic = $_SESSION['senderImg'];

	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$buildedPlaces = array();
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//Load places
		$sql = "SELECT ID, PLACEID FROM worldPlaces ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$placeIdNum = $row['ID'];
				$placeId = $row['PLACEID'];
				$build = "
					<div id='place'>
						<button type='button' class='placeName' onclick='previewWorldPlace(\"$placeIdNum\", \"$sender\")'>
							$placeId
						</button>
					</div>
				";
				array_push($buildedPlaces, $build);
			}
		}
	}
	$conn->close();

echo "
		<html>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
				<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<title>All places</title>
				<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
				<link href='../../fonts.css' rel='stylesheet' type='text/css'>
				<script type='text/javascript' src='../../java.js'></script>
				<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
				<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
				<script src='http://maps.google.com/maps/api/js?sensor=false'></script>
				<script type='text/javascript'>
				</script>
			</head>
			<body>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "	
				<div id='sub-menu'>
					<div id='otherOption' class='left'>
						<a href='myPlaces.php'>My places</a>
					</div>
					<div id='currentRight'>
						<a href='explorePlaces.php'>All places</a>
					</div>
				</div>
				<div id='tagAPlace'>
					<button type='button' title='Current location' onclick='getLocation()'><img src='https://cdn2.iconfinder.com/data/icons/pittogrammi/142/93-512.png' /></button>
				</div>
				<div id='mapContainer'>					
					<button type='button' class='hideButton' onclick='hideMap()'></button>
					<div id='mapHolder'></div>
				</div>
				<div id='body'>
					<div id='placesContainer'>
";
	
	foreach ($buildedPlaces as $place) {
		echo $place;
	}

echo "
					</div>
				</div>
			</body>
		</html>	
";

#Scroll to point
	$getScrollPos = $_COOKIE['scrollToPos'];
	if (isset($getScrollPos)) {
		echo "
			<script>
				$(window).scrollTop($getScrollPos);
				document.cookie = 'scrollToPos=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			</script>
		";
	}
?>