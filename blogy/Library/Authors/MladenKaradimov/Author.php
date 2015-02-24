<?php
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
				$profileHref = trim($line);
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
echo "
<html>
	<META http-equiv='content-type' content='text/html; charset=utf-8'>
	<head>
		<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
		<title>$profileFirst's story</title>
		<link href='../../../style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../../fonts.css' rel='stylesheet' type='text/css'>
		<script type='text/javascript' src='../../../java.js'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
		<script type='text/javascript'>
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
		<div id='menu'>
			<a href='http://www.vss.free.bg' target='_blank' class='logo-button' target='_blank'><img src='../../images/logo.png' /></a>
			<a href='../../../index.php'>Home</a>
			<a href='../../../Blogies_index.php'>Blogies</a>
			<a href='../../../Downloads.html'>Downloads</a>
			<a href='../../../SignIn.html'>Log in</a>
		</div>
		
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
			<br>
			<div id='followers'>
				<h1>$followersCount followers</h1>
				<a href='conf_Following.html'>Follow</a>
			</div>
		</div>
		<div id='body'>
			<table id='main-table'>
";

	$stack = array();
	$getStack = fopen("Posts/Stack.txt", "r") or die("Stack not found.");
	while (! feof($getStack)) {
		$line = fgets($getStack);
		$line = trim($line);
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
				$line = str_replace("<br />", "", $line);
				if (trim($line) == "$+Shared") {
					$buildShared = 1;
				} 
				
				if ($buildShared == 1) {
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
							<a href='http://www.blogy.sitemash.net/Library/Authors/$authorSender/Author.php' target='_blank'>
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