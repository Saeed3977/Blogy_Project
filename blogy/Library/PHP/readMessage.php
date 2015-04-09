<?php
	session_start();
	$sender = $_SESSION['sender'];
	$profilePic = $_SESSION['senderImg'];

	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$line_count = 0;
	
	$messangerId = $_COOKIE['receiverId'];

	if (!isset($messangerId)) {
		echo "<script>window.history.back();</script>";
	}
	$parseMessanger = fopen("../Authors/$messangerId/config.txt", "r") or die("Unable to start parsing.");
	while (!feof($parseMessanger)) {
		$line = fgets($parseMessanger);
		if ($line_count == 0) {
			$messangerImg = trim($line);
		}
		else
		if ($line_count == 1) {
			$messangerHref = trim($line);
		}
		else
		if ($line_count == 3) {
			$messangerFN = trim($line);
		}
		else
		if ($line_count == 4) {
			$messangerLN = trim($line);
			break;
		}
		$line_count++;
	}
	fclose($parseMessanger);
	$line_count = 0;
	
	//Commit notifications
	$pullNotifications = fopen("../Authors/$sender/Messages/Notification.txt", "w") or die("Unable to commit.");
	fwrite($pullNotifications, "0");
	fclose($pullNotifications);
	
echo "
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<title>Story with $messangerFN</title>
			<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../fonts.css' rel='stylesheet' type='text/css'>
			<script type='text/javascript' src='../../java.js'></script>
			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
			<script type='text/javascript'>
				var flag = 0;
				var imgFlag = 0;
				
				function hideElement() {
					document.getElementById('answer').style.visibility='hidden'; 
					$('#answer').slideUp('fast');
					document.getElementById('imgPlaceholder').style.visibility='hidden';
					$('#imgPlaceholder').slideUp('fast');
				}
				
				function doPost() {
					if (flag == 0) {
						document.getElementById('answer').style.visibility='visible'; 
						$('#answer').slideDown('fast');
						flag = 1;
					}
					else
					if (flag == 1) {
						$('#answer').slideUp('fast');
						document.getElementById('answer').style.visibility='hidden'; 
						flag = 0;
					}
				}
				
				function addImg() {
					if (imgFlag == 0) {
						document.getElementById('imgPlaceholder').style.visibility='visible'; 
						$('#imgPlaceholder').slideDown('fast');
						imgFlag = 1;
					}
					else
					if (imgFlag == 1) {
						document.getElementById('imgPlaceholder').style.visibility='visible'; 
						$('#imgPlaceholder').slideUp('fast');
						imgFlag = 0;
					}
				}

				function sendMessage() {
					var text = document.getElementById('messageTXT').value;
					var img = document.getElementById('imgHolder').value;
					
					if (text.trim() == '' && img == '') {
						alert('Message needs something.');
					} else {
						if (text != '' && img != '') {
							document.getElementById('messageTXT').value +=  '<br /><img src=\"'+img+'\" alt=\"Bad image link :(\" />';
						}
						else
						if (text == '' && img != '') {
							document.getElementById('messageTXT').value += '<img src=\"'+img+'\" alt=\"Bad image link :(\" />';
						}
						
						document.getElementById('answer').action = 'sendMessage.php';
						document.forms['answer'].submit();
					}
				}
			</script>
		</head>
		<body onload='hideElement()'>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "
			<div id='body'>
				<form id='answer' method='post' style='visibility: hidden; display: none;'>
					<textarea id='messageTXT' name='content' placeholder='What&#39;s up'></textarea><br>
					<div id='imgPlaceholder' style='visibility: hidden; display: none;'>
						<div class='separate'></div>
						<input type='text' placeholder='Share image link' id='imgHolder'></input><br>
					</div>
					<a href='#' class='leftOption' onclick='addImg()'>
						<img id='imgHolder' src='https://cdn4.iconfinder.com/data/icons/adiante-apps-app-templates-incos-in-grey/128/app_type_photographer_512px_GREY.png' alt='Bad link :('>
					</a>
					<a href='#' class='rightOption' onclick='showEmojiContainer()'>
						<img src='https://cdn4.iconfinder.com/data/icons/imoticons/105/imoticon_12-128.png' alt='Bad link :(' />
					</a>
					<a href='#' onclick='sendMessage()'>Send</a>
					<div style='display: none;'>
						<input type='text' name='sender' value='$sender'></input>
						<input type='text' name='authorId' value='$messangerId'></input>
						<input type='text' name='cmd' value='1'></input>
					</div>
					<div class='emoji_container' id='emojis'>
						<button type='button' onclick='addEmoji(\"lol\")'><img src='../images/Emoji/lol.png' title='Laugh' /></button>
						<button type='button' onclick='addEmoji(\"smile\")'><img src='../images/Emoji/smile.png' title='Smile' /></button>
						<button type='button' onclick='addEmoji(\"lolo\")'><img src='../images/Emoji/lolo.png' title='Laugh out loud' /></button>
						<br>
						<button type='button' onclick='addEmoji(\"tongue\")'><img src='../images/Emoji/tongue.png' title='Tongue' /></button>
						<button type='button' onclick='addEmoji(\"inlove\")'><img src='../images/Emoji/inlove.png' title='Inlove' /></button>
						<button type='button' onclick='addEmoji(\"kiss\")'><img src='../images/Emoji/kiss.png' title='Kiss' /></button>
						<br>
						<button type='button' onclick='addEmoji(\"scare\")'><img src='../images/Emoji/scare.png' title='Scare' /></button>
						<button type='button' onclick='addEmoji(\"cry\")'><img src='../images/Emoji/cry.png' title='Cry' /></button>
						<button type='button' onclick='addEmoji(\"ooh\")'><img src='../images/Emoji/ooh.png' title='Ooh' /></button>
						<br>
						<button type='button' onclick='addEmoji(\"wat\")'><img src='../images/Emoji/wat.png' title='What' /></button>
						<button type='button' onclick='addEmoji(\"wink\")'><img src='../images/Emoji/wink.png' title='Wink' /></button>
						<button type='button' onclick='addEmoji(\"mybad\")'><img src='../images/Emoji/mybad.png' title='Oops' /></button>
						<br>
						<button type='button' onclick='addEmoji(\"meh\")'><img src='../images/Emoji/meh.png' title='Meh' /></button>
						<button type='button' onclick='addEmoji(\"sad\")'><img src='../images/Emoji/sad.png' title='Sad' /></button>
						<button type='button' onclick='addEmoji(\"muchCry\")'><img src='../images/Emoji/muchCry.png' title='Very very sad' /></button>
						<br>
						<button type='button' onclick='addEmoji(\"calm\")'><img src='../images/Emoji/calm.png' title='Calm' /></button>
						<button type='button' onclick='addEmoji(\"sexy\")'><img src='../images/Emoji/sexy.png' title='Hey sexy' /></button>
						<button type='button' onclick='addEmoji(\"angry\")'><img src='../images/Emoji/angry.png' title='You are going to ahh..' /></button>
					</div>
				</form>
				<div id='message'>
					<div id='messenger'>
						<a href='openBloger.php' onclick=\"openBloger('send')\">
							<img src='$messangerImg' alt='Bad image link :('/>
							$messangerFN $messangerLN
						</a>
						<form id='send' method='post' style='display: none;'>
							<input type='text' name='blogSender' value='$messangerId'></input>
							<input type='text' name='blogerFN' value='$messangerFN'></input>
							<input type='text' name='blogerLN' value='$messangerLN'></input>
							<input type='text' name='blogerImg' value='$messangerImg'></input>
							<input type='text' name='blogerHref' value='$messangerHref'></input>
						</form>
					</div>
					<div id='message-options'>
						<a href='#' onclick='doPost()'>
							Reply
						</a>
					</div>
					<div id='message-text'>
