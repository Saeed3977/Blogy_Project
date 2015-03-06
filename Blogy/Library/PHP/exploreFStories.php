<?php
	error_reporting(0);

	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];
	
	//Pull notifications
	$pullNotifications = fopen("../Authors/$sender/Messages/Notification.txt", "r") or die("Unable to pull.");
	$countNotifications = fread($pullNotifications, filesize("../Authors/$sender/Messages/Notification.txt"));
	fclose($pullNotifications);	
		
echo "
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<title>Stories of your people</title>
		<link href='../../style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../fonts.css' rel='stylesheet' type='text/css'>
		<script type='text/javascript' src='../../java.js'></script>
		<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
		
		<script type = 'text/javascript'> 
			function logOut() {
				document.getElementById('accountInfo').action = '../PHP/LogOut.php';
				document.forms['accountInfo'].submit();
			}
			
			function loadAll() {				
				document.getElementById('scrollPos').value = $(window).scrollTop();
				document.getElementById('reSend').action = '../PHP/exploreFStories.php';
				document.forms['reSend'].submit();
			}
		</script>
	</head>
	<body>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "	
		<div id='sub-logo'>
			<a href='exploreFStories.php'>Following</a>
			|
			<a href='exploreStories.php'>Worldwide</a>
		</div>
		<div id='body'>
			<table id='main-table'>
			<br>
";

	$followingCount = 0;
	$allPosts = array();
	$loadFollowing = fopen("../Authors/$sender/Following.txt", "r") or die("Unable to load Following.");
	while (!feof($loadFollowing)) {
		$line = trim(fgets($loadFollowing));
		if ($line != "") {
			$followingCount++;
			$postAuthor = $line;

			$files = scandir("../Authors/$postAuthor/Posts");
			foreach($files as $file) {
				if ($file != "Stack.txt" && $file != "." && $file != "..") { //&& date("Y-m-d", filemtime("../Authors/$postAuthor/Posts/$file")) == date("Y-m-d")) {
					$allPosts = array_merge($allPosts, array("$file`$postAuthor" => date("Y-m-d H:i:s", filemtime("../Authors/$postAuthor/Posts/$file"))));//array_push($todayPosts, $filedate("H:i:s", filemtime("../Authors/$postAuthor/Posts/$file")));
				}
			}
		}
	}
	fclose($loadFollowing);
		
	if ($followingCount == 0) {
		echo "<h1>You don't follow anybody :(<h1>";
	} else {
		arsort($allPosts);
		$postsToday = 0;
		$daysBack =$_GET['daysBack'];
		if (!isset($daysBack)) {
			$daysBack = 0;
		}
		foreach($allPosts as $key => $value) {
			$splitValue = explode(" ", $value);
			if ($splitValue[0] >= date("Y-m-d", strtotime("-$daysBack day"))) {
				buildPosts($key);
				$postsToday++;
			}
		}
		
		$scrollPos = $_GET['scrollPos'];
		if (!isset($scrollPos)) {
			$scrollPos = 0;
		}
		echo "
			<script>
				$(window).scrollTop($scrollPos);
			</script>
		";
	}
	
echo "</table>";
	
	if ($followingCount > 0) {
		$minDate = min(array_values($allPosts));
		if ($followingCount > 0 && date("Y-m-d", strtotime("-$daysBack day")) > explode(" ", $minDate)[0]) {
			$daysBack++;
			if ($postsToday == 0) {
				echo "<h1>No body posts today :'(</h1>";
			}
			echo "
				<div id='moreStories'>
					<button type='button' onclick='loadAll()'>
			";
			
			if ($daysBack == 1) {
				$toDisplay = "day";
			} else {
				$toDisplay = "days";
			}
				
			echo "
						Show stories from $daysBack $toDisplay back
					</button>
					<form id='reSend' method='get' style='display: none;'>
						<input type='text' id='daysBack' name='daysBack' value='$daysBack'>
						<input type='text' id='scrollPos' name='scrollPos'>
					</form>
				</div>
			";
		}
	}
	
echo "
			</div>
		</div>
	</body>
";

	function buildPosts($key) {
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		$buildShared = 0;
		$builder = 0;
		$keySplit = explode("`", $key);
		$post = $keySplit[0];
		$postAuthor = $keySplit[1];
		$contentPost = (string)NULL;
		$count = 0;
		$fd = fopen("../Authors/$postAuthor/Posts/$post", "r") or die("Unable to open post.");
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
		$count = 0;
		
		//Pull author
		$pullAuthor = fopen("../Authors/$postAuthor/config.txt", "r") or die("Unable to pull Author.");
		while (! feof($pullAuthor)) {
			$line = fgets($pullAuthor);
			if ($count == 0) {
				$authorImg = trim($line);
			}
			else
			if ($count == 1) {
				$authorHref = trim($line);
			}
			else
			if ($count == 3) {
				$authorFN = trim($line);
			}
			else
			if ($count == 4) {
				$authorLN = trim($line);
				break;
			}
			$count++;
		}
		fclose($pullAuthor);
		
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
					<div id='history'>
						<a href='openBloger.php' onclick=\"openBloger('$postAuthor')\">
							<img src='$authorImg' alt='Bad image link :('>
							<form id='$postAuthor' method='post' style='display: none;'>
								<input type='text' name='accSender' value='$sender'></input>
								<input type='text' name='imgSender' value='$profilePic'></input>
								<input type='text' name='blogSender' value='$postAuthor'></input>
								<input type='text' name='blogerFN' value='$authorFN'></input>
								<input type='text' name='blogerLN' value='$authorLN'></input>
								<input type='text' name='blogerImg' value='$authorImg'></input>
								<input type='text' name='blogerHref' value='$authorHref'></input>
							</form>
						</a>
					</div>
					<div id='history-right'>
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
					<div id='history'>
						<a href='openBloger' type='button' onclick=\"openBloger('$postAuthor')\">
							<img src='$authorImg' alt='Bad image link :('>
							<form id='$postAuthor' method='post' style='display: none;'>
								<input type='text' name='accSender' value='$sender'></input>
								<input type='text' name='imgSender' value='$profilePic'></input>
								<input type='text' name='blogSender' value='$postAuthor'></input>
								<input type='text' name='blogerFN' value='$authorFN'></input>
								<input type='text' name='blogerLN' value='$authorLN'></input>
								<input type='text' name='blogerImg' value='$authorImg'></input>
								<input type='text' name='blogerHref' value='$authorHref'></input>
							</form>
						</a>
					</div>
					<div id='history-right'>
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
		
		echo "$postBuild";
	}
?>