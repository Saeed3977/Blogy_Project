<?php
	$getId = $_COOKIE['userId'];

	$count = 0;
	$pull = fopen("../Authors/$getId/config.txt", "r") or die("Fatal: Unable to pull");
	while (!feof($pull)) {
		$line = trim(fgets($pull));
		if ($count == 0) {
			$getImg = $line;
		}
		else
		if ($count == 1) {
			$getHref = $line;
		}
		else
		if ($count == 3) {
			$getFN = $line;
		}
		else
		if ($count == 4) {
			$getLN = $line;
		}
		
		$count++;
	}
	fclose($pull);

	$set = "
		$getImg$
		$getHref$
		$getFN$
		$getLN$
	";

	echo $set;
?>