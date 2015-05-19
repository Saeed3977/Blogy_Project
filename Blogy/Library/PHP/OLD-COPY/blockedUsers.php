<?php
	$sender = $_COOKIE['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$profilePic = $_COOKIE['senderImg'];
	$profileHref = $_COOKIE['senderHref'];
	$profileFirst = $_COOKIE['senderFN'];
	$profileLast = $_COOKIE['senderLN'];
	
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
		</script>
	</head>
	<body>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "		
		<div id='sub-logo'>
			<h1>Blocked users</h1>
		</div>
		<div id='body'>
			<div id='blogers-list'>
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
		$sql = "SELECT BLOCKEDID FROM blockList$sender";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$lines_count = 0;
				$blockedId = $row['BLOCKEDID'];
				$blocked = fopen("../Authors/$blockedId/config.txt", "r") or die("Unable to open blocked.");
				while (!feof($blocked)) {
					$line = fgets($blocked);
					if ($lines_count == 0) {
						$blockedImg = trim($line);
					}
					if ($lines_count == 1) {
						$blockedHref = trim($line);
					}
					else
					if ($lines_count == 3) {
						$blockedFN = trim($line);
					}
					else
					if ($lines_count == 4) {
						$blockedLN = trim($line);
						break;
					}
					$lines_count++;
				}
				fclose($blocked);
				
				$loadComplete = "
					<button onclick=\"unBlockUser('$blockedId')\">
						<img src='$blockedImg' />
						$blockedFN $blockedLN
						<form id='$blockedId' method='post' style='display: none;'>
							<input type='text' name='blogSender' value='$blockedId'></input>
							<input type='text' name='blogerFN' value='$blockedFN'></input>
							<input type='text' name='blogerLN' value='$blockedLN'></input>
							<input type='text' name='blogerImg' value='$blockedImg'></input>
							<input type='text' name='blogerHref' value='$blockedHref'></input>
						</form>
					</button>
					<br>
				";	
				echo "$loadComplete";
			}
		}
	}
	$conn->close();
	
echo "
			</div>
		</div>
	</body>
";
?>