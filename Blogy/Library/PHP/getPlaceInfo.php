<?php
	header ("Content-Type:text/xml");

	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}

	$getId = $_COOKIE['placeId'];

	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//Load place
		$sql = "SELECT ID, PLACEID, PLACECORDS, PLACESTORY, TAGGED, LIKERS FROM worldPlaces ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$placeIdNum = $row['ID'];
				if ($placeIdNum == $getId) {
					$placeTitle = $row['PLACEID'];
					$placeCords = $row['PLACECORDS'];
					$placeStory = nl2br(trim($row['PLACESTORY']));
					$taggedFriends = $row['TAGGED'];
					$likers = $row['LIKERS'];
					break;
				}
			}
		}
	}
	$conn->close();

	//Build XML
	$XMLContent = "
		~$getId$
		~$placeTitle$
		~$placeCords$
		~$placeStory$
		~$taggedFriends$
		~$likers$
	";

	echo $XMLContent;
?>