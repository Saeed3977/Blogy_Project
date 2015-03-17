<?php
	$mail = $_POST['mail'];
	$pass = $_POST['password'];
	
	require 'helpFunctions.php';
	
	$flag = 0;
	$logs = fopen("../Authors/Info.csv","r");
	//print_r (fgetcsv($logs)); //This shit gets line from csv file
	while(!feof($logs))
	{
		if ($flag == 2) {
			break;
		}
		
		$array = fgetcsv($logs);
		if ($mail == $array[0]) {
			$flag = 1;
			
			$path = "../Authors/$array[1]/config.txt";
			$fd = fopen("$path", "r") or die("Unable to open file.");
			$line_counter = 0;
			while (!feof($fd)) {
				$line = fgets($fd);
				if ($line_counter == 0) {
					$senderImg = trim($line);
				}
				else
				if ($line_counter == 1) {
					$senderHref = trim($line);
				}
				else
				if ($line_counter == 2) {
					$sender = trim($line);
				}
				else
				if ($line_counter == 3) {
					$senderFN = trim($line);
				}
				else
				if ($line_counter == 4) {
					$senderLN = trim($line);
				}
				else
				if ($line_counter == 5) {
					$passCode = trim($line);
					break;
				}
				/*
				else
				if ($line_counter == 6) {
					$senderMail = trim($line);
				}
				else
				if ($line_counter == 7) {
					$senderNotifyOnPost = trim($line);
				}
				else
				if ($line_counter == 8) {
					$senderNotifyOnMessage = trim($line);
				}
				*/
				$line_counter++;
			}
			fclose($fd);
			
			if ($pass == $passCode) {
				$flag = 2;
				
				//Connect to data base
				$servername = "localhost";
				$username = "kdkcompu_gero";
				$password = "Geroepi4";
				$dbname = "kdkcompu_gero";
				
				$conn = mysqli_connect($servername, $username, $password, $dbname);
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				} else {
					//Creatе new TABLE
					$sql = "CREATE TABLE $sender (STACK LONGTEXT)";
					/*
						AuthorID LONGTEXT,
						AuthorImg LONGTEXT,
						AuthorHref LONGTEXT,
						AuthorFN LONGTEXT,
						AuthorLN LONGTEXT
					*/
					
					if ($conn->query($sql) === TRUE) {
						/*
						//Add info in the table
						$sql = "INSERT INTO $sender (AuthorID, AuthorImg, AuthorHref, AuthorFN, AuthorLN)
						VALUES ('$sender', '$senderImg', '$senderHref', '$senderFN', '$senderLN')";
						$conn->query($sql);
						if ($conn->query($sql) === TRUE) {
							logIn($sender, $senderImg, $senderHref, $senderFN, $senderLN);
						}
						*/
						
						$stack = buildDatabase($conn, $sender);
						logIn($sender, $senderImg, $senderHref, $senderFN, $senderLN);
					} else {					
						logIn($sender, $senderImg, $senderHref, $senderFN, $senderLN);
					}
				}
				$conn->close();
				
				/*
				*/
			}
		}
	}
	
	function buildDatabase($conn, $sender) {
		$stack = (string)NULL;
		//Build stack table
		$sql = "CREATE TABLE stack$sender (ID int NOT NULL AUTO_INCREMENT, DATE LONGTEXT NOT NULL, STACK LONGTEXT, BUILD LONGTEXT, VIEW LONGTEXT, PUBLICVIEW LONGTEXT, PRIMARY KEY (ID))";
		if ($conn->query($sql) === TRUE) {	
			//Get stack
			$stack = array();
			$pullStack = fopen("../Authors/$sender/Posts/Stack.txt", "r") or die("Unable to pull stack");
			while (!feof($pullStack)) {
				$line = trim(fgets($pullStack));
				if ($line != "") {
					$dateTime = date("Y-m-d H:i:s", filemtime("../Authors/$sender/Posts/$line.txt"));
					
					if (strpos($line, " ")) {
						$line = str_replace(" ", "6996", $line);
					}
					$post = $line;
					
					$postBuild = buildPost($sender, $post);
					$postBuild = str_replace("'", "\'", $postBuild);
					$postBuild =  htmlentities($postBuild);
					
					$postBuildViewer = buildPostViewer($sender, $post);
					$postBuildViewer = str_replace("'", "\'", $postBuildViewer);
					$postBuildViewer =  htmlentities($postBuildViewer);
					
					$postBuildStories = buildPostStories($sender, $post);
					$postBuildStories = str_replace("'", "\'", $postBuildStories);
					$postBuildStories =  htmlentities($postBuildStories);
										
					$sql = "INSERT INTO stack$sender (DATE, STACK, BUILD, VIEW, PUBLICVIEW) VALUES ('$dateTime', '$line', '$postBuild', '$postBuildViewer', '$postBuildStories')";
					$conn->query($sql);
					array_push($stack, $line);
				}
			}
			fclose($pullStack);
		}
		
		return $stack;
	}
