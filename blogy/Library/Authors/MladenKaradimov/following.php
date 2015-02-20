<?php
	$newMail = $_POST['mail'];
	
	if ($newMail != "") {
		$path = "FollowersID.html";
		$path1 = "Followers.html";
		
		$followersIDs = implode(',', file($path));
		
		$followersIDsARRAY = explode(',', $followersIDs);
		
		if (in_array($newMail, $followersIDsARRAY)) {
			header('Location: ../../Errors/E1.html');
		}
		else {
			$newID = fopen($path, "a") or die("Unable to open dir.");
			fwrite($newID, "\n$newMail");
			fclose($newID);
			
			$burst = fopen($path1, "r") or die("Unable to open dir.");
			$count = fread($burst, filesize($path1));
			fclose($burst);
			
			$num = (int)$count + 1;
			
			$result = fopen($path1, "w") or die("Unable to open file !");
			fwrite($result, $num);
			fclose($result);
			
			$result = fopen($path1, "w") or die("Unable to open file !");
			fwrite($result, $num);
			fclose($result);
			
			$count = 0;
			$fd = fopen("config.txt", "r") or die("Unable to open file");
			while (!feof($fd)) {
				$line = fgets($fd);
				if ($count == 2) {
						$name = trim($line);
						break;
				}
				$count++;
			}
			
			$fd = fopen("../Info.csv", "r") or die("Unable to open file.");
			while (!feof($fd)) {
				$line = fgetcsv($fd);
				if ($line[1] == $name) {
					$mailAuthor = $line[0];
					break;
				}
			}
			
			$content = "Hello there somebody with e-mail: $newMail just started following you. Write post about that from here: http://www.blogy.sitemash.net/SignIn.html";
			mail($mailAuthor, 'New follower', $content);
			
			header('Location: Author.php');
		}
	}
	else {
		header('Location: conf_Following.html');
	}
	/*
	$follow = fopen($path "r") or die("Unable to open location.");
	$id = fread($path, filesize($path));
	fclose($follow);
	*/
?>