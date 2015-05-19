<?php
	$sender = $_COOKIE['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}

	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$stackOrder = array();
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//Get content
		$sql = "SELECT ALBUM, SPACE FROM albumOf$sender ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				if ($row['ALBUM'] != "SPACE") {
					array_push($stackOrder, $row['ALBUM']);
				}
			}
		}
	}
	$conn->close();
	
	$stackOrder = implode(",", $stackOrder);

echo "
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<title>Slides</title>
			<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../fonts.css' rel='stylesheet' type='text/css'>		
			<script type='text/javascript' src='../../java.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
			
			<script type = 'text/javascript'>
				function checkKey(e) {
					if (e.keyCode == 37) {
						displayPreviousImage();
					}
					else
					if (e.keyCode == 39) {
						displayNextImage();
					}
					else
					if (e.keyCode == 27) {
						window.close();
					}
				}
			
				function displayNextImage() {
					clearTimeout(intervalId);
					x++;
					if (x > images.length - 1) {
						x = 0;
						count = x;
					}
					count++;
					$('#img').fadeOut('medium');
					setTimeout(nextImage, 500);
				}
				
				function nextImage() {
					$('#img').fadeIn('medium');
					document.getElementById('img').src = '../Authors/$sender/Album/'+images[x];
					document.getElementById('counter').innerHTML = 'Slide '+count+' of '+images.length;		
					intervalId = setInterval(displayNextImage, interval);		
				}
				
				function displayPreviousImage() {
					clearTimeout(intervalId);
					x--;
					if (x < 0) {
						x = images.length - 1;
						count = x + 2;
					}
					count--;
					$('#img').fadeOut('medium');
					setTimeout(previousImage, 500);
				}

				function previousImage() {
					$('#img').fadeIn('medium');
					document.getElementById('img').src = '../Authors/$sender/Album/'+images[x];
					document.getElementById('counter').innerHTML = 'Slide '+count+' of '+images.length;
					intervalId = setInterval(displayNextImage, interval);
				}

				function startTimer() {
					if (images[0] !== null) {	
						document.getElementById('img').src = '../Authors/$sender/Album/'+images[x];
						document.getElementById('counter').innerHTML = 'Slide '+count+' of '+images.length;
						intervalId = setInterval(displayNextImage, interval);
					} else {
						alert('You don\' have picture yet.');
					}
				}

				var intervalId;
				var interval = 3000;
				var images = '$stackOrder'.split(',');
				var x = 0;
				var count = 1;
			</script>
		</head>
		<body onload='startTimer()' onkeydown='checkKey(event)'>
			<div id='sliderFrame'>
				<h1 id='counter'>
					Counter
				</h1>
				<center>
					<button type='button' onclick='window.close()'>Stop slides</button>
				</center>
				<div id='slider'>
					<button type='button' class='previousSlide' onclick='displayPreviousImage();'><img src='https://cdn2.iconfinder.com/data/icons/ios-7-icons/50/back-512.png' /></button>
					<button type='button' class='nextSlide' onclick='displayNextImage();'><img src='https://cdn2.iconfinder.com/data/icons/ios-7-icons/50/forward-512.png' /></button>
";
	echo "<img id='img' src='#'/>";
echo "
				</div>
			</div>
		</body>
	</html>
";
?>