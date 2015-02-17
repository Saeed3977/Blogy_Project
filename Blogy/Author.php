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
			<div class='fb-share-button' data-href='http://www.blogy.sitemash.net/Library/Authors/$fullName/Author.php' data-layout='button'></div>
			<div id='followers'>
				<h1>$followersCount followers</h1>
				<a href='conf_Following.html'>Follow</a>
			</div>
		</div>
		<div id='body'>
			<table id='main-table'> 
						<tr>
							<td>
							</td>
							<td id='poster'>
								<h1>Test</h1>
								<p>
									Геро е смотан :D
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
			</table>
		</div>
	</body>
</html>
"
?>