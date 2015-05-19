<?php
	$sender = $_COOKIE['sender'];
	if (!isset($sender)) {
		header("Location: ../../SignIn.html");
	}
	
	$newPicture = $_COOKIE['newProfilePicture'];

	$lineCount = 0;
	$pullConfig = fopen("../Authors/$sender/config.txt", "r") or die("Fatal: Unable to get config.");
	while (!feof($pullConfig)) {
		$line = trim(fgets($pullConfig));
		if ($line != "") {
			if ($lineCount == 0) {
				$oldPicture = $line;
			}
			else
			if ($lineCount == 1) {
				$profileHref = $line;
			}
			else
			if ($lineCount == 2) {
				$senderId = $line;
			}
			else
			if ($lineCount == 3) {
				$fName = $line;
			}
			else
			if ($lineCount == 4) {
				$lName = $line;
			}
			else
			if ($lineCount == 5) {
				$password = $line;
			}
			else
			if ($lineCount == 6) {
				$mail = $line;
			}
			else
			if ($lineCount == 7) {
				$notifyOnPost = $line;
			}
			else
			if ($lineCount == 8) {
				$notifyOnMessage = $line;
			}
			$lineCount++;
		}
	}
	fclose($pullConfig);
	
	$commitConfig = fopen("../Authors/$sender/config.txt", "w") or die("Fatal: Unable to config.");
	fwrite($commitConfig, "../../../Library/Authors/$sender/Album/".$newPicture.PHP_EOL);
	fwrite($commitConfig, $profileHref.PHP_EOL);
	fwrite($commitConfig, $senderId.PHP_EOL);
	fwrite($commitConfig, $fName.PHP_EOL);
	fwrite($commitConfig, $lName.PHP_EOL);
	fwrite($commitConfig, $password.PHP_EOL);
	fwrite($commitConfig, $mail.PHP_EOL);
	fwrite($commitConfig, $notifyOnPost.PHP_EOL);
	fwrite($commitConfig, $notifyOnMessage);
	fclose($commitConfig);
	
	$newPic = "../../../Library/Authors/$sender/Album/".$newPicture;
	
	echo "
		<script>
			document.cookie = 'senderImg='+'$newPic';
			document.cookie = 'newProfilePicture=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			window.location = 'loadAlbum.php';
		</script>
	";
?>