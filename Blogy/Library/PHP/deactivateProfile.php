<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];

echo "
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<title>Confirm delete</title>
		<link href='../../style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../fonts.css' rel='stylesheet' type='text/css'>
		<script type='text/javascript' src='../../java.js'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
		<script type = 'text/javascript'> 			
			function logOut() {
				document.getElementById('accountInfo').action = '../PHP/LogOut.php';
				document.forms['accountInfo'].submit();
			}
			
			function checkInput() {
				var getMail = document.getElementById('mailContainer').value;
				var getPass = document.getElementById('passContainer').value;
			
				if (getMail.trim() == '') {
					alert('Insert your e-mail.');
				}
				
				if (getPass.trim() == '') {
					alert('Insert your password.');
				}
				
				if (getMail.trim() != '' && getPass.trim() != '') {
					deletePermanent();
				}
			}
			
			function deletePermanent() {
				document.getElementById('confirmDelete').action = '../PHP/permanentlyDelete.php';
				document.forms['confirmDelete'].submit();
			}
		</script>
	</head>
	<body>
		<form id='accountInfo' method='post' style='display: none;'>
			<input type='text' name='sender' value='$sender'></input>
			<input type='text' id='cmd' name='cmd'></input>
		</form>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "
	<form id='confirmDelete' method='post' autocomplete='off'>
		<p>
			<b>Important - Note</b><br>
			Hello dear $profileFirst,<br>
			We want you to know that once you confirm to <b>delete</b> your profile it will be <b>deleted permanently</b>.<br>
			You will loose your :<br>
			<b>
			1. Username<br>
			2. Stories<br>
			3. Followers<br>
			4. Albums<br>
			</b>
		</p>
		<!--DELETE-->
		<input type='text' id='mailContainer' name='mail' placeholder='Enter your e-mail' />
		<br>
		<input type='password' id='passContainer' name='pass' placeholder='Enter your password' />
		<br>
		<button type='button' onclick='checkInput()'>Confirm and delete</button>
		<br>
		<br>
	</form>
";
?>