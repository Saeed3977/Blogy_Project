<?php
	$getContent = trim($_POST['reportedData']);
	$getContent = nl2br($getContent);
	if (!isset($getContent)) {
		header('Location: ../../SignIn.html');
	}
	
	//Build and send mail
	$mail = "vtm.sunrise@gmail.com";
	$subject = "Error report - Blogy";
	mail($mail, $subject, $getContent);
	
	header('Location: openSettings.php');

	die();
?>