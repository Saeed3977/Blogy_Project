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

function downloadBlogy(blogyId) {
	document.cookie="objectId="+blogyId;
	window.open("Library/Downloads/"+blogyId+".rar");
	window.location="downloadBlogy.php";
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

function showSideBar() {
	var allCookies = document.cookie;
	if (allCookies.indexOf("sideBar=") != -1) {
		document.cookie = "sideBar=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
	} else {
		document.cookie="sideBar=1";
	}
	
	//document.getElementById('downButton').style.visibility='hidden';
	document.getElementById('sideBar').style.visibility='visible'; 
	$('#sideBar').fadeToggle('fast');
	$('#rightSideBar').fadeToggle('fast');
}

function showQuickMenu(id) {
	document.getElementById('quickMenu'+id).style.visibility='visible'; 
	$('#quickMenu'+id).slideToggle('fast');
}
function showQuickMenuOhana(id) {
	document.getElementById('ohanaQuickMenu'+id).style.visibility='visible'; 
	$('#ohanaQuickMenu'+id).slideToggle('fast');
}

function hideMessageBox() {
	$('#quickMessageBox').fadeOut('fast');
}

function showMessageBox(receiver) {	
	document.getElementById('receiverId').value = receiver;
	document.getElementById('receiver').innerHTML = document.getElementById(receiver).elements["blogerFN"].value + " " + document.getElementById(receiver).elements["blogerLN"].value;
	document.getElementById('messageArea').focus();
	$('#quickMessageBox').fadeIn('fast');
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
		document.cookie="scrollToPos="+$(window).scrollTop();
		document.getElementById('pageId').value = window.location.href.toString().split('/').pop(-1);
		document.getElementById('scrollPosSidebar').value = $(window).scrollTop();
		document.getElementById('sendArea').action = "sendQuickMessage.php";
		document.forms['sendArea'].submit();
	} else {
		alert("Enter something in this message.");
	}
}

function showOptions() {
	$('#optionsMenu').fadeToggle('fast');
}

function showOhanaMeaning() {
	$('#ohanaMeaning').fadeToggle('fast');
}

function addToOhana(id) {
	document.getElementById(id).action = "../PHP/addToOhana.php";
	document.forms[id].submit();
}
function removeFromOhana(id) {
	document.getElementById(id).action = "../PHP/removeFromOhana.php";
	document.forms[id].submit();
}

function blockUser(id) {
	document.getElementById(id).action = "../PHP/blockUser.php";
	document.forms[id].submit();
}
function unBlockUser(id) {
	openBloger(id);
	document.getElementById(id).action = "../PHP/unBlockUser.php";
	document.forms[id].submit();
}

function showEmojiContainer() {
	$('#emojis').fadeToggle('fast');
}

function showHideNotifications() {
	var allCookies = document.cookie;
	if (allCookies.indexOf("notification=") != -1) {
		document.cookie = "notification=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
	} else {
		document.cookie="notification=1";
	}
	
	$('#notifications').slideToggle('fast');
}
function closeNotifications() {
	document.cookie = "pageId="+window.location.href.toString().split('/').pop(-1);
	document.cookie = "scrollPos="+$(window).scrollTop();
	
	window.location = "deleteNotifications.php";
}

function addEmoji(id) {
	textArea = document.getElementById("messageTXT");
	if (id == "lol") {
		textArea.value += ":D";
	}
	else
	if (id == "smile") {
		textArea.value += ":)";
	}
	else
	if (id == "sad") {
		textArea.value += ":(";
	}
	else
	if (id == "ooh") {
		textArea.value += ":O";
	}
	else
	if (id == "inlove") {
		textArea.value += "{2369}";
	}
	else
	if (id == "kiss") {
		textArea.value += ":*";
	}
	else
	if (id == "scare") {
		textArea.value += "{666}";
	}
	else
	if (id == "cry") {
		textArea.value += ":'(";
	}
	else
	if (id == "tongue") {
		textArea.value += ":P";
	}
	else
	if (id == "wat") {
		textArea.value += "{49}";
	}
	else
	if (id == "wink") {
		textArea.value += ";)";
	}
	else
	if (id == "mybad") {
		textArea.value += "{118}";
	}
	else
	if (id == "meh") {
		textArea.value += "{999}";
	}
	else
	if (id == "lolo") {
		textArea.value += "{1010}";
	}
	else
	if (id == "muchCry") {
		textArea.value += "{7428}";
	}
	else
	if (id == "calm") {
		textArea.value += ":3";
	}
	else
	if (id == "sexy") {
		textArea.value += "{1619}";
	}
	else
	if (id == "angry") {
		textArea.value += ":@";
	}
}

function showHideHomeMenu() {
	$("#dropDownMenu").fadeToggle("fast");
}

function openDialog() {
	document.getElementById("fileToUpload").click();
}
function startToUpload() {
	if (document.getElementById('fileToUpload').value != "") {
		document.getElementById('toUpload').action = 'Upload.php';
		document.forms['toUpload'].submit();
	}
}

