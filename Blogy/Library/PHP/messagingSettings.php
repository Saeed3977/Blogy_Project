<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}

	$isChecked = (string)NULL;
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
		}
		else
		if ($doLine == 6) {
			$eMail = trim($line);
		}
		else
		if ($doLine == 7) {
			$notifyOnPost = trim($line);
		}
		else
		if ($doLine == 8) {
			$notifyOnMessage = trim($line);
		}

		$doLine++;
	}
	fclose($config);

echo "
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<title>$profileFirst's panel</title>
		<link href='../../style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../fonts.css' rel='stylesheet' type='text/css'>
		<script type='text/javascript' src='../../java.js'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
		<script type = 'text/javascript'> 
			function start() {			
				if (document.getElementById('notifyOP').checked == true) {
					document.getElementById('sendPost').value = '1';
				}
				else {
					document.getElementById('sendPost').value = '0';
				}
				
				if (document.getElementById('notifyOM').checked == true) {
					document.getElementById('sendMessage').value = '1';
				}
				else {
					document.getElementById('sendMessage').value = '0';
				}
			
				document.forms['controlPanel'].submit();
			}
			
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
			
			function exploreStories() {
				document.getElementById('accountInfo').action = '../PHP/exploreFStories.php';
				document.forms['accountInfo'].submit();
			}
		</script>
	</head>
	<body>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
	
	if ($notifyOnPost == "1") {
		$isChecked = "checked";
	} else {
		$isChecked = NULL;
	}
	
echo "	
		<form id='accountInfo' method='post' style='display: none;'>
			<input type='text' name='sender' value='$sender'></input>
			<input type='text' id='cmd' name='cmd'></input>
		</form>
		
		<div id='body'>
			<form id='controlPanel' action='../PHP/configBuild.php' method='post'>
				<h1>Notify me by e-mail, when someone I follow posts a story.</h1>
				<input type='checkbox' id='notifyOP' $isChecked></br>
";
	if ($notifyOnMessage == "1") {
		$isChecked = "checked";
	} else {
		$isChecked = NULL;
	}
echo "
				<h1>Notify me by my e-mail, when someone messages me.</h1>
				<input type='checkbox' id='notifyOM' $isChecked></br>
				<div style='display: none;'>
					<input type='text' value='$fullName' name='sender'>
					<input type='text' value='$profilePic' name='profilePic'>
					<input type='text' value='$profileHref' name='profileHref'>
					<input type='text' value='$profileFirst' name='fName'>
					<input type='text' value='$profileLast' name='lName'>
					<input type='password' value='$pass' name='pass'>
					<input type='text' id='sendPost' name='notifyOnPost'></br>
					<input type='text' id='sendMessage' name='notifyOnMessage'></br>
				</div>
				<div class='addMarginSmall'></div>
				<a href='#' onclick='start()'>Save</a>
				<div class='addMarginSmall'></div>
			</form>
		</div>
	</body>
";
?>