<?php
	$sender = $_POST['sender'];
	$messangerId = $_POST['messangerId'];
	
echo "
	<html>
		<head>
			<script type='text/javascript'>
				function reSend() {
					document.getElementById('post').action = 'readMessage.php';
					document.forms['post'].submit();
				}
			</script>
		</head>
		<body onload='reSend()'>
			<form id='post' method='post' style='display: none;'>
				<input name='messangerId' value='$messangerId'></input>
				<input name='sender' value='$sender'></input>
			</form>
		</body>
	</html>
";
	
	die();
?>