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

function openBloger(title) {
	var blogSender = document.getElementById(title).elements["blogSender"].value;
	var blogerFN = document.getElementById(title).elements["blogerFN"].value;
	var blogerLN = document.getElementById(title).elements["blogerLN"].value;
	var blogerImg = document.getElementById(title).elements["blogerImg"].value;
	var blogerHref = document.getElementById(title).elements["blogerHref"].value;

	document.cookie = "blogSender="+blogSender;
	document.cookie = "blogerFN="+blogerFN;
	document.cookie = "blogerLN="+blogerLN;
	document.cookie = "blogerImg="+blogerImg;
	document.cookie = "blogerHref="+blogerHref;
}

function reportData() {
	var data = document.getElementById('reportedData').value;
	if (data.trim() == "") {
		alert("Well tell us what is wrong first.");
	} else {
		document.getElementById("reportData").action = "../PHP/reportData.php";
		document.forms['reportData'].submit();
	}
}

function hideSideBar() {
	$('#sideBar').fadeToggle();
	//document.getElementById('sideBar').style.visibility='hidden';
	document.getElementById('downButton').style.visibility='visible';
}
function showSideBar() {
	//document.getElementById('downButton').style.visibility='hidden';
	document.getElementById('sideBar').style.visibility='visible'; 
	$('#sideBar').fadeToggle('fast');
}

function showQuickMenu(id) {
	document.getElementById('quickMenu'+id).style.visibility='visible'; 
	$('#quickMenu'+id).slideToggle('fast');
}

function showMessageBox(receiver) {
	document.getElementById('receiverId').value = receiver;
	document.getElementById('receiver').innerHTML = document.getElementById(receiver).elements["blogerFN"].value + " " + document.getElementById(receiver).elements["blogerLN"].value;
	document.getElementById('messageArea').focus();
	$('#quickMessageBox').fadeIn('fast');
}

function hideMessageBox() {
	$('#quickMessageBox').fadeOut('fast');
}

function checkKey(e) {
	var code = (e.keyCode ? e.keyCode : e.which);
	if(code == 27) { //Enter keycode
		$('#quickMessageBox').fadeOut('fast');
	}
}

function sendMessageBox() {
	var messageText = document.getElementById('messageArea').value;
	if (messageText.trim() != "") {
		document.getElementById('pageId').value = window.location.href.toString().split('/').pop(-1);
		document.getElementById('scrollPosSidebar').value =  $(window).scrollTop();
		document.getElementById('sendArea').action = "sendQuickMessage.php";
		document.forms['sendArea'].submit();
	} else {
		alert("Enter something in this message.");
	}
}