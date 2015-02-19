<?php
	$sender = $_POST['sender'];
	
	$logCheck = fopen("../Authors/$sender/LogFlag.txt", "r") or header('Location: ../../SignIn.html');
	$flag = fread($logCheck, filesize("../Authors/$sender/LogFlag.txt"));
	fclose($logCheck);
	
	if ($flag == "0") {
		header('Location: ../Errors/E4.html');
	}
	else
	if ($flag == "1") {
	$loadSender = fopen("../Authors/$sender/config.txt", "r") or die("Unable to load sender.");
	$senderPic = trim(fgets($loadSender));
	fclose($loadSender);
	
	$line_count = 0;
	
	$messangerId = $_POST['messangerId'];
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
			<title>Story with $messangerFN</title>
			<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../fonts.css' rel='stylesheet' type='text/css'>
			<script type='text/javascript' src='../../java.js'></script>
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
			
				function loadBlogers() {
						document.getElementById('post').action = 'loadBlogers.php';
						document.forms['post'].submit();
					}

				function logOut() {
					document.getElementById('post').action = 'LogOut.php';
					document.forms['post'].submit();
				}
				
				function openBloger() {
					document.getElementById('send').action = 'openBloger.php';
					document.forms['send'].submit();
				}
				
				function readMessage(id) {
					document.getElementById(id).action = 'readMessage.php';
					document.forms[id].submit();
				}
				
				function sendMessage() {
					var text = document.getElementById('messageTXT').value;
					var img = document.getElementById('imgHolder').value;
					
					if (text == '' && img == '') {
						alert('Message needs some text.');
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
			</script>
		</head>
		<body onload='hideElement()'>
			<div id='menu'>
				<a href='#' onclick='returnToHome()' class='homeButton'><img src='$senderPic'></a>
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
			<form id='post' method='post' style='display: none;'>
				<input name='sender' value='$sender'></input>
			</form>
			
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
					<a href='#' onclick='sendMessage()'>Send</a>
					<div style='display: none;'>
						<input type='text' name='sender' value='$sender'></input>
						<input type='text' name='authorId' value='$messangerId'></input>
						<input type='text' name='cmd' value='1'></input>
					</div>
				</form>
				<div id='message'>
					<div id='messenger'>
						<a href='#' onclick='openBloger()'>
							<img src='$messangerImg' alt='Bad image link :('/>
							$messangerFN $messangerLN
						</a>
						<form id='send' method='post' style='display: none;'>
							<input type='text' name='accSender' value='$sender'></input>
							<input type='text' name='imgSender' value='$senderPic'></input>
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
	
	$history = array();
	$loadHistory = fopen("../Authors/$sender/Messages/$messangerId/History.txt", "r") or die("Unable to load.");
	$flag = 0;
	while (!feof($loadHistory)) {
		$line = fgets($loadHistory);
		
		if (trim($line) == "NM") {
			$flag = 0;
			array_push($history, trim($line));
		}
		else
		if (trim($line) == "GUEST" || trim($line) == "HOST") {
			array_push($history, trim($line));
		}
		else
		if (trim($line) == "SI") {
			array_push($history, trim($line));
			$line = trim(fgets($loadHistory));
			array_push($history, $line);
		}
		else
		if ((trim($line) == "MT" || $flag == 1) && !feof($loadHistory)) {
			if ($flag == 0) {
				array_push($history, trim($line));
				$line = fgets($loadHistory);
				$flag = 1;
			}
			if ($line != "") {
				array_push($history, $line);
			}
		}
	}
	fclose($loadHistory);

	$currentLine = (string)NULL;
	$messageTXTarray = array();
	$messageTXT = (string)NULL;
	$oldMessageTXT = (string)NULL;
	
	$line_count = 0;
	$history_count = 0;
	$history_reverse = array_reverse($history);
	while ($history_count < count($history_reverse)) {
		while ($currentLine != "NM") {
			if ($currentLine != "MT" && $currentLine != "SI" && $currentLine != "GUEST" && $currentLine != "HOST" && $currentLine != "NM") {
				if ($currentLine != "") {
					array_push($messageTXTarray, $currentLine);
				}
			}
			else
			if ($currentLine == "MT") {
				$currentLine = $history_reverse[$history_count];
				$messageIMG = $currentLine;
				//$history_count += 2;
				$type = $history_reverse[$history_count + 2];
				$history_count++;
			}
			
			$currentLine = $history_reverse[$history_count];
			$history_count++;
		}
		
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		$count = 0;
		$reversedMessage = array_reverse($messageTXTarray);
		while ($count < count($reversedMessage)) {
			if (!strpos($reversedMessage[$count], "<img")) {
				$reversedMessage[$count] = str_replace("<br />", "", $reversedMessage[$count]);
				$url = NULL;
				if(preg_match($reg_exUrl, $reversedMessage[$count], $url)) {
					$reversedMessage[$count] = preg_replace($reg_exUrl, "<a href='$url[0]' target='_blank'>$url[0]</a>", $reversedMessage[$count]);
				}
			}
			$messageTXT .= $reversedMessage[$count]."<br>";
			$count++;
		}

		/*
		// The Regular Expression filter
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		$messageParse = explode(" ", $messageTXT);
		$messageParseCount = 0;

		while ($messageParseCount < count($messageParse)) {
			// Check if there is a url in the text
			$url = NULL;
			if(preg_match($reg_exUrl, $messageTXT, $url)) {
				//$messageTXT = preg_replace($reg_exUrl, "<a href='$url[0]' target='_blank'>$url[0]</a> ", $messageTXT);
				if (isValidImage($url[0])) {
					$messageTXT = preg_replace($reg_exUrl, "<img src='$url[0]' alt='Bad image link :(' />", $messageTXT);
				} else {
					$messageTXT = preg_replace($reg_exUrl, "<a href='$url[0]' target='_blank'>$url[0]</a> ", $messageTXT);
				}
			}
			
			$messageParseCount++;
		}
		*/
		
		if ($messageTXT != $oldMessageTXT) {
			if ($type == "GUEST") {
				$print = "
				<div align='left'>
					<div id='$type'>
						<div class='profileimg'>
							<img src='$messageIMG' alt='Bad image link :(' />
						</div>
						<p class='$type'>
							$messageTXT
						</p>
					</div>
					<br>
				</div>
				";
			}
			else
			if ($type == "HOST") {
				$print = "
				<div align='right'>
					<div id='$type'>
							<div class='profileimg'>
								<img src='$messageIMG' alt='Bad image link :(' />
							</div>
							<p class='$type'>
								$messageTXT
							</p>
						</div>
						<br>
				</div>
				";
			}
			
			echo "$print";
		}
		
		$currentLine = NULL;
		$oldMessageTXT = $messageTXT;		
		$messageTXT = NULL;
		$messageTXTarray = array();
	}
	
echo "
					</div>
				</div>
			</div>
		</body>
	</html>
";
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