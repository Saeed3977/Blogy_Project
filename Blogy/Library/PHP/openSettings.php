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
			
			function generalSettings() {
				document.getElementById('accountInfo').action = '../PHP/generalSettings.php';
				document.forms['accountInfo'].submit();
			}
			
			function messagingSettings() {
				document.getElementById('accountInfo').action = '../PHP/messagingSettings.php';
				document.forms['accountInfo'].submit();
			}
			
			function socializeSettings() {
				document.getElementById('accountInfo').action = '../PHP/socializeSettings.php';
				document.forms['accountInfo'].submit();
			}
			
			function exploreStories() {
				document.getElementById('accountInfo').action = '../PHP/exploreFStories.php';
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
			<h1>Settings</h1>
		</div>
		<div id='body'>
			<div id='settingsOptions'>
				<a href='#' onclick='generalSettings()'>
					<img src='https://cdn3.iconfinder.com/data/icons/ballicons-free/128/settings.png' />
					General
				</a>
				<a href='#' onclick='messagingSettings()'>
					<img src='https://cdn3.iconfinder.com/data/icons/ballicons-free/128/bubbles.png' />
					Messaging
				</a>
				<a href='#' onclick='socializeSettings()'>
					<img src='https://cdn3.iconfinder.com/data/icons/ballicons-free/128/open-box.png' />
					Socialize
				</a>
			</div>
<!--
			<form id='controlPanel' action='../PHP/configBuild.php' method='post'>
				<h1>
				<img src='$profilePic' />
				<br>
				Profile picture
				</h1>
				<input type='text' value='$profilePic' id='profilePic' name='profilePic'></input>
				<br>
				<h1>Social profile</h1>
				<input type='text' value='$profileHref' id='profileHref' name='profileHref'></input>
				<br>
				<h1>First name</h1>
				<input type='text' value='$profileFirst' id='fName' name='fName'></input>
				<br>
				<h1>Last name</h1>
				<input type='text' value='$profileLast' id='lName' name='lName'></input>
				<br>
				<h1>Password</h1>
				<input type='password' value='$pass' id='pass' name='pass'></input>
				<input type='hidden' value='$fullName' name='sender'>
				<br>
				<div class='addMarginSmall'></div>
				<a href='#' onclick='start()'>Save</a>
				<div class='addMarginSmall'></div>
			</form>
-->
		</div>
	</body>
";
?>