<?php
	$getId = $_COOKIE["objectId"];
	$blogy = "Library/Documentation/Blogy_".$getId.".html";
	
	$myfile = fopen($blogy, "r") or die("Unable to open file !");
	$count = fread($myfile, filesize($blogy));
	fclose($myfile);
	
	$num = (int)$count + 1;
			
	$result = fopen($blogy, "w") or die("Unable to open file !");
	fwrite($result, $num);
	fclose($result);

	echo "
		<script>
			document.cookie='objectId=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			window.location='Blogies_index.php';
		</script>
	";
?>