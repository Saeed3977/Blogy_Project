function showImg(id) {			
	pic = new Image();
	pic.src = document.getElementById(id).src;
	
	var title = 'Blogy';

	document.write('<head><title>');
		document.write(title);
		document.write('</title>');
		document.write('<link href=\'style.css\' rel=\'stylesheet\' type=\'text/css\' media=\'screen\' />');
		document.write('<link href=\'../../../style.css\' rel=\'stylesheet\' type=\'text/css\' media=\'screen\' />');
	
		document.write('<style>body{background-color: black;}</style>');
	document.write('</head>');
	document.write('<div id=\'sub-gallery\' align=\'center\'>');
		document.write('<a href=\'#\' onclick=\'location.reload()\'><img src=\'');
		document.write(pic.src);
		document.write('\'></а>');
	document.write('</div>');
}

function isValidEmailAddress(emailAddress) {
	var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
};

function returnToHome() {
	document.getElementById('accountInfo').action = '../PHP/logedIn.php';
	document.forms['accountInfo'].submit();
}

function openSettings() {
	document.getElementById('accountInfo').action = '../PHP/openSettings.php';
	document.forms['accountInfo'].submit();
}