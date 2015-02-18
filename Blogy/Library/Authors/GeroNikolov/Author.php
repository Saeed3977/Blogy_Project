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
		
		if ($contentPost == "NULL") {
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