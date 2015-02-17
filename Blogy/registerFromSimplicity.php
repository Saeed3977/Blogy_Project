<?php 
	$newMail = $_POST['mail'];

	if ($newMail != "") {
		$file = fopen("Library/Documentation/Users.txt", "a") or die("Unable to open file.");
		fwrite($file, "\n$newMail - Simplicity");
		fclose($file);
		
		$blogy = "Library/Documentation/Blogy_Simplicity.html";
		
		$myfile = fopen($blogy, "r") or die("Unable to open file !");
		$count = fread($myfile, filesize($blogy));
		fclose($myfile);
		
		$num = (int)$count + 1;
				
		$result = fopen($blogy, "w") or die("Unable to open file !");
		fwrite($result, $num);
		fclose($result);

		header("Location: Blogies_index.php");
		die();
	}
	else {
		header('Location: conf_Simplicity.html');
	}
?>