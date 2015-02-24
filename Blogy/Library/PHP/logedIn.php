<?php
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
			<div id='menu'>
				<a href='logedIn.php' class='homeButton'><img src='$profilePic'></a>
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
				<a href='loadBlogers.php'>Blogers</a>
				<a href='exploreFStories.php'>Stories</a>
				<a href='#' onclick='logOut()'>Log out</a>
			</div>
			
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
		$buildShared = 0;
		$builder = 0;
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
				if (trim($line) == "$+Shared") {
					$buildShared = 1;
				} 
				
				if ($buildShared == 1) {
					if ($builder == 1) {
						$currentSender = trim($line);
					}
					else
					if ($builder == 2) {
						$currentSenderImg = trim($line);
					}
					else
					if ($builder == 3) {
						$authorSender = trim($line);
					}
					else
					if ($builder == 4) {
						$authorSenderFN = trim($line);
					}
					else
					if ($builder == 5) {
						$authorSenderLN = trim($line);
					}
					else
					if ($builder == 6) {
						$authorSenderImg = trim($line);
					}
					else
					if ($builder == 7) {
						$authorSenderHref = trim($line);
					}
					
					$builder++;
					if (trim($line) == "$-") {
						$builder = 1;
						$contentPost = "
							<a href='#' onclick=\"openBloger('$authorSender')\">
								<img src='$authorSenderImg' alt='Bad image link :(' />
							</a>
							<form id='$authorSender' method='post' style='display: none;'>
								<input type='password' name='accSender' value='$currentSender'></input>
								<input type='password' name='imgSender' value='$currentSenderImg'></input>
								<input type='password' name='blogSender' value='$authorSender'></input>
								<input type='password' name='blogerFN' value='$authorSenderFN'></input>
								<input type='password' name='blogerLN' value='$authorSenderLN'></input>
								<input type='password' name='blogerImg' value='$authorSenderImg'></input>
								<input type='password' name='blogerHref' value='$authorSenderHref'></input>
							</form>
						";
						$buildShared = 0;
					}
				} else {
					$url = NULL;
					if(preg_match($reg_exUrl, $line, $url)) {
						if (!strpos($line, "<img") && !strpos($line, "<a")) {
							$line = preg_replace($reg_exUrl, "<a href='$url[0]' target='_blank'>$url[0]</a>", $line);
						}
					}
					$contentPost .= $line."<br>";
				}
				$flag = 1;
			}
			$count++;
		}
		fclose($fd);
		
		if ($contentPost == "NULL<br>") {
			$contentPost = NULL;
		}
		
		
		if (is_numeric($titlePost)) {
			$titleId = "id$titlePost";
		} else {
			$titleId = $titlePost;
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
						<a href='#' onclick=\"editPost('$titleId')\" class='left'>Edit<a>
						<a href='#' onclick=\"deletePost('$titleId')\" class='right'>Delete</a><br>
						<form id='$titleId' method='post'>
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
			if ($builder == 0) {
				$postBuild = "
				<tr>
					<td>
					</td>
					<td id='poster'>
						<div id='quickMenu'>
							<a href='#' onclick=\"editPost('$titleId')\" class='left'>Edit<a>
							<a href='#' onclick=\"deletePost('$titleId')\" class='right'>Delete</a>
							<form id='$titleId' method='post'>
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
			else
			if ($builder == 1) {
				$postBuild = "
				<tr>
					<td>
					</td>
					<td id='poster'>
						<div id='quickMenu'>
							<a href='#' class='left' style='visibility: hidden;'>Edit<a>
							<a href='#' onclick=\"deletePost('$titleId')\" class='right'>Delete</a>
							<form id='$titleId' method='post'>
								<input name='sender' value='$fullName'></input>
								<input name='postId' value='$titlePost'></input>
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
?>