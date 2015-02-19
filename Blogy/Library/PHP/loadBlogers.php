<?php
	$sender = $_POST['sender'];
	
	$logCheck = fopen("../Authors/$sender/LogFlag.txt", "r") or header('Location: ../../SignIn.html');
	$flag = fread($logCheck, filesize("../Authors/$sender/LogFlag.txt"));
	fclose($logCheck);
	
	if ($flag == "0") {
		header('Location: ../Errors/E4.html');
	}
	else
	if ($flag == "1") {
	$loadSender = fopen("../Authors/$sender/config.txt", "r") or die("Unable to load sender.");
	$senderPic = trim(fgets($loadSender));
	fclose($loadSender);
	
	//Load authors
	$stack = array();
	$loadStack = fopen("../Authors/Info.csv", "r") or die("Unable to load stack.");
	while (!feof($loadStack)) {
		$line = fgetcsv($loadStack);
		array_push($stack, trim($line[1]));
	}
	fclose($loadStack);
	
	$reversed_stack = array_reverse($stack);
	
	//Pull notifications
	$pullNotifications = fopen("../Authors/$sender/Messages/Notification.txt", "r") or die("Unable to pull.");
	$countNotifications = fread($pullNotifications, filesize("../Authors/$sender/Messages/Notification.txt"));
	fclose($pullNotifications);
	
echo "
		<html>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
				<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<title>Authors in Blogy</title>
				<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
				<link href='../../fonts.css' rel='stylesheet' type='text/css'>
				<script type='text/javascript' src='../../java.js'></script>
				<script type='text/javascript'>
					function loadBlogers() {
							document.getElementById('post').action = 'loadBlogers.php';
							document.forms['post'].submit();
						}

					function logOut() {
						document.getElementById('post').action = 'LogOut.php';
						document.forms['post'].submit();
					}
					
					function openBloger(title) {
						document.getElementById(title).action = 'openBloger.php';
						document.forms[title].submit();
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
				</script>
			</head>
			<body>
				<div id='menu'>
					<a href='#' onclick='returnToHome()' class='homeButton'><img src='$senderPic'></a>
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
				<form id='post' method='post' style='display: none;'>
					<input name='sender' value='$sender'></input>
				</form>
				
				<div id='sub-logo'>
					<h1>Authors</h1>
				</div>
				<div id='body'>
					<div id='blogers-list'>
";

	$count = 0;
	while($count < count($reversed_stack)) {
		$lines_count = 0;
		$authorId = $reversed_stack[$count];
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
						<input type='text' name='imgSender' value='$senderPic'></input>
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
		$count++;
	}

echo "
					</div>
				</div>
			</body>
		</html>	
";
	}
?>