<?php
	$fName = strip_tags($_POST['fName']);
	$lName = strip_tags($_POST['lName']);
	$mail = strip_tags($_POST['mail']);
	$picture = strip_tags($_POST['picture']);
	$profile = strip_tags($_POST['social']);
	$pass = strip_tags($_POST['pass']);
	
	if (!ctype_alnum($fName) || !ctype_alnum($lName)) {
		echo "<script>window.location='../Errors/E12.html'</script>";
		die();
	}
	
	if ($profile == "") {
		$profile = "NULL";
	}
	
	$error = "Unable to open file.";
	$fullName = "$fName$lName";
	$freeName = 0;
	$flag = 0;
	
	function isFree($location, $name) {
		while (! feof($location)) {
			$lineGet = fgetcsv($location);
			
			if ($lineGet[1] == $name) {
				//echo "Bad-Success";
				return 0;
			}
			else
			if ($lineGet[1] != $name) {
				//echo "Success";
				return 1;
			}
		}
	}
	
	//Check if user exists
	$csvCheck = fopen("../Authors/Info.csv", "r") or die("$error");
	while (! feof($csvCheck)) {
		$line = fgetcsv($csvCheck);
		
		if ($line[0] == $mail) {
			header('Location: ../Errors/E5.html');
			die();
		}
		else
		if ($line[1] == $fullName) {
			while ($freeName != 1) {
				$randomNum = rand(1, 100000);
				$fullName = "$fullName$randomNum";
				$freeName = isFree($csvCheck, $fullName);
			}
		}
	}
	fclose($csvCheck);
	
	//Add to CSV document
	$csvLine = array("$mail","$fullName","$pass");
	$fd = fopen("../Authors/Info.csv", "a") or die("$error");
	fputcsv($fd, $csvLine);
	fclose($fd);
	
	//Build Author
	mkdir("../Authors/$fullName", 0755, true);
	
	//Create security index
	$buildSecurity = fopen("../Authors/$fullName/index.php", "w") or die("Fatal: Unable to build security.");
	fwrite($buildSecurity, "<?php header('Location: ../../../SignIn.html');?>");
	fclose($buildSecurity);
		
		//User vision
	$content = authorTemplate("../Templates/Author.php");
	$author = fopen("../Authors/$fullName/Author.php", "w") or die("$error");
	fwrite($author, $content);
	fclose($author);
	
		//Follow button
	$iFollow = fopen("../Authors/$fullName/Following.txt", "w") or die("Unable to build file.");
	fclose($iFollow);
	
		//Follow action PHP
	$content = followingTemplate("../Templates/following.php");
	$followingphp = fopen("../Authors/$fullName/following.php", "w") or die("Unable to open file.");
	fwrite($followingphp, $content);
	fclose($followingphp);
	
	/*
		//Author vision
	$content = logedTemplate("../Templates/Loged.php");
	$loged = fopen("../Authors/$fullName/Loged.php", "w") or die("Unable to open file.");
	fwrite($loged, $content);
	fclose($loged);
	*/
	
		//Settings
	$content = settingsTemplate("../Templates/Settings.php");
	$settings = fopen("../Authors/$fullName/Settings.php", "w") or die("Unable to open file.");
	fwrite($settings, $content);
	fclose($settings);
	
	buildConfig("../Authors/$fullName", $picture, $profile, $fullName, $fName, $lName, $pass, $mail);
	
	mkdir("../Authors/$fullName/Posts", 0755, true);
	$stack = fopen("../Authors/$fullName/Posts/Stack.txt", "w") or die("Unable to build stack.");
	fclose($stack);
	
	mkdir("../Authors/$fullName/Messages", 0755, true);
	$stack = fopen("../Authors/$fullName/Messages/Stack.txt", "w") or die("Unable to build stack.");
	fclose($stack);
	$notifications = fopen("../Authors/$fullName/Messages/Notification.txt", "w") or die("Unable to build notifications.");
	fwrite($notifications, "0");
	fclose($notifications);
	
	function authorTemplate($path) {
		$fd = fopen($path, "r") or die("Unable to open file.");
		$template = fread($fd, filesize($path));
		fclose($fd);
		return $template;
	}
	
	function confFollowTemplate($path) {
		$fd = fopen($path, "r") or die("Unable to open file");
		$template = fread($fd, filesize($path));
		fclose($fd);
		return $template;
	}
	
	function followingTemplate($path) {
		$fd = fopen($path, "r") or die("Unable to open file.");
		$template = fread($fd, filesize($path));
		fclose($fd);
		return $template;
	}
	
	function logedTemplate($path) {
		$fd = fopen($path, "r") or die("Unable to open file.");
		$template = fread($fd, filesize($path));
		fclose($fd);
		return $template;
	}
	
	function settingsTemplate($path) {
		$fd = fopen($path, "r") or die("Unable to open file.");
		$template = fread($fd, filesize($path));
		fclose($fd);
		return $template;
	}
	
	function buildConfig($path, $picture, $profile, $fullName, $fName, $lName, $pass, $mail) {
		$fd = fopen("$path/config.txt", "w") or die("Unable to open file.");
		fwrite($fd, $picture.PHP_EOL);
		fwrite($fd, $profile.PHP_EOL);
		fwrite($fd, $fullName.PHP_EOL);
		fwrite($fd, $fName.PHP_EOL);
		fwrite($fd, $lName.PHP_EOL);
		fwrite($fd, $pass.PHP_EOL);
		fwrite($fd, $mail.PHP_EOL);
		fwrite($fd, "1".PHP_EOL);
		fwrite($fd, "1");
		fclose($fd);
		
		$followersID = fopen("$path/FollowersID.html", "w") or die("Unable to open file.");
		fclose($followersID);
	}
	
	//Send mail to admin
	$content = "User $fName $lName with e-mail: $mail has just join the community of Blogy. Check his/hers blog from here: http://www.blogy.sitemash.net/Library/Authors/$fullName/Author.php";
	mail('vtm.sunrise@gmail.com', 'New bloger', $content);
	
	header('Location: ../../SignIn.html');
	die();
?>