<?php
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
		$sql = "DELETE FROM placesOf$sender WHERE ID='$getId'";
		$conn->query($sql);
	}

	echo "
		<script>
			document.cookie = 'placeId=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			window.location = 'myPlaces.php';
		</script>
	";
?>