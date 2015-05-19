<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$postTitle = $_COOKIE['postId'];
	
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "SELECT STORYTITLE, STORYLINK, STORYCONTENT FROM stack$sender ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getTitle = $row["STORYTITLE"];
				$getTitle = str_replace("6996", " ", $getTitle);
				$getLink = $row["STORYLINK"];
				$getContent = $row["STORYCONTENT"];
				if ($postTitle == $getTitle) break;
			}
		}
	}
	$conn->close();
	
	$getBuild = "$getTitle$$getLink$$getContent";
	echo "$getBuild";
?>