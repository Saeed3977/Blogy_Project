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
		
		if (trim(explode("-", $line)[0]) == $senderMail) {
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
		
	if ($followerFound == 0) {
		array_push($followersStack, $senderMail."-".$sender);
		array_push($iFollowStack, $bloger);
		
		//Connect to data base
		$servername = "localhost";
		$username = "kdkcompu_gero";
		$password = "Geroepi4";
		$dbname = "kdkcompu_gero";
		
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} else {
			$sql = "CREATE TABLE pushTable$bloger (ID int NOT NULL AUTO_INCREMENT, MEMBER LONGTEXT, MESSAGE LONGTEXT, PRIMARY KEY (ID))";
			if ($conn->query($sql) === TRUE) {
				buildNotification($sender, $bloger, $conn);
			} else {
				buildNotification($sender, $bloger, $conn);				
			}
		}
		$conn->close();
	}
	else
	if ($followerFound == 1) {
		$followersStack = array_merge(array_diff($followersStack, array("$senderMail-$sender")));
		$iFollowStack = array_merge(array_diff($iFollowStack, array("$bloger")));
	}
	
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
		$content = "Hello there. $senderFN $senderLN with e-mail: $senderMail, just started following you. ";
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

	function buildNotification($sender, $bloger, $conn) {
		$date = date("d.M.Y");
		$sql = "INSERT INTO pushTable$bloger (MEMBER, MESSAGE, DATE) VALUES ('$sender', 'started following you', '$date')";
		$conn->query($sql);
	}

	die();
?>