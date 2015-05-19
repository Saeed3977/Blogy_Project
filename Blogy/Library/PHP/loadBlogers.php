<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$senderPic = $_SESSION['senderImg'];
	
	//Load authors
	$loops = 0;
	$stack = array();
	$loadStack = fopen("../Authors/Info.csv", "r") or die("Unable to load stack.");
	while (!feof($loadStack)) {
		$line = fgetcsv($loadStack);
		if ($line[1] != $sender && $line != "") {
			array_push($stack, trim($line[1]));
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
				<title>Authors</title>
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
					<div id='otherOption' class='left'>
						<a href='loadFriends.php'>Friends</a>
					</div>
					<div id='currentRight'>
						<a href='exploreStories.php'>Authors</a>
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