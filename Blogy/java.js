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
	var receiver = document.getElementById('receiverId').value;

	if (messageText.trim() != ""  && receiver.trim() != "") {
		document.cookie = "MESSAGE="+messageText.replace(/(?:\r\n|\r|\n)/g, '<br />');
		document.cookie = "RECEIVER="+receiver;

		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}
		requestType.open("GET", "sendQuickMessage.php", true);
		requestType.send();

		requestType.onreadystatechange = function() {
	        if (requestType.readyState == 4 && requestType.status == 200) {
	     		var storeResponce = requestType.responseText;
	 		   
	 		 	if (storeResponce == "READY") {
		 		 	document.cookie = "MESSAGE=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
		 		 	document.cookie = "RECEIVER=; expires=Thu, 01 Jan 1970 00:00:00 UTC";

		 		 	$("#quickMessageBox").fadeOut("fast");
		 		 	messageText =  "";
		 		 	receiver = "";
	 		 	}
	     	}
	    }
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
	else
	if (id == "redH") {
		textArea.value += "{23}";
	}
	else
	if (id == "blueH") {
		textArea.value += "{45}";
	}
	else
	if (id == "greenH") {
		textArea.value += "{0103}";
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


//Function to Log out
function deleteTimer() {
	clearTimeout(timeOut);
}
function setTimer() {
	timeOut = setTimeout(logMeOut(), 10000)
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
function searchFriends(pullFriends, searchEngine, objectId, cmdType) {
	var searchRes = document.getElementById(objectId);
	searchRes.style.display = 'none';
	while (searchRes.firstChild) {
	    searchRes.removeChild(searchRes.firstChild);
	}

	var searchSugestions = [];
	var getInput = document.getElementById(searchEngine).value;
	
	for (i = 0; i < pullFriends.length; i++) {
		var split = pullFriends[i].split("#");
		var search = split[0] + split[1];
		if (search.toLowerCase().indexOf(getInput.trim().toLowerCase().replace(/\s+/g, '#')) > -1 && getInput.trim() != '') {
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

			if (cmdType == 1) {
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
			else
			if (cmdType == 2) {
				searchRes.innerHTML += "<button onclick='addBloger(\""+idName+"\", \""+img+"\", \""+fName+"\", \""+lName+"\")'><img src="+img+" />"+fName+" "+lName+"</button>";
			}
		}
	}

	if (getInput.trim() != '') searchRes.style.display = 'block';
}


//Remove Element from INNER HTML
function removeElement(elementId, containerId) {
	document.getElementById(containerId).removeChild(document.getElementById(elementId));
}

//Places - Get location
function hideMap() {
	$("#mapContainer").fadeOut("fast");
	$("#placeInfoInput").slideUp("fast");
	
	taggedFriends = [];
	document.getElementById("placeInfoInput").removeChild(document.getElementById("taggedFriends"));
	document.getElementById("searchInput").value = "";
	document.getElementById("placeTitle").value = "";
	document.getElementById("placeStory").value = "";

	document.cookie = "placeLocation=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
}
function getLocation() {
    if (navigator.geolocation) {
    	$("#mapContainer").fadeToggle("fast");
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else { 
       alert("Geolocation is not supported by this browser :(");
    }
}
function showPosition(position) {
    lat = position.coords.latitude;
    lon = position.coords.longitude;
	document.cookie = "placeLocation="+lat+"#"+lon;
    latlon = new google.maps.LatLng(lat, lon);

    var myOptions = {
	    center:latlon,zoom:14,
	    mapTypeId:google.maps.MapTypeId.ROADMAP,
	    mapTypeControl:false,
	    navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
    }
    
    var map = new google.maps.Map(document.getElementById("mapHolder"), myOptions);
    var marker = new google.maps.Marker({position:latlon,map:map,title:"You are here!"});
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            alert("User denied the request for Geolocation.");
            break;
        case error.POSITION_UNAVAILABLE:
           	alert("Location information is unavailable.");
            break;
        case error.TIMEOUT:
            alert("The request to get user location timed out.");
            break;
        case error.UNKNOWN_ERROR:
            alert("An unknown error occurred.");
            break;
    }
}

//Places - Tag location
function chooseLocation() {
	$("#placeInfoInput").slideToggle("fast");
}

//Places - Tag friends
function addBloger(blogerId, img, fName, lName) {
	document.getElementById("searchInput").value = "";
	document.getElementById("searchResults").style.display = 'none';
	
	buildPush = blogerId+"#"+img+"#"+fName+"#"+lName;

	if (taggedFriends.indexOf(buildPush) == -1)	taggedFriends.push(buildPush);

	document.getElementById("taggedFriends").innerHTML = "\
		<button type='button' onclick='$(\"#friendsContainer\").fadeToggle(\"fast\")' class='taggedPeople' title='Tagged friends'><img src='https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-people-512.png' /></button>\
		<div id='friendsContainer' class='container'>\
		</div>\
	";

	var friendsContainer = document.getElementById("friendsContainer");
	while (friendsContainer.firstChild) {
	    friendsContainer.removeChild(friendsContainer.firstChild);
	}

	for (i = 0; i < taggedFriends.length; i++) {
		friendsContainer.innerHTML += "<button id='"+taggedFriends[i].split("#")[0]+"' onclick='removeBlogger(\""+taggedFriends[i].split("#")[0]+"\")'><img src="+taggedFriends[i].split("#")[1]+" />"+taggedFriends[i].split("#")[2]+" "+taggedFriends[i].split("#")[3]+"</button>";
	}
}

function removeBlogger(blogerId) {
	var coppyArray = [];
	for (i = 0; i < taggedFriends.length; i++) {
		if (taggedFriends[i].split("#")[0] != blogerId) {
			coppyArray.push(taggedFriends[i]);
		}
	}

	taggedFriends = [];

	if (coppyArray.length > 0) {
		for (i = 0; i < coppyArray.length; i++) {
			taggedFriends.push(coppyArray[i]);
		}
		
		var friendsContainer = document.getElementById("friendsContainer");
		while (friendsContainer.firstChild) {
		    friendsContainer.removeChild(friendsContainer.firstChild);
		}

		for (i = 0; i < taggedFriends.length; i++) {
			friendsContainer.innerHTML += "<button id='"+taggedFriends[i].split("#")[0]+"' onclick='removeBlogger(\""+taggedFriends[i].split("#")[0]+"\")'><img src="+taggedFriends[i].split("#")[1]+" />"+taggedFriends[i].split("#")[2]+" "+taggedFriends[i].split("#")[3]+"</button>";
		}
	} else {
		while (document.getElementById("taggedFriends").firstChild) {
		    document.getElementById("taggedFriends").removeChild(document.getElementById("taggedFriends").firstChild);
		}
	}

}

//Places - Tag place FINAL
function tagPlace() {
	var title = document.getElementById('placeTitle');
	var story = document.getElementById('placeStory');
	var flag = 0;

	if (title.value == "" || title.value.trim() == "") {
		alert("Give title to that place.");
		flag = 1;
	}

	if (story.value == "" || story.value.trim() == "") {
		alert("What is the story of that place ?");
		flag = 1;
	}

	if (flag == 0) {
		document.cookie = "placeTitle="+title.value;

		var getIDs = [];
		if (taggedFriends.length > 0) {
			for (i = 0; i < taggedFriends.length; i++) {
				getIDs.push(taggedFriends[i].split("#")[0]);
			}
			document.cookie = "taggedFriends="+getIDs.toString();
		}

		document.getElementById("placeStoryForm").action = "tagCurrentLocation.php";
		document.forms['placeStoryForm'].submit();
	}
}

//Places - Preview place
function previewPlace(placeId) {
	document.cookie = "placeId="+placeId;
	window.location = "previewPlace.php";
}
function showOnMap(lat, lon, containerId) {
	$("#"+containerId).fadeToggle('fast');
    latlon = new google.maps.LatLng(lat, lon);

    var myOptions = {
	    center:latlon,zoom:14,
	    mapTypeId:google.maps.MapTypeId.ROADMAP,
	    mapTypeControl:false,
	    navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
    }
    
    var map = new google.maps.Map(document.getElementById(containerId), myOptions);
    var marker = new google.maps.Marker({position:latlon,map:map,title:"The place is here !"});
}

//Places - Delete place
function deletePlace(placeId) {
	document.cookie = "placeId="+placeId;
	window.location = "deletePlace.php";
}

//Place - Share place
function sharePlace(placeId) {
	document.cookie = "placeId="+placeId;
	window.location = "sharePlace.php";
}

//Place - World places
	//..PREVIEW..
function previewWorldPlace(placeId, sender) {
	document.cookie = "placeId="+placeId;

	var requestType;

	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("GET", "getPlaceInfo.php", true);
	requestType.send();

	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
            //document.getElementById("txtHint").innerHTML = requestType.responseText;
			var getRequest = requestType.responseText;            
			var count = 0;
			var slice;

			//Struct
			var placeId = "";
			var placeTitle = "";
			var placeCords = "";
			var placeStory = "";
			var taggedPeople = "";
			var likers = "";

			for (i = 0; i < getRequest.length; i++) {
				if (getRequest[i] == '~') {
					count++;
					if (count == 1) {
						slice = i + 1;
						while (getRequest[slice] != '$') {
							placeId += getRequest[slice]; 
							slice++;
						}
					}
					else
					if (count == 2) {
						slice = i + 1;
						while (getRequest[slice] != '$') {
							placeTitle += getRequest[slice]; 
							slice++;
						}
					}
					else
					if (count == 3) {
						slice = i + 1;
						while (getRequest[slice] != '$') {
							placeCords += getRequest[slice]; 
							slice++;
						}
					}
					else
					if (count == 4) {
						slice = i + 1;
						while (getRequest[slice] != '$') {
							placeStory += getRequest[slice]; 
							slice++;
						}
					}
					else
					if (count == 5) {
						slice = i + 1;
						while (getRequest[slice] != '$') {
							taggedPeople += getRequest[slice]; 
							slice++;
						}
					}
					else
					if (count == 6) {
						slice = i + 1;
						while (getRequest[slice] != '$') {
							likers += getRequest[slice]; 
							slice++;
						}
					}
				}
			}

			var pinnedTimes = 0;
			if (likers != "NONE") {
				var pinnedBy = likers.split(",");
				for (i = 0; i < pinnedBy.length; i++) {
					pinnedTimes++;
				}
			} else {
				pinnedTimes = 0;
			}

			//Build UI
			if (document.getElementById('previewPlaceWorld') != null) document.getElementById("body").removeChild(document.getElementById("previewPlaceWorld"));
			var body = document.getElementById('body');
			body.innerHTML += "\
				<div id='previewPlaceWorld'>\
					<div id='menuContainer'>\
						<div class='left'>\
							<button type='button' id='pinnButton' class='pinnButton' onclick='pinPlace(\""+placeId+"\")' title='Like this place'>\
								<img src='https://cdn0.iconfinder.com/data/icons/very-basic-android-l-lollipop-icon-pack/24/like-512.png' /> "+pinnedTimes+"\
							</button>\
						</div>\
						<div class='right'>\
							<button type='button' class='hideButton' onclick='removeElement(\"previewPlaceWorld\", \"body\");'></button>\
						</div>\
						<h1>"+placeTitle+"</h1>\
					</div>\
					<div id='storyContainer'>\
						<p>\
							"+placeStory+"\
						</p>\
						<div id='taggedContainer'>\
						</div>\
						<div id='mapControler'>\
						</div>\
					</div>\
				</div>\
			";

			showOnMap(placeCords.split("#")[0], placeCords.split("#")[1], "mapControler");

			if (taggedPeople.indexOf(",") > -1) {
				document.getElementById("taggedContainer").innerHTML = "\
					<button type='button' title='Tagged people' onclick='$(\"#list\").slideToggle(\"fast\")'>\
						<img src='https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-people-512.png' />\
					</button>\
					<div id='list'>\
					</div>\
				";

				parseBlogger(taggedPeople, taggedPeople.split(",").length - 1, 0);
			} else {
				if (taggedPeople != sender) {
					document.getElementById("taggedContainer").innerHTML = "\
						<button type='button' title='Tagged people' onclick='$(\"#list\").slideToggle(\"fast\")'>\
							<img src='https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-people-512.png' />\
						</button>\
						<div id='list'>\
						</div>\
					";
					parseBlogger(taggedPeople, 0, -1);
				}
			}
        }
    }
}

//Function - ParseBlogger
function parseBlogger(blogerId, callBacks, indexPointer) {
	var flag = 0;
	if (blogerId.indexOf(",") > -1) {
		blogerId = blogerId.split(",");
		document.cookie = "userId="+blogerId[indexPointer];
		flag = 1;
	} else {
		document.cookie = "userId="+blogerId;
	}

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("GET", "getUserInfo.php", true);
	requestType.send();

	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
     		result = requestType.responseText;
 		   	var parseResult = result.split("$");

 		   	var blogerIdArgument = "";
 		   	if (flag == 1) {
 		   		blogerIdArgument = blogerId[indexPointer];
 		   	}
 		   	else {
 		   		blogerIdArgument = blogerId;
 		   	}

 		   	var build = "\
 		   		<button type='button' onclick='exploreBloger(\""+blogerIdArgument+"\")'>\
 		   			<img src='"+parseResult[0]+"' />\
 		   			"+parseResult[2]+" "+parseResult[3]+"\
 		   		</button>\
 		   		<form id='"+blogerIdArgument+"' method='post' style='display: none;'>\
					<input type='text' name='blogSender' value='"+blogerIdArgument+"'>\
					<input type='text' name='blogerFN' value='"+parseResult[2]+"'>\
					<input type='text' name='blogerLN' value='"+parseResult[3]+"'>\
					<input type='text' name='blogerImg' value='"+parseResult[0]+"'>\
					<input type='text' name='blogerHref' value='"+parseResult[1]+"'>\
				</form>\
 		   	";

			document.getElementById("list").innerHTML += build;
 		 	document.cookie = "userId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";

 		 	if (callBacks > 0) {
 		 		parseBlogger(blogerId.toString(), callBacks - 1, indexPointer + 1);
 		 	}
     	}
    }
}

