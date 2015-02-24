<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];
	
	//Pull followers
	$stackSize = filesize("../Authors/$sender/FollowersID.html");
	if ($stackSize != 0) {
		$followersStack = array();
		$loadFollowers = fopen("../Authors/$sender/FollowersID.html", "r") or die("Unable to load followers.");
		while (! feof($loadFollowers)) {
			$line = fgets($loadFollowers);
			if (trim($line) != "") {
				array_push($followersStack, trim($line));
			}
		}
	}
	
	$followers =  fopen("../Authors/$sender/Followers.html", "r") or die('Unable to open file !');
	$followersAllCount = fread($followers, filesize("../Authors/$sender/Followers.html"));
	fclose($followers);

	//Pull notifications
	$pullNotifications = fopen("../Authors/$sender/Messages/Notification.txt", "r") or die("Unable to pull.");
	$countNotifications = fread($pullNotifications, filesize("../Authors/$sender/Messages/Notification.txt"));
	fclose($pullNotifications);	
	
echo "
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<title>Your followers</title>
		<link href='../../style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../fonts.css' rel='stylesheet' type='text/css'>
		<script type='text/javascript' src='../../java.js'></script>
		
		<script type = 'text/javascript'> 
			function logOut() {
				document.getElementById('accountInfo').action = '../PHP/LogOut.php';
				document.forms['accountInfo'].submit();
			}
		</script>
	</head>
	<body>
		<div id='menu'>
			<a href='logedIn.php' class='homeButton'><img src='$profilePic'></a>
";
	if ($countNotifications != "0") {
		echo "<a href='storeMessages.php' class='notification'>$countNotifications new</a>";
	}
	else
	if ($countNotifications == "0") {
		echo "<a href='storeMessages.php'>Messages</a>";
	}	
echo "
			<a href='openSettings.php'>Settings</a>
			<a href='loadBlogers.php'>Blogers</a>
			<a href='exploreFStories.php'>Stories</a>
			<a href='#' onclick='logOut()'>Log out</a>
		</div>
		
		<form id='accountInfo' method='post' style='display: none;'>
			<input type='text' name='sender' value='$sender'></input>
			<input type='text' id='cmd' name='cmd'></input>
		</form>
		
		<div id='sub-logo'>
			<h1>Followers</h1>
		</div>
		<div id='body'>
			<div id='blogers-list'>
";
	if ($stackSize != 0) {
		$authorId = (string)NULL;
		$followersCount = 0;
		$reversedFollowersStack = array_reverse($followersStack);
		while ($followersCount < count($reversedFollowersStack)) {
			$lines_count = 0;
			$getInfo = fopen("../Authors/Info.csv", "r") or die("Fatal error: Info.csv corrupted.");
			while (!feof($getInfo)) {
				$line = fgetcsv($getInfo);
				if ($line != "") {
					if ($reversedFollowersStack[$followersCount] == $line[0]) {
						$authorId = $line[1];
						$followersAllCount--;
						
						$lines_count = 0;
						if ($authorId != "" && $authorId != $sender) {
							$author = fopen("../Authors/$authorId/config.txt", "r") or die("Unable to open author.");
							while (!feof($author)) {
								$line = fgets($author);
								if ($lines_count == 0) {
									$authorImg = trim($line);
								}
								if ($lines_count == 1) {
									$authorHref = trim($line);
								}
								else
								if ($lines_count == 3) {
									$authorFN = trim($line);
								}
								else
								if ($lines_count == 4) {
									$authorLN = trim($line);
									break;
								}
								$lines_count++;
							}
							fclose($author);
							
							$loadComplete = "
								<a href='#' onclick=\"openBloger('$authorId')\">
									<img src='$authorImg' />
									$authorFN $authorLN
									<form id='$authorId' method='post' style='display: none;'>
										<input type='text' name='accSender' value='$sender'></input>
										<input type='text' name='imgSender' value='$profilePic'></input>
										<input type='text' name='blogSender' value='$authorId'></input>
										<input type='text' name='blogerFN' value='$authorFN'></input>
										<input type='text' name='blogerLN' value='$authorLN'></input>
										<input type='text' name='blogerImg' value='$authorImg'></input>
										<input type='text' name='blogerHref' value='$authorHref'></input>
									</form>
								</a>
								<br>
							";
							echo "$loadComplete";
						}
						
						break;
					}
				}
			}
			fclose($getInfo);
			$followersCount++;
		}
		
		if ($followersAllCount != 0) {
			echo "
				<h1>
					$followersAllCount non bloger follows you. 
				</h1>
			";
		}
	} else {
		echo "
			<h1>
				You don't have followers yet :(
			</h1>
		";
	}

echo "
			</div>
		</div>
	</body>
";
?>