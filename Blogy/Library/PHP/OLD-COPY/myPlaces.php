<?php
	$sender = $_COOKIE['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$senderPic = $_COOKIE['senderImg'];

//Build user places - If not builded yet
	$buildedPlaces = array();
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "CREATE TABLE placesOf$sender (ID int NOT NULL AUTO_INCREMENT, PLACEID LONGTEXT, PLACECORDS LONGTEXT, PLACESTORY LONGTEXT, TAGGED LONGTEXT, PRIMARY KEY (ID))";
		if ($conn->query($sql) === TRUE) {/*Successfully created table*/}

		//Load places
		$sql = "SELECT ID, PLACEID FROM placesOf$sender ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$placeIdNum = $row['ID'];
				$placeId = $row['PLACEID'];
				$build = "
					<div id='place'>
						<button type='button' class='placeName' onclick='previewPlace(\"$placeIdNum\")'>
							$placeId
						</button>
					</div>
				";
				array_push($buildedPlaces, $build);
			}
		}
	}
	$conn->close();

//Load friends
	$loadStack = fopen("../Authors/$sender/Following.txt", "r") or die("Unable to load stack.");
	$stack = array();
	while (!feof($loadStack)) {
		$line = trim(fgets($loadStack));
		if ($line != "") array_push($stack, $line);
	}
	fclose($loadStack);
	
	$configStack = array();
	foreach ($stack as $friend) {
		$pickUpCount = 0;
		$parseUser = fopen("../Authors/$friend/config.txt", "r") or die("Unable to start parsing.");
		while (!feof($parseUser)) {
			$pickUpLine = trim(fgets($parseUser));
			if ($pickUpCount == 0) {
				$friendImg = $pickUpLine;
			}
			else
			if ($pickUpCount == 1) {
				$friendHref = $pickUpLine;
			}
			else
			if ($pickUpCount == 3) {
				$friendFN = $pickUpLine;
			}
			else
			if ($pickUpCount == 4) {
				$friendLN = $pickUpLine;
				break;
			}
			$pickUpCount++;
		}
		fclose($parseUser);

		array_push($configStack, "$friendFN#$friendLN#$friend#$friendImg#$friendHref");
	}

	$reversed_stack = array_reverse($configStack);
	$bindFriends = implode(",", $reversed_stack);

echo "
		<html>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
				<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<title>My places</title>
				<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
				<link href='../../fonts.css' rel='stylesheet' type='text/css'>
				<script type='text/javascript' src='../../java.js'></script>
				<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
				<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
				<script src='http://maps.google.com/maps/api/js?sensor=false'></script>
				<script type='text/javascript'>
					var pullFriends = '$bindFriends'.split(',');
					function checkInput() {
						searchFriends(pullFriends, 'searchInput', 'searchResults', 2);
					}

					var taggedFriends = [];
				</script>
			</head>
			<body>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "	
				<div id='sub-menu'>
					<div id='currentLeft'>
						<a href='myPlaces.php'>My places</a>
					</div>
					<div id='otherOption' class='right'>
						<a href='explorePlaces.php'>All places</a>
					</div>
				</div>
				<div id='tagAPlace'>
					<button type='button' title='Current location' onclick='getLocation()'><img src='https://cdn2.iconfinder.com/data/icons/pittogrammi/142/93-512.png' /></button>
				</div>
				<div id='mapContainer'>
					<button type='button' class='tagPlace' title='Tag current location' onclick='chooseLocation()'><img src='https://cdn2.iconfinder.com/data/icons/pittogrammi/142/94-512.png' /></button>					
					<button type='button' class='hideButton' onclick='hideMap()'></button>
					
					<div id='placeInfoInput'>
						<div id='taggedFriends'>
						</div>
						<div id='container'>
							<input type='text' id='placeTitle' placeholder='How do you call this place ?'>
							<input type='text' placeholder='Who were with you ?' id='searchInput' onkeyup='checkInput()'>
							<div id='searchResults'>
							</div>
							<form id='placeStoryForm' method='post'>
								<textarea id='placeStory' name='storyText' placeholder='What is the story of this place ?'></textarea>
								<button type='button' onclick='tagPlace()'>Tag place</button>
							</form>
						</div>
					</div>
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