//Places - Resize map 
function resizeToggle(containerId) {
	if (flag == 0) {
		$("#"+containerId).animate({
            height: '+=30%',
            top: '-=30%'
        }, 500);

		document.getElementById("resizeButton").src = "https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-arrow-down-512.png";
		flag = 1;
	}
	else
	if (flag == 1) {
		$("#"+containerId).animate({
            height: '-=30%',
        	top: '+=30%'
        }, 500);

		document.getElementById("resizeButton").src = "https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-arrow-up-512.png";
		flag = 0;
	}
}

//Places - PinPlace 
function pinPlace(placeId) {
	document.cookie = "placeId="+placeId;

	var requestType;

	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("GET", "pinPlace.php", true);
	requestType.send();

	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
     		var getRequest = requestType.responseText;
     		document.getElementById('pinnButton').innerHTML = "<img src='https://cdn0.iconfinder.com/data/icons/very-basic-android-l-lollipop-icon-pack/24/like-512.png' /> "+getRequest;
        	document.cookie = "getId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
        }
    }
}

//Play video
function playVideo(videoSrc, videoType) {
	document.getElementById("fullPageContainer").innerHTML += "\
			<div id='videoContainer'>\
				<video controls>\
					<source src='"+videoSrc+"' type='video/"+videoType+"'>\
				</video>\
			</div>\
	";
	$("#fullPageContainer").fadeIn("fast");
}

