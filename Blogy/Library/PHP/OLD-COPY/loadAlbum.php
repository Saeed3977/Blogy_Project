<?php
	$sender = $_COOKIE['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$profilePic = $_COOKIE['senderImg'];
	$profileHref = $_COOKIE['senderHref'];
	$profileFirst = $_COOKIE['senderFN'];
	$profileLast = $_COOKIE['senderLN'];
	
	$checkPath = "../Authors/$sender/Album";
	if (!file_exists($checkPath)) {
		mkdir($checkPath, 0777);
		$buildSecurity = fopen("../Authors/$fullName/index.php", "w") or die("Fatal: Unable to build security.");
		fwrite($buildSecurity, "<?php header('Location: ../../../../SignIn.html');?>");
		fclose($buildSecurity);
	}
	
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$stackOrder = array();
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "CREATE TABLE albumOf$sender (ID int NOT NULL AUTO_INCREMENT, ALBUM LONGTEXT, SPACE LONG, PRIMARY KEY (ID))";
		if ($conn->query($sql) === TRUE) {
			$sql = "INSERT INTO albumOf$sender (ALBUM, SPACE) VALUES ('SPACE', '100000000')";
			$conn->query($sql);
		} else {
			//Get content
			$sql = "SELECT ALBUM, SPACE FROM albumOf$sender ORDER BY ID DESC";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					if ($row['ALBUM'] != "SPACE") {
						array_push($stackOrder, $row['ALBUM']);
					} else {
						$getSizeInMB = (int)$row['SPACE'] * 0.000001;
						$getSizeInMB = number_format ($getSizeInMB, 2);
					}
				}
			}
		}
	}
	$conn->close();
	
	//Get friends
	$friendsStack = array();
	$pullFriends = fopen("../Authors/$sender/Following.txt", "r") or die("Fatal: Unable to get friends.");
	while (!feof($pullFriends)) {
		$line = trim(fgets($pullFriends));
		if ($line != "") {
			array_push($friendsStack, $line);
		}
	}
	fclose($pullFriends);
	
	//Get stack
	$stack = array();
	$loadStack = fopen("../Authors/$sender/Posts/Stack.txt", "r") or die("Unable to load Stack.");
	while (!feof($loadStack)) {
		$line = fgets($loadStack);
		if ($line != "") {
			array_push($stack, trim($line));
		}
	}
	fclose($loadStack);
	$stack = implode(",", $stack); //Convert from array to int for the JS
	
