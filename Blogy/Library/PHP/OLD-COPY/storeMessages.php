<?php
	$sender = $_COOKIE['sender'];
	$profilePic = $_COOKIE['senderImg'];

	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$fullName = $sender;
	
	//Pull notifications
	$pullNotifications = fopen("../Authors/$sender/Messages/Notification.txt", "r") or die("Unable to pull.");
	$countNotifications = fread($pullNotifications, filesize("../Authors/$sender/Messages/Notification.txt"));
	fclose($pullNotifications);
	
echo "
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<title>Messages</title>
			<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../fonts.css' rel='stylesheet' type='text/css'>
			<script type='text/javascript' src='../../java.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
			<script type='text/javascript'>
				function readMessage(id) {
					document.cookie = \"receiverId=\"+id;
					window.location=\"readMessage.php\";
				}
			</script>
		</head>
		<body>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "
			<form id='post' method='post' style='display: none;'>
				<input name='sender' value='$sender'></input>
			</form>
			
			<div id='sub-logo'>
				<h1>Messages</h1>
			</div>
			<div id='body'>
				<div id='messages'>
";

	$line_count = 0;
	$stack = array();
	$loadStack = fopen("../Authors/$sender/Messages/Stack.txt", "r") or die("Unable to load stack.");
	while (!feof($loadStack)) {
		$line = fgets($loadStack);
		if ($line != "") {
			array_push($stack, trim($line));
		}
		$line_count++;
	}
	fclose($loadStack);
	$line_count = 0;
	
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
	//$conn->close();
	
	$stack_reverse = $stack;
	while ($line_count < count($stack_reverse)) {
		$blockedPersonsByFollower = array();
		$blockerId = $stack_reverse[$line_count];
		$sql = "SELECT BLOCKEDID FROM blockList$blockerId";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				array_push($blockedPersonsByFollower, $row['BLOCKEDID']);
			}
		}
		
		if (!in_array($stack_reverse[$line_count], $blockedPersons) && !in_array($sender, $blockedPersonsByFollower)) {
			$file_count = 0;
			$parseMessenger = fopen("../Authors/$stack_reverse[$line_count]/config.txt", "r") or die("Unable to parse.");
			while (!feof($parseMessenger)) {
				$line = fgets($parseMessenger);
				if ($file_count == 0) {
					$messengerImg = trim($line);
				}
				else
				if ($file_count == 2) {
					$messangerId = trim($line);
				}
				$file_count++;
			}
			fclose($parseMessenger);
			
			$buildMessage = "
				<button type='button' onclick='readMessage(\"$messangerId\")'>
					<img src='$messengerImg' alt='Bad image link :'(' />
				</button>
				<form id='messages$messangerId' method='post' style='display: none;'>
					<input type='text' name='messangerId' value='$messangerId'></input>
				</form>
				<br>
			";
			
			echo "$buildMessage";
		}
		
		$line_count++;
	}
	
	$conn->close(); //Close SQL connection
	
echo "
				</div>
			</div>
		</body>
	</html>
";
?>