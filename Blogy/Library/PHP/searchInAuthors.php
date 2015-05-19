<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$senderPic = $_SESSION['senderImg'];
	
	//Get blocked users

	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$blockedPersons = array();
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "SELECT BLOCKEDID FROM blockList$sender";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				array_push($blockedPersons, $row['BLOCKEDID']);
			}
		}
	}

	//Load authors
	$loadStack = fopen("../Authors/Info.csv", "r") or die("Unable to load stack.");
	$stack = array();
	while (!feof($loadStack)) {
		$line = fgetcsv($loadStack);
		if ($line != "" && !in_array($line[1], $blockedPersons)) {
			$blogerId = $line[1];
			$blockedPersonsByUser = array();
			$sql = "SELECT BLOCKEDID FROM blockList$blogerId";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					array_push($blockedPersonsByUser, $row['BLOCKEDID']);
				}
			}
		
			if ($line != "" && $line[1] != $sender && !in_array($sender, $blockedPersonsByUser)) array_push($stack, $line[1]);
		}
	}
	fclose($loadStack);
	
	$configStack = array();
	foreach ($stack as $author) {
		$pickUpCount = 0;
		$parseUser = fopen("../Authors/$author/config.txt", "r") or die("Unable to start parsing.");
		while (!feof($parseUser)) {
			$pickUpLine = trim(fgets($parseUser));
			if ($pickUpCount == 0) {
				$authorImg = $pickUpLine;
			}
			else
			if ($pickUpCount == 1) {
				$authorHref = $pickUpLine;
			}
			else
			if ($pickUpCount == 3) {
				$authorFN = $pickUpLine;
			}
			else
			if ($pickUpCount == 4) {
				$authorLN = $pickUpLine;
				break;
			}
			$pickUpCount++;
		}
		fclose($parseUser);

		array_push($configStack, "$authorFN#$authorLN#$author#$authorImg#$authorHref");
	}

	$reversed_stack = array_reverse($configStack);
	$bindFriends = implode(",", $reversed_stack);

echo "
		<html>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
				<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<title>Search in Authors</title>
				<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
				<link href='../../fonts.css' rel='stylesheet' type='text/css'>
				<script type='text/javascript' src='../../java.js'></script>
				<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
				<script type='text/javascript'>
					var pullFriends = '$bindFriends'.split(',');
					function checkInput() {
						searchFriends(pullFriends, 'searchInput', 'searchResults', 1);
					}
				</script>
			</head>
			<body>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "	
				<div id='sub-menu'>
					<div id='otherOption' class='left'>
						<a href='searchInFriends.php'>Friends</a>
					</div>
					<div id='currentRight'>
						<a href='searchInAuthors.php'>Authors</a>
					</div>
				</div>
				<div id='body'>
					<div id='searchContainer'>
						<input type='text' id='searchInput' placeholder='Search an author' onkeyup='checkInput()'></input>
						<div id='searchResults'>
						</div>
					</div>
				</div>
			</body>
		</html>	
";
?>