<!DOCTYPE html>
<?php
$width = "<script type='text/javascript'>document.write(window.innerWidth);</script>";
$height = "<script type='text/javascript'>document.write(window.innerHeight);</script>";
include 'config.php'; //Get variables from config
include 'control.php';
$results = Array();
if (isset($_GET['tv'])) {
        $plexClientName = $_GET['tv'];
	$urlstring = "tv=" . $_GET['tv'];
	if ($_GET['tv'] != $configClientName && $_GET['tv'] !== "null" && $_GET['tv'] !== NULL) {
		$pseudochannel = $pseudochannelTrim . "_" . $_GET['tv'] . "/";
		$pseudochannel = trim($pseudochannel);
	} else {
		$pseudochannel = $pseudochannelTrim . "/";
		$plexClientName = $configClientName;
	}
} else {
	$urlstring = "";
}

//GET PLEX DATA
$url = "http://" . $plexServer . ":" . $plexport . "/status/sessions?X-Plex-Token=" . $plexToken; #set plex server url
$getxml = file_get_contents($url);
$xml = simplexml_load_string($getxml) or die("feed not loading");
$check_headers = get_headers($url, 1);
if (strpos($check_headers[0], "404") !== false) {
	$url_status = "404";
} else {
	$url_status = "ok";
}
$time_style=NULL;
$top_line=NULL;
$middle_line=NULL;
$bottom_line=NULL;
$dircontents=array();
$nowplaying=array();
$xmldata=array();
$chantable=array();
$ps_boxes=array();

//SET TIME AND DATE
$rightnow = time(); //time
$date = date('H:i'); //also time
//$date = date('H:i',strtotime("23:30"));
$dateunix = strtotime($date);
$day = date('D F d'); //date
$text_color='cyan';
$text_color_alt='cyan';

//CHECK IF PSEUDO CHANNEL IS RUNNING AND ON WHAT CHANNEL
//$is_ps_running = "find " . $pseudochannel . " -name running.pid -type f -exec cat {} +";
//$ps_channel_id = "find " . $pseudochannel . " -name running.pid -type f";
//$pgrep = shell_exec($is_ps_running); //check if pseudo channel is running
//$pdir = shell_exec($ps_channel_id); //identify directory has the running.pid file

$pid_array = array();
$pdir = "";
$pschandirs = glob($pseudochannel . "*", GLOB_ONLYDIR);
foreach($pschandirs as $pschan) {
	if (file_exists($pschan . "/running.pid")) {
		$pdir = $pschan . "/running.pid";
		break;
	}
}

//$pglob = glob($pseudochannel . "/psuedo-channel_*/running.pid");
if ($pdir !== "") {
	$pidfile = fopen($pdir, "r") or die("Unable to open running.pid file");
	$pgrep = fread($pidfile,filesize($pdir));
	$channel_number = str_replace($pseudochannel . "pseudo-channel_", "", $pdir); //strip the prefix of the directory name to get the channel number
	$channel_num = ltrim($channel_number, '0'); //strip the leading zero from single digit channel numbers
	$channel_num = str_replace("/running.pid", "", $channel_num); //strip running.pid filename from the variable
	$chnum = str_replace("/running.pid", "",$channel_number); //strip running.pid from the variable that keeps the leading zero
	$chnum = trim($chnum);	
} else {
	$channel_num = "NONE";
	$pgrep = 0;
}
//foreach($pglob as $pdir) {
	//$pidfile = fopen($pdir, "r") or die("Unable to open running.pid file");
	//$pgrep = fread($pidfile,filesize($ps_channel_id));
//}

// LINE STYLE VARIABLES
if ($DisplayType == 'half') {
	if($url_status=="404") {
		$time_style = "<p class='vcr-half-blink'>";
	} else {
		$time_style = "<p class='vcr-time-half'>";
	}
	$top_line = "<p class='vcr-info-half-1'>";
	$middle_line = "<p class='vcr-info-half-2'>";
	$bottom_line = "<p class='vcr-info-half-3'>";
	$side_channel = "<p class='vcr-side-half'>Channel $channel_num</p>";
	$position_half = "<img position: absolute; align: top; width='480' style='opacity:1;'>";
}

