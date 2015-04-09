<?php
	$logCheck = fopen("LogFlag.txt", "r") or die("Fatal error: Flag not found.");
	$flag = fread($logCheck, filesize("LogFlag.txt"));
	fclose($logCheck);
	
	if ($flag == "0") {
		header('Location: ../../Errors/E4.html');
	}
	else
	if ($flag == "1") {
		$followers =  fopen('Followers.html', 'r') or die('Unable to open file !');
		$followersCount = fread($followers, filesize('Followers.html'));
		fclose($followers);
		$doLine = 0;
		$config = fopen("config.txt", "r") or die("Unable to open this path.");
		while (! feof($config)) {
			$line = fgets($config);
	
			if ($doLine == 0) {
				$profilePic = $line;
			}
			else
			if ($doLine == 1) {
				$profileHref = $line;
			}
			else
			if ($doLine == 2) {
				$fullName = trim($line);
			}
			else
			if ($doLine == 3) {
				$profileFirst = trim($line);
			}
			else
			if ($doLine == 4) {
				$profileLast = trim($line);
			}
	
			$doLine++;
		}
		fclose($config);
	
		$profileName = "$profileFirst $profileLast";
		
		$stack = array();
		$loadStack = fopen("Posts/Stack.txt", "r") or die("Unable to load Stack.");
		while (!feof($loadStack)) {
			$line = fgets($loadStack);
			if ($line != "") {
				array_push($stack, trim($line));
			}
		}
		fclose($loadStack);
		$stack = implode(",", $stack); //Convert from array to int for the JS
		
		//Pull notifications
		$pullNotifications = fopen("Messages/Notification.txt", "r") or die("Unable to pull.");
		$countNotifications = fread($pullNotifications, filesize("Messages/Notification.txt"));
		fclose($pullNotifications);
		
echo "
		<html>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
				<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
				<link rel='icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
				<title>$profileFirst's story</title>
				<link href='../../../style.css' rel='stylesheet' type='text/css' media='screen'>
				<link href='../../../fonts.css' rel='stylesheet' type='text/css'>		
				<script type='text/javascript' src='../../../java.js'></script>
				<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
				<script type='text/javascript'> 
					var stack = '$stack'.split(',');
					var flag = 0;
					
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
						
						for (var i = 0; i < title.length; i++) {
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
							if (img == '' && content == '') {
								alert('Well write something or add a picture.');
							}
							else {
								document.getElementById('post').action = '../../PHP/writeMethod.php';
								document.forms['post'].submit();
							}
						} else {
							var message = 'You already have post with title \"'+title+'\"';
							alert(message);
						}
					}
					
					function loadBlogers() {
						document.getElementById('post').action = '../../PHP/loadBlogers.php';
						document.forms['post'].submit();
					}
					
					function editPost(title) {
						document.getElementById(title).action = '../../PHP/editMethod.php';
						document.forms[title].submit();
					}
					
					function deletePost(title) {
						document.getElementById(title).action = '../../PHP/deleteMethod.php';
						document.forms[title].submit();
					}
					
					function logOut() {
						document.getElementById('post').action = '../../PHP/LogOut.php';
						document.forms['post'].submit();
					}
					
					function openMessages(state) {
						if (state == 0) {
							document.getElementById('cmd').value = '0';
							document.getElementById('post').action = '../../PHP/storeMessages.php';
							document.forms['post'].submit();
						}
						else
						if (state == 1) {
							document.getElementById('cmd').value = '1';
							document.getElementById('post').action = '../../PHP/storeMessages.php';
							document.forms['post'].submit();
						}
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
				<div id='menu'>
					<a href='Loged.php' class='homeButton'><img src='$profilePic'></a>
					<a href='Settings.php'>Settings</a>
					<a href='#' onclick='loadBlogers()'>Blogers</a>
					<a href='#' onclick='logOut()'>Log out</a>
				</div>
				<div id='author'>
					<a href='$profileHref' target='_blank'>
						<img src='$profilePic'>
						<br>
						$profileName
					</a>
					<br>
					<div id='personalMessage'>
						<div id='left' class='fb-share-button' data-href='http://www.blogy.sitemash.net/Library/Authors/$fullName/Author.php' data-layout='button'></div>
";

	if ($countNotifications != "0") {
		echo "<a href='#' class='right-notification' onclick='openMessages(1)'>$countNotifications</a>";
	}
	else
	if ($countNotifications == "0") {
		echo "<a href='#' class='right' onclick='openMessages(0)'>Messages</a>";
	}						

echo "
					</div>
					<div id='followers'>
						<h1>$followersCount followers</h1>
						<a href='#' onclick='doPost()'>Post</a>
					</div>
				</div>
				<div id='body'>
					<form id='post' method='post' style='visibility: hidden; display: none;'>
							<input type='text' placeholder='Give it title.' id='title' name='title'>
							<input type='text' placeholder='Place link for an image.' id='postImg' name='photo'>
							<textarea placeholder='What&#39;s up ?' id='content' name='content'></textarea>
							<a href='#' onclick='writePost()'>Post</a>
							
							<input type='hidden' name='sender' value='$fullName'></input>
							<input type='hidden' name='fname' value='$profileFirst'></input>
							<input type='hidden' id='cmd' name='cmd' value='1'></input>
					</form>
					<table id='main-table'>
";
	$path = "Posts/*.txt";
	$stack = array();
	$stack_size = 0;
	
	$getStack = fopen("Posts/Stack.txt", "r") or die("Stack not found.");
	while (! feof($getStack)) {
		$line = fgets($getStack);
		$line = trim($line);
		if ($line != "") {
			array_push($stack, $line);
		}
	}
	
	$reversed_stack = array_reverse($stack);
	
	$post_count = 0;
	$count = 0;
	$flag = 0;
	$contentPost = (string)NULL;
	while ($post_count < count($reversed_stack)) {
		$count = 0;
		$fd = fopen("Posts/".$reversed_stack[$post_count].".txt", "r") or die("Unable to open post.");
		while (!feof($fd)) {
			$line = fgets($fd);
			if ($count == 0) {
				$titlePost = trim($line);
			}
			else
			if ($count == 1) {
				$postImg = trim($line);
			}
			else
			if ($count == 2 || $flag == 1) {
				$contentPost .= $line;
				$flag = 1;
			}
			$count++;
		}
		fclose($fd);
		
		if ($contentPost == "NULL") {
			$contentPost = NULL;
		}
		
		if ($postImg != "NULL") {
			$postBuild = "
			<tr>
				<td>
				</td>
				<td id='poster'>
					<div id='quickMenu'>
						<a href='#' onclick=\"editPost('$titlePost')\" class='left'>Edit<a>
						<a href='#' onclick=\"deletePost('$titlePost')\" class='right'>Delete</a><br>
						<form id='$titlePost' method='post'>
							<input name='sender' value='$fullName'></input>
							<input name='postId' value='$titlePost'></input>
							<input name='content' value='$contentPost'></input>
						</form>
					<h1>$titlePost</h1>
					</div>
					<img src='$postImg' alt='Image link is broken :('/>
					<p>
						$contentPost
					</p>
				</td>
				<td>
				</td>
			</tr>
			<tr>
				<td>
					<br>
				</td>
			</tr>
			";
		}
		else
		if ($postImg == "NULL") {
			$postBuild = "
			<tr>
				<td>
				</td>
				<td id='poster'>
					<div id='quickMenu'>
						<a href='#' onclick=\"editPost('$titlePost')\" class='left'>Edit<a>
						<a href='#' onclick=\"deletePost('$titlePost')\" class='right'>Delete</a>
						<form id='$titlePost' method='post'>
							<input name='sender' value='$fullName'></input>
							<input name='postId' value='$titlePost'></input>
							<input name='content' value='$contentPost'></input>
						</form>
					<h1>$titlePost</h1>
					</div>
					<p>
						$contentPost
					</p>
				</td>
				<td>
				</td>
			</tr>
			<tr>
				<td>
					<br>
				</td>
			</tr>
			";
		}
		
		$post_count++;
		echo "$postBuild";
		$contentPost = NULL;
	}
	
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
	}
?>