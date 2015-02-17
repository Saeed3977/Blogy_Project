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
			while (! feof($fd)) {
				$line = fgets($fd);
				if ($line_counter == 2) {
					$sender = trim($line);
				}
				else
				if ($line_counter == 5) {
					$passCode = trim($line);
				}
				$line_counter++;
			}
			fclose($fd);
			
			if ($pass == $passCode) {
				$flag = 2;
				
				//Check login
				$fd = fopen("../Authors/$array[1]/LogFlag.txt", "w") or die("Unable to open file.");
				fwrite($fd, "1");
				fclose($fd);
				
				echo "
					<html>
						<head>
							<script type='text/javascript'>
								function reSend() {
									document.getElementById('post').action = 'logedIn.php';
									document.forms['post'].submit();
								}
							</script>
						</head>
						<body onload='reSend()'>
							<form id='post' method='post' style='display: none;'>
								<input name='sender' value='$sender'></input>
							</form>
						</body>
					</html>
				";
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
	die();
?>