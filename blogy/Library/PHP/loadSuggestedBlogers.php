<?php
	session_start();
	$sender = $_SESSION['sender'];

	if (isset($_COOKIE['sideBar'])) {
		$cmd = "style='display: none;'";
	} else {
		$cmd = "";
	}
	
echo "
	<div id='sideBar' $cmd>
			<h1>People you may know</h1>
				<div id='suggestions'>
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
	
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$blockedPersons = array();
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "SELECT BLOCKEDID FROM blockList$sender";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				array_push($blockedPersons, $row['BLOCKEDID']);
			}
		}
	}
	
	if (!empty($pullFollowing)) {
		$returnStack = array();
		foreach ($pullFollowing as $authorId) {
			if (filesize("../Authors/$authorId/Following.txt") > 0) {
				$loadFollowers = fopen("../Authors/$authorId/Following.txt", "r") or die("Fatal: Could not load.");
				while (!feof($loadFollowers)) {
					$line = trim(fgets($loadFollowers));
					if ($line != "" && $line != $sender) {
						$blockedPersonsByFollower = array();
						$sql = "SELECT BLOCKEDID FROM blockList$line";
						$pick = $conn->query($sql);
						if ($pick->num_rows > 0) {
							while ($row = $pick->fetch_assoc()) {
								array_push($blockedPersonsByFollower, $row['BLOCKEDID']);
							}
						}
						
						if (!in_array($line, $pullFollowing) && !in_array($line, $blockedPersons) && !in_array($sender, $blockedPersonsByFollower)) {
							array_push($returnStack, $line);
						}
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
					<button onclick=\"exploreBloger('$sugestion');\">
						<img src='$sugestionImg' />
						$sugestionFN $sugestionLN
					</button>
					<form id='$sugestion' method='post' style='display: none;'>
						<input type='text' name='blogSender' value='$sugestion'>
						<input type='text' name='blogerFN' value='$sugestionFN'>
						<input type='text' name='blogerLN' value='$sugestionLN'>
						<input type='text' name='blogerImg' value='$sugestionImg'>
						<input type='text' name='blogerHref' value='$sugestionHref'>
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
	
	$conn->close(); //Close SQL Connection
	
echo "
			</div>
			<div id='friendsOnline'>
				<h1>Friends online</h1>
				<div id='onlineFriends'>
";
	include 'loadOnlineFriends.php';
echo "
				</div>
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

echo "
	<div id='rightSideBar' $cmd>
		<button type='button' onclick='showOhanaMeaning()'>
			<h1>Ohana</h1>
		</button>
		<div id='ohanaMeaning' class='arrow_box_ohana'>
			<p>
				<b>Ohana</b> means <b>family</b>.<br>
				<b>Family</b> means nobody gets left behind - or forgotten.
			</p>
		</div>
";
	include 'loadOhana.php';
echo "
	</div>
";
?>