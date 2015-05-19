<?php
	session_start();
	$sender = $_SESSION["sender"];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}

	$authorId = $_COOKIE["authorId"];

	$lines_count = 0;
	$author = fopen("../Authors/$authorId/config.txt", "r") or die("Unable to open author.");
	while (!feof($author)) {
		$line = fgets($author);
		if ($lines_count == 0) {
			$authorImg = trim($line);
		}
		if ($lines_count == 1) {
			$authorHref = trim($line);
		}
		else
		if ($lines_count == 3) {
			$authorFN = trim($line);
		}
		else
		if ($lines_count == 4) {
			$authorLN = trim($line);
			break;
		}
		$lines_count++;
	}
	fclose($author);
	
	$getBuild = "
		<a href='openBloger.php' onclick=\"openBloger('$authorId')\">
			<img src='$authorImg' />
			$authorFN $authorLN
			<form id='$authorId' method='post' style='display: none;'>
				<input type='text' name='blogSender' value='$authorId'></input>
				<input type='text' name='blogerFN' value='$authorFN'></input>
				<input type='text' name='blogerLN' value='$authorLN'></input>
				<input type='text' name='blogerImg' value='$authorImg'></input>
				<input type='text' name='blogerHref' value='$authorHref'></input>
			</form>
		</a>
		<br>
	";	

	echo "$getBuild";
?>