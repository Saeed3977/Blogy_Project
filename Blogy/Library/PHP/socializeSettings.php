<?php
	$sender = $_POST['sender'];
	
	$doLine = 0;
	$config = fopen("../Authors/$sender/config.txt", "r") or die("Unable to open this path.");
	while (! feof($config)) {
		$line = fgets($config);

		if ($doLine == 0) {
			$profilePic = $line;
		}
		else
		if ($doLine == 1) {
			$profileHref = $line;
		}
		else
		if ($doLine == 2) {
			$fullName = trim($line);
		}
		else
		if ($doLine == 3) {
			$profileFirst = trim($line);
		}
		else
		if ($doLine == 4) {
			$profileLast = trim($line);
		}
		else
		if ($doLine == 5) {
			$pass = $line;
			break;
		}

		$doLine++;
	}
	fclose($config);
	
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
			function loadBlogers() {
				document.getElementById('accountInfo').action = '../PHP/loadBlogers.php';
				document.forms['accountInfo'].submit();
			}
			
			function logOut() {
				document.getElementById('accountInfo').action = '../PHP/LogOut.php';
				document.forms['accountInfo'].submit();
			}
			
			function openMessages(state) {
				if (state == 0) {
					document.getElementById('cmd').value = '0';
					document.getElementById('accountInfo').action = '../PHP/storeMessages.php';
					document.forms['accountInfo'].submit();
				}
				else
				if (state == 1) {
					document.getElementById('cmd').value = '1';
					document.getElementById('accountInfo').action = '../PHP/storeMessages.php';
					document.forms['accountInfo'].submit();
				}
			}
			
			function exploreStories() {
				document.getElementById('accountInfo').action = '../PHP/exploreFStories.php';
				document.forms['accountInfo'].submit();
			}
			
			function openBloger(title) {
				document.getElementById(title).action = 'openBloger.php';
				document.forms[title].submit();
			}
			
			function exploreFollowers() {
				document.getElementById('accountInfo').action = '../PHP/exploreFollowers.php';
				document.forms['accountInfo'].submit();
			}
		</script>
	</head>
	<body>
		<div id='menu'>
			<a href='#' onclick='returnToHome()' class='homeButton'><img src='$profilePic'></a>
";
	if ($countNotifications != "0") {
		echo "<a href='#' onclick='openMessages(1)' class='notification'>$countNotifications new</a>";
	}
	else
	if ($countNotifications == "0") {
		echo "<a href='#' onclick='openMessages(0)'>Messages</a>";
	}	
echo "
			<a href='#' onclick='openSettings()'>Settings</a>
			<a href='#' onclick='loadBlogers()'>Blogers</a>
			<a href='#' onclick='exploreStories()'>Stories</a>
			<a href='#' onclick='logOut()'>Log out</a>
		</div>
		
		<form id='accountInfo' method='post' style='display: none;'>
			<input type='text' name='sender' value='$sender'></input>
			<input type='text' id='cmd' name='cmd'></input>
		</form>
		
		<div id='sub-logo'>
			<h1>Socialize</h1>
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
					<a href='#' onclick='exploreFollowers()'>
						<h1>$cmd<h1>
					</a>
				</div>
			</div>
		</div>
	</body>
"
?>