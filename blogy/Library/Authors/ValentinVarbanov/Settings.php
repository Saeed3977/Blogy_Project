<?php
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
		else
		if ($doLine == 5) {
			$pass = $line;
		}

		$doLine++;
	}
	fclose($config);
		
echo "
	<head>
		<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
		<title>$profileFirst's panel</title>
		<link href='../../../style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../../fonts.css' rel='stylesheet' type='text/css'>
		<script type='text/javascript' src='../../../java.js'></script>
		
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
					alert('Choose your social network account');
					flag = 1;
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
			
			function loadBlogers() {
				document.getElementById('controlPanel').action = '../../PHP/loadBlogers.php';
				document.forms['controlPanel'].submit();
			}
			
			function logOut() {
				document.getElementById('controlPanel').action = '../../PHP/LogOut.php';
				document.forms['controlPanel'].submit();
			}
		</script>
	</head>
	<body>
		<div id='menu'>
			<a href='Loged.php' class='homeButton'><img src='$profilePic'></a>
			<a href='Settings.php'>Settings</a>
			<a href='#' onclick='loadBlogers()'>Blogers</a>
			<a href='#' onclick='logOut()'>Log out</a>
		</div>
		<div id='body'>
			<form id='controlPanel' action='../../PHP/configBuild.php' method='post'>
				<h1>
				<img src='$profilePic' />
				<br>
				Profile picture
				</h1>
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
				<input type='hidden' value='$fullName' name='sender'>
				<br>
				<div class='addMarginSmall'></div>
				<a href='#' onclick='start()'>Save</a>
				<div class='addMarginSmall'></div>
			</form>
		</div>
	</body>
"
?>