/*	
	function buildPost($sender, $post) {
		$postBuild = (string)NULL;
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		$count = 0;
		$flag = 0;
		$contentPost = (string)NULL;
	
		if (strpos($post, "6996")) {
			$post = str_replace("6996", " ", $post);
		}
	
		$buildShared = 0;
		$builder = 0;
		$count = 0;
		$fd = fopen("../Authors/$sender/Posts/$post.txt", "r") or die("Unable to open post.");
		while (!feof($fd)) {
			$line = fgets($fd);
			if ($count == 0) {
				$titlePost = trim($line);
			}
			else
			if ($count == 1) {
				$postImg = trim($line);
			}
			else
			if ($count == 2 || $flag == 1) {
				$line = str_replace("<br />", "", $line);
				if (trim($line) == "$+Shared") {
					$buildShared = 1;
				} 
				
				if ($buildShared == 1) {
					if ($builder == 1) {
						$currentSender = trim($line);
					}
					else
					if ($builder == 2) {
						$currentSenderImg = trim($line);
					}
					else
					if ($builder == 3) {
						$authorSender = trim($line);
					}
					else
					if ($builder == 4) {
						$authorSenderFN = trim($line);
					}
					else
					if ($builder == 5) {
						$authorSenderLN = trim($line);
					}
					else
					if ($builder == 6) {
						$authorSenderImg = trim($line);
					}
					else
					if ($builder == 7) {
						$authorSenderHref = trim($line);
					}
					
					$builder++;
					if (trim($line) == "$-") {
						$builder = 1;
						$contentPost = "
							<a href='openBloger.php' onclick=\"openBloger('$authorSender')\">
								<img src='$authorSenderImg' alt='Bad image link :(' />
							</a>
							<form id='$authorSender' method='post' style='display: none;'>
								<input type='password' name='accSender' value='$currentSender'></input>
								<input type='password' name='imgSender' value='$currentSenderImg'></input>
								<input type='password' name='blogSender' value='$authorSender'></input>
								<input type='password' name='blogerFN' value='$authorSenderFN'></input>
								<input type='password' name='blogerLN' value='$authorSenderLN'></input>
								<input type='password' name='blogerImg' value='$authorSenderImg'></input>
								<input type='password' name='blogerHref' value='$authorSenderHref'></input>
							</form>
						";
						$buildShared = 0;
					}
				} else {
					$url = NULL;
					if(preg_match($reg_exUrl, $line, $url)) {
						if (!strpos($line, "<img") && !strpos($line, "<a")) {
							$line = preg_replace($reg_exUrl, "<a href='$url[0]' target='_blank'>$url[0]</a>", $line);
						}
					}
					$contentPost .= $line."<br>";
				}
				$flag = 1;
			}
			$count++;
		}
		fclose($fd);
		
		if ($contentPost == "NULL<br>") {
			$contentPost = NULL;
		}
		
		
		if (is_numeric($titlePost)) {
			$titleId = "id$titlePost";
		} else {
			$titleId = $titlePost;
		}
		
		if ($postImg != "NULL") {
			$parseUrl = parse_url($postImg);

			if ($parseUrl['host'] == 'www.youtube.com' || $parseUrl['host'] == 'youtu.be') {
				$query = $parseUrl['query'];
				$queryParse = explode("=", $query);
				
				if ($parseUrl['host'] == 'youtu.be') {
					$queryParse = $parseUrl['query'];
					$src = "http://".$parseUrl['host']."/$queryParse";
				} else {
					$src = "https://".$parseUrl['host']."/embed/$queryParse[1]";
				}
				
				$cmd = "<iframe src='$src' frameborder='0' allowfullscreen></iframe>";
			}
			else 
			if ($parseUrl['host'] == 'vimeo.com') {
				$query = $parseUrl['path'];
				$cmd ="<iframe src='//player.vimeo.com/video$query' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
			}
			else
			if ($parseUrl['host'] == 'www.dailymotion.com') {
				$query = $parseUrl['path'];
				$src = "//www.dailymotion.com/embed/$query";
				$cmd = "<iframe src='$src' frameborder='0' allowfullscreen></iframe>";
			}
			else
			if ($parseUrl['host'] == 'www.metacafe.com') {
				$query = $parseUrl['path'];
				$queryParse = explode("/", $query);
				$src = "http://www.metacafe.com/embed/$queryParse[2]/";
				$cmd = "<iframe src='$src' allowFullScreen frameborder=0></iframe>";
			}
			else {
				$url_headers=get_headers($postImg, 1);

				if(isset($url_headers['Content-Type'])){
					$type=strtolower($url_headers['Content-Type']);

					$valid_image_type=array();
					$valid_image_type['image/png']='';
					$valid_image_type['image/jpg']='';
					$valid_image_type['image/jpeg']='';
					$valid_image_type['image/jpe']='';
					$valid_image_type['image/gif']='';
					$valid_image_type['image/tif']='';
					$valid_image_type['image/tiff']='';
					$valid_image_type['image/svg']='';
					$valid_image_type['image/ico']='';
					$valid_image_type['image/icon']='';
					$valid_image_type['image/x-icon']='';

					if(isset($valid_image_type[$type])) {
						$cmd = "<img src='$postImg' alt='Image link is broken :('/>";
					} else {
						$cmd = "<h2>Unsupported player :(</h2>";
					}
				}
			}
			
			$postBuild = "
			<tr>
				<td>
				</td>
				<td id='poster'>
					<div id='quickMenu'>
						<a href='#' onclick=\"editPost('$titleId')\" class='left'>Edit<a>
						<a href='#' onclick=\"deletePost('$titleId')\" class='right'>Delete</a><br>
						<form id='$titleId' method='post'>
							<input name='sender' value='$fullName'></input>
							<input name='postId' value='$titlePost'></input>
							<input name='content' value='$contentPost'></input>
						</form>
					<h1>$titlePost</h1>
					</div>
					$cmd
					<p>
						$contentPost
					</p>
				</td>
				<td>
				</td>
			</tr>
			<tr>
				<td>
					<br>
				</td>
			</tr>
			";
		}
		else
		if ($postImg == "NULL") {
			if ($builder == 0) {
				$postBuild = "
				<tr>
					<td>
					</td>
					<td id='poster'>
						<div id='quickMenu'>
							<a href='#' onclick=\"editPost('$titleId')\" class='left'>Edit<a>
							<a href='#' onclick=\"deletePost('$titleId')\" class='right'>Delete</a>
							<form id='$titleId' method='post'>
								<input name='sender' value='$fullName'></input>
								<input name='postId' value='$titlePost'></input>
								<input name='content' value='$contentPost'></input>
							</form>
						<h1>$titlePost</h1>
						</div>
						<p>
							$contentPost
						</p>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td>
						<br>
					</td>
				</tr>
				";
			}
			else
			if ($builder == 1) {
				$postBuild = "
				<tr>
					<td>
					</td>
					<td id='poster'>
						<div id='quickMenu'>
							<a href='#' class='left' style='visibility: hidden;'>Edit<a>
							<a href='#' onclick=\"deletePost('$titleId')\" class='right'>Delete</a>
							<form id='$titleId' method='post'>
								<input name='sender' value='$fullName'></input>
								<input name='postId' value='$titlePost'></input>
							</form>
						<h1>$titlePost</h1>
						</div>
						<p>
							$contentPost
						</p>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td>
						<br>
					</td>
				</tr>
				";
			}
		}
		
		return $postBuild;
	}
*/
	
	if ($flag == 0) {
		header('Location: ../Errors/E2.html');	
	}
	else 
	if ($flag == 1) {
		header('Location: ../Errors/E3.html');
	}
	
	fclose($logs);
	
	function logIn($sender, $senderImg, $senderHref, $senderFN, $senderLN) {
		session_start();
		$_SESSION['sender'] = $sender;
		$_SESSION['senderImg'] = $senderImg;
		$_SESSION['senderHref'] = $senderHref;
		$_SESSION['senderFN'] = $senderFN;
		$_SESSION['senderLN'] = $senderLN;
		$_SESSION[$sender] = "logedIn";
		
		header('Location: logedIn.php');
	}

