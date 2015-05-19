<?php	
	//Connect to data base
	$servername = "localhost";
	$username = "kdkcompu_gero";
	$password = "Geroepi4";
	$dbname = "kdkcompu_gero";
	
	$onlineStack = array();
	$onlineUsers = array();

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

	
	$followingPull = fopen("../Authors/$sender/Following.txt", "r") or die("Fatal: Could not start opening.");
	while (!feof($followingPull)) {
		$line = trim(fgets($followingPull));
		if ($line != "") {
			$sugestion = $line;

			//Check if sugestion is online	
			$sql = "SELECT USERID FROM logedUsers WHERE USERID='$sugestion'";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				$lineCount = 0;
				$pullInfo = fopen("../Authors/$sugestion/config.txt", "r") or die("Fatal: Could not load.");
				while (!feof($pullInfo)) {
					$pickupLine = trim(fgets($pullInfo));
					if ($line != "") {
						if ($lineCount == 0) {
							$sugestionImg = $pickupLine;
						}
						else
						if ($lineCount == 1) {
							$sugestionHref = $pickupLine;
						}
						else
						if ($lineCount == 3) {
							$sugestionFN = $pickupLine;
						}
						else
						if ($lineCount == 4) {
							$sugestionLN = $pickupLine;
							break;
						}
					}
					$lineCount++;
				}
				fclose($pullInfo);
				
				//<a href='openBloger.php' type='button' onclick=\"openBloger('$sugestion')\">
				//</a>
				$buildOnline = "
					<button onclick=showQuickMenu('$sugestion')>
						<img src='$sugestionImg' />
						$sugestionFN $sugestionLN
					</button>
					<div id='quickMenu$sugestion' class='ohanaQuickMenu' style='display: none;'>
						<div id='quickMenu'>
							<button type='button' onclick=\"openBloger('$sugestion'); window.location=&#39;openBloger.php&#39;\">
								View story
							</button>
							<form id='$sugestion' method='post' style='display: none;'>
								<input type='text' name='blogSender' value='$sugestion'>
								<input type='text' name='blogerFN' value='$sugestionFN'>
								<input type='text' name='blogerLN' value='$sugestionLN'>
								<input type='text' name='blogerImg' value='$sugestionImg'>
								<input type='text' name='blogerHref' value='$sugestionHref'>
							</form>
							<button type='button' onclick=\"showMessageBox('$sugestion')\">
								Quick message
							</button>
						</div>
					</div>
					<br>
				";
				array_push($onlineStack, $buildOnline);
			}
		}
	}
	fclose($followingPull);
	
	if (empty($onlineStack)) {
		echo "<h2>No body is online now :(</h2>";
	} else {
		foreach($onlineStack as $onlineFriend) {
			echo "$onlineFriend";
		}
	}
?>