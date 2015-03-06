<?php
	require 'helpFunctions.php';

	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
			header("Location: ../../SignIn.html");
	}
	
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$cmd = $_POST['cmd'];
	
	$realoc = (string)NULL;
	
	if ($cmd == "0") {
		$postId = $_POST['postId'];
		$postImg = $_POST['postImg'];
		$postContent = trim($_POST['content']);
		$postContent = nl2br($postContent);
		
		if (strpos($postContent, "</script>")) {
			$postContent = str_replace("<script>", "", $postContent);
			$postContent = str_replace("</script>", "", $postContent);;
		}			
		if (strpos($postContent, "?php")) {
			$postContent = str_replace("<?php", "", $postContent);
			$postContent = str_replace("?>", "", $postContent);
		}
		
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
		
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} else {
			$postBuild = buildPost($sender, $postId);
			$postBuild = str_replace("'", "\'", $postBuild);
			$postBuild =  htmlentities($postBuild);
			if (strpos($postId, " ")) {
				$postId = str_replace(" ", "6996", $postId);
			}
			$sql = "UPDATE stack$sender SET BUILD='$postBuild' WHERE STACK='$postId'";
			$conn->query($sql);
		}
		$conn->close();
	}
	else
	if ($cmd == "1" || $cmd == "2") {
		if ($cmd == "1") {
			$senderFirstName = $_POST['fname'];
			$titlePost = $_POST['title'];
			$contentPost = trim($_POST['content']);
			$contentPost = nl2br($contentPost);
			$postPic = $_POST['photo'];
			
			if (strpos($contentPost, "</script>")) {
				$contentPost = str_replace("<script>", "", $contentPost);
				$contentPost = str_replace("</script>", "", $contentPost);;
			}			
			if (strpos($contentPost, "?php")) {
				$contentPost = str_replace("<?php", "", $contentPost);
				$contentPost = str_replace("?>", "", $contentPost);
			}
			
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
		}
		
		if ($cmd == "2") {
			$senderPic = $_POST['senderImg'];
			$authorId = $_POST['blogerId'];
			$senderFirstName = $_POST['authorFN'];
			$authorLN = $_POST['authorLN'];
			$authorImg = $_POST['authorImage'];
			$authorHref = $_POST['authorHref'];
			$titlePost = "Story of $senderFirstName";
			$postPic = "";
			$contentPost = "
				$+Shared
					$sender
					$senderPic
					$authorId
					$senderFirstName
					$authorLN
					$authorImg
					$authorHref
				$-
			";
			
			$realoc = "
				<html>
					<head>
						<script type='text/javascript'>
							function reSend() {
								document.getElementById('post').action = 'openBloger.php';
								document.forms['post'].submit();
							}
						</script>
					</head>
					<body onload='reSend()'>
						<form id='post' method='post' style='display: none;'>
							<input type='password' name='accSender' value='$sender'></input>
							<input type='password' name='imgSender' value='$senderPic'></input>
							<input type='password' name='blogSender' value='$authorId'></input>
							<input type='password' name='blogerFN' value='$senderFirstName'></input>
							<input type='password' name='blogerLN' value='$authorLN'></input>
							<input type='password' name='blogerImg' value='$authorImg'></input>
							<input type='password' name='blogerHref' value='$authorHref'></input>
						</form>
					</body>
				</html>
			";
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
		
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} else {
			$postBuild = buildPost($sender, $titlePost);
			$postBuild = str_replace("'", "\'", $postBuild);
			$postBuild =  htmlentities($postBuild);
			if (strpos($titlePost, " ")) {
				$titlePost = str_replace(" ", "6996", $titlePost);
			}
			$sql = "INSERT INTO stack$sender (STACK, BUILD) VALUES ('$titlePost', '$postBuild')";
			$conn->query($sql);
		}
		$conn->close();
		
		sendMail($sender, $senderFirstName, $cmd);
	}
	
	function sendMail($sender, $senderFirstName, $cmd) {
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
							if ($cmd == "1") {
								$subject = "New blog in Blogy";
								$content = "Hello there. $senderFirstName just posted something into Blogy. Check it from here: http://www.blogy.sitemash.net/Library/Authors/$sender/Author.php";
							}
							else
							if ($cmd == "2") {
								$subject = "Your story was shared";
								$content = "Hello $senderFirstName, someone just shared your story.";
							}
							
							mail($mail, $subject, $content);
						}
					}
				}
			}
		}
	}

if ($cmd != 2) {
	header('Location: logedIn.php');
} else {
	echo "$realoc";
}

	die();
?>