";
	
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$title = $sender."AND".$messangerId;
		$sql = "SELECT MESSANGER, MESSAGE FROM $title ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$authorId = $row['MESSANGER'];
				$message = $row['MESSAGE'];
				
				$message = convertMessage($message);
				
				if ($authorId == $sender) {
					echo "
						<div align='right'>
							<div id='HOST'>
								<div class='profileimg'>
									<img src='$profilePic' alt='Bad image link :(' />
								</div>
								<p class='HOST'>
									$message
								</p>
							</div>
							<br>
						</div>
					";
				}
				else
				if ($authorId == $messangerId) {
					echo "
						<div align='left'>
							<div id='GUEST'>
								<div class='profileimg'>
									<img src='$messangerImg' alt='Bad image link :(' />
								</div>
								<p class='GUEST'>
									$message
								</p>
							</div>
							<br>
						</div>
					";
				}
			}
		}
	}
	
echo "
					</div>
				</div>
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

	function convertMessage($message) {
		$message = html_entity_decode($message);
		
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		$message = strip_tags($message, "<br><br /><img>");
		if (!strpos($message, "Bad image link")) {
			$url = NULL;
			if(preg_match($reg_exUrl, $message, $url)) {
				$message = preg_replace($reg_exUrl, "<a href='$url[0]' target='_blank'>$url[0]</a>", $message);
			}
		}
		
		//:D
		$message = str_replace(":D", "<img src='../images/Emoji/lol.png' class='emoji'/>", $message);
		//:P
		$message = str_replace(":P", "<img src='../images/Emoji/tongue.png' class='emoji-fixed'/>", $message);
		$message = str_replace(":p", "<img src='../images/Emoji/tongue.png' class='emoji-fixed'/>", $message);
		//<3
		$message = str_replace("<3", "<img src='../images/Emoji/heart.png' class='emoji'/>", $message);
		//:O
		$message = str_replace(":O", "<img src='../images/Emoji/ooh.png' class='emoji'/>", $message);
		//:)
		$message = str_replace(":)", "<img src='../images/Emoji/smile.png' class='emoji'/>", $message);
		//;)
		$message = str_replace(";)", "<img src='../images/Emoji/wink.png' class='emoji'/>", $message);
		//:(
		$message = str_replace(":(", "<img src='../images/Emoji/sad.png' class='emoji'/>", $message);
		//;'(
		$message = str_replace(":'(", "<img src='../images/Emoji/cry.png' class='emoji'/>", $message);
		$message = str_replace(";(", "<img src='../images/Emoji/cry.png' class='emoji'/>", $message);
		//:*
		$message = str_replace(":*", "<img src='../images/Emoji/kiss.png' class='emoji-fixed'/>", $message);
		//0.0
		$message = str_replace("0.0", "<img src='../images/Emoji/wat.png' class='emoji'/>", $message);
		$message = str_replace("O.O", "<img src='../images/Emoji/wat.png' class='emoji'/>", $message);
		$message = str_replace("{49}", "<img src='../images/Emoji/wat.png' class='emoji'/>", $message);
		//Inlove
		$message = str_replace("{2369}", "<img src='../images/Emoji/inlove.png' class='emoji'/>", $message);
		//Scare
		$message = str_replace(":|", "<img src='../images/Emoji/scare.png' class='emoji'/>", $message);
		$message = str_replace("{666}", "<img src='../images/Emoji/scare.png' class='emoji'/>", $message);
		//MyBad - Oops
		$message = str_replace("{118}", "<img src='../images/Emoji/mybad.png' class='emoji'/>", $message);
		//Meh
		$message = str_replace("{999}", "<img src='../images/Emoji/meh.png' class='emoji'/>", $message);
		//Much Cry
		$message = str_replace("{7428}", "<img src='../images/Emoji/muchCry.png' class='emoji'/>", $message);
		//LoLo
		$message = str_replace("{1010}", "<img src='../images/Emoji/lolo.png' class='emoji'/>", $message);
		//Calm
		$message = str_replace(":3", "<img src='../images/Emoji/calm.png' class='emoji'/>", $message);
		//Sexy
		$message = str_replace("{1619}", "<img src='../images/Emoji/sexy.png' class='emoji'/>", $message);
		//Angry
		$message = str_replace(":@", "<img src='../images/Emoji/angry.png' class='emoji'/>", $message);
		
		return $message;
	}

	function isValidImage($urlPath) {
		$url_headers = get_headers($urlPath, 1);
		if (isset($url_headers['Content-Type'])) {
			error_reporting(E_ERROR | E_PARSE);
			$type = strtolower($url_headers['Content-Type']);
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
			if (isset($valid_image_type[$type])){
				return true; // Its an image
			}
			return false;// Its an URL
		}
	}
?>