<?php
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

			if ($parseUrl['host'] == 'www.youtube.com') {
				$query = $parseUrl['query'];
				$queryParse = explode("=", $query);
				$src = "https://".$parseUrl['host']."/embed/$queryParse[1]";
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
			
			/*
			<iframe src="//www.break.com/embed/2820004?embed=1" width="464" height="280" webkitallowfullscreen mozallowfullscreen allowfullscreen frameborder="0">
			*/
			
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
?>