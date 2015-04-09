<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$lineCount = 0;
	$getConfig = fopen("../Authors/$sender/config.txt", "r") or die("Fatal: Unable to get config.");
	while (!feof($getConfig)) {
		$line = trim(fgets($getConfig));
		if ($line != "") {
			if ($lineCount == 5) {
				$password = $line;
			}
			else
			if ($lineCount == 6) {
				$mail = $line;
			}
			$lineCount++;
		}
	}
	fclose($getConfig);
	
	$getMail = $_POST['mail'];
	$getPass = $_POST['pass'];
	
	if ($getMail == $mail) {
		if ($getPass == $password) {
			deleteProfile($sender);
		} else {
			header('Location: ../Errors/E7.html');
		}
	} else {
		header('Location: ../Errors/E6.html');
	}
	
	function deleteProfile($sender) {
		rmdir_recursive("../Authors/$sender/");
		
		$allUsers = array();
		//Delete from users
		$pullAll = fopen("../Authors/Info.csv", "r") or die("Fatal: Error.");
		while (!feof($pullAll)) {
			$line = trim(fgetcsv($pullAll));
			if ($line != "" && $line[1] != $sender) {
				array_push($allUsers, $line);
			}
		}
		fclose($pullAll);
		
		$commitAll = fopen("../Authors/Info.csv", "w") or die("Fatal: Error.");
		foreach ($allUsers as $user) {
			fputcsv($commitAll, $user);
		}
		fclose($commitAll);
		
		//Delete tables
		//Connect to data base
		$servername = "localhost";
		$username = "kdkcompu_gero";
		$password = "Geroepi4";
		$dbname = "kdkcompu_gero";
		
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} else {
			$sql = "DROP TABLE $sender";
			$conn->query($sql);
			$sql = "DROP TABLE stack$sender";
			$conn->query($sql);
			$sql = "DROP TABLE blockList$sender";
			$conn->query($sql);
			$sql = "DROP TABLE pushTable$sender";
			$conn->query($sql);
			$sql = "DELETE FROM worldStories WHERE AuthorPOST='$sender:[*]'";
			$conn->query($sql);
		}
		
		//echo "<script>window.location='../../index.php';</script>";
	}
	
	function rmdir_recursive($dir) {
		$it = new RecursiveDirectoryIterator($dir);
		$it = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
		foreach($it as $file) {
			if ('.' === $file->getBasename() || '..' ===  $file->getBasename()) continue;
			if ($file->isDir()) rmdir($file->getPathname());
			else unlink($file->getPathname());
		}
		rmdir($dir);
	}
?>