if ($DisplayType == 'full') {
      foreach ($xml->Video as $playdata) {
          if($playdata->Player['title'] == $plexClientName) {
			$video_duration = (int)$playdata['duration'];
			if($playdata['type'] == "movie") {
				if ($video_duration < "1800000") { //COMMERCIAL
				$text_color='cyan';
				$text_color_alt='cyan';
				} else { //MOVIE
				$text_color='yellow';
				$text_color_alt='white';
				}
			} elseif($playdata['type'] == "show" || $playdata['parentTitle'] != "") { //TV SHOW
			$text_color='yellow';
			$text_color_alt='white';
			} else {
			$text_color='cyan';
			$text_color_alt='cyan';
			}
			}
		  }

# SET FULL OPTIONS
	$time_style = "<p class='vcr-time-full-idle' style='color: $text_color;top:60px;'>";
	$top_line = "<p class='vcr-info-full-1-idle' style='color: $text_color; font-size:85px;top:240px;'>";
	$middle_line = "<p class='vcr-info-full-2-idle' style='color:cyan; font-size:55px;top:310px;'>";
	$bottom_line = "<p class='vcr-info-full-3-idle' style='color: cyan; font-size:65px;top:390px;'>";
	$side_channel = "<p class='vcr-side-full' style='color: cyan;font-size:75px;top:5px;'>Channel $channel_num</p>";

	$position_play_full = "<img position: absolute; top: 0; width='100%' style='opacity:1;'>";
	$position_idle_full = "<img position: absolute; top: 0; src='/assets/vcr-play.jpg' width='100%' style='opacity:1;'>";
}

//if(strcmp($channel_num," ")<=0){
//	$channel_num=0;
//}

//If Nothing is Playing
$text_color='cyan';
$text_color_alt='cyan';
if ($DisplayType == 'full') {
	$position=$position_idle_full;
}
if ($DisplayType == 'half') {
	$position=$position_half;
}
if($url_status=="404") {
	$top_section = $time_style . "12:00</p>" . $position;
	} else {
	$top_section = $time_style . $date . "</p>" . $position;
}
if ($pgrep >= 1) { //PSEUDO CHANNEL ON
	$middle_section = $top_line . "Channel $channel_num</p>";
	$bottom_section = $middle_line . "</p>";
	$nowplaying = "Channel $channel_num Standing By...";
	$nowplayingtext['status'] = 'Waiting';
	$nowplayingtext['channelNum'] = "$channel_num";
} else { //PSEUDO CHANNEL OFF
	$middle_section = $top_line . $day . "</p>";
	$bottom_section = "<p></p>";
	$nowplaying = "Please Stand By...";
	$nowplayingtext['status'] = 'Not Playing';
}

  if ($xml['size'] != '0') { //IF PLAYING CONTENT
      foreach ($xml->Video as $clients) {
          if($clients->Player['title'] == $plexClientName) { //If the active client on plex matches the client in the config
			    //IF PLAYING COMMERCIAL
				if($clients['type'] == "movie" && $clients['duration'] < 1800000) {
	          			#$text_color='cyan';
					#$text_color_alt='cyan';
                                        $commercialURL = "http://" . $plexServer . ":" . $plexport . $clients['key'] . "?X-Plex-Token=" . $plexToken;
                                        $getCommercialXML = file_get_contents($commercialURL);
                                        $commercialData = simplexml_load_string($getCommercialXML) or die("feed not loading");
					if ($DisplayType == 'full') {
					$position=$position_idle_full;
					}
					if ($DisplayType == 'half') {
					$position=$position_half;
					}
					$top_section = $time_style . $date . "</p>" . $position;
					$middle_section = $top_line . $commercialData['librarySectionTitle'] . "</p>";
					$bottom_section = "<p></p>";
					$title_clean = str_replace("_", " ", $clients['title']);
					$nowplaying = "<a href='schedule.php?$urlstring' style='color:white'>Now Playing: <span style='color:red;'>" . $title_clean . "</span> on Channel ". $channel_num . "</a>";
					$nowplayingtext['status'] = 'Playing';
					$nowplayingtext['mediaType'] = "Commercial";
					$nowplayingtext['title'] = $commercialData['librarySectionTitle'];
					$nowplayingtext['channelNum'] = $channel_num;
				}
				//IF PLAYING MOVIE
				if($clients['type'] == "movie" && $clients['duration'] >= 1800000) {
					$actor = "";
					$spin = 1;
					foreach ($clients->Role as $role) {
						if ($spin == 1) {
							$actor = $role['tag'];
						} else {
							$actor .= " and " . $role['tag'];
							break;
						}
						$spin = $spin + 1;
					}
					$nowplayingtext['status'] = 'Playing';
					$nowplayingtext['mediaType'] = "Movie";
					$nowplayingtext['title'] = $clients['title'];
					$nowplayingtext['year'] = $clients['year'];
					$nowplayingtext['channelNum'] = $channel_num;
					$nowplayingtext['rating'] = $clients['contentRating'];
					$nowplayingtext['tagline'] = $clients['tagline'];
					$nowplayingtext['actor'] = $actor;
				}
				//IF PLAYING TV SHOW
				if($clients['type'] == "show" || $clients['parentTitle'] != "") {
					if ($DisplayType == 'half') {
						$art = $clients['parentThumb'];
						$background_art	= "<img style='position: absolute; align: left; left: 0;' src='http:\/\/$plexServer:$plexport$art?X-Plex-Token=$plexToken' width='130'>";
						$position=$position_half;
					}
					if ($DisplayType == 'full') {
						$art = $clients['grandparentArt'];
						$background_art	= "<div style='opacity:.5;width:100%;height:100%;position: absolute; align: left; left: 0;background:url(http:\/\/$plexServer:$plexport$art?X-Plex-Token=$plexToken);background-repeat:no-repeat;background-position: center center;background-size:cover;' src='' width='100%' ></div>";
						$position=$position_play_full;
						$text_color='yellow';
						$text_color_alt='white';
					}
					$top_section =  $background_art . $time_style . $date . "</p>" . $position;
					$middle_section = $top_line . $clients['grandparentTitle'] . "</p>" . $middle_line . $clients['parentTitle'] . ", Episode " . $clients['index'] . "</p>";
					$bottom_section = $bottom_line . $clients['title'] . "</p>" . $side_channel . "</p>";
					$nowplaying = "<a href='schedule.php?$urlstring' style='color:white'>Now Playing:  <span style='color:red;'>" . $clients['grandparentTitle'] . " • " . $clients['parentTitle'] . ", Episode " . $clients['index'] . " • " . $clients['title'] . "</span> on Channel ". $channel_num . "</a>";
					$nowplayingtext['status'] = 'Playing';
					$nowplayingtext['mediaType'] = "TV";
					$nowplayingtext['title'] = $clients['grandparentTitle'];
					$nowplayingtext['season'] = $clients['parentTitle'];
					$nowplayingtext['episodeNum'] = $clients['index'];
					$nowplayingtext['episodeTitle'] = $clients['title'];
					$nowplayingtext['channelNum'] = $channel_num;
					}
				}
		  }
	  }