function stopVideo() {
	$("#fullPageContainer").fadeOut("fast");
	document.getElementById("fullPageContainer").removeChild(document.getElementById("videoContainer"));
}

//Load stories - Author
function loadStories(storeIDs, cmd, from, sender) {
	if (storeIDs != "") {
		var parseIDs = storeIDs.split(",");
		document.cookie = "sender="+sender+";domain=.blogy.sitemash.net;path=/";
		document.cookie = "getId="+parseIDs[loops - 1]+";domain=.blogy.sitemash.net;path=/";
		document.cookie = "buildFor="+cmd+";domain=.blogy.sitemash.net;path=/";

		var table = document.getElementById('main-table');
		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}

		var build;
		if (from == 0) {
			if ($("#loadSign").size() == 0 && loops > 0) {
				build = "\
					<div id='loadSign'>\
						<img src='../images/loadSign.GIF' />\
					</div>\
				";
			}
			requestType.open("GET", "pullStories.php", true);
		} 
		else 
		if (from == 1) {
			if ($("#loadSign").size() == 0 && loops > 0) {
				build = "\
					<div id='loadSign'>\
						<img src='../../images/loadSign.GIF' />\
					</div>\
				";
			}
			requestType.open("GET", "../../PHP/pullStories.php", true);
		}
		else
		if (from == 2) {
			if ($("#loadSign").size() == 0 && loops > 0) {
				build = "\
					<div id='loadSign'>\
						<img src='../../images/loadSign.GIF' />\
					</div>\
				";
			}
			requestType.open("GET", "pullStories.php", true);
		}

		$("#body").append(build);
		requestType.send();

		requestType.onreadystatechange = function() {
			if (requestType.readyState == 4 && requestType.status == 200) {
				$("#loadSign").remove();
				$("#main-table").append(requestType.responseText);
				document.cookie = "sender=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
				document.cookie = "getId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
				document.cookie = "buildFor=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
				if (dinamic - loops < 4 && loops > 1) {
					loops--;
					loadStories(storeIDs, cmd, from, sender);
				} else {
					flag = 0;
				}
			}
		}
	}
}

