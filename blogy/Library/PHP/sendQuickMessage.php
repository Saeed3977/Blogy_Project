<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$senderImg = $_SESSION['senderImg'];
	$senderHref = $_SESSION['senderHref'];
	$senderFirst = $_SESSION['senderFN'];
	$senderLast = $_SESSION['senderLN'];
	
	//Get parts from the JS
	$pageId = $_POST['pageId'];
	$authorId = $_POST['receiverId'];
	$scrollPos = $_POST['scrollPos'];
	
	if (!isset($authorId)) {
		echo "<script>window.close();</script>";
	}

	//Build message
	$message = trim($_POST['messageArea']);
	$message = nl2br($message);
	$message = str_replace("'", "\'", $message);
	$message =  htmlentities($message);
	
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$title = $sender."AND".$authorId;
		$sql = "CREATE TABLE $title (ID int NOT NULL AUTO_INCREMENT, MESSANGER LONGTEXT, MESSAGE LONGTEXT, PRIMARY KEY (ID))";
		if ($conn->query($sql) === TRUE) {
			sendMessage($sender, $conn, $message, $title);
		} else {
			sendMessage($sender, $conn, $message, $title);
		}
		
		$title = $authorId."AND".$sender;
		$sql = "CREATE TABLE $title (ID int NOT NULL AUTO_INCREMENT, MESSANGER LONGTEXT, MESSAGE LONGTEXT, PRIMARY KEY (ID))";
		if ($conn->query($sql) === TRUE) {
			sendMessage($sender, $conn, $message, $title);
		} else {
			sendMessage($sender, $conn, $message, $title);
		}
	}
	$conn->close();
	
	reCordinateStack($sender, $authorId);
	reCordinateStack($authorId, $sender);
	
	$pullNotification = fopen("../Authors/$authorId/Messages/Notification.txt", "r") or die("Bad request for pull.");
	$pullCount = fread($pullNotification, filesize("../Authors/$authorId/Messages/Notification.txt"));
	$count = (int)$pullCount + 1;
	fclose($pullNotification);
	
	$commitNotification = fopen("../Authors/$authorId/Messages/Notification.txt", "w") or die("Unable to commit.");
	fwrite($commitNotification, $count);
	fclose($commitNotification);
	
	$_SESSION['pageId'] = $pageId;
	$_SESSION['scrollPos'] = $scrollPos;
	
	$realoc = "Location: $pageId";
	header($realoc);
	
//..........................................//	
	function reCordinateStack($sender, $authorId) {
		$stack = array();
		$loadStack = fopen("../Authors/$sender/Messages/Stack.txt", "r") or die("Unable to load stack.");
		while (!feof($loadStack)) {
			$line = trim(fgets($loadStack));
			if ($line != "") {
				array_push($stack, $line);
			}
		}
		fclose($loadStack);
		
		$line_count = 0;
		while ($line_count < count($stack)) {
			if ($stack[$line_count] == $authorId) {
				$stack = array_merge(array_diff($stack, array("$authorId")));
				break;
			}
			$line_count++;
		}
		array_push($stack, $authorId);
		
		$line_count = 0;
		$stack_reverse = array_reverse($stack);
		$commitStack = fopen("../Authors/$sender/Messages/Stack.txt", "w") or die("Unable to commit.");
		while ($line_count < count($stack_reverse)) {
			fwrite($commitStack, $stack_reverse[$line_count].PHP_EOL);
			$line_count++;
		}
		fclose($commitStack);
	}

	function addToMessages($title, $authorId, $conn) {
		$sql = "INSERT INTO $title (MESSANGER) VALUES ('$authorId')";
		$conn->query($sql);
	}
	
	function sendMessage($sender, $conn, $message, $title) {
		$sql = "INSERT INTO $title (MESSANGER, MESSAGE) VALUES ('$sender', '$message')";
		$conn->query($sql);
	}
?>