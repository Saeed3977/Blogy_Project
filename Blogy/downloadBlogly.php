<?php
	$blogy = "Library/Documentation/Blogy_Blogly.html";
	
	$myfile = fopen($blogy, "r") or die("Unable to open file !");
	$count = fread($myfile, filesize($blogy));
	fclose($myfile);
	
	$num = (int)$count + 1;
			
	$result = fopen($blogy, "w") or die("Unable to open file !");
	fwrite($result, $num);
	fclose($result);

	header("Location: Blogies_index.php");
	die();
?>