<?php
	session_start();
	if (!isset($sender)) {
		echo "<script>window.location='../../SignIn.html';</script>";
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];
	
	//Pull notifications
	$pullNotifications = fopen("../Authors/$sender/Messages/Notification.txt", "r") or die("Unable to pull.");
	$countNotifications = fread($pullNotifications, filesize("../Authors/$sender/Messages/Notification.txt"));
	fclose($pullNotifications);
	
echo "
			<div id='menu'>
				<button type='button' class='hvr-push' onclick='showSideBar()'></button>
				<div id='homeMenu'>
					<button type='button' class='homeButton' onclick='showHideHomeMenu()'><img src='$profilePic'></button>
					<div id='dropDownMenu' class='dropDown' style='display: none;'>
						<a href='logedIn.php' class='split'>Home</a>
						<a href='loadAlbum.php'>Album</a>
					</div>
				</div>
";
	if ($countNotifications != "0") {
		echo "<a href='storeMessages.php' class='notification'>$countNotifications new</a>";
	}
	else
	if ($countNotifications == "0") {
		echo "<a href='storeMessages.php'>Messages</a>";
	}	
echo "
				<a href='openSettings.php'>Settings</a>
				<a href='loadBlogers.php'>Bloggers</a>
				<a href='exploreFStories.php'>Stories</a>
				<button type='button' class='logOut' onclick='logOut()'>Log out</button>
			</div>
			<div id='click-container' onclick='showHideNotifications()'>
				<h1>Notifications</h1>
			</div>
";
	
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {}
	
	//Check push notifications
	$pushNotifications = array();
	$sql = "SELECT MEMBER, MESSAGE FROM pushTable$sender";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$member = $row['MEMBER'];
			$message = $row['MESSAGE'];
			array_push($pushNotifications, "$member-$message");
		}
	}

	$pushNotifications = array_reverse($pushNotifications);
	
	$_SESSION['notification'] = $_COOKIE['notification'];
	if (!isset($_SESSION['notificationsStack'])) {
		$_SESSION['notificationsStack'] = $pushNotifications;
	} else {
		if ($_SESSION['notificationsStack'] != $pushNotifications) {
			$_SESSION['notificationsStack'] = $pushNotifications;
			$_SESSION['notification'] = 0;
			echo "<script>document.cookie = 'notification=; expires=Thu, 01 Jan 1970 00:00:00 UTC';</script>";
		}
		/*
		else
		if ($_SESSION['notificationsStack'] == $pushNotifications) {
			$_SESSION['notification'] = $_COOKIE['notification'];
			echo "<script>alert(".$_SESSION['notification'].");</script>";
		}
		*/
	}
	
	if ($_SESSION['notification'] == 1) {
		$cmd = "style='display: none;'";
	} else {
		$cmd = "";
	}
	
	echo "
		<div id='notifications' class='pushNotification' $cmd>
			<div id='title'>
				<button type='button' onclick='showHideNotifications()'></button>
				<h1>Notifications</h1>
			</div>
			<div id='stack'>
	";
	
	if (empty($pushNotifications)) {
		echo "
		<center>
			<p>No new notifications.</p>
		</center>";
	}
	
	if ($_SESSION['notification'] == 1) {
		echo "<script>$('#notifications').slideUp('fast');</script>";
	}
	
		foreach ($pushNotifications as $notification) {
			$member = explode("-", $notification)[0];
			$message = explode("-", $notification)[1];
			$lineCount = 0;
			$pullInfo = fopen("../Authors/$member/config.txt", "r") or die("Fatal: Could not load.");
			while (!feof($pullInfo)) {
				$line = trim(fgets($pullInfo));
				if ($line != "") {
					if ($lineCount == 0) {
						$memberImg = $line;
					}
					else
					if ($lineCount == 1) {
						$memberHref = $line;
					}
					else
					if ($lineCount == 3) {
						$memberFN = $line;
					}
					else
					if ($lineCount == 4) {
						$memberLN = $line;
						break;
					}
				}
				$lineCount++;
			}
			fclose($pullInfo);
			
			$build = "
				<div id='notification'>
					<p>
						<a href='openBloger.php' onclick=\"openBloger('$member')\">
							<img src='$memberImg' />
							$memberFN $memberLN
						</a>
						$message.
					</p>
					<form id='$member' method='post' style='display: none;'>
						<input type='text' name='blogSender' value='$member'></input>
						<input type='text' name='blogerFN' value='$memberFN'></input>
						<input type='text' name='blogerLN' value='$memberLN'></input>
						<input type='text' name='blogerImg' value='$memberImg'></input>
						<input type='text' name='blogerHref' value='$memberHref'></input>
					</form>
				</div>
			";
			
			echo "$build";
		}
	echo"
			</div>
		</div>
	";
	
	if (isset($_COOKIE['scrollPos'])) {
		$scrollPos = $_COOKIE['scrollPos'];
		echo "
			<script>
				var head = document.getElementsByTagName('head')[0];
				var script = document.createElement('script');
				script.src = 'https://code.jquery.com/jquery-1.10.2.js';
				head.appendChild(script);
				$(window).scrollTop($scrollPos);
			</script>
		";
		 unset($_COOKIE['scrollPos']);
	}
?>