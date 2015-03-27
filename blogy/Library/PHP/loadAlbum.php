<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];
	
	$checkPath = "../Authors/$sender/Album";
	if (!file_exists($checkPath)) {
		mkdir($checkPath, 0777);
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
	
echo "
				<button type='button' class='uploadButton' title='Upload' onclick='openDialog()'><img src='https://cdn2.iconfinder.com/data/icons/ios-7-icons/50/upload-128.png'></button>
				<form id='toUpload' style='display: none;' method='post' enctype='multipart/form-data'>
					<input type='file' name='fileToUpload' id='fileToUpload' onchange='startToUpload()'>
				</form>
			</div>
";
	include "loadMenu.php";
	include 'loadSuggestedBlogers.php';

echo "
			<div id='storeFriends'>
				<button class='hideButton' onclick='hideContainerFriends()'></button>
				<h1>Send to :</h1>
";

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
		
		$build = "
			<button type='button' onclick=' shareAlbumObject(\"$friend\")'>
				<img src='$friendImg' />
				$friendFN $friendLN
			</button>
		";
		
		echo $build;
	}

echo "
			</div>
			<div id='makePost'>
				<button class='hideButton' onclick='hideContainerPost()'></button>
				<div id='container'>
					<img id='imgToPost' src='#' />
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
	
	foreach ($stackOrder as $picture) {
		$src = "../Authors/$sender/Album/$picture";
		$buildImg = "
			<div id='imgContainer'>
				<img id='$picture' src='$src' alt='Bad image link :('>
				<div id='imgOptions'>
					<a href='$src' data-lightbox='roadtrip'>
						<button type='button' class='split'>View</button>
					</a>
					<button type='button' class='split' onclick='showContainerPost(\"$picture\", \"$sender\")'>Make a story</button>
					<button type='button' class='split' onclick='showContainerFriends(\"$picture\")'>Send to a friend</button>
					<button type='button' class='split' onclick='setAsProfilePic(\"$picture\")'>Set as profile pic.</button>
					<button type='button' onclick='deleteObjectFromAlbum(\"$picture\")'>Delete</button>
				</div>
				<form id='picture$picture' method='post' style='display: none'>
					<input id='pictureId' name='pictureId' value='$picture'>
				</form>
			</div>
		";
		echo $buildImg;
	}
	
echo "
			</div>
		</body>
";
?>