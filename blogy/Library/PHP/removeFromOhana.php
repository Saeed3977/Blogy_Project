<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$blogerId = $_POST['blogSender'];
	$buildOhana = array();
	
	if (file_exists("../Authors/$sender/Ohana.txt")) {
		$pullOhana = fopen("../Authors/$sender/Ohana.txt", "r") or die("Fatal: Unable to open Ohana");
		while (!feof($pullOhana)) {
			$line = trim(fgets($pullOhana));
			if ($line != "") {
				if ($line != $blogerId) {
					array_push($buildOhana, $line);
				}
			}
		}
		fclose($pullOhana);
		
		$commitOhana = fopen("../Authors/$sender/Ohana.txt", "w") or die("Fatal: Could not commit Ohana.");
		foreach ($buildOhana as $member) {
			fwrite($commitOhana, $member.PHP_EOL);
		}
		fclose($commitOhana);
		
		echo "<script>window.location='openBloger.php'</script>";
	}
?>