<?php
	$mail = $_POST['mail'];
	$pass = $_POST['password'];
	
	require 'helpFunctions.php';
	
	$flag = 0;
	$logs = fopen("../Authors/Info.csv","r");
	//print_r (fgetcsv($logs)); //This shit gets line from csv file
	while(!feof($logs))	{
		if ($flag == 2) {
			break;
		}
		
		$array = fgetcsv($logs);
		if ($mail == $array[0]) {
			$flag = 1;
			
			$path = "../Authors/$array[1]/config.txt";
			$fd = fopen("$path", "r") or die("Unable to open file.");
			$line_counter = 0;
			while (!feof($fd)) {
				$line = fgets($fd);
				if ($line_counter == 0) {
					$senderImg = trim($line);
				}
				else
				if ($line_counter == 1) {
					$senderHref = trim($line);
				}
				else
				if ($line_counter == 2) {
					$sender = trim($line);
				}
				else
				if ($line_counter == 3) {
					$senderFN = trim($line);
				}
				else
				if ($line_counter == 4) {
					$senderLN = trim($line);
				}
				else
				if ($line_counter == 5) {
					$passCode = trim($line);
					break;
				}
				/*
				else
				if ($line_counter == 6) {
					$senderMail = trim($line);
				}
				else
				if ($line_counter == 7) {
					$senderNotifyOnPost = trim($line);
				}
				else
				if ($line_counter == 8) {
					$senderNotifyOnMessage = trim($line);
				}
				*/
				$line_counter++;
			}
			fclose($fd);
			
			if ($pass == $passCode) {
				$flag = 2;
				
				$buildNotifications = array();
				//Connect to data base
				$servername = "localhost";
				$username = "kdkcompu_gero";
				$password = "Geroepi4";
				$dbname = "kdkcompu_gero";
				
				$conn = mysqli_connect($servername, $username, $password, $dbname);
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				} else {
					/*
						//Get notes
						$sql = "SELECT NOTEID, NOTETEXT, NOTEDATE FROM notesOf$sender ORDER BY ID DESC";
						$pick = $conn->query($sql);
						if ($pick->num_rows > 0) {
							while ($row = $pick->fetch_assoc()) {
								$getDate = $row['NOTEDATE'];
								if ($getDate == date("m/d/Y")) {
									$getTitle = $row['NOTEID'];
									array_push($buildNotifications, $getTitle);
								}
							}
						}
					*/
						
					if (!empty($buildNotifications)) {
						$sql = "CREATE TABLE pushTable$sender (ID int NOT NULL AUTO_INCREMENT, MEMBER LONGTEXT, MESSAGE LONGTEXT, DATE LONGTEXT, PRIMARY KEY (ID))";
						if ($conn->query($sql) === TRUE) {
							buildNotification($sender, $buildNotifications, $conn);
						} else {
							buildNotification($sender, $buildNotifications, $conn);				
						}
					}
						
					//Creatе new TABLE
					$sql = "CREATE TABLE logedUsers (ID int NOT NULL AUTO_INCREMENT, USERID LONGTEXT, PRIMARY KEY (ID))";
					if ($conn->query($sql) === TRUE) {}					
					
					$sql = "DELETE FROM logedUsers WHERE USERID='$sender'";
					$conn->query($sql);
					
					$sql = "CREATE TABLE stack$sender (ID int NOT NULL AUTO_INCREMENT, DATETIME LONGTEXT, STORYTITLE LONGTEXT, STORYLINK LONGTEXT, STORYCONTENT LONGTEXT, PRIMARY KEY (ID))";
					if ($conn->query($sql) === TRUE) {}

					$sql = "CREATE TABLE worldStories (ID int NOT NULL AUTO_INCREMENT, AuthorTitle LONGTEXT, LINK LONGTEXT, POST LONGTEXT, PRIMARY KEY (ID))";
					if ($conn->query($sql) === TRUE) {}

					logIn($sender, $senderImg, $senderHref, $senderFN, $senderLN);
				}
				$conn->close();
			}
		}
	}	
	fclose($logs);
	
	function buildNotification($followerID, $notes, $conn) {
		$date = date("d.M.Y");
		foreach ($notes as $note) {
			$sql = "DELETE FROM pushTable$followerID WHERE MEMBER='*It is*' AND MESSAGE='time for $note' AND DATE='$date'";
			$conn->query($sql);
			$sql = "INSERT INTO pushTable$followerID (MEMBER, MESSAGE, DATE) VALUES ('*It is*', 'time for $note', '$date')";
			$conn->query($sql);
		}
	}	
	
	if ($flag == 0) {
		header('Location: ../Errors/E2.html');	
	}
	else 
	if ($flag == 1) {
		header('Location: ../Errors/E3.html');
	}
	
	function logIn($sender, $senderImg, $senderHref, $senderFN, $senderLN) {
		session_start();
		$_SESSION["sender"] = $sender;
		$_SESSION["senderImg"] = $senderImg;
		$_SESSION["senderHref"] = $senderHref;
 		$_SESSION["senderFN"] = $senderFN;
		$_SESSION["senderLN"] = $senderLN;

		require_once 'Detect/Mobile_Detect.php';
		$detect = new Mobile_Detect;
		if($detect->isMobile() && !$detect->isTablet()) {
			header("Location: logedIn.php");
		} else { //Web
			header("Location: logedIn.php");
		}

/*
		echo "
			<script>
				document.cookie = 'sender='+'$sender';
				document.cookie = 'senderImg='+'$senderImg';
				document.cookie = 'senderHref='+'$senderHref';
				document.cookie = 'senderFN='+'$senderFN';
				document.cookie = 'senderLN='+'$senderLN';
				window.location = 'logedIn.php';
			</script>
		";
*/
	}

	die();
?>