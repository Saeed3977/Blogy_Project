<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$postTitle = $_POST['postId'];
	
	$count = 0;
	$flag = 0;
	$postContent = (string)NULL;
	$load = fopen("../Authors/$sender/Posts/$postTitle.txt", "r") or die("Unable to load author's post.");
	while (!feof($load)) {
		$line = fgets($load);
		if ($count == 1) {
			$postImg = trim($line);
		}
		else
		if ($count == 2 || $flag == 1) {
			$postContent .= $line;
			$flag = 1;
		}
		$count++;
	}
	
	if ($postImg == "NULL") {
		$postImg = NULL;
	}
	
	if ($postContent == "NULL") {
		$postContent = NULL;
	}
	
	$postContent = str_replace("<br />", "", $postContent);
	
echo "
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<title>Edit post</title>
			<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../fonts.css' rel='stylesheet' type='text/css'>		
			
			<script type='text/javascript'>
				function buildPost() {
					var postImg = document.getElementById('img').value;
					var postContent = document.getElementById('content').value;
					
					if (postImg == '' && postContent == '') {
						alert('Your post need to have something in it.');
					} else {
						document.getElementById('editor').action = 'writeMethod.php';
						document.forms['editor'].submit();
					}
				}
			</script>
		</head>
		<body>
			<div id='body'>
				<form id='editor' method='post'>
					<h1>$postTitle</h1>
					<input type='text' id='img' name='postImg' placeholder='Place link to an image.' value='$postImg'></input><br>
					<textarea placeholder='What&#39;s up ?' id='content' name='content' value='$postContent'>$postContent</textarea><br>
					<a href='#' onclick='buildPost()'>Post</a>
					<a href='logedIn.php'>Ignore</a>
					<br>
					<div class='addMarginSmall'></div>
					<input type='text' name='sender' value='$sender' style='display:none;'></input>
					<input type='text' name='cmd' value='0' style='display:none;'></input>
					<input type='text' name='postId' value='$postTitle' style='display:none;'></input>
				</form>
			</div>
		</body>
	</html>
";
	
	die();
?>