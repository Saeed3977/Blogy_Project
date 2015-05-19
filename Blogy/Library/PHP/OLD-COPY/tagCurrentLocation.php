<?php
	$sender = $_COOKIE['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}

	$getTitle = strip_tags(trim($_COOKIE['placeTitle']));
	$getStory = strip_tags($_POST['storyText']);
	$getLocation = $_COOKIE['placeLocation'];

	if (isset($_COOKIE['taggedFriends'])) {
		$getTags = explode(",", $_COOKIE['taggedFriends']);
		array_push($getTags, $sender);
	} else {
		$getTags = "NONE";
	}

	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		if (is_array($getTags)) $getTags = implode(",", $getTags);
		$sql = "INSERT INTO placesOf$sender (PLACEID, PLACECORDS, PLACESTORY, TAGGED) VALUES ('$getTitle', '$getLocation', '$getStory', '$getTags')";
		$conn->query($sql);

		//Add to tagged friends places & make notification..
		if ($getTags != "NONE") {
			if (is_string($getTags)) {
				$getTags = explode(",", $getTags); 
			}
			foreach ($getTags as $friend) {
				if ($friend != $sender) {
					$sql = "CREATE TABLE placesOf$friend (ID int NOT NULL AUTO_INCREMENT, PLACEID LONGTEXT, PLACECORDS LONGTEXT, PLACESTORY LONGTEXT, TAGGED LONGTEXT, PRIMARY KEY (ID))";
					if ($conn->query($sql) === TRUE) {/*Successfully created table*/}
					
					$sql = "INSERT INTO placesOf$friend (PLACEID, PLACECORDS, PLACESTORY, TAGGED) VALUES ('$getTitle', '$getLocation', '$getStory', '".implode(",", $getTags)."')";
					$conn->query($sql);
				
					$sql = "SELECT ID, PLACEID FROM placesOf$friend ORDER BY ID DESC";
					$pick = $conn->query($sql);
					if ($pick->num_rows > 0) {
						while ($row = $pick->fetch_assoc()) {
							$placeIdNum = $row['ID'];
							$placeIdTitle = $row['PLACEID'];
							if ($placeIdTitle == $getTitle) break;
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
		}
	}
	$conn->close();

	function buildNotification($sender, $followerID, $placeIdNum, $conn) {
		$date = date("d.M.Y");
		$sql = "INSERT INTO pushTable$followerID (MEMBER, MESSAGE, DATE) VALUES ('$sender', '$placeIdNum#tagged you in a place', '$date')";
		$conn->query($sql);
	}

	echo "
		<script>
			document.cookie = 'taggedFriends=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			window.location = 'myPlaces.php';
		</script>
	";
?>