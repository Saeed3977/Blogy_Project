<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$target_dir = "../Authors/$sender/Album/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			$uploadOk = 0;
		}
	}
	// Check if file already exists
	$filecounter = 0;
	if (file_exists($target_file)) {
		while (file_exists($target_file)) {
			$target_file = str_replace($filecounter, "", $target_file);
			$filecounter++;
			$target_file .= "$filecounter";
		}
	}
	
	// Check file size
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$sql = "SELECT SPACE FROM albumOf$sender";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getSpace = (int)$row['SPACE'];
			break;
		}
	}
	
	if ($_FILES["fileToUpload"]["size"] > 5000000 && $_FILES["fileToUpload"]["size"] <= $getSpace) {
		echo "<script>window.location='../Errors/E8.html'</script>";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		echo "<script>window.location='../Errors/E9.html'</script>";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "<script>window.location='../Errors/E10.html'</script>";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			if ($filecounter == 0) {
				$fileName = basename($_FILES["fileToUpload"]["name"]);
			}
			else
			if ($filecounter > 0) {
				$fileName = basename($_FILES["fileToUpload"]["name"]).$filecounter;
			}		
			
			$sql = "INSERT INTO albumOf$sender (ALBUM, SPACE) VALUES ('$fileName', '0')";
			$conn->query($sql);
			
			$freeSpace = $getSpace - filesize($target_dir.$fileName);
			
			$sql = "UPDATE albumOf$sender SET SPACE='$freeSpace' WHERE ALBUM='SPACE'";
			$conn->query($sql);
	
			$conn->close();
			
			echo "<script>window.location='loadAlbum.php'</script>";
		} else {
			echo "<script>window.location='../Errors/E10.html'</script>";
		}
	}
?>