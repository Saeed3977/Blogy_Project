<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}

	$authorIds = explode(",", $_COOKIE['shareWith']);
	$placeId = $_COOKIE['placeId'];

	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//Get place info.
		$sql = "SELECT ID, PLACEID, PLACECORDS, PLACESTORY, TAGGED FROM placesOf$sender ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$placeIdNum = $row['ID'];
				if ($placeIdNum == $placeId) {
					$placeTitle = $row['PLACEID'];
					$placeCords = $row['PLACECORDS'];
					$placeStory = $row['PLACESTORY'];
					break;
				}
			}
		}

		//Share place
		foreach ($authorIds as $friend) {
			$taggedFriends = $sender.','.$friend;
			
			$sql = "CREATE TABLE placesOf$friend (ID int NOT NULL AUTO_INCREMENT, PLACEID LONGTEXT, PLACECORDS LONGTEXT, PLACESTORY LONGTEXT, TAGGED LONGTEXT, PRIMARY KEY (ID))";
			if ($conn->query($sql) === TRUE) {/*Successfully created table*/}
			
			$sql = "INSERT INTO placesOf$friend (PLACEID, PLACECORDS, PLACESTORY, TAGGED) VALUES ('$placeTitle', '$placeCords', '$placeStory', '$taggedFriends')";
			$conn->query($sql);

			$sql = "SELECT ID, PLACEID FROM placesOf$friend ORDER BY ID DESC";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$placeIdNum = $row['ID'];
					$placeIdTitle = $row['PLACEID'];
					if ($placeIdTitle == $placeTitle) break;
				}
			}

			$sql = "CREATE TABLE pushTable$friend (ID int NOT NULL AUTO_INCREMENT, MEMBER LONGTEXT, MESSAGE LONGTEXT, DATE LONGTEXT, PRIMARY KEY (ID))";
			if ($conn->query($sql) === TRUE) {
				buildNotification($sender, $friend, $placeIdNum, $conn);
			} else {
				buildNotification($sender, $friend, $placeIdNum, $conn);			
			}
		}
	}
	$conn->close();

	function buildNotification($sender, $followerID, $placeIdNum, $conn) {
		$date = date("d.M.Y");
		$sql = "INSERT INTO pushTable$followerID (MEMBER, MESSAGE, DATE) VALUES ('$sender', '$placeIdNum#shared a place with you', '$date')";
		$conn->query($sql);
	}

	echo "
		<script>
			document.cookie = 'shareWith=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			window.location = 'previewPlace.php';
		</script>
	";
?>