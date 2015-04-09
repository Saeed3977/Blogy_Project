<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$authorIds = explode(",", $_COOKIE['shareWith']);
	$elementId = $_COOKIE['sharePicture'];
	
	$message = "
		<img src='../Authors/$sender/Album/$elementId' />
	";
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
		foreach ($authorIds as $authorId) {
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
			
			$sql = "CREATE TABLE pushTable$authorId (ID int NOT NULL AUTO_INCREMENT, MEMBER LONGTEXT, MESSAGE LONGTEXT, DATE LONGTEXT, PRIMARY KEY (ID))";
			if ($conn->query($sql) === TRUE) {
				buildNotification($sender, $authorId, $conn);
			} else {
				buildNotification($sender, $authorId, $conn);				
			}
			
			reCordinateStack($sender, $authorId);
			reCordinateStack($authorId, $sender);
			
			$pullNotification = fopen("../Authors/$authorId/Messages/Notification.txt", "r") or die("Bad request for pull.");
			$pullCount = fread($pullNotification, filesize("../Authors/$authorId/Messages/Notification.txt"));
			$count = (int)$pullCount + 1;
			fclose($pullNotification);
			
			$commitNotification = fopen("../Authors/$authorId/Messages/Notification.txt", "w") or die("Unable to commit.");
			fwrite($commitNotification, $count);
			fclose($commitNotification);
		}
	}
	$conn->close();
	
	echo "
		<script>
			document.cookie = 'shareWith=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			document.cookie = 'sharePicture=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			window.location = 'loadAlbum.php';
		</script>
	";
	
//..........................................//	
	function buildNotification($sender, $followerID, $conn) {
		$date = date("d.M.Y");
		$sql = "INSERT INTO pushTable$followerID (MEMBER, MESSAGE, DATE) VALUES ('$sender', 'just send you an image', '$date')";
		$conn->query($sql);
	}
	
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