//Build UI
echo "
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<title>$profileFirst's album</title>
			<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../fonts.css' rel='stylesheet' type='text/css'>		
			<script type='text/javascript' src='../../java.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
			
			<link href='../../LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
			<script src='../../LightBox/js/jquery-1.11.0.min.js'></script>
			<script src='../../LightBox/js/lightbox.min.js'></script>
			
			<script>
				var stack = '$stack'.split(',');
				function writePost() {
					var title = document.getElementById('titleIdCode').value;
					var isHere = 0;
					
					for (var i = 0; i < stack.length; i++) {
						if (stack[i] == title) {
							isHere = 1;
						}
					}
					
					if (isHere == 0) {
						var img = document.getElementById('postImg').value;
						var content = document.getElementById('content').value;
						
						if (title == '') {
							alert('Give title to your post.');
						}
						else
						if (img == '' && content.trim() == '') {
							alert('Well write something or add a picture.');
						}
						else {
							document.getElementById('writePost').action = '../PHP/writeMethodAlbum.php';
							document.forms['writePost'].submit();
						}
					} else {
						var message = 'You already have post with title \"'+title+'\"';
						alert(message);
					}
				}
				
				var sendTo = [];
				function shareAlbumObject(userId, id) {
					if (sendTo.indexOf(userId) > -1) {
						document.getElementById(\"friend\"+id).style.webkitFilter = 'grayscale(1)';
						var index = sendTo.indexOf(userId);
						sendTo.splice(index, 1);
					} else {
						document.getElementById(\"friend\"+id).style.webkitFilter = 'grayscale(0)';
						sendTo.push(userId);
					}
					
					if (sendTo.length > 0) {
						document.getElementById('sendButton').style.visibility= 'visible';
					} else {
						document.getElementById('sendButton').style.visibility= 'hidden';
					}
				}
				
				function sendImage() {
					var shareWith = sendTo.toString();
					document.cookie = 'shareWith='+shareWith;
					window.location = 'sendAlbumImage.php';
				}
			</script>
		</head>
		<body>
			<div id='sub-logo'>
				<h1>Album</h1>
			</div>
			<div id='albumOptions'>
				<span>Free space: $getSizeInMB"."mb</span>
";
	
	if (!empty($stackOrder) && count($stackOrder) >= 2) {
		echo "<button type='button' class='slideShow' title='Slides' onclick='window.open(\"startSlideShow.php\")'><img src='http://cdn.flaticon.com/png/256/61433.png' /></button>";
	}
	
	if ($getSizeInMB >= 5.00) {
		echo "
			<button type='button' class='uploadButton' title='Upload' onclick='openDialog()'><img src='https://cdn2.iconfinder.com/data/icons/ios-7-icons/50/upload-128.png'></button>
			<form id='toUpload' style='display: none;' method='post' enctype='multipart/form-data'>
				<input type='file' name='fileToUpload' id='fileToUpload' onchange='startToUpload()'>
			</form>
		";
	}
	
echo "
			</div>
";
	include "loadMenu.php";
	include 'loadSuggestedBlogers.php';

echo "
			<div id='storeFriends'>
				<button class='sendButton' id='sendButton' onclick='sendImage()'>Send</button>
				<button class='hideButton' onclick='hideContainerFriends()'></button>
				<h1>Choose friends :</h1>
				<div id='chooseToSend'>
";
	
	$friendId = 0;
	foreach ($friendsStack as $friend) {
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
		
		$friendId++;
		$build = "
			<button type='button' onclick='shareAlbumObject(\"$friend\", \"$friendId\")'>
				<img id='friend$friendId' src='$friendImg' />
				$friendFN $friendLN
			</button>
		";
		
		echo $build;
	}

echo "
				</div>
			</div>
			<div id='makePost'>
				<button class='hideButton' onclick='hideContainerPost()'></button>
				<div id='container'>
					<form id='writePost' method='post'>
						<input type='text' placeholder='Give it title.' id='titleIdCode' name='title'>
						<input type='text' style='display: none;' id='postImg' name='photo'>
						<br>
						<textarea placeholder='What&#39;s up ?' id='content' name='content'></textarea>
						
						<a href='#' onclick='writePost()'>Post</a>
						
						<input type='hidden' name='sender' value='$sender'></input>
						<input type='hidden' name='fname' value='$profileFirst'></input>
						<input type='hidden' id='cmd' name='cmd' value='1'></input>
					</form>
				</div>
			</div>
			<div id='albumImages'>
";
	
	$countId = 0;
	foreach ($stackOrder as $picture) {
		$countId++;
		$src = "../Authors/$sender/Album/$picture";

		$parseType = ".".explode(".", $picture)[1];
		if (strpos($parseType, "jpg") || strpos($parseType, "png") || strpos($parseType, "jpeg") || strpos($parseType, "gif")) {
			$buildImg = "
				<div id='imgContainer' onmouseover='$(\"#$countId\").fadeIn(\"fast\");' onmouseleave='$(\"#$countId\").fadeOut(\"fast\");'>
					<img id='$picture' src='$src' alt='Bad image link :('>
					<div id='$countId' style='display: none;'>
						<div id='imgOptions'>
							<a href='$src' data-lightbox='roadtrip'>
								<button type='button' class='split'>View</button>
							</a>
							<button type='button' class='split' onclick='showContainerPost(\"$picture\", \"$sender\", \"$countId\")'>Make a story</button>
							<button type='button' class='split' onclick='showContainerFriends(\"$picture\")'>Send to a friend</button>
							<button type='button' class='split' onclick='setAsProfilePic(\"$picture\")'>Set as profile pic.</button>
							<button type='button' onclick='deleteObjectFromAlbum(\"$picture\")'>Delete</button>
						</div>
					</div>
					<form id='picture$picture' method='post' style='display: none'>
						<input id='pictureId' name='pictureId' value='$picture'>
					</form>
				</div>
			";
		}
		else 
		if (strpos($parseType, "mp4") || strpos($parseType, "ogg") || strpos($parseType, "webm")) {
			if (strpos($parseType, "mp4")) {
				$type = "mp4";
			}
			else
			if (strpos($parseType, "ogg")) {
				$type = "ogg";
			}
			else
			if (strpos($parseType, "webm")) {
				$type = "webm";
			}

			$buildImg = "
				<div id='imgContainer' onmouseover='$(\"#$countId\").fadeIn(\"fast\");' onmouseleave='$(\"#$countId\").fadeOut(\"fast\");'>
					<video>
						<source src='$src' type='video/$type'>
					</video>
					<div id='$countId' style='display: none;'>
						<div id='imgOptions'>
							<button type='button' class='split' onclick='playVideo(\"$src\", \"$type\")'>Play</button>
							<button type='button' class='split' onclick='showContainerPost(\"$picture\", \"$sender\")'>Make a story</button>
							<button type='button' class='split' onclick='showContainerFriends(\"$picture\")'>Send to a friend</button>
							<button type='button' onclick='deleteObjectFromAlbum(\"$picture\")'>Delete</button>
						</div>
					</div>
					<form id='picture$picture' method='post' style='display: none'>
						<input id='pictureId' name='pictureId' value='$picture'>
					</form>
				</div>
			";
		}

		echo $buildImg;
	}
	
echo "
			</div>
		</body>
";

#Scroll to point
	$getScrollPos = $_COOKIE['scrollToPos'];
	if (isset($getScrollPos)) {
		echo "
			<script>
				$(window).scrollTop($getScrollPos);
				document.cookie = 'scrollToPos=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			</script>
		";
	}
?>