/*	
	function buildPostViewer($sender, $post) {
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		$flag = 0;
		$contentPost = (string)NULL;
		
		if (strpos($post, "6996")) {
			$post = str_replace("6996", " ", $post);
		}
		
		$buildShared = 0;
		$builder = 0;
		$count = 0;
		$fd = fopen("../Authors/$sender/Posts/".$post.".txt", "r") or die("Unable to open post.");
		while (!feof($fd)) {
			$line = fgets($fd);
			if ($count == 0) {
				$titlePost = trim($line);
			}
			else
			if ($count == 1) {
				$postImg = trim($line);
			}
			else
			if ($count == 2 || $flag == 1) {
				$line = str_replace("<br />", "", $line);
				if (trim($line) == "$+Shared") {
					$buildShared = 1;
				}
				
				if ($buildShared == 1) {
					if ($builder == 1) {
						$currentSender = trim($line);
					}
					else
					if ($builder == 2) {
						$currentSenderImg = trim($line);
					}
					else
					if ($builder == 3) {
						$authorSender = trim($line);
					}
					else
					if ($builder == 4) {
						$authorSenderFN = trim($line);
					}
					else
					if ($builder == 5) {
						$authorSenderLN = trim($line);
					}
					else
					if ($builder == 6) {
						$authorSenderImg = trim($line);
					}
					else
					if ($builder == 7) {
						$authorSenderHref = trim($line);
					}
					
					$builder++;
					if (trim($line) == "$-") {
						$builder = 1;
						if ($authorSender == $sender) {
							$contentPost = "
								<a href='logedIn.php'>
									<img src='$authorSenderImg' alt='Bad image link :(' />
								</a>
							";
						}
						else
						if ($authorSender != $sender) {
							$contentPost = "
								<a href='openBloger.php' onclick=\"openBloger('$authorSender')\">
									<img src='$authorSenderImg' alt='Bad image link :(' />
								</a>
								<form id='$authorSender' method='post' style='display: none;'>
									<input type='password' name='blogSender' value='$authorSender'></input>
									<input type='password' name='blogerFN' value='$authorSenderFN'></input>
									<input type='password' name='blogerLN' value='$authorSenderLN'></input>
									<input type='password' name='blogerImg' value='$authorSenderImg'></input>
									<input type='password' name='blogerHref' value='$authorSenderHref'></input>
								</form>
							";
						}
						$buildShared = 0;
					}
				} else {
					$url = NULL;
					if(preg_match($reg_exUrl, $line, $url)) {
						$line = preg_replace($reg_exUrl, "<a href='$url[0]' target='_blank'>$url[0]</a>", $line);
					}
					$contentPost .= $line."<br>";
				}
				$flag = 1;
			}
			$count++;
		}
		fclose($fd);
		
		if ($contentPost == "NULL<br>") {
			$contentPost = NULL;
		}
		
		if ($postImg != "NULL") {
			$parseUrl = parse_url($postImg);

			if ($parseUrl['host'] == 'www.youtube.com' || $parseUrl['host'] == 'youtu.be') {
				$query = $parseUrl['query'];
				$queryParse = explode("=", $query);
				
				if ($parseUrl['host'] == 'youtu.be') {
					$queryParse = $parseUrl['query'];
					$src = "http://".$parseUrl['host']."/$queryParse";
				} else {
					$src = "https://".$parseUrl['host']."/embed/$queryParse[1]";
				}
				
				$cmd = "<iframe src='$src' frameborder='0' allowfullscreen></iframe>";
			}
			else 
			if ($parseUrl['host'] == 'vimeo.com') {
				$query = $parseUrl['path'];
				$cmd ="<iframe src='//player.vimeo.com/video$query' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
			}
			else
			if ($parseUrl['host'] == 'www.dailymotion.com') {
				$query = $parseUrl['path'];
				$src = "//www.dailymotion.com/embed/$query";
				$cmd = "<iframe src='$src' frameborder='0' allowfullscreen></iframe>";
			}
			else
			if ($parseUrl['host'] == 'www.metacafe.com') {
				$query = $parseUrl['path'];
				$queryParse = explode("/", $query);
				$src = "http://www.metacafe.com/embed/$queryParse[2]/";
				$cmd = "<iframe src='$src' allowFullScreen frameborder=0></iframe>";
			}
			else {
				$url_headers=get_headers($postImg, 1);

				if(isset($url_headers['Content-Type'])){
					$type=strtolower($url_headers['Content-Type']);

					$valid_image_type=array();
					$valid_image_type['image/png']='';
					$valid_image_type['image/jpg']='';
					$valid_image_type['image/jpeg']='';
					$valid_image_type['image/jpe']='';
					$valid_image_type['image/gif']='';
					$valid_image_type['image/tif']='';
					$valid_image_type['image/tiff']='';
					$valid_image_type['image/svg']='';
					$valid_image_type['image/ico']='';
					$valid_image_type['image/icon']='';
					$valid_image_type['image/x-icon']='';

					if(isset($valid_image_type[$type])) {
						$cmd = "<img src='$postImg' alt='Image link is broken :('/>";
					} else {
						$cmd = "<h2>Unsupported player :(</h2>";
					}
				}
			}
			
			$postBuild = "
			<tr>
				<td>
				</td>
				<td id='poster'>
					<h1>$titlePost</h1>
					$cmd
					<p>
						$contentPost
					</p>
				</td>
				<td>
				</td>
			</tr>
			<tr>
				<td>
					<br>
				</td>
			</tr>
			";
		}
		else
		if ($postImg == "NULL") {
			$postBuild = "
			<tr>
				<td>
				</td>
				<td id='poster'>
					<h1>$titlePost</h1>
					<p>
						$contentPost
					</p>
				</td>
				<td>
				</td>
			</tr>
			<tr>
				<td>
					<br>
				</td>
			</tr>
			";
		}
	
		return $postBuild;
	}
*/
	die();
?>