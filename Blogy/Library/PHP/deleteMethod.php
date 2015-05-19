<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$postId = $_COOKIE["postTitle"];
	
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		if (strpos($postId, " ")) {
			$postId = str_replace(" ", "6996", $postId);
		}
		$sql = "DELETE FROM stack$sender WHERE STORYTITLE='$postId'";
		$conn->query($sql);
		$sql = "DELETE FROM worldStories WHERE AuthorTitle='$sender$$postId'";
		$conn->query($sql);
	}
	$conn->close();

	echo "READY";
?>