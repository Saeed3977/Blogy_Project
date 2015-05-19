<?php
	$sender = $_COOKIE['sender'];
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
		//Pull
		$sql = "SELECT ID, LIKERS FROM worldPlaces ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				if ($row['ID'] == $getId) {
					$commitLikers = $row['LIKERS'];
					break;
				}
			}
		}


		$likes = 0;
		if ($commitLikers == "NONE") {
			$commitLikers = $sender;
			$commitResult = $commitLikers;
			$likes = 1;
		} else {
			if (strpos($commitLikers, ",") !== false) {
				$count = 0;
				$parse = explode(",", $commitLikers);
				$commitLikers = array();
				while ($count < count($parse)) {
					if ($parse[$count] != $sender) array_push($commitLikers, $parse[$count]);
					$count++;
				}
				if (!in_array($sender, $parse)) array_push($commitLikers, $sender);
			
				$count = 0;
				while ($count < count($commitLikers)) {
					$likes++;
					$count++;
				}

				$commitResult = implode(",", $commitLikers);
			} else {
				if ($commitLikers != $sender) {
					$commitLikers .= ",".$sender; 
					$likes = 2;
				} else {
					$commitLikers = "NONE"; 
					$likes = 0;
				}

				$commitResult = $commitLikers;
			}
		}

		//Commit
		$sql = "UPDATE worldPlaces SET LIKERS='".$commitResult."' WHERE ID='$getId'";
		$conn->query($sql);
	}
	$conn->close();

	echo "$likes";
?>