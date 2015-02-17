<?php
	$sender = $_POST['sender'];
	
	$fd = fopen("../Authors/$sender/LogFlag.txt", "w") or die("Unable to open file.");
	fwrite($fd, "0");
	fclose($fd);
	
	header('Location: ../../index.php');
	die();
?>