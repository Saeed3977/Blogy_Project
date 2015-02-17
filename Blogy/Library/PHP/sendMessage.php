<?php
	$line_count = 0;
	$toSend = (string)NULL;
	
	$sender = $_POST['sender'];
	$parseSender = fopen("../Authors/$sender/config.txt", "r") or die("Unable to start parsing.");
	while (!feof($parseSender)) {
		$line = fgets($parseSender);
		if ($line_count == 0) {
			$senderImg = trim($line);
		}
		else
		if ($line_count == 3) {
			$senderFN = trim($line);
		}
		else
		if ($line_count == 4) {
			$senderLN = trim($line);
		}
		$line_count++;
	}
	fclose($parseSender);
	$line_count = 0;
	
	$authorId = $_POST['authorId'];
	$parseAuthor = fopen("../Authors/$authorId/config.txt", "r") or die("Unable to start parsing.");
	while (!feof($parseAuthor)) {
		$line = fgets($parseAuthor);
		if ($line_count == 0) {
			$authorImg = trim($line);
		}
		else
		if ($line_count == 1) {
			$authorHref = trim($line);
		}
		else
		if ($line_count == 3) {
			$authorFN = trim($line);
		}
		else
		if ($line_count == 4) {
			$authorLN = trim($line);
		}
		else
		if ($line_count == 6) {
			$authorMail = trim($line);
		}
		else
		if ($line_count == 8) {
			$toSend = trim($line);
		}
		$line_count++;
	}
	fclose($parseAuthor);
	$line_count = 0;
	
	//Build message
	$message = trim($_POST['content']);
	$message = nl2br($message);

	buildSendTOsomeone($authorId, $sender, $senderImg, $message);
	buildSendTOsender($sender, $authorId, $senderImg, $message);
	
	if ($toSend == "1") {
		$subject = "New message in Blogy";
		$content = "Hello there. $senderFN just send you a message. Check it from here: http://www.blogy.sitemash.net/SignIn.html";
		mail($authorMail, $subject, $content);
	}
	
	function buildSendTOsender($sender, $authorId, $senderImg, $message) {
		$line_count = 0;
		$stack = array();
		$loadStack = fopen("../Authors/$sender/Messages/Stack.txt", "r") or die("Unable to load stack.");
		while (!feof($loadStack)) {
			$line = trim(fgets($loadStack));
			if ($line != "") {
				array_push($stack, $line);
			}
		}
		fclose($loadStack);
		
		while ($line_count < count($stack)) {
			if ($stack[$line_count] == $authorId) {
				$stack = array_merge(array_diff($stack, array("$authorId")));
				break;
			}
			$line_count++;
		}
		array_push($stack, $authorId);
		
		if (! file_exists("../Authors/$sender/Messages/$authorId")) {
			mkdir("../Authors/$sender/Messages/$authorId");
		}
		
		$line_count = 0;
		
		$commitMessage = fopen("../Authors/$sender/Messages/$authorId/History.txt", "a") or die("Unable to commit.");
		fwrite($commitMessage, "NM".PHP_EOL);
		fwrite($commitMessage, "HOST".PHP_EOL);
		fwrite($commitMessage, "SI".PHP_EOL);
		fwrite($commitMessage, $senderImg.PHP_EOL);
		fwrite($commitMessage, "MT".PHP_EOL);
		fwrite($commitMessage, $message.PHP_EOL);
		fclose($commitMessage);
	
		$stack_reverse = array_reverse($stack);
		$commitStack = fopen("../Authors/$sender/Messages/Stack.txt", "w") or die("Unable to commit.");
		while ($line_count < count($stack_reverse)) {
			fwrite($commitStack, $stack_reverse[$line_count].PHP_EOL);
			$line_count++;
		}
		fclose($commitStack);
	}
	
	function buildSendTOsomeone($authorId, $sender, $senderImg, $message) {
		$line_count = 0;
		$stack = array();
		$loadStack = fopen("../Authors/$authorId/Messages/Stack.txt", "r") or die("Unable to load stack.");
		while (!feof($loadStack)) {
			$line = trim(fgets($loadStack));
			if ($line != "") {
				array_push($stack, $line);
			}
		}
		fclose($loadStack);

		while ($line_count < count($stack)) {
			if ($stack[$line_count] == $sender) {
				$stack = array_merge(array_diff($stack, array("$sender")));
				break;
			}
			$line_count++;
		}
		array_push($stack, $sender);
		
		if (! file_exists("../Authors/$authorId/Messages/$sender")) {
			mkdir("../Authors/$authorId/Messages/$sender");
		}
		
		$line_count = 0;
		
		$commitMessage = fopen("../Authors/$authorId/Messages/$sender/History.txt", "a") or die("Unable to commit.");
		fwrite($commitMessage, "NM".PHP_EOL);
		fwrite($commitMessage, "GUEST".PHP_EOL);
		fwrite($commitMessage, "SI".PHP_EOL);
		fwrite($commitMessage, $senderImg.PHP_EOL);
		fwrite($commitMessage, "MT".PHP_EOL);
		fwrite($commitMessage, $message.PHP_EOL);
		fclose($commitMessage);
		
		$stack_reverse = array_reverse($stack);
		$commitStack = fopen("../Authors/$authorId/Messages/Stack.txt", "w") or die("Unable to commit.");
		while ($line_count < count($stack_reverse)) {
			fwrite($commitStack, $stack_reverse[$line_count].PHP_EOL);
			$line_count++;
		}
		fclose($commitStack);
		
		$pullNotification = fopen("../Authors/$authorId/Messages/Notification.txt", "r") or die("Bad request for pull.");
		$pullCount = fread($pullNotification, filesize("../Authors/$authorId/Messages/Notification.txt"));
		$count = (int)$pullCount + 1;
		fclose($pullNotification);
		
		$commitNotification = fopen("../Authors/$authorId/Messages/Notification.txt", "w") or die("Unable to commit.");
		fwrite($commitNotification, $count);
		fclose($commitNotification);
	}

	$cmd = $_POST['cmd'];

	if ($cmd == "0") {
echo "
	<html>
		<head>
			<script type='text/javascript'>
				function reSend() {
					document.getElementById('post').action = 'openBloger.php';
					document.forms['post'].submit();
				}
			</script>
		</head>
		<body onload='reSend()'>
			<form id='post' method='post' style='display: none;'>
				<input name='accSender' value='$sender'></input>
				<input name='imgSender' value='$senderImg'></input>
				<input name='blogSender' value='$authorId'></input>
				<input name='blogerFN' value='$authorFN'></input>
				<input name='blogerLN' value='$authorLN'></input>
				<input name='blogerImg' value='$authorImg'></input>
				<input name='blogerHref' value='$authorHref'></input>
			</form>
		</body>
	</html>
";
	}
	else
	if ($cmd == "1") {
echo "
	<html>
		<head>
			<script type='text/javascript'>
				function reSend() {
					document.getElementById('post').action = 'realocFromMessanger.php';
					document.forms['post'].submit();
				}
			</script>
		</head>
		<body onload='reSend()'>
			<form id='post' method='post' style='display: none;'>
				<input name='messangerId' value='$authorId'></input>
				<input name='sender' value='$sender'></input>
			</form>
		</body>
	</html>
";
	}
	
	//print_r($stack);
	die();
?>