//Load stories - Explorer
function loadExplorerStories(storePosts, cmd) {
	if (storePosts != "") {
		if ($("#loadSign").size() == 0 && loops > 0) {
			var build = "\
				<div id='loadSign'>\
					<img src='../images/loadSign.GIF' />\
				</div>\
			";
			$(build).appendTo("#body");
		}
		
		var parsePosts = storePosts.split(",");

		document.cookie = "getId="+parsePosts[loops - 1].split("@")[0]+";domain=.blogy.sitemash.net;path=/";
		document.cookie = "buildFor=2"+";domain=.blogy.sitemash.net;path=/";
		if (cmd == 0) document.cookie = "authorInfo="+parsePosts[loops - 1].split("@")[1];
		if (cmd == 1) document.cookie = "buildWorldStories=1";

		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}
		requestType.open("GET", "pullStories.php", true);
		requestType.send();

		requestType.onreadystatechange = function() {
			if (requestType.readyState == 4 && requestType.status == 200) {
	 			//Store stories
				//container.push(requestType.responseText);
				$("#loadSign").remove();
				$("#main-table").append(requestType.responseText);
				document.cookie = "sender=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
				document.cookie = "getId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
				document.cookie = "buildFor=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
				if (dinamic - loops < 4) {
					loops--;
					loadExplorerStories(storePosts, cmd);
				} else {
					flag = 0;
				}
			}
		}
	}
}

