<?php
	function parseContent($title, $link, $content, $author, $arg) {
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

		$title = str_replace("6996", " ", $title);

		$content = nl2br($content);
		$url = NULL;

		if ($content != "") {
			$splitContent = explode("<br />", $content);
			$content = "";
			foreach ($splitContent as $line) {
				if(preg_match($reg_exUrl, $line, $url)) {
					if (!strpos($line, "<img") && !strpos($line, "<a")) {
						$line = preg_replace($reg_exUrl, "<a href='$url[0]' target='_blank'>$url[0]</a>", $line);
					}
				}
				$content .= "$line<br />";
			}
		}
		
		if ($link != "") {
			$parseUrl = parse_url($link);

			if ($parseUrl['host'] == 'www.youtube.com' || $parseUrl['host'] == 'm.youtube.com' || $parseUrl['host'] == 'youtu.be') {
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
				if (filter_var($link, FILTER_VALIDATE_URL)) {
					$url_headers=get_headers($link, 1);
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
							<a href='$link' data-lightbox='roadtrip'>
								<img src='$link' alt='Image link is broken :('/>
							</a>
						";
					} else {
						$cmd = "<h2>Unsupported player :(</h2>";
					}
				} else {
					$cmd = "
						<a href='$link' data-lightbox='roadtrip'>
							<img src='$link' alt='Image link is broken :('/>
						</a>
					";
				}
			}

			if ($arg == 0) {$postBuild = parseForUser(1, $title, $cmd, $content);}
			if ($arg == 1) {$postBuild = parseForViewer(1, $title, $cmd, $content);}
			if ($arg == 2) {$postBuild = parseForStories(1, $title, $cmd, $author, $content);}
		}
		else
		if ($postLink == "") {
			if ($arg == 0) {$postBuild = parseForUser(0, $title, 0, $content);}
			if ($arg == 1) {$postBuild = parseForViewer(0, $title, 0, $content);}
			if ($arg == 2) {$postBuild = parseForStories(0, $title, 0, $author, $content);}
		}

		return $postBuild;
	}

	function parseForUser($arg, $title, $cmd, $content) {
		$title = str_replace("'", "#", $title);
		$title = str_replace("\"", "#", $title);

		$getId = $_COOKIE['getId'];

		if ($arg == 1) {
			$build = "
				<tbody class='$getId'>
					<tr>
						<td>
						</td>
						<td id='poster'>
							<div id='quickMenu'>
								<a href='#!' onclick=\"editPost('$title', '$getId')\" class='left'>Edit<a>
								<a href='#!' onclick=\"deletePost('$title', '$getId')\" class='right'>Delete</a><br>
			";

			$title = str_replace("%id%", "", $title);

			$build .= "
							<h1>$title</h1>
							</div>
							$cmd
							<p>
								$content
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
				</tbody>
			";			
		} else 
			if ($arg == 0) {
				$build = "
					<tbody class='$getId'>
						<tr>
							<td>
							</td>
							<td id='poster'>
								<div id='quickMenu'>
									<a href='#!' onclick=\"editPost('$title', '$getId')\" class='left'>Edit<a>
									<a href='#!' onclick=\"deletePost('$title', '$getId')\" class='right'>Delete</a><br>
				";

				$title = str_replace("%id%", "", $title);

				$build .= "
								<h1>$title</h1>
								</div>
								<p>
									$content
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
					</tbody>
				";
			}

		return $build;
	}

	function parseForViewer($arg, $title, $cmd, $content) {
		$title = str_replace("%id%", "", $title);

		$getId = $_COOKIE['getId'];

		if ($arg == 1) {
			$build = "
				<tbody class='$getId'>
					<tr>
						<td>
						</td>
						<td id='poster'>
							<h1>$title</h1>
							$cmd
							<p>
								$content
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
				</tbody>
			";			
		} else 
			if ($arg == 0) {
				$build = "
					<tbody class='$getId'>
						<tr>
							<td>
							</td>
							<td id='poster'>
								<h1>$title</h1>
								<p>
									$content
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
					</tbody>
				";
			}

		return $build;
	}

	function parseForStories($arg, $title, $cmd, $author, $content) {
		$title = str_replace("%id%", "", $title);

		$getId = $_COOKIE['getId'];

		if ($arg == 1) {
			$build = "
				<tbody class='$getId'>
					<tr>
						<td>
						</td>
						<td id='poster'>
							<div id='history'>
								<a href='openBloger.php' onclick=\"openBloger('$author[0]')\">
									<img src='$author[1]' alt='Bad image link :('>
									<form id='$author[0]' method='post' style='display: none;'>
										<input type='text' name='blogSender' value='$author[0]'></input>
										<input type='text' name='blogerFN' value='$author[3]'></input>
										<input type='text' name='blogerLN' value='$author[4]'></input>
										<input type='text' name='blogerImg' value='$author[1]'></input>
										<input type='text' name='blogerHref' value='$author[2]'></input>
									</form>
								</a>
							</div>
							<div id='history-right'>
								<h1>$title</h1>
							</div>
							$cmd
							<p>
								$content
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
				</tbody>
			";
		} else
			if ($arg == 0) {
				$build = "
					<tbody class='$getId'>
						<tr>
							<td>
							</td>
							<td id='poster'>
								<div id='history'>
									<a href='openBloger.php' onclick=\"openBloger('$author[0]')\">
										<img src='$author[1]' alt='Bad image link :('>
										<form id='$author[0]' method='post' style='display: none;'>
											<input type='text' name='blogSender' value='$author[0]'></input>
											<input type='text' name='blogerFN' value='$author[3]'></input>
											<input type='text' name='blogerLN' value='$author[4]'></input>
											<input type='text' name='blogerImg' value='$author[1]'></input>
											<input type='text' name='blogerHref' value='$author[2]'></input>
										</form>
									</a>
								</div>
								<div id='history-right'>
									<h1>$title</h1>
								</div>
								<p>
									$content
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
					</tbody>
				";
			}

		return $build;
	}
?>