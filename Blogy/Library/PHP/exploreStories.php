<?php
	$sender = $_POST['sender'];
	
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
		}
		else
		if ($doLine == 5) {
			$pass = $line;
			break;
		}

		$doLine++;
	}
	fclose($config);
	
	//Pull notifications
	$pullNotifications = fopen("../Authors/$sender/Messages/Notification.txt", "r") or die("Unable to pull.");
	$countNotifications = fread($pullNotifications, filesize("../Authors/$sender/Messages/Notification.txt"));
	fclose($pullNotifications);	
		
echo "
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<title>Worldwide stories</title>
		<link href='../../style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../fonts.css' rel='stylesheet' type='text/css'>
		<script type='text/javascript' src='../../java.js'></script>
		
		<script type = 'text/javascript'> 
			function loadBlogers() {
				document.getElementById('accountInfo').action = '../PHP/loadBlogers.php';
				document.forms['accountInfo'].submit();
			}
			
			function logOut() {
				document.getElementById('accountInfo').action = '../PHP/LogOut.php';
				document.forms['accountInfo'].submit();
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
		
			function openBloger(title) {
				document.getElementById(title).action = 'openBloger.php';
				document.forms[title].submit();
			}
			
			function change() {
				document.getElementById('accountInfo').action = '../PHP/exploreFStories.php';
				document.forms['accountInfo'].submit();
			}
			
			function exploreStories() {
				document.getElementById('accountInfo').action = '../PHP/exploreStories.php';
				document.forms['accountInfo'].submit();
			}
			
			function openBloger(title) {
				if (title != \"$sender\") {
					document.getElementById(title).action = 'openBloger.php';
					document.forms[title].submit();
				} else {
					returnToHome();
				}
			}
		</script>
	</head>
	<body>
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
			<input type='text' id='cmd' name='cmd'></input>
		</form>
		
		<div id='sub-logo'>
			<a href='#' class='current'>Worldwide</a>
			/
			<a href='#' onclick='change()'>Following</a>
		</div>
		<div id='body'>
			<table id='main-table'>
			<br>
";

	//Pull stack
	$stack = array();
	$pullStack = fopen("../Authors/World/History.txt", "r") or die("Unable to pull");
	while (! feof($pullStack)) {
		$line = trim(fgets($pullStack));
		if ($line != "") {
			array_push($stack, $line);
		}
	}
	fclose($pullStack);
	
	$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	$stackCount = 0;
	$reversStack = array_reverse($stack);
	while ($stackCount < count($reversStack)) {
		$postId = $reversStack[$stackCount];
		$stackCount++;
		$postAuthor = $reversStack[$stackCount];
		$stackCount++;
		
		if (file_exists("../Authors/$postAuthor/Posts/$postId.txt") == 1) {
			$contentPost = (string)NULL;
			$count = 0;
			$fd = fopen("../Authors/$postAuthor/Posts/$postId.txt", "r") or die("Unable to open post.");
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
							<a href='#' onclick=\"openBloger('$postAuthor')\">
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
							<a href='#' onclick=\"openBloger('$postAuthor')\">
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
	}
	
echo "
			</table>
		</div>
	</body>
";
?>