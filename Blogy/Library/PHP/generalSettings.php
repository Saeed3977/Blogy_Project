<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}

	$doLine = 0;
	$config = fopen("../Authors/$sender/config.txt", "r") or die("Unable to open this path.");
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
	
	if ($profileHref == "NULL") {
		$profileHref = NULL;
	}
	
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
				var profilePic = document.getElementById('profilePic').value;
				var profileHref = document.getElementById('profileHref').value;
				var fName = document.getElementById('fName').value;
				var pass = document.getElementById('pass').value;
				
				var flag = 0;
			
				if (profilePic == '') {
					alert('Choose some your profile picture.');
					flag = 1;
				}
				else 
				if (profileHref == '') {
					document.getElementById('profileHref').value = 'NULL';
				}
				else
				if (fName == '') {
					alert('You are supposed to have First name.');
					flag = 1;
				}
				else
				if (lName == '') {
					alert('And you also need and Last name.');
					flag = 1;
				}
				else
				if (pass == '') {
					alert('Yes, you need password !');
					flag = 1;
				}
				else {
					flag = 0;
				}
				
				if (flag == 0) { 
					document.forms['controlPanel'].submit();
				}
			}

			function logOut() {
				document.getElementById('accountInfo').action = '../PHP/LogOut.php';
				document.forms['accountInfo'].submit();
			}
		</script>
	</head>
	<body>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "
		<form id='accountInfo' method='post' style='display: none;'>
			<input type='text' name='sender' value='$sender'></input>
			<input type='text' id='cmd' name='cmd'></input>
		</form>

		<div id='body'>
			<form id='controlPanel' action='../PHP/configBuild.php' method='post'>
				<div id='pushDownFirefox'>
					<h1>
					<img src='$profilePic' />
					<br>
					Profile picture
					</h1>
				</div>
				<input type='text' value='$profilePic' id='profilePic' name='profilePic'></input>
				<br>
				<h1>Social profile</h1>
				<input type='text' value='$profileHref' id='profileHref' name='profileHref'></input>
				<br>
				<h1>First name</h1>
				<input type='text' value='$profileFirst' id='fName' name='fName'></input>
				<br>
				<h1>Last name</h1>
				<input type='text' value='$profileLast' id='lName' name='lName'></input>
				<br>
				<h1>Password</h1>
				<input type='password' value='$pass' id='pass' name='pass'></input>
				<div style='display: none;'>
					<input type='text' value='$fullName' name='sender'>
					<input type='text' value='$notifyOnPost' name='notifyOnPost'>
					<input type='text' value='$notifyOnMessage' name='notifyOnMessage'>
				</div>
				<br>
				<div id='marginBottomFirefox'>
					<a href='#' onclick='start()'>Save</a>
				</div>
			</form>
		</div>
	</body>
";
?>