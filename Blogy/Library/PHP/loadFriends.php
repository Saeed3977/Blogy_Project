<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$senderPic = $_SESSION['senderImg'];
	
	//Load authors
	$loops = 0;
	$loadStack = fopen("../Authors/$sender/Following.txt", "r") or die("Unable to load stack.");
	$stack = array();
	while (!feof($loadStack)) {
		$line = trim(fgets($loadStack));
		if ($line != $sender && $line != "") {
			array_push($stack, $line);
			$loops++;
		}
	}
	fclose($loadStack);
	$stack = implode(",", $stack);

echo "
		<html>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
				<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<title>Friends</title>
				<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
				<link href='../../fonts.css' rel='stylesheet' type='text/css'>
				<script type='text/javascript' src='../../java.js'></script>
				<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
				<script type='text/javascript'>
				</script>
			</head>
			<body onload='loadBloggers($loops, \"$stack\", \"blogers-list\")'>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "	
				<div id='sub-menu'>
					<div id='currentLeft'>
						<a href='loadFriends.php'>Friends</a>
					</div>
					<div id='otherOption' class='right'>
						<a href='loadBlogers.php'>Authors</a>
					</div>
				</div>
				<div id='body'>
					<div id='blogers-list'>
					</div>
				</div>
			</body>
		</html>	
";
?>