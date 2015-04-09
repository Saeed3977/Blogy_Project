<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$objectId = $_POST['pictureId'];
	$src = "../Authors/$sender/Album/$objectId";
	
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "DELETE FROM albumOf$sender WHERE ALBUM='$objectId'";
	$conn->query($sql);
	
	$sql = "SELECT SPACE FROM albumOf$sender";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getFreeSpace = (int)$row['SPACE'];
			break;
		}
	}
	
	$getFileSize = filesize($src);
	
	$calcSpace = $getFreeSpace + $getFileSize;
	$sql = "UPDATE albumOf$sender SET SPACE='$calcSpace' WHERE ALBUM='SPACE'";
	$conn->query($sql);
	
	$conn->close();
	
	unlink($src);

	echo "<script>window.location='loadAlbum.php';</script>";
?>