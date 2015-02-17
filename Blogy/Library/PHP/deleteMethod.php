<?php
	$sender = $_POST['sender'];
	$postId = $_POST['postId'];
	$newStack = array();
	
	$getStack = fopen("../Authors/$sender/Posts/Stack.txt", "r") or die("Unable to open stack.");
	while (!feof($getStack)) {
		$line = trim(fgets($getStack));
		if ($line != $postId && $line != "") {
			array_push($newStack, $line);
		}
	}
	fclose($getStack);
	
	$pushStack = fopen("../Authors/$sender/Posts/Stack.txt", "w") or die("Unable to push stack.");
	foreach ($newStack as $line) {
		fwrite($pushStack, $line.PHP_EOL);
	}
	fclose($pushStack);
	
	//Pull World History
	$newStack = array();
	$getStack = fopen("../Authors/World/History.txt", "r") or die("Unable to open stack.");
	while (!feof($getStack)) {
		$line = trim(fgets($getStack));
		if ($line != $sender) {
			array_push($newStack, $line);
		}
		else
		if ($line == $sender) {
			array_push($newStack, $line);
			$line = trim(fgets($getStack));
			if ($line == $postId) {
				array_pop($newStack);
			}
			else
			if ($line != $postId) {
				array_push($newStack, $line);
			}
		}
	}
	fclose($getStack);
	
	$pushStack = fopen("../Authors/World/History.txt", "w") or die("Unable to push stack.");
	foreach ($newStack as $line) {
		fwrite($pushStack, $line.PHP_EOL);
	}
	fclose($pushStack);
	
	/*
	$putHistory = array();
	$getHistory = fopen("../Authors/World/History.txt", "r") or die("Unable to open stack.");
	while (!feof($getHistory)) {
		$line = trim(fgets($getHistory));
		if ($line == $sender && $line != "") {
			array_push($putHistory, $line);
			$line = trim(fgets($getHistory));
			if ($line != $postId && $line != "") {
				array_push($putHistory, $line);
				echo "$line";
			}
		}
	}
	fclose($getHistory);
	
	print_r($putHistory);
	
	$pushHistory = fopen("../Authors/World/History.txt", "w") or die("Unable to push stack.");
	foreach ($putHistory as $line) {
		fwrite($pushHistory, $line.PHP_EOL);
	}
	fclose($pushHistory);
	*/
	
	unlink("../Authors/$sender/Posts/$postId.txt");

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

	die();
?>