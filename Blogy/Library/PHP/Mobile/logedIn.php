<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../../SignIn.html');
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];
	
	$fullName = $sender;

	$followersCount = -1;
	$countFollowers =  fopen("../../Authors/$sender/FollowersID.html", "r") or die("Fatal: Could not get Followers.");
	while (!feof($countFollowers)) {
		$followersCount++;
		$line = trim(fgets($countFollowers));
	}
	fclose($countFollowers);
	
	if ($followersCount == "1") {
		$cmdFollowers = "$followersCount follower";
	} else {
		$cmdFollowers = "$followersCount followers";
	}
	
	$profileName = "$profileFirst $profileLast";
	
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";

	$storeIDs = array();
	$storePostsNames = array();
	$countStories = 0;

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "SELECT ID, STORYTITLE, STORYLINK, STORYCONTENT FROM stack$sender ORDER BY ID";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getId = $row["ID"];
				$getTitle = $row["STORYTITLE"];
				
				/*$getLink = $row["STORYLINK"];
				$getContent = $row["STORYCONTENT"];

				array_push($storePosts, parseContent($getTitle, $getLink, $getContent, 0, "0"));
				*/

				$countStories++;
				array_push($storeIDs, $getId);
				array_push($storePostsNames, $getTitle);
			}
		}
	}
	$conn->close();
	
	$storeIDs = implode(",", $storeIDs);
	$storePostsNames = implode(",", $storePostsNames); //Convert from array to int for the JS
		
echo "
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<title>$profileFirst's story</title>
			<link href='CSS/style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../../fonts.css' rel='stylesheet' type='text/css'>

			<script type='text/javascript' src='JAVA/java.js'></script>		
			<script type='text/javascript' src='../../../java.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
			<script src='http://code.jquery.com/jquery-1.11.0.min.js'></script>
			
			<link href='../../../LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
			<script src='../../../LightBox/js/jquery-1.11.0.min.js'></script>
			<script src='../../../LightBox/js/lightbox.min.js'></script>
			
			<script type='text/javascript'> 			
				var stack = '$storePostsNames'.split(',');
				var flag = 0;
				var POSTPOINTER;

				var shareFlag = 0;
				function shareIt() {
					if (shareFlag == 0) {
						$('#shareMethod').slideDown('fast');
						shareFlag = 1;
					}
					else
					if (shareFlag== 1) {
						$('#shareMethod').slideUp('fast');
						shareFlag = 0;
					}
				}
				
				function doPost() {
					$('#post').slideToggle('fast');
				}
				
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
							document.getElementById('post').action = '../PHP/writeMethod.php';
							document.forms['post'].submit();
						}
					} else {
						var message = 'You already have post with title \"'+title+'\"';
						alert(message);
					}
				}

				//Callback function - Load stories
				var container = [];
				var loops = parseInt('$countStories');
				var dinamic = loops;
				
				function callBack() {
					dinamic -= 5;
					loops--;
					if (loops > 0)
						loadStories(\"$storeIDs\", \"0\", 2, \"$sender\");
				}

				var flag = 0;
			</script>
		</head>
		<body onload='loadStories(\"$storeIDs\", \"0\", 2, \"$sender\");' onscroll='checkPos()'>
			<div id='editorContainer' onclick='$(\"#editorContainer\").fadeOut(\"fast\")'>
				<div id='containerTXT' onclick='event.cancelBubble=true;if(event.stopPropagation) event.stopPropagation();return false;'>
					<button class='hideButton' onclick='clearEditorContainer()'></button>
					<h1 id='editorTitle'>Title</h1>
					<input onclick='this.setSelectionRange(0, this.value.length)' type='text' id='editorLink' placeholder='Link to an image or to a video'>
					<textarea id='editorContent' placeholder=\"What's up ?\"></textarea>
					<button type='button' onclick='updatePost(1)'>Post</button>
				</div>
			</div>
";
	
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';

echo "
			<div id='shareMethod' style='display: none;'>
				<div id='buttons'>
					<div id='facebook'>
						<a href='http://www.facebook.com/share.php?u=http://www.blogy.sitemash.net/Library/Authors/$fullName/Author.php&title=$profileFirst $profileLast's story' target='_blank'>
							<img src='https://cdn1.iconfinder.com/data/icons/logotypes/32/square-facebook-128.png' />
						</a>
					</div>
					<div id='twitter'>
						<a href='http://twitter.com/home?status=Check+this+story+http://www.blogy.sitemash.net/Library/Authors/$fullName/Author.php' target='_blank'>
							<img src='https://cdn1.iconfinder.com/data/icons/logotypes/32/square-twitter-128.png' />
						</a>
					</div>
					<div id='googlePlus'>
						<a href='https://plus.google.com/share?url=http://www.blogy.sitemash.net/Library/Authors/$fullName/Author.php' target='_blank'>
							<img src='https://cdn1.iconfinder.com/data/icons/logotypes/32/square-google-plus-128.png' />
						</a>
					</div>
				</div>
			</div>
			
			<div id='author'>
				<div class='left'>
					<a title='Share' href='#' onclick='shareIt()'>
						<img src='https://cdn3.iconfinder.com/data/icons/virtual-notebook/16/button_share-128.png' />
					</a>
				</div>
				<div class='right' style='visibility: hidden;'>
					<a href='#'>
						<img src='https://cdn2.iconfinder.com/data/icons/metroicons/48/i.png' />
					</a>
				</div>
";
	
	if ($profileHref != "NULL") {
		echo "
			<div id='profilePictureImg'>
				<a href='$profilePic' class='profilePicture' data-lightbox='roadtrip'>
					<img src='$profilePic' />
				</a>
			</div>
			<br>
			<a href='$profileHref' target='_blank'>
				$profileName
			</a>
		";
	}
	else
	if ($profileHref == "NULL") {
		echo "
			<div id='profilePictureImg'>
				<a href='$profilePic' class='profilePicture' data-lightbox='roadtrip'>
					<img src='$profilePic' />
				</a>
			</div>
			<br>
			<a class='inactive'>
				$profileName
			</a>
		";
	}

echo "
				<div id='followers'>
					<a href='exploreFollowers.php' class='header'>$cmdFollowers</a>
					<br>
					<a href='#' onclick='doPost()'>Post</a>
				</div>
			</div>
";
	
echo "
			<div id='body'>
				<form id='post' method='post' style='display: none;' enctype='multipart/form-data' onclick='doPost()'>
					<div id='container' onclick='event.cancelBubble=true;if(event.stopPropagation) event.stopPropagation();return false;'>
						<input type='text' placeholder='Give it title.' id='titleIdCode' name='title'>
						<input type='text' placeholder='Place link for an image or a video' id='postImg' name='photo'>
						<textarea placeholder='What&#39;s up ?' id='content' name='content'></textarea>
						
						<a href='#' onclick='writePost()'>Post</a>
						
						<input type='hidden' name='sender' value='$fullName'></input>
						<input type='hidden' name='fname' value='$profileFirst'></input>
						<input type='hidden' id='cmd' name='cmd' value='1'></input>
					</div>
				</form>
				<table id='main-table'>
";

	/*foreach ($storePosts as $post) {
		echo "$post";
	}*/
	
	echo "
					</table>
				</div>
			</body>
		</html>
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