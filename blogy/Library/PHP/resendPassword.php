<?php
	$getMail = trim($_POST['mail']);
	if (!isset($getMail)) {
		echo "<script>window.location='../../SignIn.html'</script>";
	}
	
	$flag = 0;
	$getIDs = fopen("../Authors/Info.csv", "r") or die("Fatal: Unable to open.");
	while (!feof($getIDs)) {
		$line = fgetcsv($getIDs);
		if ($line[0] == $getMail) {
			$sender = $line[1];
			$flag = 1;
			break;
		}
	}
	fclose($getIDs);
	
	if ($flag == 0) {
		echo "<script>window.location='../Errors/E2.html';</script>";
	}
	else
	if ($flag == 1) {
		$line_count = 0;
		$getConfig = fopen("../Authors/$sender/config.txt", "r") or die("Fatal: Unable to get config.");
		while (!feof($getConfig)) {
			$line = trim(fgets($getConfig));
			if ($line != "") {
				if ($line_count == 3) {
					$senderFN = $line;
				}
				else
				if ($line_count == 5) {
					$getPass = $line;
					break;
				}
				$line_count++;
			}
		}
		fclose($getConfig);
		
		$requestTime = date("Y-m-d / H:i:s");
	
		$message = "
			Hello there, $senderFN.
			At $requestTime your profile sends a request for forgotten password.
			This is your password: $getPass
			We recommend you to change it when you log in to your profile.
			Log in from here: http://www.blogy.sitemash.net/SignIn.html
			If you don't send this request log in to your profile and send as a report.
			Thank you and have a nice day, from team of Blogy :)
		";
		
		mail($getMail, "Forgotten password", $message);
		echo "<script>window.location='../Errors/M1.html';</script>";		
	}
?>