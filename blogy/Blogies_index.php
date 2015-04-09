<?php
	//1 blogy
	$myfile1 = fopen('Library/Documentation/Blogy_Blogly.html', 'r') or die('Unable to open file !');
	$count1 = fread($myfile1, filesize('Library/Documentation/Blogy_Blogly.html'));
	fclose($myfile1);

	//2 blogy
	$myfilе2 = fopen('Library/Documentation/Blogy_Blue.html', 'r') or die('Unable to open file !');
	$count2 = fread($myfilе2, filesize('Library/Documentation/Blogy_Blue.html'));
	fclose($myfilе2);
	
	//3 blogy
	$myfilе3 = fopen('Library/Documentation/Blogy_Simplicity.html', 'r') or die('Unable to open file !');
	$count3 = fread($myfilе3, filesize('Library/Documentation/Blogy_Simplicity.html'));
	fclose($myfilе3);

	//Authors
	$author1 = "Library/Authors/GeroNikolov/Author.php";
	$author2 = "Library/Authors/ChristinaYorgova/Author.php";
	
	echo "
<html>
	<head>
		<link rel='shortcut icon' href='Library/images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='Library/images/Blogy-ICO.png' type='image/x-icon'>
		<title>Blogy</title>
		<link href='style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= 'fonts.css' rel='stylesheet' type='text/css'>
		
		<link rel='stylesheet' href='lightbox.css' type='text/css' media='screen' />
		
		<script type='text/javascript' src='java.js'></script>
		<script type='text/javascript'>
			function sendBlogy() {
				window.open('mailto:vtm.sunrise@gmail.com?subject=New blogy&body=Add your style and send it.%0D%0AThank you and welcome to Blogy :)');
			}
		</script>
		<script type='text/javascript' src='java.js'></script>
	</head>
	<body>
		<div id='menu'>
			<a href='http://www.vss.free.bg' target='_blank' class='logo-button'><img src='Library/images/logo.png' /></a>
			<a href='index.php'>Home</a>
			<a href='Blogies_index.php'>Blogies</a>
			<a href='Downloads.html'>Downloads</a>
			<a href='SignIn.html'>Log in</a>
		</div>
		<div id='sub-logo'>
			<h1>Choose your way.</h1>
		</div>
		<div id='body'>
			<div id='add-button'>
				<a href='#' onclick='sendBlogy()'>Add blogy</a>
			</div>
			<table id='main-table'>
				<tr>
					<td>
					</td>
					<td id='blogies'>
						<h1>Simplicity</h1>
						<a href='#' onclick='showImg(\"pic3\")'>
							<img src='Library/Blogies/Blogy-Chrisie.png' id='pic3'/>
						</a>
							<h2>$count3 downloads</h2>
							<h3 class='autor'>Author: <a href='#'>Christina Yorgova</a></h3>
						<p class='separate'>
							<b>Simplicity</b> is free Blogy template.<br>
							It comes with 2 HTML files, 1 JS file and 2 CSS files.<br>
							You can do whatever you want with the files.<br>
							Also <b>thank you</b> Chrisie for the support.
						</p>
						<div class='separate'>
							<a href='#' onclick='downloadBlogy(\"Simplicity\")'>Download</a>
						</div>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td>
						<br>
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td id='blogies'>
						<h1>Blogly</h1>
						<a href='#' onclick='showImg(\"pic1\")'>
							<img src='Library/Blogies/Blogy-Gero1.png' id='pic1'/>
						</a>
						<!--<h2>8 downloads</h2>-->
							<h2>$count1 downloads</h2>
							<h3 class='autor'>Author: <a href='$author1'>Gero Nikolov</a></h3>
						<p class='separate'>
							<b>Blogly</b> is free and simple Blogy template.<br>
							It comes with 1 HTML file, and small picture Library.<br>
							You can do whatever you want with the HTML file but you can't re-sell the pictures.
						</p>
						<div class='separate'>
							<a href='#' onclick='downloadBlogy(\"Blogly\")'>Download</a>
						</div>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td>
						<br>
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td id='blogies'>
						<h1>Blue</h1>
						<a href='#' onclick='showImg(\"pic2\")'>
							<img src='Library/Blogies/Blogy-Gero.png' id='pic2'/>
						</a>
						<!--<h2>8 downloads</h2>-->			
							<h2>$count2 downloads</h2>
							<h3 class='autor'>Author: <a href='$author1'>Gero Nikolov</a></h3>
						<p class='separate'>
							<b>Blue</b> is free and simple Blogy template.<br>
							It comes with 3 HTML files, and small picture Library.<br>
							You can do whatever you want with the HTML files but you can't re-sell the pictures.
						</p>
						<div class='separate'>
							<a href='#' onclick='downloadBlogy(\"Blue\")'>Download</a>
						</div>
					</td>
					<td>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
	";
?>