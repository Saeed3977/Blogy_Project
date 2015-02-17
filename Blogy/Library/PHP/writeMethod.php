<?php
	$cmd = $_POST['cmd'];
	$sender = $_POST['sender'];
	
	if ($cmd == "0") {
		$postId = $_POST['postId'];
		$postImg = $_POST['postImg'];
		$postContent = trim($_POST['content']);
		$postContent = nl2br($postContent);
		
		$writePost = fopen("../Authors/$sender/Posts/$postId.txt", "w") or die("Unable to locate post.");
		fwrite($writePost, $postId.PHP_EOL);
		if ($postImg != "") {
			fwrite($writePost, $postImg.PHP_EOL);
		}
		else
		if ($postImg == "") {
			fwrite($writePost, "NULL".PHP_EOL);
		}
		fwrite($writePost, $postContent);
		fclose($writePost);
	}
	else
	if ($cmd == "1") {
		$senderFirstName = $_POST['fname'];
		$titlePost = $_POST['title'];
		$contentPost = trim($_POST['content']);
		$contentPost = nl2br($contentPost);
		$postPic = $_POST['photo'];
		
		$length = strlen($titlePost);
		for ($ch = 0; $ch < $length; $ch++) {
			if ($titlePost[$ch] == chr(34)) {
				$titlePost[$ch] = "#";
			}
			else
			if ($titlePost[$ch] == chr(39)) {
				$titlePost[$ch] = "@";
			}
		}
		
		$addToStack = fopen("../Authors/$sender/Posts/Stack.txt", "a") or die("Unable to open file.");
		fwrite($addToStack, $titlePost.PHP_EOL);
		fclose($addToStack);
		
		$post = fopen("../Authors/$sender/Posts/$titlePost.txt", "w") or die("Unable to open file");
		fwrite($post, $titlePost.PHP_EOL);
		if ($postPic != "") {
			fwrite($post, $postPic.PHP_EOL);
		}
		else
		if ($postPic == "") {
			fwrite($post, "NULL".PHP_EOL);
		}
		
		if ($contentPost != "") {
			fwrite($post, $contentPost);
		}
		else
		if ($contentPost == "") {
			fwrite($post, "NULL");
		}
		fclose($post);
		
		$commitWorld = fopen("../Authors/World/History.txt", "a") or die("Unable to commit.");
		fwrite($commitWorld, $sender.PHP_EOL);
		fwrite($commitWorld, $titlePost.PHP_EOL);
		fclose($commitWorld);
		
		sendMail($sender, $senderFirstName);
	}
	
	function sendMail($sender, $senderFirstName) {
		$followers = fopen("../Authors/$sender/Followers.html", "r") or die("Unable to open file.");
		$summary = fread($followers, filesize("../Authors/$sender/Followers.html"));
		fclose($followers);
		
		$followerFullName = (string)NULL;
		if ($summary != "0") {
			$followersIDs = fopen("../Authors/$sender/FollowersID.html", "r") or die("Unable to open file.");
			while (! feof($followersIDs)) {
				$toSend = (string)NULL;
				$parseFollowerConfig = (string)NULL;
				$followerID = trim(fgets($followersIDs));
				$line_count = 0;
				$getInfo = fopen("../Authors/Info.csv", "r") or die("Fatal error: Info.csv corrupted.");
				while (!feof($getInfo)) {
					$line = fgetcsv($getInfo);
					if ($line != "") {
						if ($followerID == $line[0]) {
							$followerFullName = $line[1];
							break;
						}
					}
				}
				fclose($getInfo);
				
				if ($followerFullName != "") {
					$count = 0;
					$parseFollowerConfig = fopen("../Authors/$followerFullName/config.txt", "r") or die("Unable to start parsing.");
					while (! feof($parseFollowerConfig)) {
						$pickLine = fgets($parseFollowerConfig);
						if ($count == 7) {
							$toSend = trim($pickLine);
							break;
						}
						$count++;
					}
					fclose($parseFollowerConfig);
					
					if ($toSend == "1") {
						$mail = $followerID;
						$mail = trim($mail);
						if ($mail != "") {
							$subject = "New blog in Blogy";
							$content = "Hello there. $senderFirstName just posted something into Blogy. Check it from here: http://www.blogy.sitemash.net/Library/Authors/$sender/Author.php";
							mail($mail, $subject, $content);
						}
					}
				}
			}
		}
	}

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
				<input name='sender' value='$sender'></input>
			</form>
		</body>
	</html>
";

	die();
?>