<?php
	$sender = $_POST['sender'];
	
	$logCheck = fopen("../Authors/$sender/LogFlag.txt", "r") or header('Location: ../../SignIn.html');
	$flag = fread($logCheck, filesize("../Authors/$sender/LogFlag.txt"));
	fclose($logCheck);
	
	if ($flag == "0") {
		header('Location: ../Errors/E4.html');
	}
	else
	if ($flag == "1") {
		$followers =  fopen("../Authors/$sender/Followers.html", "r") or die('Unable to open file !');
		$followersCount = fread($followers, filesize("../Authors/$sender/Followers.html"));
		fclose($followers);
	
	$doLine = 0;
		$config = fopen("../Authors/$sender/config.txt", "r") or die("Unable to open this path.");
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
				break;
			}

			$doLine++;
		}
		fclose($config);
		
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
		
		//Pull notifications
		$pullNotifications = fopen("../Authors/$sender/Messages/Notification.txt", "r") or die("Unable to pull.");
		$countNotifications = fread($pullNotifications, filesize("../Authors/$sender/Messages/Notification.txt"));
		fclose($pullNotifications);
		
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
						if (img == '' && content == '') {
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
				
				function loadBlogers() {
					document.getElementById('post').action = '../PHP/loadBlogers.php';
					document.forms['post'].submit();
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
				
				function openMessages(state) {
					if (state == 0) {
						document.getElementById('cmd').value = '0';
						document.getElementById('post').action = '../PHP/storeMessages.php';
						document.forms['post'].submit();
					}
					else
					if (state == 1) {
						document.getElementById('cmd').value = '1';
						document.getElementById('post').action = '../PHP/storeMessages.php';
						document.forms['post'].submit();
					}
				}
				
				function exploreFollowers() {
					document.getElementById('accountInfo').action = '../PHP/exploreFollowers.php';
					document.forms['accountInfo'].submit();
				}
				
				function exploreStories() {
					document.getElementById('accountInfo').action = '../PHP/exploreFStories.php';
					document.forms['accountInfo'].submit();
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
				<a href='#' onclick='returnToHome()' class='homeButton'><img src='$profilePic'></a>
";
	if ($countNotifications != "0") {
		echo "<a href='#' onclick='openMessages(1)' class='notification'>$countNotifications new</a>";
	}
	else
	if ($countNotifications == "0") {
		echo "<a href='#' onclick='openMessages(0)'>Messages</a>";
	}	
echo "
				<a href='#' onclick='openSettings()'>Settings</a>
				<a href='#' onclick='loadBlogers()'>Blogers</a>
				<a href='#' onclick='exploreStories()'>Stories</a>
				<a href='#' onclick='logOut()'>Log out</a>
			</div>
			
			<form id='accountInfo' method='post' style='display: none;'>
				<input type='text' name='sender' value='$sender'></input>
			</form>
			
			<div id='author'>
				<a href='$profileHref' target='_blank'>
					<img src='$profilePic' />
					<br>
					$profileName
				</a>
				<br>
				<div id='personalMessage'>
					<a title='send to Facebook' href='http://www.facebook.com/sharer.php?s=100&p[title]=&p[summary]=$profileFirst $profileLast&p[url]=http://www.blogy.sitemash.net/Library/Authors/$fullName/Author.php&p[images][0]=$profilePic' target='_blank'>
						Share
					</a>
				</div>
";
echo "
				<div id='followers'>
					<a href='#' class='header' onclick='exploreFollowers()'>$followersCount followers</a>
					<br>
					<a href='#' onclick='doPost()'>Post</a>
				</div>
			</div>
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

	$stack = array();
	$stack_size = 0;
	
	$getStack = fopen("../Authors/$sender/Posts/Stack.txt", "r") or die("Stack not found.");
	while (! feof($getStack)) {
		$line = trim(fgets($getStack));
		if ($line != "") {
			array_push($stack, $line);
		}
	}
	
	$reversed_stack = array_reverse($stack);
	
	$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	$post_count = 0;
	$count = 0;
	$flag = 0;
	$contentPost = (string)NULL;
	while ($post_count < count($reversed_stack)) {
		$count = 0;
		$fd = fopen("../Authors/$sender/Posts/".$reversed_stack[$post_count].".txt", "r") or die("Unable to open post.");
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
				$line = str_replace("<br />", "", $line);
				$url = NULL;
				if(preg_match($reg_exUrl, $line, $url)) {
					$line = preg_replace($reg_exUrl, "<a href='$url[0]' target='_blank'>$url[0]</a>", $line);
				}
				$contentPost .= $line."<br>";
				$flag = 1;
			}
			$count++;
		}
		fclose($fd);
		
		if ($contentPost == "NULL<br>") {
			$contentPost = NULL;
		}
		
		if ($postImg != "NULL") {
			$parseUrl = parse_url($postImg);

			if ($parseUrl['host'] == 'www.youtube.com') {
				$query = $parseUrl['query'];
				$queryParse = explode("=", $query);
				$src = "https://".$parseUrl['host']."/embed/$queryParse[1]";
				$cmd = "<iframe src='$src' frameborder='0' allowfullscreen></iframe>";
			}
			else 
			if ($parseUrl['host'] == 'vimeo.com') {
				$query = $parseUrl['path'];
				$cmd ="<iframe src='//player.vimeo.com/video$query' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
			}
			else
			if ($parseUrl['host'] == 'www.dailymotion.com') {
				$query = $parseUrl['path'];
				$src = "//www.dailymotion.com/embed/$query";
				$cmd = "<iframe src='$src' frameborder='0' allowfullscreen></iframe>";
			}
			else
			if ($parseUrl['host'] == 'www.metacafe.com') {
				$query = $parseUrl['path'];
				$queryParse = explode("/", $query);
				$src = "http://www.metacafe.com/embed/$queryParse[2]/";
				$cmd = "<iframe src='$src' allowFullScreen frameborder=0></iframe>";
			}
			else {
				$url_headers=get_headers($postImg, 1);

				if(isset($url_headers['Content-Type'])){
					$type=strtolower($url_headers['Content-Type']);

					$valid_image_type=array();
					$valid_image_type['image/png']='';
					$valid_image_type['image/jpg']='';
					$valid_image_type['image/jpeg']='';
					$valid_image_type['image/jpe']='';
					$valid_image_type['image/gif']='';
					$valid_image_type['image/tif']='';
					$valid_image_type['image/tiff']='';
					$valid_image_type['image/svg']='';
					$valid_image_type['image/ico']='';
					$valid_image_type['image/icon']='';
					$valid_image_type['image/x-icon']='';

					if(isset($valid_image_type[$type])) {
						$cmd = "<img src='$postImg' alt='Image link is broken :('/>";
					} else {
						$cmd = "<h2>Unsupported player :(</h2>";
					}
				}
			}
			
			/*
			<iframe src="//www.break.com/embed/2820004?embed=1" width="464" height="280" webkitallowfullscreen mozallowfullscreen allowfullscreen frameborder="0">
			*/
			
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
					$cmd
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