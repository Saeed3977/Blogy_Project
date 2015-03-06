<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$senderImg = $_SESSION['senderImg'];
	$senderHref = $_SESSION['senderHref'];
	$senderFirst = $_SESSION['senderFN'];
	$senderLast = $_SESSION['senderLN'];
	
	//Get parts from the JS
	$pageId = $_POST['pageId'];
	$receiver = $_POST['receiverId'];
	$scrollPos = $_POST['scrollPos'];
	$messageTXT = trim($_POST['messageArea']);
	$messageTXT = nl2br($messageTXT);
	
	//Parse
	if (!isset($receiver)) {
		echo "<script>window.close();</script>";
	}
	$parseAuthor = fopen("../Authors/$receiver/config.txt", "r") or die("Unable to start parsing.");
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
	
	buildSendTOsomeone($receiver, $sender, $senderImg, $messageTXT);
	buildSendTOsender($sender, $receiver, $senderImg, $messageTXT);
	
	$_SESSION['pageId'] = $pageId;
	$_SESSION['scrollPos'] = $scrollPos;
	
	$realoc = "Location: $pageId";
	header($realoc);
	
//..........................................//	
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
?>