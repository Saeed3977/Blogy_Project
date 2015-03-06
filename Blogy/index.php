<?php
	$myfile = fopen('Library/Documentation/SViews.html', 'r') or die('Unable to open file !');
	$count = fread($myfile, filesize('Library/Documentation/SViews.html'));
	fclose($myfile);
	
	$num = (int)$count + 1;
	
	$result = fopen('Library/Documentation/SViews.html', 'w') or die('Unable to open file !');
	fwrite($result, $num);
	fclose($result);

	echo "
<html>
	<head>
		<link rel='shortcut icon' href='Library/images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='Library/images/Blogy-ICO.png' type='image/x-icon'>
		<title>Blogy</title>
		<link href='style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= 'fonts.css' rel='stylesheet' type='text/css'>
		<link rel='stylesheet' href='fonts.css' />
	</head>
	<body>
		<div id='menu'>
			<a href='http://www.vss.free.bg' target='_blank' class='logo-button'><img src='Library/images/logo.png' /></a>
			<a href='index.php'>Home</a>
			<a href='Blogies_index.php'>Blogies</a>
			<a href='Downloads.html'>Downloads</a>
			<a href='SignIn.html'>Log in</a>
		</div>
		<div id='logo'>
			<h1>Blogy</h1>
			<p>#Be unique</p>
		</div>
		<div id='body' align='center'>
			<table id='main-table'>
				<tr>
					<td id='element'>
						<h1>Meet Blogy</h1>
						<p>
							<b>Blogy</b> is free network where you can start and learn how to,<br>
							create your own <b>blog</b> and share your personal moments.<br> 
							In <b>Blogy</b> you are the author of your personal story.<br>
							The idea for <b>Blogy</b> comes from <a href='Library/Authors/GeroNikolov/Author.php' class='linker' target='_blank'>@Gero Nikolov</a>.
						</p>
					</td>
					<td>
					</td>
					<td id='element'>
						<h1>How it works ?</h1>
						<p>
							If you know what <b>HTML</b> & <b>CSS</b> code means you can<br>
							choose and download the style you've liked and start editing it.<br>
							But if you only want to have your own blog where to share your <b>personal moments</b><br>
							You have just to register in <b>Blogy</b> and start <b>blogy</b>-ing.
						</p>
						<div class='addMargin'></div>
					</td>
				</tr>
				<tr>
					<td>
						<br>
					</td>
				</tr>
				<tr>
					<td id='element'>
						<h1>Latest Style</h1>
						<div id='element-gallery'>
							<a href='Blogies_index.php'>
								Simplicity
								<img src='Library/Blogies/Blogy-Chrisie.png'/>
							</a>
						</div>
					</td>
					<td>
					</td>
					<td id='element'>
						<h1>Be a part of the project</h1>
						<p>
							<b>Blogy</b> is free network for blogers.<br>
							Here you can share stories and photos with your followers.<br>
							Once you create an account in <b>Blogy</b> you become an author.<br>
							The <b>authors</b> are very important for the <b>ecosystem</b>.<br>
							When someone of them post something (post or blogy template) they are building<br>
							one big library of information which is the main idea of <b>Blogy</b>.
						</p>
						<div class='addMargin'></div>
						<br>
						<div class='uniq_button'>
							<a href='Register.html'>Register</a> 
							<a href='https://www.facebook.com/theblogy' target='_blank'>Stay in touch</a>
						</div>
						<div class='addMargin'></div>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
	";
?>