echo json_encode($nowplayingtext);
$jsonData = "{";
$l = 0;
foreach ($nowplayingtext as $k => $v) {
	if ($l !== 0) {
		$jsonData .= ",";
	}
	$jsonData .= "\"$k\":\"$v\"";
	$l = $l+1;
}
$jsonData .= "}";
file_put_contents($plexClientName . '.json',$jsonData);

$tvlocations = glob($pseudochannelTrim . "*", GLOB_ONLYDIR);
$boxes = '';
foreach ($tvlocations as $tvbox) {
	if ($tvbox == $pseudochannelMaster) {
		$boxname = $configClientName;
		$boxes .= "<li><a href='schedule.php?tv=$boxname' class='gn-icon gn-icon-videos'>TV: $boxname</a></li>";
	} else {
		$boxname = explode("_", $tvbox);
		$boxes .= "<li><a href='schedule.php?tv=$boxname[1]' class='gn-icon gn-icon-videos'>TV: $boxname[1]</a></li>";
	}
}
?>

<html lang="en" class="no-js" style="height:100%">
	<head>
            <script
            src="https://code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
            crossorigin="anonymous">
            </script>
		<style type="text/css">a {text-decoration: none}</style>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>FakeTV Guide and Control</title>
		<meta name="description" content="A page that works with Pseudo Channel and Plex to display now playing data and allow viewing and navigation of Pseudo Channel schedules" />
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<link rel="apple-touch-icon" sizes="180x180" href="assets/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="assets/favicon-16x16.png">
		<link rel="manifest" href="assets/site.webmanifest">
		<link rel="mask-icon" href="assets/safari-pinned-tab.svg" color="#5bbad5">
		<link rel="shortcut icon" href="assets/favicon.ico">
		<meta name="msapplication-TileColor" content="#2b5797">
		<meta name="msapplication-config" content="assets/browserconfig.xml">
		<meta name="theme-color" content="#ffffff">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="js/classie.js"></script>
		<script src="js/gnmenu.js"></script>
		<script src="js/modernizr.custom.js"></script>
		<script>
		var query = window.location.search.substring(1)
		if(query.length) {
			if(window.history != undefined && window.history.pushState != undefined) {
				window.history.pushState({}, document.title, window.location.pathname);
			}
		}
		</script>
		<script language="JavaScript">
		function httpGet(theUrl)
		{
			var xmlHttp = new XMLHttpRequest();
			xmlHttp.open( "GET", theUrl, false );
			xmlHttp.send( null );
			return xmlHttp.responseText;
		}
		</script>
	</head>
	<body>

		<div class="container main-container">
			</br><pre style='color:white' id='text'></br><?php echo $nowplayingtext ?></pre>
		</div><!-- /container -->
	</body>
</html>
