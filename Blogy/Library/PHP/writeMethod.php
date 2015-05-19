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
		$postImg = strip_tags($postImg);

		$postContent = trim($_POST['content']);
		if ($postContent != "") {
			$postContent = strip_tags($postContent);
		} else {
			$postContent = NULL;
		}

		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} else {
			if (strpos($postId, " ")) {
				$postId = str_replace(" ", "6996", $postId);
			}

			$sql = "UPDATE stack$sender SET STORYLINK='$postImg', STORYCONTENT='$postContent' WHERE STORYTITLE='$postId'";
			$conn->query($sql);
			$sql = "UPDATE worldStories SET LINK='$postImg', POST='$postContent' WHERE AuthorTitle='$sender:$postId'";
			$conn->query($sql);
		}
		$conn->close();
	}
	else
	if ($cmd == "1" || $cmd == "2") {
		if ($cmd == "1") {
			$senderFirstName = $_POST['fname'];

			$titlePost = trim($_POST['title']);
			$titlePost = strip_tags($titlePost);
			if (is_numeric($titlePost)) {
				$titlePost = "%id%$titlePost";
			}

			$postPic = $_POST['photo'];
			$postPic = strip_tags($postPic);

			$contentPost = trim($_POST['content']);
			if ($contentPost != "") {
				$contentPost = strip_tags($contentPost);
			} else {
				$contentPost = NULL;
			}
		}
		
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} else {
			/*
			$postBuild = buildPost($sender, $titlePost);
			$postBuild = str_replace("'", "\'", $postBuild);
			$postBuild =  htmlentities($postBuild);
			*/

			if (strpos($titlePost, " ")) {
				$titlePost = str_replace(" ", "6996", $titlePost);
			}

			$titlePost = str_replace("'", "#", $titlePost);
			$titlePost = str_replace("\"", "#", $titlePost);
			$titlePost = str_replace("$", "#", $titlePost);

			$dateTime = date("d.m.Y-H:i:s");

			$sql = "INSERT INTO stack$sender (DATETIME, STORYTITLE, STORYLINK, STORYCONTENT) VALUES ('$dateTime', '$titlePost', '$postPic', '$contentPost')";
			$conn->query($sql);
			$sql = "INSERT INTO worldStories (AuthorTitle, LINK, POST) VALUES ('$sender$$titlePost', '$postPic', '$contentPost')";
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