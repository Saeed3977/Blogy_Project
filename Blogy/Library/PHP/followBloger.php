<?php 
/*
	Add check if the user already is a follower to the current bloger.
	If true then do un-follow action [TRUE]
	else
	If false then do follow action [TRUE]
*/

	$line_counter = 0;
	
	$sender = $_POST['sender'];
	$parseSender = fopen("../Authors/$sender/config.txt", "r") or die("Unable to start parsing.");
	while (!feof($parseSender)) {
		$line = fgets($parseSender);
		if ($line_counter == 0) {
			$senderImg = trim($line);
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
		if ($line_counter == 6) {
			$senderMail = trim($line);
		}
		$line_counter++;
	}
	fclose($parseSender);
	$line_counter = 0;
	
	$bloger = $_POST['authorId'];
	$parseBloger = fopen("../Authors/$bloger/config.txt", "r") or die("Unable to start parsing.");
	while (!feof($parseBloger)) {
		$line = fgets($parseBloger);
		if ($line_counter == 0) {
			$blogerImg = trim($line);
		}
		else
		if ($line_counter == 1) {
			$blogerHref = trim($line);
		}
		else
		if ($line_counter == 3) {
			$blogerFN = trim($line);
		}
		else
		if ($line_counter == 4) {
			$blogerLN = trim($line);
		}
		else
		if ($line_counter == 6) {
			$blogerMail = trim($line);
		}
		$line_counter++;
	}
	fclose($parseBloger);
	
	$followerFound = 0;
	$getFollowers = fopen("../Authors/$bloger/FollowersID.html", "r") or die("Unable to get followers.");
	$followersStack = array();
	while (!feof($getFollowers)) {
		$line = fgets($getFollowers);
		if ($line != "") {
			array_push($followersStack, trim($line));
		}
		
		if (trim($line) == $senderMail) {
			$followerFound = 1;
		}
	}
	fclose($getFollowers);
	
	$iFollowStack = array();
	$getFollowing = fopen("../Authors/$sender/Following.txt", "r") or die("Unable to get following authors.");
	while (!feof($getFollowing)) {
		$line = trim(fgets($getFollowing));
		if ($line != "") {
			array_push($iFollowStack, $line);
		}
	}
	fclose($getFollowing);
	
	//Get followers count
	$followersCountLoad = fopen("../Authors/$bloger/Followers.html", "r") or die("Unable to load followers.");
	$followersCount = fread($followersCountLoad, filesize("../Authors/$bloger/Followers.html"));
	fclose($followersCountLoad);
	
	if ($followerFound == 0) {
		$count = (int)$followersCount + 1;
		array_push($followersStack, $senderMail);
		array_push($iFollowStack, $bloger);
	}
	else
	if ($followerFound == 1) {
		$count = (int)$followersCount - 1;
		$followersStack = array_merge(array_diff($followersStack, array("$senderMail")));
		$iFollowStack = array_merge(array_diff($iFollowStack, array("$bloger")));
	}
	
	//Save counter
	$followersCountSave = fopen("../Authors/$bloger/Followers.html", "w") or die("Unable to open file.");
	fwrite($followersCountSave, $count);
	fclose($followersCountSave);
	
	//Build stack
	$followersCount = 0;
	$followersStackFile = fopen("../Authors/$bloger/FollowersID.html", "w") or die("Unable to add follower to the stack.");
	while ($followersCount < count($followersStack)) {
		fwrite($followersStackFile, $followersStack[$followersCount].PHP_EOL);
		$followersCount++;
	}
	fclose($followersStackFile);
	
	$followersCount = 0;
	$iFollowCommit = fopen("../Authors/$sender/Following.txt", "w") or die("Unable to add author.");
	while ($followersCount < count($iFollowStack)) {
		fwrite($iFollowCommit, $iFollowStack[$followersCount].PHP_EOL);
		$followersCount++;
	}
	fclose($iFollowCommit);
	
	if ($followerFound == 0) {
		$subject = "New follower";
		$content = "Hello there. $senderFN $senderLN with e-mail: $senderMail, just start following you. ";
		mail($blogerMail, $subject, $content);
	}

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
				<input name='blogSender' value='$bloger'></input>
				<input name='blogerFN' value='$blogerFN'></input>
				<input name='blogerLN' value='$blogerLN'></input>
				<input name='blogerImg' value='$blogerImg'></input>
				<input name='blogerHref' value='$blogerHref'></input>
			</form>
		</body>
	</html>
";

	die();
?>