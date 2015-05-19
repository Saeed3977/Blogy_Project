<?php
	$sender = $_COOKIE['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$senderPic = $_COOKIE['senderImg'];
	
	//Load authors
	$loadStack = fopen("../Authors/$sender/Following.txt", "r") or die("Unable to load stack.");
	$stack = array();
	while (!feof($loadStack)) {
		$line = trim(fgets($loadStack));
		if ($line != "") array_push($stack, $line);
	}
	fclose($loadStack);
	
	$configStack = array();
	foreach ($stack as $friend) {
		$pickUpCount = 0;
		$parseUser = fopen("../Authors/$friend/config.txt", "r") or die("Unable to start parsing.");
		while (!feof($parseUser)) {
			$pickUpLine = trim(fgets($parseUser));
			if ($pickUpCount == 0) {
				$friendImg = $pickUpLine;
			}
			else
			if ($pickUpCount == 1) {
				$friendHref = $pickUpLine;
			}
			else
			if ($pickUpCount == 3) {
				$friendFN = $pickUpLine;
			}
			else
			if ($pickUpCount == 4) {
				$friendLN = $pickUpLine;
				break;
			}
			$pickUpCount++;
		}
		fclose($parseUser);

		array_push($configStack, "$friendFN#$friendLN#$friend#$friendImg#$friendHref");
	}

	$reversed_stack = array_reverse($configStack);
	$bindFriends = implode(",", $reversed_stack);

echo "
		<html>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
				<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<title>Search in Friends</title>
				<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
				<link href='../../fonts.css' rel='stylesheet' type='text/css'>
				<script type='text/javascript' src='../../java.js'></script>
				<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
				<script type='text/javascript'>
					var pullFriends = '$bindFriends'.split(',');
					function checkInput() {
						searchFriends(pullFriends, 'searchInput', 'searchResults', 1);
					}
				</script>
			</head>
			<body>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "	
				<div id='sub-menu'>
					<div id='currentLeft'>
						<a href='searchInFriends.php'>Friends</a>
					</div>
					<div id='otherOption' class='right'>
						<a href='searchInAuthors.php'>Authors</a>
					</div>
				</div>
				<div id='body'>
					<div id='searchContainer'>
						<input type='text' id='searchInput' placeholder='Search a friend' onkeyup='checkInput()'></input>
						<div id='searchResults'>
						</div>
					</div>
				</div>
			</body>
		</html>	
";
?>