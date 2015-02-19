<?php
	$sender = $_POST['sender'];
	
	$loadSender = fopen("../Authors/$sender/config.txt", "r") or die("Unable to load sender.");
	$senderPic = trim(fgets($loadSender));
	fclose($loadSender);
	
	$cmd = $_POST['cmd'];
	
	if ($cmd == "1") {
		$commitNotifications = fopen("../Authors/$sender/Messages/Notification.txt", "w") or die("Unable to pull");
		fwrite($commitNotifications, "0");
		fclose($commitNotifications);
	}
	
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
				function loadBlogers() {
						document.getElementById('post').action = 'loadBlogers.php';
						document.forms['post'].submit();
					}

				function logOut() {
					document.getElementById('post').action = 'LogOut.php';
					document.forms['post'].submit();
				}
				
				function openBloger(title) {
					document.getElementById(title).action = 'openBloger.php';
					document.forms[title].submit();
				}
				
				function readMessage(id) {
					document.getElementById(id).action = 'readMessage.php';
					document.forms[id].submit();
				}
				
				function openMessages(state) {
					if (state == 0) {
						document.getElementById('cmd').value = '0';
						document.getElementById('accountInfo').action = '../PHP/storeMessages.php';
						document.forms['accountInfo'].submit();
					}
					else
					if (state == 1) {
						document.getElementById('cmd').value = '1';
						document.getElementById('accountInfo').action = '../PHP/storeMessages.php';
						document.forms['accountInfo'].submit();
					}
				}
				
				function exploreStories() {
					document.getElementById('accountInfo').action = '../PHP/exploreFStories.php';
					document.forms['accountInfo'].submit();
				}
			</script>
		</head>
		<body>
			<div id='menu'>
				<a href='#' onclick='returnToHome()' class='homeButton'><img src='$senderPic'></a>
";
	if ($countNotifications != "0") {
		echo "<a href='#' onclick='openMessages(1)' class='notification'>$countNotifications new</a>";
	}
	else
	if ($countNotifications == "0") {
		echo "<a href='#' onclick='openMessages(0)'>Messages</a>";
	}	
echo "
				<a href='#' onclick='openSettings()'>Settings</a>
				<a href='#' onclick='loadBlogers()'>Blogers</a>
				<a href='#' onclick='exploreStories()'>Stories</a>
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