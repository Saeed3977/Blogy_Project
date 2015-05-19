<?php
	$sender = $_COOKIE['sender'];
	$getId = $_COOKIE['getId'];
	$getCmd = $_COOKIE['buildFor'];

	if ($getCmd == 1) {
		$sender = $_COOKIE['blogSender'];
	}
	else
	if ($getCmd == 2) {
		$authorInfo = explode("#", $_COOKIE['authorInfo']);
		$sender = $authorInfo[0];
	}

	include "loadStories.php";
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";

	$storePosts = array();
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		if (!isset($_COOKIE["buildWorldStories"])) {
			$sql = "SELECT STORYTITLE, STORYLINK, STORYCONTENT FROM stack$sender WHERE ID=$getId";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$getTitle = $row["STORYTITLE"];
					$getLink = $row["STORYLINK"];
					$getContent = $row["STORYCONTENT"];
				}
			}
		} else {
			$sql = "SELECT AuthorTitle, LINK, POST FROM worldStories WHERE ID=$getId";
			$result = mysqli_query($conn, $sql);
			$ROWS = mysqli_num_rows($result);
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$getAuthorTitle = $row["AuthorTitle"];
					$getLink = $row["LINK"];
					$getContent = $row["POST"];

					$getTitle = explode("$", $getAuthorTitle)[1];

					$oldAuthor = $postAuthor;
					$postAuthor = explode("$", $getAuthorTitle)[0];
					if ($postAuthor != $oldAuthor) {
						$lineCount = 0;
						$pullInfo = fopen("../Authors/$postAuthor/config.txt", "r") or die("Fatal: Could not load.");
						while (!feof($pullInfo)) {
							$line = trim(fgets($pullInfo));
							if ($line != "") {
								if ($lineCount == 0) {
									$authorImg = $line;
								}
								else
								if ($lineCount == 1) {
									$authorHref = $line;
								}
								else
								if ($lineCount == 3) {
									$authorFN = $line;
								}
								else
								if ($lineCount == 4) {
									$authorLN = $line;
									break;
								}
							}
							$lineCount++;
						}
						fclose($pullInfo);
					}

					$authorInfo = array("$postAuthor", "$authorImg", "$authorHref", "$authorFN", "$authorLN");	
				}
			}
		}
	}
	$conn->close();

	setcookie("buildWorldStories", "", time() - 3600);
		
	if ($getCmd == 0) echo parseContent($getTitle, $getLink, $getContent, 0, "0");
	else
	if ($getCmd == 1 || $getCmd == 3) echo parseContent($getTitle, $getLink, $getContent, 0, "1");
	else
	if ($getCmd == 2) echo parseContent($getTitle, $getLink, $getContent, $authorInfo, "2");
?>