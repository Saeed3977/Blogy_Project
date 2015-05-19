<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}	

	//Get receiver
	$receiver = $_COOKIE["RECEIVER"];
	
	if (!isset($receiver)) {
		echo "<script>window.close();</script>";
	}

	//Build message
	$message = $_COOKIE["MESSAGE"];
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
		$title = $sender."AND".$receiver;
		$sql = "CREATE TABLE $title (ID int NOT NULL AUTO_INCREMENT, MESSANGER LONGTEXT, MESSAGE LONGTEXT, PRIMARY KEY (ID))";
		if ($conn->query($sql) === TRUE) {
			sendMessage($sender, $conn, $message, $title);
		} else {
			sendMessage($sender, $conn, $message, $title);
		}
		
		$title = $receiver."AND".$sender;
		$sql = "CREATE TABLE $title (ID int NOT NULL AUTO_INCREMENT, MESSANGER LONGTEXT, MESSAGE LONGTEXT, PRIMARY KEY (ID))";
		if ($conn->query($sql) === TRUE) {
			sendMessage($sender, $conn, $message, $title);
		} else {
			sendMessage($sender, $conn, $message, $title);
		}
		
		$sql = "CREATE TABLE pushTable$receiver (ID int NOT NULL AUTO_INCREMENT, MEMBER LONGTEXT, MESSAGE LONGTEXT, DATE LONGTEXT, PRIMARY KEY (ID))";
		if ($conn->query($sql) === TRUE) {
			buildNotification($sender, $receiver, $conn);
		} else {
			buildNotification($sender, $receiver, $conn);				
		}
	}
	$conn->close();
	
	reCordinateStack($sender, $receiver);
	reCordinateStack($receiver, $sender);
	
	$pullNotification = fopen("../Authors/$receiver/Messages/Notification.txt", "r") or die("Bad request for pull.");
	$pullCount = fread($pullNotification, filesize("../Authors/$receiver/Messages/Notification.txt"));
	$count = (int)$pullCount + 1;
	fclose($pullNotification);
	
	$commitNotification = fopen("../Authors/$receiver/Messages/Notification.txt", "w") or die("Unable to commit.");
	fwrite($commitNotification, $count);
	fclose($commitNotification);
	
	echo "READY";
	
//..........................................//	
	function buildNotification($sender, $followerID, $conn) {
		$date = date("d.M.Y");
		$sql = "INSERT INTO pushTable$followerID (MEMBER, MESSAGE, DATE) VALUES ('$sender', 'just send you a message', '$date')";
		$conn->query($sql);
	}

	function reCordinateStack($sender, $receiver) {
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
			if ($stack[$line_count] == $receiver) {
				$stack = array_merge(array_diff($stack, array("$receiver")));
				break;
			}
			$line_count++;
		}
		array_push($stack, $receiver);
		
		$line_count = 0;
		$stack_reverse = array_reverse($stack);
		$commitStack = fopen("../Authors/$sender/Messages/Stack.txt", "w") or die("Unable to commit.");
		while ($line_count < count($stack_reverse)) {
			fwrite($commitStack, $stack_reverse[$line_count].PHP_EOL);
			$line_count++;
		}
		fclose($commitStack);
	}

	function addToMessages($title, $receiver, $conn) {
		$sql = "INSERT INTO $title (MESSANGER) VALUES ('$receiver')";
		$conn->query($sql);
	}
	
	function sendMessage($sender, $conn, $message, $title) {
		$sql = "INSERT INTO $title (MESSANGER, MESSAGE) VALUES ('$sender', '$message')";
		$conn->query($sql);
	}
?>