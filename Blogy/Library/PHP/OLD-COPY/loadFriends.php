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
		array_push($stack, $line);
	}
	fclose($loadStack);
	
	$reversed_stack = array_reverse($stack);

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
			<body>
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
		
		$count++;
	}

echo "
					</div>
				</div>
			</body>
		</html>	
";
?>