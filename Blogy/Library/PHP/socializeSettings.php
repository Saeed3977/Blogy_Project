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
	
	//Pull notifications
	$pullNotifications = fopen("../Authors/$sender/Messages/Notification.txt", "r") or die("Unable to pull.");
	$countNotifications = fread($pullNotifications, filesize("../Authors/$sender/Messages/Notification.txt"));
	fclose($pullNotifications);
	
	echo "
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<title>$profileFirst's panel</title>
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
			<h1>Statistics</h1>
		</div>
		<div id='body'>
			<div id='socialize-container'>
				<div id='left-socialize-container'>
";

	$posts = 0;
	$loadPosts = fopen("../Authors/$sender/Posts/Stack.txt", "r") or die("Unable to open file.");
	while (!feof($loadPosts)) {
		$line = trim(fgets($loadPosts));
		if ($line != "") {
			$posts++;
		}
	}
	fclose($loadPosts);
	
	if ($posts == 1) {
		$cmd = "$posts post";
	} else {
		$cmd = "$posts posts";
	}
	
echo " 
					<a href='#' onclick='returnToHome()'>
						<h1>$cmd</h1>
					</a>
				</div>
				<div id='middle-socialize-container'>
";

	$printMethod = (string)NULL;
	$followers = 0;
	$loadFollowing = fopen("../Authors/$sender/Following.txt", "r") or die("Unable to open file.");
	while (!feof($loadFollowing)) {
		$line = trim(fgets($loadFollowing));
		if ($line != "") {
			$pickUpCount = 0;
			$parseUser = fopen("../Authors/$line/config.txt", "r") or die("Unable to start parsing.");
			while (!feof($parseUser)) {
				$pickUpLine = trim(fgets($parseUser));
				if ($pickUpCount == 0) {
					$userImg = $pickUpLine;
				}
				else
				if ($pickUpCount == 1) {
					$userHref = $pickUpLine;
				}
				else
				if ($pickUpCount == 3) {
					$userFN = $pickUpLine;
				}
				else
				if ($pickUpCount == 4) {
					$userLN = $pickUpLine;
					break;
				}
				$pickUpCount++;
			}
			fclose($parseUser);
			
			$authorId = $line;
			
			$printMethod .= "
				<a href='#' onclick=\"openBloger('$authorId')\">
					<img src='$userImg' alt='Bad image link :(' />
					$userFN $userLN
					<form id='$authorId' method='post' style='display: none;'>
						<input type='text' name='accSender' value='$sender'></input>
						<input type='text' name='imgSender' value='$profilePic'></input>
						<input type='text' name='blogSender' value='$authorId'></input>
						<input type='text' name='blogerFN' value='$userFN'></input>
						<input type='text' name='blogerLN' value='$userLN'></input>
						<input type='text' name='blogerImg' value='$userImg'></input>
						<input type='text' name='blogerHref' value='$userHref'></input>
					</form>
				</a>
				<br>
			";
			
			$followers++;
		}
	}
	fclose($loadFollowing);
	
	$cmd = "$followers following";

echo "
					<h1>$cmd<h1>
					$printMethod
				</div>
				<div id='right-socialize-container'>
";

	$printMethod = (string)NULL;
	$followers = 0;
	$loadFollowing = fopen("../Authors/$sender/Following.txt", "r") or die("Unable to open file.");
	while (!feof($loadFollowing)) {
		$line = trim(fgets($loadFollowing));
		if ($line != "") {
			$pickUpCount = 0;
			$parseUser = fopen("../Authors/$line/config.txt", "r") or die("Unable to start parsing.");
			while (!feof($parseUser)) {
				$pickUpLine = trim(fgets($parseUser));
				if ($pickUpCount == 0) {
					$userImg = $pickUpLine;
				}
				else
				if ($pickUpCount == 1) {
					$userHref = $pickUpLine;
				}
				else
				if ($pickUpCount == 3) {
					$userFN = $pickUpLine;
				}
				else
				if ($pickUpCount == 4) {
					$userLN = $pickUpLine;
					break;
				}
				$pickUpCount++;
			}
			fclose($parseUser);
			
			$authorId = $line;
			
			$printMethod .= "
				<a href='#' onclick=\"openBloger('$authorId')\">
					<img src='$userImg' alt='Bad image link :(' />
					$userFN $userLN
					<form id='$authorId' method='post' style='display: none;'>
						<input type='text' name='accSender' value='$sender'></input>
						<input type='text' name='imgSender' value='$profilePic'></input>
						<input type='text' name='blogSender' value='$authorId'></input>
						<input type='text' name='blogerFN' value='$userFN'></input>
						<input type='text' name='blogerLN' value='$userLN'></input>
						<input type='text' name='blogerImg' value='$userImg'></input>
						<input type='text' name='blogerHref' value='$userHref'></input>
					</form>
				</a>
				<br>
			";
			
			$followers++;
		}
	}
	fclose($loadFollowing);
	
	$loadFollowers =  fopen("../Authors/$sender/Followers.html", "r") or die('Unable to open file !');
	$followersCount = fread($loadFollowers, filesize("../Authors/$sender/Followers.html"));
	fclose($loadFollowers);
	
	if ($followersCount == "1") {
		$cmd = "$followersCount follower";
	} else {
		$cmd = "$followersCount followers";
	}

echo "
					<a href='exploreFollowers.php'>
						<h1>$cmd<h1>
					</a>
				</div>
			</div>
		</div>
	</body>
";
?>