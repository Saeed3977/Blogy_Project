<?php
	$sender = $_COOKIE['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$profilePic = $_COOKIE['senderImg'];
	$profileHref = $_COOKIE['senderHref'];
	$profileFirst = $_COOKIE['senderFN'];
	$profileLast = $_COOKIE['senderLN'];
	
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
	
echo "
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<title>Your followers</title>
		<link href='../../style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../fonts.css' rel='stylesheet' type='text/css'>
		<script type='text/javascript' src='../../java.js'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
		
		<script type = 'text/javascript'> 
			function logOut() {
				document.getElementById('accountInfo').action = '../PHP/LogOut.php';
				document.forms['accountInfo'].submit();
			}
		</script>
	</head>
	<body>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "		
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
		foreach ($reversedFollowersStack as $follower) {
			$lines_count = 0;
			$authorId = explode("-", $follower)[1];
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
					<a href='openBloger.php' onclick=\"openBloger('$authorId')\">
						<img src='$authorImg' />
						$authorFN $authorLN
						<form id='$authorId' method='post' style='display: none;'>
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