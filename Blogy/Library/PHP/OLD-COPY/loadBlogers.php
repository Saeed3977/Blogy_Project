<?php
	$sender = $_COOKIE['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$senderPic = $_SESSION['senderImg'];
	
	//Load authors
	$stack = array();
	$loadStack = fopen("../Authors/Info.csv", "r") or die("Unable to load stack.");
	while (!feof($loadStack)) {
		$line = fgetcsv($loadStack);
		array_push($stack, trim($line[1]));
	}
	fclose($loadStack);
	
	$reversed_stack = array_reverse($stack);

echo "
		<html>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
				<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<title>Authors</title>
				<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
				<link href='../../fonts.css' rel='stylesheet' type='text/css'>
				<script type='text/javascript' src='../../java.js'></script>
				<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
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
						<a href='loadFriends.php'>Friends</a>
					</div>
					<div id='currentRight'>
						<a href='exploreStories.php'>Authors</a>
					</div>
				</div>
				<div id='body'>
					<div id='blogers-list'>
";

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

	$count = 0;
	while($count < count($reversed_stack)) {
		$blockedPersonsByFollower = array();
		$blockerId = $reversed_stack[$count];
		$sql = "SELECT BLOCKEDID FROM blockList$blockerId";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				array_push($blockedPersonsByFollower, $row['BLOCKEDID']);
			}
		}
		
		if (!in_array($reversed_stack[$count], $blockedPersons) && !in_array($sender, $blockedPersonsByFollower)) {
			$lines_count = 0;
			$authorId = $reversed_stack[$count];
			if ($authorId != "" && $authorId != $sender) {
				$author = fopen("../Authors/$authorId/config.txt", "r") or die("Unable to open author.");
				while (!feof($author)) {
					$line = fgets($author);
					if ($lines_count == 0) {
						$authorImg = trim($line);
					}
					if ($lines_count == 1) {
						$authorHref = trim($line);
					}
					else
					if ($lines_count == 3) {
						$authorFN = trim($line);
					}
					else
					if ($lines_count == 4) {
						$authorLN = trim($line);
						break;
					}
					$lines_count++;
				}
				fclose($author);
				
				$loadComplete = "
					<a href='openBloger.php' onclick=\"openBloger('$authorId')\">
						<img src='$authorImg' />
						$authorFN $authorLN
						<form id='$authorId' method='post' style='display: none;'>
							<input type='text' name='blogSender' value='$authorId'></input>
							<input type='text' name='blogerFN' value='$authorFN'></input>
							<input type='text' name='blogerLN' value='$authorLN'></input>
							<input type='text' name='blogerImg' value='$authorImg'></input>
							<input type='text' name='blogerHref' value='$authorHref'></input>
						</form>
					</a>
					<br>
				";	
				echo "$loadComplete";
			}
		}
		$count++;
	}

	$conn->close(); //Close SQL connection

echo "
					</div>
				</div>
			</body>
		</html>	
";
?>