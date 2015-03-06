<?php
	session_start();
	$sender = $_SESSION['sender'];
	
echo "
	<div id='sideBar'>
				<h1>People you may know</h1>
					<div id='suggestions'>
";

	$scrollPosSidebar = $_POST['scrollPosSidebar'];
	if (!isset($scrollPosSidebar)) {
		$scrollPosSidebar = 0;
	}
	echo "
		<script>
			$(window).scrollTop($scrollPosSidebar);
		</script>
	";
	
	$pullFollowing = array();
	$followingPull = fopen("../Authors/$sender/Following.txt", "r") or die("Fatal: Could not start opening.");
	while (!feof($followingPull)) {
		$line = trim(fgets($followingPull));
		if ($line != "") {
			array_push($pullFollowing, $line);
		}
	}
	fclose($followingPull);
	
	if (!empty($pullFollowing)) {
		$returnStack = array();
		foreach ($pullFollowing as $authorId) {
			if (filesize("../Authors/$authorId/Following.txt") > 0) {
				$loadFollowers = fopen("../Authors/$authorId/Following.txt", "r") or die("Fatal: Could not load.");
				while (!feof($loadFollowers)) {
					$line = trim(fgets($loadFollowers));
					if ($line != "" && $line != $sender && !in_array($line, $pullFollowing)) {
						array_push($returnStack, $line);
					}
				}
				fclose($loadFollowers);
			}
		}
		$returnStack = array_unique($returnStack);
		sort($returnStack);
		foreach ($returnStack as $sugestion) {
			$lineCount = 0;
			if ($sugestion != NULL) {
				$pullInfo = fopen("../Authors/$sugestion/config.txt", "r") or die("Fatal: Could not load.");
				while (!feof($pullInfo)) {
					$line = trim(fgets($pullInfo));
					if ($line != "") {
						if ($lineCount == 0) {
							$sugestionImg = $line;
						}
						else
						if ($lineCount == 1) {
							$sugestionHref = $line;
						}
						else
						if ($lineCount == 3) {
							$sugestionFN = $line;
						}
						else
						if ($lineCount == 4) {
							$sugestionLN = $line;
							break;
						}
					}
					$lineCount++;
				}
				fclose($pullInfo);
								
				//Build and print
				echo "
					<a href='openBloger.php' type='button' onclick=\"openBloger('$sugestion')\">
						<img src='$sugestionImg' />
						$sugestionFN $sugestionLN
					</a>
					<form id='$sugestion' method='post' style='display: none;'>
						<input type='text' name='blogSender' value='$sugestion'></input>
						<input type='text' name='blogerFN' value='$sugestionFN'></input>
						<input type='text' name='blogerLN' value='$sugestionLN'></input>
						<input type='text' name='blogerImg' value='$sugestionImg'></input>
						<input type='text' name='blogerHref' value='$sugestionHref'></input>
					</form>
					<br>
				";
			}
		}
		
		if (empty($returnStack)) {
			echo "<h2>There is no new suggestions for you.</h2>";
		}
	} else {
		echo "<h2>You don't follow anybody.</h2>";
	}
	
echo "
			</div>
			<div id='friendsOnline'>
				<h1>Friends online</h1>
";
	include 'loadOnlineFriends.php';
echo "
			</div>
	</div>
	<div id='quickMessageBox' style='display: none;'>
		<div id='title'>
			<h1 id='receiver'></h1>
			<button type='button' onclick='hideMessageBox()'></button>
		</div>
		<form id='sendArea' method='post'>
			<textarea id='messageArea' name='messageArea' placeholder=\"What's up ?\" onkeypress='checkKey(event)'></textarea>
			<button type='button' class='sendButton' onclick='sendMessageBox()'>Send</button>
			<div style='display: none;'>
				<input type='text' id='pageId' name='pageId'>
				<input type='text' id='scrollPosSidebar' name='scrollPosSidebar'>
				<input type='text' id='receiverId' name='receiverId'>
			</div>
		</form>
	</div>
";
?>