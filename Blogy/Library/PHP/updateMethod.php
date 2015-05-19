<?php
	session_start();
	$sender = $_SESSION["sender"];

	$postId = trim($_COOKIE["postId"]);
	$postImg = $_COOKIE["postLink"];
	$postImg = strip_tags($postImg);
	$postContent = trim($_COOKIE["postContent"]);
	if ($postContent != "") {
		$postContent = str_replace("<br />", "\r\n", $postContent);
		$postContent = strip_tags($postContent);
	} else {
		$postContent = NULL;
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
		$postId = str_replace(" ", "6996", $postId);

		$sql = "UPDATE stack$sender SET STORYLINK='$postImg', STORYCONTENT='$postContent' WHERE STORYTITLE='$postId'";
		$conn->query($sql);
		$sql = "UPDATE worldStories SET LINK='$postImg', POST='$postContent' WHERE AuthorTitle='$sender:$postId'";
		$conn->query($sql);
	}
	$conn->close();

	echo "READY";
?>