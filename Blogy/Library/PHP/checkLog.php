<?php
	$mail = $_POST['mail'];
	$pass = $_POST['password'];
	
	$flag = 0;
	$logs = fopen("../Authors/Info.csv","r");
	//print_r (fgetcsv($logs)); //This shit gets line from csv file
	while(!feof($logs))
	{
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
				
				//Connect to data base
				$servername = "localhost";
				$username = "kdkcompu_gero";
				$password = "Geroepi4";
				$dbname = "kdkcompu_gero";
				
				$conn = mysqli_connect($servername, $username, $password, $dbname);
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				} else {
					//Creatе new TABLE
					$sql = "CREATE TABLE $sender (
					AuthorID LONGTEXT,
					AuthorImg LONGTEXT,
					AuthorHref LONGTEXT,
					AuthorFN LONGTEXT,
					AuthorLN LONGTEXT
					)";
					
					if ($conn->query($sql) === TRUE) {
						//Add info in the table
						$sql = "INSERT INTO $sender (AuthorID, AuthorImg, AuthorHref, AuthorFN, AuthorLN)
						VALUES ('$sender', '$senderImg', '$senderHref', '$senderFN', '$senderLN')";
						$conn->query($sql);
						if ($conn->query($sql) === TRUE) {
							logIn($sender, $senderImg, $senderHref, $senderFN, $senderLN);
						}
					} else {
						logIn($sender, $senderImg, $senderHref, $senderFN, $senderLN);
					}
					
					$conn->close();
				}
				
				/*
				*/
			}
		}
	}
	
	if ($flag == 0) {
		header('Location: ../Errors/E2.html');	
	}
	else 
	if ($flag == 1) {
		header('Location: ../Errors/E3.html');
	}
	
	fclose($logs);
	
	function logIn($sender, $senderImg, $senderHref, $senderFN, $senderLN) {
		session_start();
		$_SESSION['sender'] = $sender;
		$_SESSION['senderImg'] = $senderImg;
		$_SESSION['senderHref'] = $senderHref;
		$_SESSION['senderFN'] = $senderFN;
		$_SESSION['senderLN'] = $senderLN;
		
		header('Location: logedIn.php');
	}
	die();
?>