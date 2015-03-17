<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$postId = $_POST['postId'];
	$newStack = array();
	
	$getStack = fopen("../Authors/$sender/Posts/Stack.txt", "r") or die("Unable to open stack.");
	while (!feof($getStack)) {
		$line = trim(fgets($getStack));
		if ($line != $postId && $line != "") {
			array_push($newStack, $line);
		}
	}
	fclose($getStack);
	
	$pushStack = fopen("../Authors/$sender/Posts/Stack.txt", "w") or die("Unable to push stack.");
	foreach ($newStack as $line) {
		fwrite($pushStack, $line.PHP_EOL);
	}
	fclose($pushStack);
	
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
		$sql = "DELETE FROM stack$sender WHERE STACK='$postId'";
		$conn->query($sql);
		$sql = "DELETE FROM worldStories WHERE AuthorPOST='$sender:$postId'";
		$conn->query($sql);
	}
	$conn->close();
	/*
	$putHistory = array();
	$getHistory = fopen("../Authors/World/History.txt", "r") or die("Unable to open stack.");
	while (!feof($getHistory)) {
		$line = trim(fgets($getHistory));
		if ($line == $sender && $line != "") {
			array_push($putHistory, $line);
			$line = trim(fgets($getHistory));
			if ($line != $postId && $line != "") {
				array_push($putHistory, $line);
				echo "$line";
			}
		}
	}
	fclose($getHistory);
	
	print_r($putHistory);
	
	$pushHistory = fopen("../Authors/World/History.txt", "w") or die("Unable to push stack.");
	foreach ($putHistory as $line) {
		fwrite($pushHistory, $line.PHP_EOL);
	}
	fclose($pushHistory);
	*/
	
	$postId = str_replace("6996", " ", $postId);
	unlink("../Authors/$sender/Posts/$postId.txt");

	header('Location: logedIn.php');

	die();
?>