<?php
	//error_reporting(0);
/*
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	//$sender = $_POST['sender'];
*/
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];
/*
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "SELECT AuthorID, AuthorImg, AuthorHref, AuthorFN, AuthorLN FROM $authorLoger";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$sender = $row['AuthorID'];
				$profilePic = $row['AuthorImg'];
				$profileHref = $row['AuthorHref'];
				$profileFirst = $row['AuthorFN'];
				$profileLast = $row['AuthorLN'];
			}
		}
	}
	$conn->close();
*/
	
	$fullName = $sender;

	$followers =  fopen("../Authors/$sender/Followers.html", "r") or die('Unable to open file !');
	$followersCount = fread($followers, filesize("../Authors/$sender/Followers.html"));
	fclose($followers);
	$profileName = "$profileFirst $profileLast";
	
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
		
echo "
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<title>$profileFirst's story</title>
			<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../fonts.css' rel='stylesheet' type='text/css'>		
			<script type='text/javascript' src='../../java.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
			<script type='text/javascript'> 
				var stack = '$stack'.split(',');
				var flag = 0;
				
				var shareFlag = 0;
				function shareIt() {
					if (shareFlag == 0) {
						document.getElementById('shareMethod').style.visibility='visible'; 
						$('#shareMethod').slideDown('fast');
						shareFlag = 1;
					}
					else
					if (shareFlag== 1) {
						$('#shareMethod').slideUp('fast');
						document.getElementById('shareMethod').style.visibility='hidden'; 
						shareFlag = 0;
					}
				}
				
				function hideElement() {
					document.getElementById('post').style.visibility='hidden'; 
					$('#post').slideUp('fast');
				}
				
				function doPost() {
					if (flag == 0) {
						document.getElementById('post').style.visibility='visible'; 
						$('#post').slideDown('fast');
						flag = 1;
					}
					else
					if (flag == 1) {
						$('#post').slideUp('fast');
						document.getElementById('post').style.visibility='hidden'; 
						flag = 0;
					}
				}
				
				function writePost() {
					var title = document.getElementById('title').value;
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
				
				function editPost(title) {
					document.getElementById(title).action = '../PHP/editMethod.php';
					document.forms[title].submit();
				}

				function deletePost(title) {
					document.getElementById(title).action = '../PHP/deleteMethod.php';
					document.forms[title].submit();
				}
				
				function logOut() {
					document.getElementById('post').action = '../PHP/LogOut.php';
					document.forms['post'].submit();
				}
			</script>
		</head>
		<body onload='hideElement()'>
			<div id='fb-root'></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = '//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=249236501932040&version=v2.0';
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
";
	include 'loadMenu.php';
echo "
			<form id='accountInfo' method='post' style='display: none;'>
				<input type='text' name='sender' value='$sender'></input>
			</form>
			
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
			<a href='$profileHref' target='_blank'>
				<img src='$profilePic' />
				<br>
				$profileName
			</a>
		";
	}
	else
	if ($profileHref == "NULL") {
		echo "
			<a class='inactive'>
				<img src='$profilePic' />
				<br>
				$profileName
			</a>
		";
	}

echo "
				<div id='followers'>
					<a href='exploreFollowers.php' class='header'>$followersCount followers</a>
					<br>
					<a href='#' onclick='doPost()'>Post</a>
				</div>
			</div>
";
	include 'loadSuggestedBlogers.php';
echo "
			<div id='body'>
				<form id='post' method='post' style='visibility: hidden; display: none;'>
					<input type='text' placeholder='Give it title.' id='title' name='title'>
					<input type='text' placeholder='Place link for an image or to a video.' id='postImg' name='photo'>
					<textarea placeholder='What&#39;s up ?' id='content' name='content'></textarea>
					
					<a href='#' onclick='writePost()'>Post</a>
					
					<input type='hidden' name='sender' value='$fullName'></input>
					<input type='hidden' name='fname' value='$profileFirst'></input>
					<input type='hidden' id='cmd' name='cmd' value='1'></input>
				</form>
				<table id='main-table'>
";

	include 'loadStories.php';
	
	echo "
						<tr>
							<td>
							</td>
							<td id='poster'>
								<h1>Hello there :)</h1>
								<img src='https://scontent-a-ams.xx.fbcdn.net/hphotos-xap1/t31.0-8/10914966_812627088773787_3619532195404352482_o.jpg' alt='Image ling is broken :('>
								<p>
									Welcome on board <b>$profileFirst</b>.<br>
									This is the place where you can share photos and stories with your followers.<br>
									Have <b>fun</b> and enjoy <b>Blogy</b>-ing.<br>
								</p>
							</td>
							<td>
							</td>
						</tr>
					</table>
				</div>
			</body>
		</html>
";
?>