function sendLocation() {
	document.getElementById("postImg").value = document.getElementById("dialogWindow").value;
	
	var getObject = document.getElementById("dialogWindow");
	if (getObject.value != null) {
		document.cookie = "uploadPicture="+document.getElementById("postImg").value;
	}
	else
	if (getObject.value == null) {
		document.cookie = "uploadPicture=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
	}
}
function checkLocation() {
	var getObject = document.getElementById("postImg");
	if (getObject.value != document.getElementById("dialogWindow").value) {
		document.cookie = "uploadPicture=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
	}
}

function logMeOut() {
	window.location = "LogOut.php";
}

//Read message from notification
function readMessage(id) {
	document.cookie = "receiverId="+id;
	window.location="readMessage.php";
}

//Album options
function setAsProfilePic(id) {
	document.cookie = "newProfilePicture="+id;
	window.location = 'setNewProfilePic.php';
}
function showContainerPost(id, sender) {
	document.getElementById("imgToPost").src = '../../../Library/Authors/'+sender+'/Album/'+id;
	document.getElementById("postImg").value = '../../../Library/Authors/'+sender+'/Album/'+id;
	
	$("#makePost").fadeIn('fast');
}
function hideContainerPost(id) {
	$("#makePost").fadeOut('fast');
}
function showContainerFriends(id) {
	$("#storeFriends").fadeIn("fast");
	document.cookie = "sharePicture="+id;
}
function hideContainerFriends() {
	$("#storeFriends").fadeOut("fast");
	document.cookie = "sharePicture=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
}
function deleteObjectFromAlbum(id) {
	document.getElementById("picture"+id).action = 'deleteAlbumObject.php';
	document.forms["picture"+id].submit();
}

//Notes
function showNoteBuilder() {
	$("#noteBuilder").fadeIn("fast");
}
function hideNoteBuilder() {
	$("#noteBuilder").fadeOut("fast");
}
function pinNote(org, getId, getDate) {
	var noteTitle = document.getElementById("noteTitle").value;
	var noteDate = document.getElementById("datepicker").value;
	var noteContent = document.getElementById("noteContent").value;
	
	var flag = 0;
	
	if (noteTitle.trim() == "") {
		alert("Give title to your note.");
		flag = 1;
	}
	else
	if (noteDate.trim() == "") {
		alert("Choose date for your note.");
		flag = 1;
	}
	else
	if (noteContent.trim() == "") {
		alert("Enter something in your note.");
		flag = 1;
	}
	
	if (flag == 0) {
		if (org == 0) {
			document.getElementById('noteForm').action = 'pinNote.php';
			document.forms['noteForm'].submit();
		}
		else
		if (org == 1) {
			document.cookie = "oldNoteId="+getId;
			document.cookie = "oldNoteDate="+getDate;
			document.getElementById('noteForm').action = 'updateNote.php';
			document.forms['noteForm'].submit();
		}
	}
}
function deleteNote(id) {
	document.cookie = "noteId="+id;
	window.location = 'deleteNote.php';
}
function previewNote(id, date) {
	document.cookie = "noteId="+id;
	document.cookie = "noteDate="+date;
	window.location = 'previewNote.php';
}

//Searches
function exploreBloger(blogerId) {
	openBloger(blogerId.toString());
	window.location = 'openBloger.php';
}
function searchFriends(pullFriends) {
	var searchRes = document.getElementById('searchResults');
	searchRes.style.display = 'none';
	while (searchRes.firstChild) {
	    searchRes.removeChild(searchRes.firstChild);
	}

	var searchSugestions = [];
	var getInput = document.getElementById('searchInput').value;
	
	for (i = 0; i < pullFriends.length; i++) {
		if (pullFriends[i].indexOf(getInput.trim().replace(/\s+/g, '#')) > -1 && getInput.trim() != '') {
			searchSugestions.push(pullFriends[i]);
		}
	}

	if (searchSugestions.length == 0) {
		searchRes.innerHTML += '<h1>No matches found :(</h1>';
	} 
	else 
	if (searchSugestions.length > 0) {
		for (count = 0; count < searchSugestions.length; count++) {
			var splitResult = searchSugestions[count].split('#');
			var fName = splitResult[0];
			var lName = splitResult[1];
			var idName = splitResult[2];
			var img = splitResult[3];
			var href = splitResult[4];

			searchRes.innerHTML += "<button onclick='exploreBloger(\""+idName+"\")'><img src="+img+" />"+fName+" "+lName+"</button>";
			searchRes.innerHTML += 
			"<form id='"+idName+"' method='post' style='display: none;'>\
				<input type='text' name='blogSender' value='"+idName+"'>\
				<input type='text' name='blogerFN' value='"+fName+"'>\
				<input type='text' name='blogerLN' value='"+lName+"'>\
				<input type='text' name='blogerImg' value='"+img+"'>\
				<input type='text' name='blogerHref' value='"+href+"'>\
			</form>";
		}
	}

	if (getInput.trim() != '') searchRes.style.display = 'block';
}