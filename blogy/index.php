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
						<div class='linker'>
							<p>
								<b>Blogy</b> is your own <b>story</b> where	to share your <b>personal</b> moments.<br>
								CEO / Founder: <a href='Library/Authors/GeroNikolov/Author.php' target='_blank'>Gero Nikolov</a><br>
								Co-Founder: <a href='Library/Authors/MladenKaradimov/Author.php' target='_blank'>Mladen Karadimov</a><br>
								Design lead: <a href='Library/Authors/DimitarGeorgiev/Author.php' target='_blank'>Dimitar Georgiev</a><br>
								Test lead: <a href='Library/Authors/ValentinVarbanov/Author.php' target='_blank'>Valentin Varbanov</a><br>
							</p>
						</div>
					</td>
					<td>
					</td>
					<td id='element'>
						<h1>How it works ?</h1>
						<p>
							Actually it is pretty <b>simple</b>.<br>
							Just post everything <b>you are interested at</b>.<br>
							Also read <b>your friends interesting stories</b>.<br>
							Thats all :)
							<br>
							<br>
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
							Once you create your account just start <b>blogy</b>-ing.<br>
							Share all things, all around the web that make <b>you feel special</b>.<br>
							Sharing your story makes our world bigger and more interesting.<br>
							It will be a pleasure for us to call you <b>friend</b><br>
							we are waiting for <b>your unique character</b>.<br>
							<br>
							Also if you love to create beautiful CSS designs you have the oportunity<br>
							to upload all of your projects into the <b>Blogies</b> section.<br>
							<b>Hello again and welcome to Blogy.</b>
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