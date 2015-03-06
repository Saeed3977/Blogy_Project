<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$senderPic = $_SESSION['senderImg'];
	
	$line_counter = 0;
	$parseSender = fopen("../Authors/$sender/config.txt", "r") or die("Unable to start parsing.");
	while (!feof($parseSender)) {
		$line = trim(fgets($parseSender));
		if ($line_counter == 6) {
			$senderMail = $line;
			break;
		}
		$line_counter++;
	}
	fclose($parseSender);
	
	//Get cookies
	$blogerSender = $_COOKIE['blogSender'];
	if (!isset($blogerSender) || $blogSender == "") {
		echo "<script>window.close();</script>";
	}
	$blogerFN = $_COOKIE['blogerFN'];
	$blogerLN = $_COOKIE['blogerLN'];
	$blogerImg = $_COOKIE['blogerImg'];
	$blogerHref = $_COOKIE['blogerHref'];
	$profileName = "$blogerFN $blogerLN";
	
	$followersLoad = fopen("../Authors/$blogerSender/Followers.html", "r") or die("Unable to load Followers.");
	$followersCount = fread($followersLoad, filesize("../Authors/$blogerSender/Followers.html"));
	fclose($followersLoad);
	
echo " 
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<title>$blogerFN's story</title>
			<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../fonts.css' rel='stylesheet' type='text/css'>
			<script type='text/javascript' src='../../java.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>			
			<script type='text/javascript'>
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
				
				function doPost() {
					if (flag == 0) {
						document.getElementById('messageInput').style.visibility='visible'; 
						$('#messageInput').slideDown('fast');
						flag = 1;
					}
					else
					if (flag == 1) {
						$('#messageInput').slideUp('fast');
						document.getElementById('messageInput').style.visibility='hidden'; 
						flag = 0;
					}
				}
			
				function sendMessage() {
					var text = document.getElementById('content').value;
					if (text == '') {
						alert('Well write something in your message.');
					}
					else
					if (text != '') {
						document.getElementById('messageInput').action = 'sendMessage.php';
						document.forms['messageInput'].submit();
					}
				}
			
				function loadBlogers() {
					document.getElementById('post').action = 'loadBlogers.php';
					document.forms['post'].submit();
				}

				function logOut() {
					document.getElementById('post').action = 'LogOut.php';
					document.forms['post'].submit();
				}
				
				function followAuthor() {
					document.getElementById('post').action = 'followBloger.php';
					document.forms['post'].submit();
				}
				
				function openMessages(state) {
					if (state == 0) {
						document.getElementById('cmd').value = '0';
						document.getElementById('accountInfo').action = '../PHP/storeMessages.php';
						document.forms['accountInfo'].submit();
					}
					else
					if (state == 1) {
						document.getElementById('cmd').value = '1';
						document.getElementById('accountInfo').action = '../PHP/storeMessages.php';
						document.forms['accountInfo'].submit();
					}
				}
				
				function exploreStories() {
					document.getElementById('accountInfo').action = '../PHP/exploreFStories.php';
					document.forms['accountInfo'].submit();
				}
				
				function shareStory() {
					document.getElementById('share').action = '../PHP/writeMethod.php';
					document.forms['share'].submit();
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
			<form id='accountInfo' method='post' style='display: none;'>
				<input type='text' name='sender' value='$sender'></input>
				<input type='text' id='cmd' name='cmd'></input>
			</form>
			<form id='post' method='post' style='display: none;'>
				<input name='sender' value='$sender'></input>
				<input name='authorId' value='$blogerSender'></input>
			</form>

			<div id='shareMethod' style='display: none;'>
				<div id='buttons'>
					<div id='facebook'>
						<a href='http://www.facebook.com/share.php?u=http://www.blogy.sitemash.net/Library/Authors/$blogerSender/Author.php&title=$blogerFN $blogerLN's story' target='_blank'>
							<img src='https://cdn1.iconfinder.com/data/icons/logotypes/32/square-facebook-128.png' />
						</a>
					</div>
					<div id='twitter'>
						<a href='http://twitter.com/home?status=Check+this+story+http://www.blogy.sitemash.net/Library/Authors/$blogerSender/Author.php' target='_blank'>
							<img src='https://cdn1.iconfinder.com/data/icons/logotypes/32/square-twitter-128.png' />
						</a>
					</div>
					<div id='googlePlus'>
						<a href='https://plus.google.com/share?url=http://www.blogy.sitemash.net/Library/Authors/$blogerSender/Author.php' target='_blank'>
							<img src='https://cdn1.iconfinder.com/data/icons/logotypes/32/square-google-plus-128.png' />
						</a>
					</div>
					<div id='senderIcon'>
						<a href='#' onclick='shareStory()'>
							<img src='$senderPic' />
						</a>
						<form id='share' method='post' style='display: none;'>
							<input type='password' value='2' name='cmd'>
							<input type='password' value='$blogerSender' name='blogerId'>
							<input type='password' value='$blogerFN' name='authorFN'>
							<input type='password' value='$blogerLN' name='authorLN'>
							<input type='password' value='$blogerImg' name='authorImage'>
							<input type='password' value='$blogerHref' name='authorHref'>
						</form>
					</div>
				</div>
			</div>
			
			<div id='author'>
				<div class='left'>
					<a title='Share' href='#' onclick='shareIt()'>
						<img src='https://cdn3.iconfinder.com/data/icons/virtual-notebook/16/button_share-128.png' />
					</a>
				</div>
				<div class='right'>
					<a href='#' onclick='doPost()'>
						<img src='https://cdn4.iconfinder.com/data/icons/linecon/512/send-128.png' />
					</a>
				</div>
";

	if ($blogerHref != "NULL") {
		echo "
			<a href='$blogerHref' target='_blank'>
				<img src='$blogerImg' />
				<br>
				$profileName
			</a>
		";
	}
	else
	if ($blogerHref == "NULL") {
		echo "
			<a class='inactive'>
				<img src='$blogerImg' />
				<br>
				$profileName
			</a>
		";
	}

echo "
			<br>
			<div id='followers'>
				<h1>$followersCount followers</h1>
";

	$isFollower = 0;
	$loadStack = fopen("../Authors/$blogerSender/FollowersID.html", "r") or die("Unable to load stack.");
	while (!feof($loadStack)) {
		$line = trim(fgets($loadStack));
		if ($line == $senderMail) {
			$isFollower = 1;
			break;
		}
	}
	fclose($loadStack);
	
	if ($isFollower == 0) {
		echo "<a href='#' onclick='followAuthor()'>Follow</a>";
	}
	else
	if ($isFollower == 1) {
		echo "
		<div id='follower'>
			<a href='#' onclick='followAuthor()'>Unfollow</a>
		</div>";
	}
			
			//	<a href='#' onclick='followAuthor()'>Follow</a>

echo "
			</div>
		</div>
		<div id='body'>
			<form id='messageInput' method='post' style='visibility: hidden; display: none;'>
				<textarea placeholder='What&#39;s up ?' id='content' name='content'></textarea>
				<a href='#' onclick='sendMessage()'>Send</a>
				
				<div style='display: none;'>
					<input type='text' name='sender' value='$sender'></input>
					<input type='text' name='authorId' value='$blogerSender'></input>
					<input type='text' name='cmd' value='0'></input>
				</div>
			</form>
			<table id='main-table'>
";

	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$history = array();
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "SELECT STACK, BUILD FROM stack$blogerSender";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$postId = $row['STACK'];
				$postId = str_replace("6996", " ", $postId);
				array_push($history, $postId);
			}
		}
	}
	$conn->close();
	
	$reversed_stack = array_reverse($history);
	
	$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	$post_count = 0;
	$count = 0;
	$flag = 0;
	$contentPost = (string)NULL;
	while ($post_count < count($reversed_stack)) {
		$buildShared = 0;
		$builder = 0;
		$count = 0;
		$fd = fopen("../Authors/$blogerSender/Posts/".$reversed_stack[$post_count].".txt", "r") or die("Unable to open post.");
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
						if ($authorSender == $sender) {
							$contentPost = "
								<a href='logedIn.php'>
									<img src='$authorSenderImg' alt='Bad image link :(' />
								</a>
							";
						}
						else
						if ($authorSender != $sender) {
							$contentPost = "
								<a href='openBloger.php' onclick=\"openBloger('$authorSender')\">
									<img src='$authorSenderImg' alt='Bad image link :(' />
								</a>
								<form id='$authorSender' method='post' style='display: none;'>
									<input type='password' name='blogSender' value='$authorSender'></input>
									<input type='password' name='blogerFN' value='$authorSenderFN'></input>
									<input type='password' name='blogerLN' value='$authorSenderLN'></input>
									<input type='password' name='blogerImg' value='$authorSenderImg'></input>
									<input type='password' name='blogerHref' value='$authorSenderHref'></input>
								</form>
							";
						}
						$buildShared = 0;
					}
				} else {
					$url = NULL;
					if(preg_match($reg_exUrl, $line, $url)) {
						$line = preg_replace($reg_exUrl, "<a href='$url[0]' target='_blank'>$url[0]</a>", $line);
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
			
			$postBuild = "
			<tr>
				<td>
				</td>
				<td id='poster'>
					<h1>$titlePost</h1>
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
					<h1>$titlePost</h1>
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
			</table>
		</div>
	</body>
</html>
";
?>