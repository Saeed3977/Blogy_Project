<?php
	session_start();
	$sender = $_SESSION['sender'];
	$profilePic = $_SESSION['senderImg'];

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
			<script type='text/javascript'>
				function logOut() {
					document.getElementById('post').action = 'LogOut.php';
					document.forms['post'].submit();
				}
				
				function readMessage(id) {
					document.getElementById(id).action = 'readMessage.php';
					document.forms[id].submit();
				}
			</script>
		</head>
		<body>
			<div id='menu'>
				<a href='logedIn.php' class='homeButton'><img src='$profilePic'></a>
";
	if ($countNotifications != "0") {
		echo "<a href='storeMessages.php' class='notification'>$countNotifications new</a>";
	}
	else
	if ($countNotifications == "0") {
		echo "<a href='storeMessages.php'>Messages</a>";
	}	
echo "
				<a href='openSettings.php'>Settings</a>
				<a href='loadBlogers.php'>Blogers</a>
				<a href='exploreFStories.php'>Stories</a>
				<a href='#' onclick='logOut()'>Log out</a>
			</div>
			
			<form id='accountInfo' method='post' style='display: none;'>
				<input type='text' name='sender' value='$sender'></input>
				<input type='text' id='cmd' name='cmd'></input>
			</form>
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
	
	$stack_reverse = $stack;
	while ($line_count < count($stack_reverse)) {
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
			<a href='#' onclick='readMessage(\"$messangerId\")'>
				<img src='$messengerImg' alt='Bad image link :'(' />
			</a>
			<form id='$messangerId' method='post' style='display: none;'>
				<input type='text' name='sender' value='$sender'></value>
				<input type='text' name='messangerId' value='$messangerId'></input>
			</form>
			<br>
		";
		
		echo "$buildMessage";
		
		$line_count++;
	}
	
echo "
				</div>
			</div>
		</body>
	</html>
";
?>