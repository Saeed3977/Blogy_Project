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
	if (!isset($blogerSender)) {
		echo "<script>window.history.back();</script>";
	}
	
	if ($blogerSender == $sender) {
		echo "<script>window.location='logedIn.php'</script>";
	}

	$blogerFN = $_COOKIE['blogerFN'];
	$blogerLN = $_COOKIE['blogerLN'];
	$blogerImg = $_COOKIE['blogerImg'];
	$blogerHref = $_COOKIE['blogerHref'];
	$profileName = "$blogerFN $blogerLN";
	
	$followersCount = -1;
	$countFollowers =  fopen("../Authors/$blogerSender/FollowersID.html", "r") or die("Fatal: Could not get Followers.");
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
	} else {
		$blockedPersons = array();
		$sql = "SELECT BLOCKEDID FROM blockList$sender";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				array_push($blockedPersons, $row['BLOCKEDID']);
			}
		}
	}
	
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
			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>			
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>	
			
			<link href='../../LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
			<script src='../../LightBox/js/jquery-1.11.0.min.js'></script>
			<script src='../../LightBox/js/lightbox.min.js'></script>
			
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
				
				function followAuthor() {
					document.getElementById('post').action = 'followBloger.php';
					document.forms['post'].submit();
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
					<a href='#' onclick='showOptions()'>
						<img src='https://cdn3.iconfinder.com/data/icons/google-material-design-icons/48/ic_menu_48px-128.png' />
					</a>
					<div class='arrow_box' id='optionsMenu' style='display: none;'>
";
	if (!in_array($blogerSender, $blockedPersons)) {
		echo "
			<button type='button' class='split' onclick='showMessageBox(\"$blogerSender\")'>Send message</button><br>
			<form id='$blogerSender' method='post' style='display: none;'>
				<input type='text' name='blogSender' value='$blogerSender'></input>
				<input type='text' name='blogerFN' value='$blogerFN'></input>
				<input type='text' name='blogerLN' value='$blogerLN'></input>
				<input type='text' name='blogerImg' value='$blogerImg'></input>
				<input type='text' name='blogerHref' value='$blogerHref'></input>
			</form>
		";
	}

	if (file_exists("../Authors/$sender/Ohana.txt")) {
		$interupt = 0;
		$pullOhana = fopen("../Authors/$sender/Ohana.txt", "r") or die("Fatal: Could not get ohana.");
		while (!feof($pullOhana)) {
			$line = trim(fgets($pullOhana));
			if ($line != "") {
				if ($line == $blogerSender) {
					$button = "<button type='button' class='split' onclick='removeFromOhana(\"$blogerSender\")'>Remove from Ohana</button><br>";
					$interupt = 1;
					break;
				}
			}
		}
		
		if ($interupt == 0) {
			$button = "<button type='button' class='split' onclick='addToOhana(\"$blogerSender\")'>Add to Ohana</button><br>";
		}
	} else {
		$button = "<button type='button' class='split' onclick='addToOhana(\"$blogerSender\")'>Add to Ohana</button><br>";
	}
	
	if (!in_array($blogerSender, $blockedPersons)) {
		$blockUnblock = "<button type='button' onclick='blockUser(\"$blogerSender\")'>Block user</button></br>";
	} else {
		$blockUnblock = "<button type='button' onclick='unBlockUser(\"$blogerSender\")'>Unblock user</button></br>";
	}
	
echo "
						$button
						$blockUnblock
					</div>
				</div>
";

	if ($blogerHref != "NULL") {
		echo "
			<div id='profilePictureImg'>
				<a href='$blogerImg' class='profilePicture' data-lightbox='roadtrip'>
					<img src='$blogerImg' />
				</a>
			</div>
			<br>
			<a href='$blogerHref' target='_blank'>
				$profileName
			</a>
		";
	}
	else
	if ($blogerHref == "NULL") {
		echo "
			<div id='profilePictureImg'>
				<a href='$blogerImg' class='profilePicture' data-lightbox='roadtrip'>
					<img src='$blogerImg' />
				</a>
			</div>
			<br>
			<a class='inactive'>
				$profileName
			</a>
		";
	}

echo "
			<br>
			<div id='followers'>
				<h1>$cmdFollowers</h1>
";

	$isFollower = 0;
	$loadStack = fopen("../Authors/$blogerSender/FollowersID.html", "r") or die("Unable to load stack.");
	while (!feof($loadStack)) {
		$line = trim(fgets($loadStack));
		if (explode("-", $line)[0] == $senderMail) {
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
			<table id='main-table'>
";
	
	$history = array();
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "SELECT STACK, BUILD, VIEW FROM stack$blogerSender ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				echo html_entity_decode($row['VIEW']);
			}
		}
	}
	$conn->close();
	
echo "
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