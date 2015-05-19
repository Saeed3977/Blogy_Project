<?php
	$doLine = 0;
	$config = fopen("config.txt", "r") or die("Unable to open this path.");
	while (! feof($config)) {
		$line = trim(fgets($config));

		if ($doLine == 0) {
			$profilePic = $line;
		}
		else
		if ($doLine == 1) {
			$profileHref = $line;
		}
		else
		if ($doLine == 2) {
			$fullName = $line;
		}
		else
		if ($doLine == 3) {
			$profileFirst = $line;
		}
		else
		if ($doLine == 4) {
			$profileLast = $line;
			break;
		}
		$doLine++;
	}
	fclose($config);
	$profileName = "$profileFirst $profileLast";
	
	$followersCount = -1;
	$countFollowers =  fopen("FollowersID.html", "r") or die("Fatal: Could not get Followers.");
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

	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Get stories
	$storeIDs = array();
	$countStories = 0;
	$sql = "SELECT ID, STORYTITLE, STORYLINK, STORYCONTENT FROM stack$fullName ORDER BY ID";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getId = $row["ID"];
			$countStories++;
			array_push($storeIDs, $getId);
		}
	}
	$storeIDs = implode(",", $storeIDs);
		
echo "
<html>
	<META http-equiv='content-type' content='text/html; charset=utf-8'>
	<head>
		<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
		<title>$profileFirst's story</title>
		<link href='../../../style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../../fonts.css' rel='stylesheet' type='text/css'>

		<link href='../../../LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
		<script src='../../../LightBox/js/jquery-1.11.0.min.js'></script>
		<script src='../../../LightBox/js/lightbox.min.js'></script>

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

			//Callback function - Load stories
			var container = [];
			var loops = parseInt('$countStories');
			var dinamic = loops;
			
			function callBack() {
				dinamic -= 5;
				loops--;
				if (loops > 0)
					loadStories(\"$storeIDs\", \"3\", 1, \"$fullName\");
			}

			var flag = 0;
		</script>
	</head>
	<body onload='loadStories(\"$storeIDs\", \"3\", 1, \"$fullName\");' onscroll='checkPos()'>
		<div id='menu'>
			<a href='../../../index.html' class='logo-button'><img src='../../images/Blogy-ICO.png' /></a>
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
				<h1>$cmdFollowers</h1>
				<a href='../../../SignIn.html'>Follow</a>
			</div>
		</div>
		<div id='body'>
			<table id='main-table'>
			</table>
		</div>
	</body>
</html>
";
?>