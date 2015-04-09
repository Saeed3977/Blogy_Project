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
		$postId = trim($_POST['postId']);
		$postImg = $_POST['postImg'];
		$postContent = trim($_POST['content']);
		$postContent = nl2br($postContent);
		
		$postContent = strip_tags($postContent);
		
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
			$postBuildViewer = buildPostViewer($sender, $postId);
			$postBuildViewer = str_replace("'", "\'", $postBuildViewer);
			$postBuildViewer =  htmlentities($postBuildViewer);
			$postBuildStories = buildPostStories($sender, $postId);
			$postBuildStories = str_replace("'", "\'", $postBuildStories);
			$postBuildStories =  htmlentities($postBuildStories);
			if (strpos($postId, " ")) {
				$postId = str_replace(" ", "6996", $postId);
			}
			$sql = "UPDATE stack$sender SET BUILD='$postBuild', VIEW='$postBuildViewer', PUBLICVIEW='$postBuildStories' WHERE STACK='$postId'";
			$conn->query($sql);
			$sql = "UPDATE worldStories SET POST='$postBuildStories' WHERE AuthorPOST='$sender:$postId'";
			$conn->query($sql);
		}
		$conn->close();
	}
	else
	if ($cmd == "1" || $cmd == "2") {
		if ($cmd == "1") {
			$senderFirstName = $_POST['fname'];
			$titlePost = trim($_POST['title']);
			$contentPost = trim($_POST['content']);
			$contentPost = nl2br($contentPost);
			$postPic = $_POST['photo'];
			
			$titlePost = strip_tags($titlePost);
			$content = strip_tags($contentPost);
			
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
		
/*
		//Check and upload file
		if (isset($_COOKIE['uploadPicture'])) {
			require 'Upload.php';
			$postFile = $_POST['fileUpload'];
			$dir = "../Authors/$sender/Album";
			//$fileName = array_pop(explode("\\", $postPic));
			if (!file_exists($dir)) {
				if (mkdir($dir, 0700)) {
					uploadFile($dir, $_POST['fileUpload']);
				} else {
					die("Fatal: Album not created");
				}
			} else {
				$dir = $dir."/";
				uploadFile($dir, $_POST['fileUpload']);
			}
			
			echo $_POST['fileUpload'];
		}
*/	
		
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
		
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} else {
			$dateTime = date("Y-m-d H:i:s", filemtime("../Authors/$sender/Posts/$titlePost.txt"));
			$postBuild = buildPost($sender, $titlePost);
			$postBuild = str_replace("'", "\'", $postBuild);
			$postBuild =  htmlentities($postBuild);
			$postBuildViewer = buildPostViewer($sender, $titlePost);
			$postBuildViewer = str_replace("'", "\'", $postBuildViewer);
			$postBuildViewer =  htmlentities($postBuildViewer);
			$postBuildStories = buildPostStories($sender, $titlePost);
			$postBuildStories = str_replace("'", "\'", $postBuildStories);
			$postBuildStories =  htmlentities($postBuildStories);
			if (strpos($titlePost, " ")) {
				$titlePost = str_replace(" ", "6996", $titlePost);
			}
			$sql = "INSERT INTO stack$sender (DATE, STACK, BUILD, VIEW, PUBLICVIEW) VALUES ('$dateTime', '$titlePost', '$postBuild', '$postBuildViewer', '$postBuildStories')";
			$conn->query($sql);
			$sql = "INSERT INTO worldStories (AuthorPOST, POST) VALUES ('$sender:$titlePost', '$postBuildStories')";
			$conn->query($sql);
		}
		
		sendMail($sender, $senderFirstName, $cmd, $conn);
	}
	
	function sendMail($sender, $senderFirstName, $cmd, $conn) {		
		$followerFullName = (string)NULL;
		$followersIDs = fopen("../Authors/$sender/FollowersID.html", "r") or die("Unable to open file.");
		while (! feof($followersIDs)) {
			$toSend = (string)NULL;
			$parseFollowerConfig = (string)NULL;
			$followerID = trim(fgets($followersIDs));
			$line_count = 0;
			
			$mail = explode("-", $followerID)[0];
			$followerFullName = explode("-", $followerID)[1];
			
			$sql = "CREATE TABLE pushTable$followerFullName (ID int NOT NULL AUTO_INCREMENT, MEMBER LONGTEXT, MESSAGE LONGTEXT, DATE LONGTEXT, PRIMARY KEY (ID))";
			if ($conn->query($sql) === TRUE) {
				buildNotification($sender, $followerFullName, $conn);
			} else {
				buildNotification($sender, $followerFullName, $conn);				
			}
			
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
	
	function buildNotification($sender, $followerID, $conn) {
		$date = date("d.M.Y");
		$sql = "INSERT INTO pushTable$followerID (MEMBER, MESSAGE, DATE) VALUES ('$sender', 'just shared a story', '$date')";
		$conn->query($sql);
	}
	
if ($cmd != 2) {
	echo "<script>window.location='logedIn.php'</script>";
} else {
	echo "$realoc";
}

	die();
?>