//Page manipulations - Explore Stories / Explore F. Stories
function checkPos() { //Checks the scroll and loads more stories if true
	if ($(window).scrollTop() + document.body.clientHeight + 400 >= $(window).height()) {
		if (loops > 0 && flag == 0) {flag = 1; callBack();}
	}
}


//Manipulate post
	//Delete post
function deletePost(postTitle, postId) {
	document.cookie = "postTitle="+postTitle;

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("GET", "deleteMethod.php", true);
	requestType.send();

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
 			var storeResponce = requestType.responseText;
			
			if (storeResponce == "READY") {
				$("."+postId).remove();
				document.cookie = "postTitle=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
			}
		}
	}
}

	//Edit post
function editPost(postTitle, postId) {
	document.cookie = "postId="+postTitle;
	POSTPOINTER = postId;

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("GET", "editMethod.php", true);
	requestType.send();

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
 			var storeResponce = requestType.responseText;
			storeResponce = storeResponce.split("$");

			document.getElementById("editorTitle").innerHTML = storeResponce[0];
			document.getElementById("editorLink").value = storeResponce[1];
			document.getElementById("editorContent").value = storeResponce[2];

			document.cookie = "postId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";

			$("#editorContainer").fadeIn("fast");
		}
	}
}

	//Close editor
