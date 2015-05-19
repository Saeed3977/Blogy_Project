<?php
	$sender = $_COOKIE['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$profilePic = $_COOKIE['senderImg'];
	$profileHref = $_COOKIE['senderHref'];
	$profileFirst = $_COOKIE['senderFN'];
	$profileLast = $_COOKIE['senderLN'];
	
	$fullName = $sender;

	$followersCount = -1;
	$countFollowers =  fopen("../Authors/$sender/FollowersID.html", "r") or die("Fatal: Could not get Followers.");
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
			<script src='http://code.jquery.com/jquery-1.11.0.min.js'></script>
			
			<link href='../../LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
			<script src='../../LightBox/js/jquery-1.11.0.min.js'></script>
			<script src='../../LightBox/js/lightbox.min.js'></script>
			
			<script type='text/javascript'> 			
				var stack = '$stack'.split(',');
				var flag = 0;
				
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
					if (flag == 0) {
						$('#post').slideDown('fast');
						flag = 1;
					}
					else
					if (flag == 1) {
						$('#post').slideUp('fast');
						flag = 0;
					}
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
				
				function editPost(title) {
					document.cookie = 'scrollToPos='+$(window).scrollTop();
					document.getElementById(title).action = '../PHP/editMethod.php';
					document.forms[title].submit();
				}

				function deletePost(title) {
					document.cookie = 'scrollToPos='+$(window).scrollTop();
					document.getElementById(title).action = '../PHP/deleteMethod.php';
					document.forms[title].submit();
				}
			</script>
		</head>
		<body>
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
				<form id='post' method='post' style='display: none;' enctype='multipart/form-data'>
					<input type='text' placeholder='Give it title.' id='titleIdCode' name='title'>
					<input type='text' placeholder='Place link for an image or a video' id='postImg' name='photo'>
					<input type='file' style='display:none' id='dialogWindow' name='fileUpload' onchange='sendLocation()'/>
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