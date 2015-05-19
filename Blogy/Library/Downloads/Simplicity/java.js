function showImg(id) {			
	pic = new Image();
	pic.src = document.getElementById(id).src;
	
	var title = 'Portfolio';

	document.write('<head><title>');
		document.write(title);
		document.write('</title>');
		document.write('<link href=\'style.css\' rel=\'stylesheet\' type=\'text/css\' media=\'screen\' />');
	
		document.write('<style>body{background-color: black;} #sub-gallery img{border-radius: 2px;}</style>');
	document.write('</head>');
	document.write('<div id=\'sub-gallery\'>');
		document.write('<a href=\'#\' onclick=\'location.reload()\'><img src=\'');
		document.write(pic.src);
		document.write('\'></а>');
	document.write('</div>');
}