function clearEditorContainer() {
	$("#editorContainer").fadeOut("fast");

	document.cookie = "postPoint=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
	document.getElementById("editorTitle").innerHTML = "Title";
	document.getElementById("editorLink").value = "";
	document.getElementById("editorContent").value = "";
}

	//Update post 
function updatePost() {
	document.cookie = "postId="+document.getElementById("editorTitle").innerHTML;
	document.cookie = "postLink="+document.getElementById("editorLink").value;
	document.cookie = "postContent="+document.getElementById("editorContent").value.replace(/(?:\r\n|\r|\n)/g, '<br />');

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("GET", "updateMethod.php", true);
	requestType.send();

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
 			var storeResponce = requestType.responseText;

			if (storeResponce == "READY") {
				document.cookie = "getId="+POSTPOINTER;
				document.cookie = "buildFor=0";
			
				var requestType1;
				if (window.XMLHttpRequest) {
					requestType1 = new XMLHttpRequest();
				} else {
					requestType1 = new ActiveXObject("Microsoft.XMLHTTP");
				}
				requestType1.open("GET", "pullStories.php", true);
				requestType1.send();

				requestType1.onreadystatechange = function() {
					if (requestType1.readyState == 4 && requestType1.status == 200) {
			 			var storePost = requestType1.responseText;
						
			 			$("."+POSTPOINTER).replaceWith(storePost);

						document.cookie = "getId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
						document.cookie = "buildFor=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
						document.cookie = "postId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
						document.cookie = "postLink=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
						document.cookie = "postContent=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
						clearEditorContainer();
					}
				}
			}
		}
	}
}

//Load bloggers
function loadBloggers(loops, stack, container) {
	if (stack != "") {
		parseStack = stack.split(",");
		document.cookie = "authorId="+parseStack[loops - 1];

		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}
		requestType.open("GET", "giveMePerson.php", true);
		requestType.send();

		requestType.onreadystatechange = function() {
			if (requestType.readyState == 4 && requestType.status == 200) {
	 			var storeResponce = requestType.responseText;
				
				document.getElementById(container).innerHTML += storeResponce;

				document.cookie = "authorId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";

				if (loops > 1) {
					loops--;
					loadBloggers(loops, stack, container);
				}
			}
		}
	}
}

//Notifications - Show notification date
function showNotificationDate(date) {
	$("#notifications").children("#title").children("h2").html(date);
}

//Notifications - Clear date container
function clearDateContainer() {
	$(".pushNotification").children("#title").children("h2").html("");
}

//User manipulation
	//Imitate logout
function imitateLogOut() {
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("GET", "imitateLogOut.php", true);
	requestType.send();
}
	//Imitate login
function imitateLogIn() {
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("GET", "imitateLogIn.php", true);
	requestType.send();
}


window.onunload = window.onbeforeunload = function() {imitateLogOut();};
window.onload = imitateLogIn();

//Check if element is in the viewport
function isScrolledIntoView(elem) {
    var $elem = $(elem);
    var $window = $(window);

    var docViewTop = $window.scrollTop();
    var docViewBottom = docViewTop + $window.height();

    var elemTop = $elem.offset().top;
    var elemBottom = elemTop + $elem.height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}






//Index page - Effects
/*
function dragToTOP(elementId) {
	$("#"+elementId).fadeOut("fast");
}
function dragToBOT(elementId) {
	$("#"+elementId).fadeIn("fast");
}

function setIndex(elementId, index) {
	document.getElementById(elementId).style.display = 'block';
	document.getElementById(elementId).style.zIndex = index;
}
function removeIndex(elementId) {
	document.getElementById(elementId).style.zIndex = 0;
}
*/