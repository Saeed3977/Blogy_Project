<?php
	$pic = $_POST['profilePic'];
	$social = $_POST['profileHref'];
	$fName = $_POST['fName'];
	$lName = $_POST['lName'];
	$pass = $_POST['pass'];
	$dir = $_POST['sender'];
	$notifyOnPost = $_POST['notifyOnPost'];
	$notifyOnMessage = $_POST['notifyOnMessage'];
	
	$count = 0;
	$loadConfig = fopen("../Authors/$dir/config.txt", "r") or die("Unable to open config.");
	while (! feof($loadConfig)) {
		$line = fgets($loadConfig);
		if ($count == 6) {
			$mail = trim($line);
		}
		$count++;
	}
	fclose($loadConfig);
	
	$path = "../Authors/$dir/config.txt";
	
	$fd = fopen("$path", "w") or die("Unable to open file.");
	fwrite($fd, $pic.PHP_EOL);
	fwrite($fd, $social.PHP_EOL);
	fwrite($fd, $dir.PHP_EOL);
	fwrite($fd, $fName.PHP_EOL);
	fwrite($fd, $lName.PHP_EOL);
	fwrite($fd, $pass.PHP_EOL);
	fwrite($fd, $mail.PHP_EOL);
	fwrite($fd, $notifyOnPost.PHP_EOL);
	fwrite($fd, $notifyOnMessage);
	fclose($fd);	
	
echo "
	<html>
		<head>
			<script type='text/javascript'>
				function reSend() {
					document.getElementById('post').action = 'logedIn.php';
					document.forms['post'].submit();
				}
			</script>
		</head>
		<body onload='reSend()'>
			<form id='post' method='post' style='display: none;'>
				<input name='sender' value='$dir'></input>
			</form>
		</body>
	</html>
";
	
	die();
?>