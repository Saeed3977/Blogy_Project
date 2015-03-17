<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$blogerId = $_POST['blogSender'];
	
	if (file_exists("../Authors/$sender/Ohana.txt")) {
		addToOhana($sender, $blogerId);
	} else {
		$build = fopen("../Authors/$sender/Ohana.txt", "w") or die("Fatal: Could not create Ohana.txt");
		fclose($build);
		
		addToOhana($sender, $blogerId);
	}
	
	function addToOhana($sender, $newMemeber) {
		$addMember = fopen("../Authors/$sender/Ohana.txt", "a") or die("Fatal: Ohana not found.");
		fwrite($addMember, $newMemeber.PHP_EOL);
		fclose($addMember);
		
		//Connect to data base
		$servername = "localhost";
		$username = "kdkcompu_gero";
		$password = "Geroepi4";
		$dbname = "kdkcompu_gero";
	
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} else {
			$sql = "CREATE TABLE pushTable$newMemeber (ID int NOT NULL AUTO_INCREMENT, MEMBER LONGTEXT, MESSAGE LONGTEXT, PRIMARY KEY (ID))";
			if ($conn->query($sql) === TRUE) {
				buildNotification($sender, $newMemeber, $conn);
			} else {
				buildNotification($sender, $newMemeber, $conn);				
			}
		}
		$conn->close();
		
		echo "<script>window.location='openBloger.php'</script>";
	}
	
	function buildNotification($sender, $newMemeber, $conn) {
		$sql = "INSERT INTO pushTable$newMemeber (MEMBER, MESSAGE) VALUES ('$sender', 'added you in Ohana')";
		$conn->query($sql);
	}
?>