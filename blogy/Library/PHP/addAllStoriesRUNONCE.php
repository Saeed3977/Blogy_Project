<?php //Add all stories into global database algorithm - RUN ONCE
	//Pull stack
	$paths = scandir("../Authors");
	$stack = array();
	
	foreach ($paths as $path) {
		if ($path != "World" && $path != "Info.csv" && $path != "." && $path != ".." && $path != "index.php") {
			$pullStack = fopen("../Authors/$path/Posts/Stack.txt", "r") or die("Unable to pull");
			while (! feof($pullStack)) {
				$line = trim(fgets($pullStack));
				if ($line != "") {
					$stack = array_merge($stack, array("$line`$path" => date("Y-m-d H:i:s", filemtime("../Authors/$path/Posts/$line.txt"))));
				}
			}
			fclose($pullStack);
		}
	}

	arsort($stack);
	$allPosts = array_reverse($stack);
	
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "CREATE TABLE worldStories (ID int NOT NULL AUTO_INCREMENT, AuthorPOST LONGTEXT, POST LONGTEXT, PRIMARY KEY (ID))";
		$conn->query($sql);
		
		foreach ($allPosts as $key => $value) {
			$author = explode("`", $key)[1];
			$postId = explode("`", $key)[0];
			$postBuild = buildPosts($key);
			$postBuild = str_replace("'", "\'", $postBuild);
			$postBuild =  htmlentities($postBuild);
			$sql = "INSERT INTO worldStories (AuthorPOST, POST) VALUES ('$author:$postId', '$postBuild')";
			$conn->query($sql);
		}
	}
	$conn->close();
	
	function buildPosts($key) {
		$postId = explode("`", $key)[0];
		$postAuthor = explode("`", $key)[1];
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		$buildShared = 0;
		$builder = 0;
		$contentPost = (string)NULL;
		$count = 0;
		$fd = fopen("../Authors/$postAuthor/Posts/$postId.txt", "r") or die("Unable to open post.");
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
									<input type='password' name='accSender' value='$sender'></input>
									<input type='password' name='imgSender' value='$profilePic'></input>
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
		$count = 0;
		
		//Pull author
		$pullAuthor = fopen("../Authors/$postAuthor/config.txt", "r") or die("Unable to pull Author.");
		while (! feof($pullAuthor)) {
			$line = fgets($pullAuthor);
			if ($count == 0) {
				$authorImg = trim($line);
			}
			else
			if ($count == 1) {
				$authorHref = trim($line);
			}
			else
			if ($count == 3) {
				$authorFN = trim($line);
			}
			else
			if ($count == 4) {
				$authorLN = trim($line);
				break;
			}
			$count++;
		}
		fclose($pullAuthor);
		
		if ($postImg != "NULL") {
			$parseUrl = parse_url($postImg);

			if ($parseUrl['host'] == 'www.youtube.com' || $parseUrl['host'] == 'm.youtube.com' ||$parseUrl['host'] == 'youtu.be' ) {
				$query = $parseUrl['query'];
				$queryParse = explode("=", $query);
				
				if ($parseUrl['host'] == 'youtu.be') {
					$queryParse = $parseUrl['path'];
					$src = "https://www.youtube.com/embed/$queryParse";
				}
				else
				if ($parseUrl['host'] == 'm.youtube.com') {
					$src = "https://www.youtube.com/embed/$queryParse[1]";
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
				if (filter_var($postImg, FILTER_VALIDATE_URL)) {
					$url_headers=get_headers($postImg, 1);
				}
				
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
						$cmd = "
							<a href='$postImg' data-lightbox='roadtrip'>
								<img src='$postImg' alt='Image link is broken :('/>
							</a>
						";
					} else {
						$cmd = "<h2>Unsupported player :(</h2>";
					}
				}  else {
					$cmd = "
						<a href='$postImg' data-lightbox='roadtrip'>
							<img src='$postImg' alt='Image link is broken :('/>
						</a>
					";
				}
			}
			
			$postBuild = "
			<tr>
				<td>
				</td>
				<td id='poster'>
					<div id='history'>
						<a href='openBloger.php' onclick=\"openBloger('$postAuthor')\">
							<img src='$authorImg' alt='Bad image link :('>
							<form id='$postAuthor' method='post' style='display: none;'>
								<input type='text' name='accSender' value='$sender'></input>
								<input type='text' name='imgSender' value='$profilePic'></input>
								<input type='text' name='blogSender' value='$postAuthor'></input>
								<input type='text' name='blogerFN' value='$authorFN'></input>
								<input type='text' name='blogerLN' value='$authorLN'></input>
								<input type='text' name='blogerImg' value='$authorImg'></input>
								<input type='text' name='blogerHref' value='$authorHref'></input>
							</form>
						</a>
					</div>
					<div id='history-right'>
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
			$postBuild = "
			<tr>
				<td>
				</td>
				<td id='poster'>
					<div id='history'>
						<a href='openBloger.php' onclick=\"openBloger('$postAuthor')\">
							<img src='$authorImg' alt='Bad image link :('>
							<form id='$postAuthor' method='post' style='display: none;'>
								<input type='text' name='accSender' value='$sender'></input>
								<input type='text' name='imgSender' value='$profilePic'></input>
								<input type='text' name='blogSender' value='$postAuthor'></input>
								<input type='text' name='blogerFN' value='$authorFN'></input>
								<input type='text' name='blogerLN' value='$authorLN'></input>
								<input type='text' name='blogerImg' value='$authorImg'></input>
								<input type='text' name='blogerHref' value='$authorHref'></input>
							</form>
						</a>
					</div>
					<div id='history-right'>
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
		
		return $postBuild;
	}
?>