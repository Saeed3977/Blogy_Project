<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		echo "<script>window.location='../../../SignIn.html';</script>";
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];
	
	//Pull notifications
	$pullNotifications = fopen("../../Authors/$sender/Messages/Notification.txt", "r") or die("Unable to pull.");
	$countNotifications = fread($pullNotifications, filesize("../../Authors/$sender/Messages/Notification.txt"));
	fclose($pullNotifications);
	
echo "
			<div id='menu'>
				<div id='homeMenu'>
					<button type='button' onclick='showHide()'><img src='$profilePic'>$profileFirst $profileLast</button>
				</div>
				<div id='dropDownMenu' onclick='showHide()'>
					<a href='logedIn.php'>Home</a>
					<a href='#'>Messages</a>
					<a href='#'>Album</a>
					<a href='#'>Places</a>
					<a href='#'>Settings</a>
					<a href='#'>Bloggers</a>
					<a href='#'>Stories</a>
					<a href='#'>Search</a>
					<a href='#' onclick='logMeOut()'>Log out</a>
				</div>
";
	/*if ($countNotifications != "0") {
		echo "<a href='storeMessages.php' class='notification'>$countNotifications new</a>";
	}
	else
	if ($countNotifications == "0") {
		echo "<a href='storeMessages.php'>Messages</a>";
	}	*/
echo "
				<!--<a href='openSettings.php'>Settings</a>
				<a href='loadFriends.php'>Bloggers</a>
				<a href='exploreFStories.php'>Stories</a>
				<a href='#' onclick='logMeOut()'>Log out</a>
				<button type='button' class='searchButton' title='Search' onclick='window.location=\"searchInFriends.php\"; '><img src='../images/search.png' /></button>-->
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
	$sql = "SELECT MEMBER, MESSAGE, DATE FROM pushTable$sender";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$member = $row['MEMBER'];
			$message = $row['MESSAGE'];
			$date = $row['DATE'];
			array_push($pushNotifications, "$member-$message-$date");
		}
	}

	$pushNotifications = array_reverse($pushNotifications);

	if (!isset($_COOKIE['notificationsStack'])) {
		echo "<script>document.cookie = 'notificationsStack='+'".implode(",", $pushNotifications)."';</script>";
	} else {
		if ($_COOKIE['notificationsStack'] != implode(",", $pushNotifications)) {
			echo "<script>document.cookie = 'notificationsStack='+'".implode(",", $pushNotifications)."';</script>";
			echo "<script>document.cookie = 'notification=; expires=Thu, 01 Jan 1970 00:00:00 UTC';</script>";
		}
		/*
		else
		if ($_SESSION['notificationsStack'] == $pushNotifications) {
			$_SESSION['notification'] = $_SESSION['notification'];
			echo "<script>alert(".$_SESSION['notification'].");</script>";
		}
		*/
	}
	
	if (isset($_COOKIE['notification'])) {
		$cmd = "style='display: none;'";
	} else {
		$cmd = "";
	}
	
	echo "
		<div id='notifications' class='pushNotification' $cmd>
			<div id='title'>
				<button type='button' onclick='showHideNotifications()' title='Hide notifications'></button>
				<h1>Notifications</h1>
				<h2></h2>
			</div>
			<div id='stack'>
	";
	
	if (empty($pushNotifications)) {
		echo "
		<center>
			<p>No new notifications.</p>
		</center>";
	}
	
	if (isset($_SESSION['notification'])) {
		echo "<script>$('#notifications').slideUp('fast');</script>";
	}
	
		foreach ($pushNotifications as $notification) {
			$member = explode("-", $notification)[0];
			$message = explode("-", $notification)[1];
			$date = explode("-", $notification)[2];
			$lineCount = 0;
			if (!strpos($member, "is*")) {
				$pullInfo = fopen("../../Authors/$member/config.txt", "r") or die("Fatal: Could not load.");
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
				
				$date = str_replace(".", "-", $date);
			
				//Parse and build special notifications
				if (strpos($message, "you a message")) {
					$message = "just send you a <a href='#' onclick='readMessage(\"$member\")'>message</a>";
				} 
				else 
				if (strpos($message, "#tagged you in a place")) {
					$getId = explode("#", $message)[0];
					$message = "tagged you in a <a href='previewPlace.php' onclick='previewPlace(\"$getId\")'>place</a>";
				}
				else
				if (strpos($message, "#shared a place with you")) {
					$getId = explode("#", $message)[0];
					$message = "shared a <a href='previewPlace.php' onclick='previewPlace(\"$getId\")'>place</a> with <a href='logedIn.php'>you</a>";
				}
				
				$build = "
					<div id='notification'>					
						<p onmouseover='showNotificationDate(\"$date\")' onmouseleave='clearDateContainer()'>
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
			} else {
				$memberImg = "https://cdn4.iconfinder.com/data/icons/Mobile-Icons/128/07_note.png";
				$message = "<a href='loadNotes.php'>".$message."</a>";
			
				$build = "
					<div id='notification'>					
						<div id='notificationDateContainer'>
							$date
						</div>
						<p>
							<img src='$memberImg' />
							It is
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
			}		
			
			
			echo "$build";
		}
	echo"
			</div>
		</div>
	";
?>