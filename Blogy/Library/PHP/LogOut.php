<?php
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	session_start();
	$sender = $_SESSION['sender'];
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//Remove from TABLE
		$sql = "DELETE FROM logedUsers WHERE USERID='$sender'";
		$conn->query($sql);
	}
	$conn->close();
	
	session_destroy();

	echo "
		<script>
			document.cookie = 'sender=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			document.cookie = 'senderImg=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			document.cookie = 'senderHref=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			document.cookie = 'senderFN=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			document.cookie = 'senderLN=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			window.location = '../../../index.php';
		</script>
	";
	
	die();
?>