<!DOCTYPE html>
<?php
$ch_file="";
include('./control.php');
include('./config.php');
$tvlocations = glob($pseudochannelTrim . "*", GLOB_ONLYDIR);
$boxes = '';
?>
<html lang="en" class="no-js" style="height:100%">
	<head>
            <script
            src="https://code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
            crossorigin="anonymous">
			</script>
			<script>
			topbar = "topbar.php";
                        $(document).ready( function() {
                                $("#topbar").load(topbar);
				$.getJSON(topbar,function(data) {
					$.each(data, function(key,val) {
						$('#'+key).html(val);
					});
				});
                        });
                </script>
		<style type="text/css">a {text-decoration: none}</style>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>FakeTV Schedule Editor for Pseudo Channel</title>
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
	</head>
	<body>
		<?php
		$dircontents=array();
		$xtraDecade = '';
		$xtraGenre =  '';
		$xtraStudio =  '';
		$xtraCollection =  '';
		$xtraRating =  '';
		$xtraYear= '';
		$ch_number = $_GET['channelNumber'];
		$databasefile = $pseudochannelMaster . "/pseudo-channel_" . $ch_number . "/pseudo-channel.db";
		$entryID = $_GET['id'];
		$psDB = new SQLite3($databasefile);
		$ch_file = "ch" . $ch_number;
		$ch_row = "row" . $ch_number;
		$sqlData = array();
		if ($_GET['durationMax'] == "999") {
			$setDurationMax = "43200000";
		} else {
			$setDurationMax = $_GET['durationMax'];
		}
	if ($_GET['saveChanges'] == "save") {
		if ($_GET['librarytype'] == "Movies") {
			$setMediaID = "1";
			$libraryType = "Movies";
			if ($_GET['movieTitleEntry'] == 'random') {
				$setDuration = $_GET['durationMin'] . "," . $setDurationMax;
				$setSeasonEpisode = NULL;
				if ($_GET['year'] != '') {
					$setYear = $_GET['year'];
				} else {
					$setYear = NULL;
				}
				if ($_GET['genre'] != '') {
					$setGenres = $_GET['genre'];
				} else {
					$setGenres = NULL;
				}
				if ($_GET['actors'] != '') {
					$setActors = $_GET['actors'];
				} else {
					$setActors = NULL;
				}
				if ($_GET['studio'] != '') {
					$setStudio = $_GET['studio'];
				} else {
					$setStudio = NULL;
				}
				if ($_GET['collections'] != '') {
					$setCollections = $_GET['collections'];
				} else {
					$setCollections = NULL;
				}
				if ($_GET['movieRating'] != '') {
					if ($_GET['includeLower'] == '1') {
						$ratingDrift = '<';
					} elseif ($_GET['includeHigher'] == '1') {
						$ratingDrift = '>';
					} else {
						$ratingDrift = '=';
					}
					$setRating = $_GET['movieRating'] . ',' . $ratingDrift;
				} else {
					$setRating = NULL;
				}
				if ($_GET['kevinbacon'] == 'kevinbacon') {
					$titleEntry = 'kevinbacon';
				} else {
					$titleEntry = 'random';
				}
			} else {
				$titleEntry = $_GET['movieTitleEntry'];
				$setDuration = "1,43200000";
			}
		} elseif ($_GET['librarytype'] == "TV Shows") {
			$libraryType = "TV Shows";
			$titleEntry = $_GET['showTitleEntry'];
			if ($_GET['showTitleEntry'] == "random") {
				$setSeason = $_GET['season'];
				$setEpisode = $_GET['episode'];
				$setDuration = $_GET['durationMin'] . "," . $setDurationMax;
				if ($setSeason != "" && $setEpisode != "") {
						$setSeasonEpisode = $setSeason . "," . $setEpisode;
				} elseif ($setSeason == "" && $setEpisode != "") {
					$setSeasonEpisode = "*," . $setEpisode;
				} elseif ($setSeason != "" && $setEpisode == "") {
					$setSeasonEpisode = $setSeason . ",*";
				} else {
					$setSeasonEpisode = NULL;
				}
				
				if ($_GET['year'] != '') {
					$setYear = $_GET['year'];
				} else {
					$setYear = NULL;
				}
				if ($_GET['genre'] != '') {
					$setGenres = $_GET['genre'];
				} else {
					$setGenres = NULL;
				}
				if ($_GET['actors'] != '') {
					$setActors = $_GET['actors'];
				} else {
					$setActors = NULL;
				}
				if ($_GET['studio'] != '') {
					$setStudio = $_GET['studio'];
				} else {
					$setStudio = NULL;
				}
				if ($_GET['collections'] != '') {
					$setCollections = $_GET['collections'];
				} else {
					$setCollections = NULL;
				}
				if ($_GET['tvRating'] != '') {
					if ($_GET['includeLower'] == '1') {
						$ratingDrift = '<';
					} elseif ($_GET['includeHigher'] == '1') {
						$ratingDrift = '>';
					} else {
						$ratingDrift = '=';
					}
					$setRating = $_GET['tvRating'] . ',' . $ratingDrift;
				} else {
					$setRating = NULL;
				}
				if ($_GET['randomEpisode'] == 'randomEpisode') {
					$setMediaID = "999";
				} elseif ($_GET['randomEpisode'] != 'randomEpisode') {
					$setMediaID = "998";
				}
			} elseif ($_GET['showTitleEntry'] != "random") {
				$setDuration = "1,43200000";
				if ($_GET['randomEpisode'] == 'randomEpisode') {
					$setMediaID = "9999";
				} elseif ($_GET['randomEpisode'] != 'randomEpisode') {
					$setMediaID = "2";
				}
			}
			if ($_GET['rerun'] == 'rerun') {
				$rerun = 1;
			} else {
				$rerun = 0;
			}
		}

		$setStartTimeUnix = strtotime($_GET['setTime']);
		$setStartTime = strftime("%H:%M:%S",$setStartTimeUnix);

		if ($_GET['timeMode'] == "Strict Start Time") {
			$setStrictTime = "true";
			$setTimeShift = "0";
			$setMaxOverlap = "99";
		} elseif ($_GET['timeMode'] == "Variable Start Time") {
			$setStrictTime = "false";
			$setTimeShift = $_GET['timeShift'];
		} elseif ($_GET['timeMode'] == "Allow Preempting") {
			$setStrictTime = "secondary";
			$setTimeShift = "0";
			$setMaxOverlap = $_GET['maxOverlap'];
		} else {
			$setStrictTime = "secondary";
			$setTimeShift = "0";
			$setMaxOverlap = $_GET['maxOverlap'];
		}
		$saveData = "UPDATE schedule SET unix=:unixTime,mediaID=:mediaID,title=:titleEntry,duration=:setDuration,startTime=:setStartTime,endTime=0,dayOfWeek=:dayofweek,startTimeUnix=:setStartTimeUnix,section=:section,strictTime=:setStrictTime,timeShift=:timeShift,overlapMax=:maxOverlap,xtra=:xtra, seasonEpisode=:setSeasonEpisode,year=:setYear,genres=:setGenres,actors=:setActors,studio=:setStudio,collections=:setCollections,rating=:setRating,rerun=:rerun WHERE id LIKE :entryID";
		$statement = $psDB->prepare($saveData);
		$statement->bindParam(":unixTime", $_GET['unixTime']);
		$statement->bindParam(":mediaID", $setMediaID);
		$statement->bindParam(":titleEntry", $titleEntry);
		$statement->bindParam(":setDuration", $setDuration);
		$statement->bindParam(":setStartTime", $setStartTime);
		$statement->bindParam(":dayofweek", $_GET['dayofweek']);
		$statement->bindParam(":setStartTimeUnix", $setStartTimeUnix);
		$statement->bindParam(":section", $libraryType);
		$statement->bindParam(":setStrictTime", $setStrictTime);
		$statement->bindParam(":timeShift", $setTimeShift);
		$statement->bindParam(":maxOverlap", $setMaxOverlap);
		$statement->bindParam(":xtra", $_GET['xtraArgs']);
		$statement->bindParam(":setSeasonEpisode", $setSeasonEpisode);
		$statement->bindParam(":setYear", $setYear);
		$statement->bindParam(":setGenres", $setGenres);
		$statement->bindParam(":setActors", $setActors);
		$statement->bindParam(":setStudio", $setStudio);
		$statement->bindParam(":setCollections", $setCollections);
		$statement->bindParam(":setRating", $setRating);
		$statement->bindParam(":rerun", $rerun);
		$statement->bindParam(":entryID", $entryID);
		$results = $statement->execute();
		if($results==FALSE && $_GET['saveChanges'] == "save") {
			$echoSave = "<span style='color:yellow'>ERROR: $statement->lastErrorMsg()</span>";
		} elseif ($_GET['saveChanges'] == "save") {
			$echoSave = "<span style='color:yellow'>Changes Saved</span>";
		}
	}

		$result = $psDB->query("SELECT * FROM schedule WHERE id LIKE " . $entryID); //get data on selected entry
		$sqlData = $result->fetchArray();

	        $moviesData = array();
		$allMovies = array();
        	$moviesResult = $psDB->query("SELECT title FROM movies"); //get all movie titles
		while ($moviesData = $moviesResult->fetchArray(SQLITE3_ASSOC)) {
			array_push($allMovies, $moviesData['title']);
		}
		$movieDropDown = "";
		foreach ($allMovies as $oneMovie) {
			if ($sqlData['title'] == $oneMovie) {
				$movieDropDown .= "<option value='" . $oneMovie . "' selected>" . $oneMovie . "</option>";
			} else {
				$movieDropDown .= "<option value='" . $oneMovie . "'>" . $oneMovie . "</option>";
			}
		}

		if ($sqlData['title'] == "random") {
			$randomTitleSelected = "selected";
		} else {
			$randomTitleSelected = "";
		}

		if ($sqlData['title'] == "kevinbacon") {
			$kevinBaconSelected = "checked";
		} else {
			$kevinBaconSelected = "";
		}
		
		if ($sqlData['mediaID'] == 999 || $sqlData['mediaID'] == 9999) {
			$randomEpisodeSelected = "checked";
		} else {
			$randomEpisodeSelected = "";
		}

		if ($sqlData['rerun'] == "1") {
			$rerunSelected = "checked";
		} else {
			$rerunSelected = "";
		}
		
                $showData = array();
		$allShows = array();
                $showsResult = $psDB->query("SELECT title FROM shows ORDER BY id"); //get all show titles
		while ($showData = $showsResult->fetchArray(SQLITE3_ASSOC)) {
			array_push($allShows, $showData['title']);
		}

		$showDropDown = "";
		foreach ($allShows as $oneShow) {
			if ($sqlData['title'] == $oneShow) {
				$showDropDown .= "<option value='" . str_replace("'", "&#039;", $oneShow) . "' selected>" . $oneShow . "</option>";
			} else {
				$showDropDown .= "<option value='" . str_replace("'", "&#039;", $oneShow) . "'>" . $oneShow . "</option>";
			}
		}

                $movieData = array();
		$allMovies = array();
                $moviesResult = $psDB->query("SELECT title FROM movies ORDER BY id"); //get all movie titles
		while ($movieData = $moviesResult->fetchArray(SQLITE3_ASSOC)) {
			array_push($allMovies, $movieData['title']);
		}

		$movieDropDown = "";
		foreach ($allMovies as $oneMovie) {
			if ($sqlData['title'] == $oneMovie) {
				$movieDropDown .= "<option value='" . str_replace("'", "&#039;", $oneMovie) . "' selected>" . $oneMovie . "</option>";
			} else {
				$movieDropDown .= "<option value='" . str_replace("'", "&#039;", $oneMovie) . "'>" . $oneMovie . "</option>";
			}
		}

		$startTime = strtotime($sqlData['startTime']);
		if ($sqlData['section'] == "TV Shows" && $sqlData['title'] == "random") {
			$dataTitle = "Random TV Show";
		} elseif ($sqlData['section'] == "Movies" && $sqlData['title'] == "random") {
			$dataTitle = "Random Movie";
		} else {
			$dataTitle = $sqlData['title'];
		}
		if ($sqlData['duration'] == "0,43200000") {
			$dataDuration = "N/A";
			$dataDurationMin = "0";
			$dataDurationMax = "999";
		} else {
			$dataDurationExplode = explode(",", $sqlData['duration']);
			$dataDuration = $dataDurationExplode[0] . " - " . $dataDurationExplode[1] . " minutes";
			$dataDurationMin = $dataDurationExplode[0];
			$dataDurationMax = $dataDurationExplode[1];
		}
		if ($sqlData['strictTime'] == "true") {
			$timeMode = "Strict Start Time";
		} elseif ($sqlData['strictTime'] == "false") {
			$timeMode = "Variable Start Time";
		} elseif ($sqlData['strictTime'] == "secondary") {
			$timeMode = "Allow Preempting";
		} else {
			$timeMode = "INVALID ENTRY";
		}
		if ($sqlData['xtra'] != "") {
			$xtraArray = explode(";", $sqlData['xtra']);
			foreach ($xtraArray as $xtraD) {
				$xA = explode(":", $xtraD);
				if ($xA[0] == 'decade') {
					$xtraDecade = $xA[1];
				}
				if ($xA[0] == 'genre') {
					$xtraGenre = $xA[1];
				}
				if ($xA[0] == 'studio') {
					$xtraStudio = $xA[1];
				}
				if ($xA[0] == 'collection') {
					$xtraCollection = $xA[1];
				}
				if ($xA[0] == 'rating') {
					$xtraRating = $xA[1];
				}
			}
			$xtraData = str_replace(";","</br>",$sqlData['xtra']);
			$xtraData = str_replace(":",": ",$xtraData);
			$xtraData = ucwords($xtraData,">");
			$xtraData = ucwords($xtraData," ");
			$xtraData = ucwords($xtraData,"-");
		} else {
			$xtraData = "";
		}
		
		if ($sqlData['collections'] != "") {
			$xtraCollection = $sqlData['collections'];
		}

		if ($sqlData['studio'] != "") {
			$xtraStudio = $sqlData['studio'];
		}
		
		if ($sqlData['year'] != "") {
			if (substr($sqlData['year'], -1) == '*') { 
				$xtraDecade = substr_replace($sqlData['year'], '0', -1);
			} else {
				$xtraYear = $sqlData['year'];
			}
		}	

		if ($sqlData['seasonEpisode'] != "") {
			$seArray = explode(',',$sqlData['seasonEpisode']);
			$xtraSeason = $seArray[0];
			if ($seArray[1] != '*') {
				$xtraEpisode = $seArray[1];
			} else {
				$xtraEpisode = '';
			}
		} else {
			$xtraSeason = '';
			$xtraEpisode = '';			
		}
		if ($sqlData['rating'] != "") {
			$ratingArray = explode(',', $sqlData['rating']);
			$ratingCountry = $ratingArray[0];
			$xtraRating = $ratingArray[1];
		}

		if ($sqlData['genres'] != "") {
			$xtraGenre = $sqlData['genres'];
		}
		if ($sqlData['actors'] != "") {
			$xtraActors = $sqlData['actors'];
		}
		if ($sqlData['section'] == "TV Shows") {
			$selectShows = " checked";
			$selectMovies = "";
			$selectRandom = "";
		} elseif ($sqlData['section'] == "Movies") {
                        $selectShows = "";
                        $selectMovies = " checked";
                        $selectRandom = "";
                } elseif ($sqlData['section'] == "random") {
                        $selectShows = "";
                        $selectMovies = "";
                        $selectRandom = " checked";
                } else {
                        $selectShows = "";
                        $selectMovies = "";
                        $selectRandom = "";
		}
                $daysDropDown = "";
		$allDays = array('everyday','weekdays','weekends','mondays','tuesdays','wednesdays','thursdays','fridays','saturdays','sundays');
                foreach ($allDays as $theDay) {
                        if ($sqlData['dayOfWeek'] == $theDay) {
                                $daysDropDown .= "<option value='" . $theDay . "' selected>" . ucfirst($theDay) . "</option>";
                        } else {
                                $daysDropDown .= "<option value='" . $theDay . "'>" . ucfirst($theDay) . "</option>";
                        }
		}
		$timeModeDropDown = "";
		$allTimeModes = array("Strict Start Time","Variable Start Time","Allow Preempting");
		foreach ($allTimeModes as $theTimeMode) {
			if ($timeMode == $theTimeMode) {
				$timeModeDropDown .= "<option value='" . $theTimeMode . "' selected>" . $theTimeMode . "</option>";
			} else {
				$timeModeDropDown .= "<option value='" . $theTimeMode . "' >" . $theTimeMode . "</option>";
			}
		}
		?>
     		<div id="topbar" name="topbar"></div>
		<!-- show data from entry -->
		<div class="container" style="margin-top:80px;color:white;text-align:left;margin-left:.5em" name="entryinfo">
			<h2 style='font-size:1.8em;line-height:.5em'>Entry Details</h2>
			<span style='font-size:0.7em'>Last Modified: <?php echo strftime('%Y-%m-%d %H:%M:%S %Z',$sqlData['unix']); ?></span>
			<p style='text-align:left;font-size:.8em'>
			<span>Channel: <b><?php echo $ch_number; ?></b></br>ID: <b><?php echo $sqlData['id']; ?></b></span>
			<span></br>Library Type: <b><?php echo $sqlData['section']; ?></b></span>
			<span></br>Title: <b><?php echo $dataTitle; ?></b></span>
			<span style='line-height:50%'></br><b><?php echo ucfirst($sqlData['dayOfWeek']); ?></b> at <b><?php echo strftime("%H:%M", $startTime); ?></b></span>
			<span style='color:white'></br>Duration: <b><?php echo $dataDuration; ?></b></span>
			<span style='color:white'></br>Time Mode: <b><?php echo $timeMode; ?></b></span>
			<span style='color:white'></br>Time Shift: <b><?php echo $sqlData['timeShift']; ?> Minute Increments</b></span>
			<span style='color:white'></br>Maximum Preempting: <b><?php echo $sqlData['overlapMax']; ?> Minutes</b></span>
			<span style='color:white'></br>Extra Arguments:</br><b><?php echo $xtraData; ?></b></span>
			
			<div class="container" style="color:white;text-align:left;font-size:1em" name="editform"><form>  <!--  edit form to change data in entry -->
			<input type='hidden' value='<?php echo $_GET['channelNumber'] ?>' name='channelNumber' id='channelNumber'></input>
			<input type='hidden' value='<?php echo $_GET['id'] ?>' name='id' id='id'></input>
			<h2 style='font-size:1.8em;line-height:.5em'>Edit Entry</h2>

			<span>Media Type: </span>
			<input type='radio' id='Movies' name='librarytype' value='Movies' <?php echo $selectMovies; ?> class="MyHide">
			<label for='Movies'>Movie</label>
			<input type='radio' id='TVShows' name='librarytype' value='TV Shows' <?php echo $selectShows; ?> class="">
			<label for='Movies'>TV Show</label></br></br>
			
			<label for='dayofweek'>Day(s): </label>
			<select name='dayofweek' id='dayofweek'>
			<?php echo $daysDropDown; ?>
			</select></br>
			
			<label for='setTime'>Time: </label>
			<input type='time' value='<?php echo $sqlData['startTime'] ?>' name='setTime' id='setTime' required></br>

			<label for='timeMode'>Time Mode: </label>
			<select name='timeMode' id='timeMode'>
			<?php echo $timeModeDropDown; ?></select></br>
			
			<div id='timeShiftDiv'><label for='timeShift'>Time Shift: </label>
			<input type='number' value='<?php echo $sqlData['timeShift'] ?>' name='timeShift' id='timeShift' style='width:45px'></input></br></div>
			
			<div id='maxOverlapDiv'><label for='maxOverlap'>Max Preempting: </label>
			<input type='number' value='<?php echo $sqlData['overlapMax'] ?>' name='maxOverlap' id='maxOverlap' style='width:45px'></input></br></div>
			</br>
			
			<!--<label for='libraryType'>Library Type: </label>
			<select name='librarytype' id='librarytype'>
			<option value='Movies'<?php echo $selectMovies; ?> >Movie</option>
			<option value='TV Shows'<?php echo $selectShows; ?> >TV Show</option>
			<option value='random'<?php echo $selectRandom; ?> >Random Show Episode</option>
			</select></br> -->
			
			<select name='showTitleEntry' id='showTitleEntry'>
			<option value='random'<?php echo $randomTitleSelected; ?> >Random Title</option>
			<?php echo $showDropDown; ?></select>

			<select name='movieTitleEntry' id='movieTitleEntry'>
			<option value='random'<?php echo $randomTitleSelected; ?> >Random Title</option>
			<?php echo $movieDropDown; ?></select></br>

			<!--
			//random episode
			//do not advance
			//kevin bacon mode -->
			
			<span id='randomEpisodeDiv'><input type='checkbox' id='randomEpisode' name='randomEpisode' value='randomEpisode' <?php echo $randomEpisodeSelected; ?>>
			<label for='randomEpisode' id='randomEpisodeLabel'>Random Episode</label></span>
			<span id='rerunDiv'><input type='checkbox' id='rerun' name='rerun' value='rerun' <?php echo $rerunSelected; ?>>
			<label for='rerun' id='rerunLabel'>Rerun Episode</label></span>
			<input type='checkbox' id='kevinbacon' name='kevinbacon' value='kevinbacon'<?php echo $kevinBaconSelected; ?>>
			
			<label for='kevinbacon' id='kevinbaconLabel'>Kevin Bacon Mode</label></br></br>
			<div id='for_random'>
			<div id='seasonEpisode'>
			<span id='seasonText'>Season# </span>
			<input type='text' id='season' name='season' style='width:30px' value='<?php echo $xtraSeason; ?>'>
			<span id='episodeText'>Episode# </span>
			<input type='text' id='episode' name='episode' style='width:30px' value='<?php echo $xtraEpisode; ?>'>
			</br></div>

			<label for='durationMin' id='durationMinLabel'>Duration: Min</label>
			<input type='number' value='<?php echo $dataDurationMin; ?>' name='durationMin' id='durationMin' style='width:55px'></input>
			<label for='durationMax' id='durationMaxLabel'>Max</label>
			<input type='number' value='<?php echo $dataDurationMax; ?>' name='durationMax' id='durationMax' style='width:55px'></input></br>

			<label for='year' id='yearLabel'>Decade or Year: </label>
			<select id="year" name="year">
			<option value=""></option>
			<option value="">----DECADES----</option>
			<option value="202*" <?php if($xtraDecade == "2020") echo "selected=\"selected\""; ?>>2020s</option>
			<option value="201*" <?php if($xtraDecade == "2010") echo "selected=\"selected\""; ?>>2010s</option>
			<option value="200*" <?php if($xtraDecade == "2000") echo "selected=\"selected\""; ?> >2000s</option>
			<option value="199*" <?php if($xtraDecade == "1990") echo "selected=\"selected\""; ?>>1990s</option>
			<option value="198*" <?php if($xtraDecade == "1980") echo "selected=\"selected\""; ?>>1980s</option>
			<option value="197*" <?php if($xtraDecade == "1970") echo "selected=\"selected\""; ?>>1970s</option>
			<option value="196*" <?php if($xtraDecade == "1960") echo "selected=\"selected\""; ?>>1960s</option>
			<option value="195*" <?php if($xtraDecade == "1950") echo "selected=\"selected\""; ?>>1950s</option>
			<option value="194*" <?php if($xtraDecade == "1940") echo "selected=\"selected\""; ?>>1940s</option>
			<option value="193*" <?php if($xtraDecade == "1930") echo "selected=\"selected\""; ?>>1930s</option>
			<option value="192*" <?php if($xtraDecade == "1920") echo "selected=\"selected\""; ?>>1920s</option>
			<option value="">----YEARS----</option>
			<option value="2022" <?php if($xtraYear == "2022") echo "selected=\"selected\""; ?>>2022</option>
			<option value="2021" <?php if($xtraYear == "2021") echo "selected=\"selected\""; ?>>2021</option>
			<option value="2020" <?php if($xtraYear == "2020") echo "selected=\"selected\""; ?>>2020</option>
			<option value="2019" <?php if($xtraYear == "2019") echo "selected=\"selected\""; ?>>2019</option>
			<option value="2018" <?php if($xtraYear == "2018") echo "selected=\"selected\""; ?>>2018</option>
			<option value="2017" <?php if($xtraYear == "2017") echo "selected=\"selected\""; ?>>2017</option>
			<option value="2016" <?php if($xtraYear == "2016") echo "selected=\"selected\""; ?>>2016</option>
			<option value="2015" <?php if($xtraYear == "2015") echo "selected=\"selected\""; ?>>2015</option>
			<option value="2014" <?php if($xtraYear == "2014") echo "selected=\"selected\""; ?>>2014</option>
			<option value="2013" <?php if($xtraYear == "2013") echo "selected=\"selected\""; ?>>2013</option>
			<option value="2012" <?php if($xtraYear == "2012") echo "selected=\"selected\""; ?>>2012</option>
			<option value="2011" <?php if($xtraYear == "2011") echo "selected=\"selected\""; ?>>2011</option>
			<option value="2010" <?php if($xtraYear == "2010") echo "selected=\"selected\""; ?>>2010</option>
			<option value="2009" <?php if($xtraYear == "2009") echo "selected=\"selected\""; ?>>2009</option>
			<option value="2008" <?php if($xtraYear == "2008") echo "selected=\"selected\""; ?>>2008</option>
			<option value="2007" <?php if($xtraYear == "2007") echo "selected=\"selected\""; ?>>2007</option>
			<option value="2006" <?php if($xtraYear == "2006") echo "selected=\"selected\""; ?>>2006</option>
			<option value="2005" <?php if($xtraYear == "2005") echo "selected=\"selected\""; ?>>2005</option>
			<option value="2004" <?php if($xtraYear == "2004") echo "selected=\"selected\""; ?>>2004</option>
			<option value="2003" <?php if($xtraYear == "2003") echo "selected=\"selected\""; ?>>2003</option>
			<option value="2002" <?php if($xtraYear == "2002") echo "selected=\"selected\""; ?>>2002</option>
			<option value="2001" <?php if($xtraYear == "2001") echo "selected=\"selected\""; ?>>2001</option>
			<option value="2000" <?php if($xtraYear == "2000") echo "selected=\"selected\""; ?>>2000</option>
			<option value="1999" <?php if($xtraYear == "1999") echo "selected=\"selected\""; ?>>1999</option>
			<option value="1998" <?php if($xtraYear == "1998") echo "selected=\"selected\""; ?>>1998</option>
			<option value="1997" <?php if($xtraYear == "1997") echo "selected=\"selected\""; ?>>1997</option>
			<option value="1996" <?php if($xtraYear == "1996") echo "selected=\"selected\""; ?>>1996</option>
			<option value="1995" <?php if($xtraYear == "1995") echo "selected=\"selected\""; ?>>1995</option>
			<option value="1994" <?php if($xtraYear == "1994") echo "selected=\"selected\""; ?>>1994</option>
			<option value="1993" <?php if($xtraYear == "1993") echo "selected=\"selected\""; ?>>1993</option>
			<option value="1992" <?php if($xtraYear == "1992") echo "selected=\"selected\""; ?>>1992</option>
			<option value="1991" <?php if($xtraYear == "1991") echo "selected=\"selected\""; ?>>1991</option>
			<option value="1990" <?php if($xtraYear == "1990") echo "selected=\"selected\""; ?>>1990</option>
			<option value="1989" <?php if($xtraYear == "1989") echo "selected=\"selected\""; ?>>1989</option>
			<option value="1988" <?php if($xtraYear == "1988") echo "selected=\"selected\""; ?>>1988</option>
			<option value="1987" <?php if($xtraYear == "1987") echo "selected=\"selected\""; ?>>1987</option>
			<option value="1986" <?php if($xtraYear == "1986") echo "selected=\"selected\""; ?>>1986</option>
			<option value="1985" <?php if($xtraYear == "1985") echo "selected=\"selected\""; ?>>1985</option>
			<option value="1984" <?php if($xtraYear == "1984") echo "selected=\"selected\""; ?>>1984</option>
			<option value="1983" <?php if($xtraYear == "1983") echo "selected=\"selected\""; ?>>1983</option>
			<option value="1982" <?php if($xtraYear == "1982") echo "selected=\"selected\""; ?>>1982</option>
			<option value="1981" <?php if($xtraYear == "1981") echo "selected=\"selected\""; ?>>1981</option>
			<option value="1980" <?php if($xtraYear == "1980") echo "selected=\"selected\""; ?>>1980</option>
			<option value="1979" <?php if($xtraYear == "1979") echo "selected=\"selected\""; ?>>1979</option>
			<option value="1978" <?php if($xtraYear == "1978") echo "selected=\"selected\""; ?>>1978</option>
			<option value="1977" <?php if($xtraYear == "1977") echo "selected=\"selected\""; ?>>1977</option>
			<option value="1976" <?php if($xtraYear == "1976") echo "selected=\"selected\""; ?>>1976</option>
			<option value="1975" <?php if($xtraYear == "1975") echo "selected=\"selected\""; ?>>1975</option>
			<option value="1974" <?php if($xtraYear == "1974") echo "selected=\"selected\""; ?>>1974</option>
			<option value="1973" <?php if($xtraYear == "1973") echo "selected=\"selected\""; ?>>1973</option>
			<option value="1972" <?php if($xtraYear == "1972") echo "selected=\"selected\""; ?>>1972</option>
			<option value="1971" <?php if($xtraYear == "1971") echo "selected=\"selected\""; ?>>1971</option>
			<option value="1970" <?php if($xtraYear == "1970") echo "selected=\"selected\""; ?>>1970</option>
			<option value="1969" <?php if($xtraYear == "1969") echo "selected=\"selected\""; ?>>1969</option>
			<option value="1968" <?php if($xtraYear == "1968") echo "selected=\"selected\""; ?>>1968</option>
			<option value="1967" <?php if($xtraYear == "1967") echo "selected=\"selected\""; ?>>1967</option>
			<option value="1966" <?php if($xtraYear == "1966") echo "selected=\"selected\""; ?>>1966</option>
			<option value="1965" <?php if($xtraYear == "1965") echo "selected=\"selected\""; ?>>1965</option>
			<option value="1964" <?php if($xtraYear == "1964") echo "selected=\"selected\""; ?>>1964</option>
			<option value="1963" <?php if($xtraYear == "1963") echo "selected=\"selected\""; ?>>1963</option>
			<option value="1962" <?php if($xtraYear == "1962") echo "selected=\"selected\""; ?>>1962</option>
			<option value="1961" <?php if($xtraYear == "1961") echo "selected=\"selected\""; ?>>1961</option>
			<option value="1960" <?php if($xtraYear == "1960") echo "selected=\"selected\""; ?>>1960</option>
			<option value="1959" <?php if($xtraYear == "1959") echo "selected=\"selected\""; ?>>1959</option>
			<option value="1958" <?php if($xtraYear == "1958") echo "selected=\"selected\""; ?>>1958</option>
			<option value="1957" <?php if($xtraYear == "1957") echo "selected=\"selected\""; ?>>1957</option>
			<option value="1956" <?php if($xtraYear == "1956") echo "selected=\"selected\""; ?>>1956</option>
			<option value="1955" <?php if($xtraYear == "1955") echo "selected=\"selected\""; ?>>1955</option>
			<option value="1954" <?php if($xtraYear == "1954") echo "selected=\"selected\""; ?>>1954</option>
			<option value="1953" <?php if($xtraYear == "1953") echo "selected=\"selected\""; ?>>1953</option>
			<option value="1952" <?php if($xtraYear == "1952") echo "selected=\"selected\""; ?>>1952</option>
			<option value="1951" <?php if($xtraYear == "1951") echo "selected=\"selected\""; ?>>1951</option>
			<option value="1950" <?php if($xtraYear == "1950") echo "selected=\"selected\""; ?>>1950</option>
			<option value="1949" <?php if($xtraYear == "1949") echo "selected=\"selected\""; ?>>1949</option>
			<option value="1948" <?php if($xtraYear == "1948") echo "selected=\"selected\""; ?>>1948</option>
			<option value="1947" <?php if($xtraYear == "1947") echo "selected=\"selected\""; ?>>1947</option>
			<option value="1946" <?php if($xtraYear == "1946") echo "selected=\"selected\""; ?>>1946</option>
			<option value="1945" <?php if($xtraYear == "1945") echo "selected=\"selected\""; ?>>1945</option>
			<option value="1944" <?php if($xtraYear == "1944") echo "selected=\"selected\""; ?>>1944</option>
			<option value="1943" <?php if($xtraYear == "1943") echo "selected=\"selected\""; ?>>1943</option>
			<option value="1942" <?php if($xtraYear == "1942") echo "selected=\"selected\""; ?>>1942</option>
			<option value="1941" <?php if($xtraYear == "1941") echo "selected=\"selected\""; ?>>1941</option>
			<option value="1940" <?php if($xtraYear == "1940") echo "selected=\"selected\""; ?>>1940</option>
			<option value="1939" <?php if($xtraYear == "1939") echo "selected=\"selected\""; ?>>1939</option>
			<option value="1938" <?php if($xtraYear == "1938") echo "selected=\"selected\""; ?>>1938</option>
			<option value="1937" <?php if($xtraYear == "1937") echo "selected=\"selected\""; ?>>1937</option>
			<option value="1936" <?php if($xtraYear == "1936") echo "selected=\"selected\""; ?>>1936</option>
			<option value="1935" <?php if($xtraYear == "1935") echo "selected=\"selected\""; ?>>1935</option>
			<option value="1934" <?php if($xtraYear == "1934") echo "selected=\"selected\""; ?>>1934</option>
			<option value="1933" <?php if($xtraYear == "1933") echo "selected=\"selected\""; ?>>1933</option>
			<option value="1932" <?php if($xtraYear == "1932") echo "selected=\"selected\""; ?>>1932</option>
			<option value="1931" <?php if($xtraYear == "1931") echo "selected=\"selected\""; ?>>1931</option>
			<option value="1930" <?php if($xtraYear == "1930") echo "selected=\"selected\""; ?>>1930</option>
			<option value="1929" <?php if($xtraYear == "1929") echo "selected=\"selected\""; ?>>1929</option>
			<option value="1928" <?php if($xtraYear == "1928") echo "selected=\"selected\""; ?>>1928</option>
			<option value="1927" <?php if($xtraYear == "1927") echo "selected=\"selected\""; ?>>1927</option>
			<option value="1926" <?php if($xtraYear == "1926") echo "selected=\"selected\""; ?>>1926</option>
			<option value="1925" <?php if($xtraYear == "1925") echo "selected=\"selected\""; ?>>1925</option>
			<option value="1924" <?php if($xtraYear == "1924") echo "selected=\"selected\""; ?>>1924</option>
			<option value="1923" <?php if($xtraYear == "1923") echo "selected=\"selected\""; ?>>1923</option>
			<option value="1922" <?php if($xtraYear == "1922") echo "selected=\"selected\""; ?>>1922</option>
			<option value="1921" <?php if($xtraYear == "1921") echo "selected=\"selected\""; ?>>1921</option>
			<option value="1920" <?php if($xtraYear == "1920") echo "selected=\"selected\""; ?>>1920</option>
			</select></br>

			<label for='genre' id='genreLabel'>Genres: </label>
			<input type='text' id='genre' name='genre' style='width:200px' value='<?php echo $xtraGenre; ?>'>
			</br></input>
			<label for='actors' id='actorsLabel'>Actors: </label>
			<input type='text' id='actors' name='actors' style='width:204px' value='<?php echo $xtraActors; ?>'>
			</br></input>
			<label for='studio' id='studioLabel'>Studio: </label>
			<input type='text' id='studio' name='studio' style='width:204px' value='<?php echo $xtraStudio; ?>'>
			</br></input>
			<label for='collections' id='collectionsLabel'>Collections: </label>
			<input type='text' id='collections' name='collections' style='width:172px' value='<?php echo $xtraCollection; ?>'></br></input>
			
			<span id='ratingText'>Rating: </span>
			<select id='movieRating' name='movieRating'>
			<option value=""></option>
			<option value="">----USA----</option>
			<option value="US,G" <?php if($ratingCountry == "US" && $xtraRating == "G") echo "selected=\"selected\""; ?>>G</option>
			<option value="US,PG" <?php if($ratingCountry == "US" && $xtraRating == "PG") echo "selected=\"selected\""; ?>>PG</option>
			<option value="US,PG-13" <?php if($ratingCountry == "US" && $xtraRating == "PG-13") echo "selected=\"selected\""; ?>>PG-13</option>
			<option value="US,R" <?php if($ratingCountry == "US" && $xtraRating == "R") echo "selected=\"selected\""; ?>>R</option>
			<option value="US,NC-17" <?php if($ratingCountry == "US" && $xtraRating == "NC-17") echo "selected=\"selected\""; ?>>NC-17</option>
			<option value="US,NR" <?php if($ratingCountry == "US" && $xtraRating == "NR") echo "selected=\"selected\""; ?>>Not Rated</option>
			<option value="">----AUS----</option>
			<option value="AUS,AUS-G" <?php if($ratingCountry == "AUS" && $xtraRating == "G") echo "selected=\"selected\""; ?>>G</option>
			<option value="AUS,AUS-PG" <?php if($ratingCountry == "AUS" && $xtraRating == "PG") echo "selected=\"selected\""; ?>>PG</option>
			<option value="AUS,AUS-M" <?php if($ratingCountry == "AUS" && $xtraRating == "M") echo "selected=\"selected\""; ?>>M</option>
			<option value="AUS,MA15+" <?php if($ratingCountry == "AUS" && $xtraRating == "MA15+") echo "selected=\"selected\""; ?>>MA15+</option>
			<option value="AUS,R18+" <?php if($ratingCountry == "AUS" && $xtraRating == "R18+") echo "selected=\"selected\""; ?>>R18+</option>
			<option value="AUS,X18+" <?php if($ratingCountry == "AUS" && $xtraRating == "X18+") echo "selected=\"selected\""; ?>>X18+</option>
			<option value="">----CANADA----</option>
			<option value="CA,G" <?php if($ratingCountry == "CA" && $xtraRating == "G") echo "selected=\"selected\""; ?>>G</option>
			<option value="CA,PG" <?php if($ratingCountry == "CA" && $xtraRating == "PG") echo "selected=\"selected\""; ?>>PG</option>
			<option value="CA,14A" <?php if($ratingCountry == "CA" && $xtraRating == "14A") echo "selected=\"selected\""; ?>>14A</option>
			<option value="CA,18A" <?php if($ratingCountry == "CA" && $xtraRating == "18A") echo "selected=\"selected\""; ?>>18A</option>
			<option value="CA,R" <?php if($ratingCountry == "CA" && $xtraRating == "R") echo "selected=\"selected\""; ?>>R</option>
			<option value="CA,A" <?php if($ratingCountry == "CA" && $xtraRating == "A") echo "selected=\"selected\""; ?>>A</option>
			<option value="">----UK----</option>
			<option value="UK,U" <?php if($ratingCountry == "UK" && $xtraRating == "U") echo "selected=\"selected\""; ?>>U</option>
			<option value="UK,12A" <?php if($ratingCountry == "UK" && $xtraRating == "12A") echo "selected=\"selected\""; ?>>12A</option>
			<option value="UK,15" <?php if($ratingCountry == "UK" && $xtraRating == "15") echo "selected=\"selected\""; ?>>15</option>
			<option value="UK,18" <?php if($ratingCountry == "UK" && $xtraRating == "18") echo "selected=\"selected\""; ?>>18</option>
			<option value="UK,R18" <?php if($ratingCountry == "UK" && $xtraRating == "R18") echo "selected=\"selected\""; ?>>R18</option>
			</select>
			<select id='tvRating' name='tvRating'>
			<option value=""></option>
			<option value="">----USA----</option>
			<option value="US,TV-Y" <?php if($xtraRating == "TV-Y") echo "selected=\"selected\""; ?>>TV-Y</option>
			<option value="US,TV-Y7" <?php if($xtraRating == "TV-Y7") echo "selected=\"selected\""; ?>>TV-Y7</option>
			<option value="US,TV-G" <?php if($xtraRating == "TV-G") echo "selected=\"selected\""; ?>>TV-G</option>
			<option value="US,TV-PG" <?php if($xtraRating == "TV-PG") echo "selected=\"selected\""; ?>>TV-PG</option>
			<option value="US,TV-14" <?php if($xtraRating == "TV-14") echo "selected=\"selected\""; ?>>TV-14</option>
			<option value="US,TV-MA" <?php if($xtraRating == "TV-MA") echo "selected=\"selected\""; ?>>TV-MA</option>
			<option value="US,NR" <?php if($xtraRating == "NR") echo "selected=\"selected\""; ?>>Not Rated</option>
			<option value="">----AUS----</option>
			<option value="AUS,C">C</option>
			<option value="AUS,P" <?php if($xtraRating == "P") echo "selected=\"selected\""; ?>>P</option>
			<option value="AUS,G" <?php if($xtraRating == "G") echo "selected=\"selected\""; ?>>G</option>
			<option value="AUS,PG">PG</option>
			<option value="AUS,M" <?php if($xtraRating == "M") echo "selected=\"selected\""; ?>>M</option>
			<option value="AUS,MA15+" <?php if($xtraRating == "MA15+") echo "selected=\"selected\""; ?>>MA15+</option>
			<option value="AUS,AV15+" <?php if($xtraRating == "AV15+") echo "selected=\"selected\""; ?>>AV15+</option>
			<option value="AUS,R18+" <?php if($xtraRating == "R18+") echo "selected=\"selected\""; ?>>R18+</option>
			<option value="AUS,E" <?php if($xtraRating == "E") echo "selected=\"selected\""; ?>>E</option>
			<option value="">----CANADA----</option>
			<option value="CA,C" <?php if($xtraRating == "C") echo "selected=\"selected\""; ?>>C</option>
			<option value="CA,C8" <?php if($xtraRating == "C8") echo "selected=\"selected\""; ?>>C8</option>
			<option value="CA,G" <?php if($xtraRating == "G") echo "selected=\"selected\""; ?>>G</option>
			<option value="CA,PG" <?php if($xtraRating == "PG") echo "selected=\"selected\""; ?>>PG</option>
			<option value="CA,14+" <?php if($xtraRating == "14+") echo "selected=\"selected\""; ?>>14+</option>
			<option value="CA,18+" <?php if($xtraRating == "18+") echo "selected=\"selected\""; ?>>18+</option>
			<option value="CA,Exempt" <?php if($xtraRating == "Exempt") echo "selected=\"selected\""; ?>>Exempt</option>
			<option value="">----UK----</option>
			<option value="UK,U" <?php if($xtraRating == "U") echo "selected=\"selected\""; ?>>U</option>
			<option value="UK,12A" <?php if($xtraRating == "12A") echo "selected=\"selected\""; ?>>12A</option>
			<option value="UK,15" <?php if($xtraRating == "15") echo "selected=\"selected\""; ?>>15</option>
			<option value="UK,18" <?php if($xtraRating == "18") echo "selected=\"selected\""; ?>>18</option>
			<option value="UK,R18" <?php if($xtraRating == "R18") echo "selected=\"selected\""; ?>>R18</option>
			</select></br>
			
			<input type='checkbox' id='includeLower' name='includeLower' value='1' <?php if($ratingArray[2] == "<") echo " checked"; ?>>
			<label for='includeLower' id='includeLowerLabel'>Include all lower ratings </label>
			<input type='checkbox' id='includeHigher' name='includeHigher' value='1' <?php if($ratingArray[2] == ">") echo " checked"; ?>>
			<label for='includeLower' id='includeHigherLabel'>Include all higher ratings</label></br>
			</br>
			</div>
			<!--<label for='xtraArgs'>Legacy Extra Arguments:</br><span style='font-size:0.8em'>(separate each category and value with a colon ':' and multiple entries with a semicolon ';')</span></br></label>
			<textarea name='xtraArgs' id='xtraArgs' style='width:500px' value='<?php echo $sqlData['xtra']; ?>'><?php echo $sqlData['xtra']; ?></textarea></br>
			<span style='font-size:.9em'>Example: genre:action;decade:1980;collection:holiday</span></br>-->
			
			<input type='hidden' value='<?php echo time(); ?>' name='unixTime' id='unixTime'></input>
			<input type='hidden' value='save' name='saveChanges' id='saveChanges'></input>
			<input type='submit' value='Save Changes'></input></form></p>
			<?php echo $echoSave; ?></br>
			<a style='color:white;text-align:left;font-size:0.7em' href="db-schedule.php?channel=<?php echo $_GET['channelNumber'];?>&dayOfWeek=all">&#8592; Return to Channel Schedule Page</a></br>
		</div>
		<script>
		  $(document).ready(function() {
		  var timeModeJS = "<?php echo $sqlData['strictTime'] ?>";
		  if (Movies.checked) {
				if ($('#movieTitleEntry').val() == 'random' ) {
					for_random();
					random_movie();
				} else {
					for_not_random();
					not_random_movie();
					}
		  }
		  if (TVShows.checked) {
				if ($('#showTitleEntry').val() == 'random') {
					random_tv();
					for_random();
				} else {
					not_random_tv();
					for_not_random();
					}
				if (rerun.checked) {
					$('#randomEpisodeDiv').addClass('myHide');
				} else {
					$('#randomEpisodeDiv').removeClass('myHide');
				}
				if (randomEpisode.checked) {
					$('#rerunDiv').addClass('myHide');
				} else {
					$('#rerunDiv').removeClass('myHide');
				}
		  }
		  if (timeModeJS == 'true') {
			  $('#timeShiftDiv').addClass('myHide');
			  $('#maxOverlapDiv').addClass('myHide');
		  } else if (timeModeJS == 'false') {
			  $('#timeShiftDiv').removeClass('myHide');
			  $('#maxOverlapDiv').addClass('myHide');
		  } else if (timeModeJS == 'secondary') {
			  $('#timeShiftDiv').addClass('myHide');
			  $('#maxOverlapDiv').removeClass('myHide');
		  }
			$('#timeMode').change(function() {
			  if ($('#timeMode').val() == 'Strict Start Time') {
				  $('#timeShiftDiv').addClass('myHide');
				  $('#maxOverlapDiv').addClass('myHide');
			  } else if ($('#timeMode').val() == 'Variable Start Time') {
				  $('#timeShiftDiv').removeClass('myHide');
				  $('#maxOverlapDiv').addClass('myHide');
			  } else if ($('#timeMode').val() == 'Allow Preempting') {
				  $('#timeShiftDiv').addClass('myHide');
				  $('#maxOverlapDiv').removeClass('myHide');
			  }
			});

			$('#randomEpisode').change(function() {
			  if (randomEpisode.checked) {
				  $('rerunDiv').addClass('myHide');
			  } else {
				  $('rerunDiv').removeClass('myHide');
			  }
			});

			$("input[type=radio]").change(function() {
			  $("select").removeClass('myVisible myHide');
			  if (Movies.checked) {
				if ($('#movieTitleEntry').val() == 'random') {
					for_random();
					random_movie();
				} else {
					for_not_random();
					not_random_movie();
					}
				}
			  if (TVShows.checked) {
				if ($('#showTitleEntry').val() == 'random') {
					random_tv();
					for_random();
				} else {
					not_random_tv();
					for_not_random();
					}
				if (rerun.checked) {
					$('#randomEpisodeDiv').addClass('myHide');
				} else {
					$('#randomEpisodeDiv').removeClass('myHide');
				}
				if (randomEpisode.checked) {
					$('#rerunDiv').addClass('myHide');
				} else {
					$('#rerunDiv').removeClass('myHide');
				}
			  }
			});
			
			$('#rerun').change(function() {
				if (rerun.checked) {
					$('#randomEpisodeDiv').addClass('myHide');
				} else {
					$('#randomEpisodeDiv').removeClass('myHide');
				}
			});

			$('#randomEpisode').change(function() {
				if (randomEpisode.checked) {
					$('#rerunDiv').addClass('myHide');
				} else {
					$('#rerunDiv').removeClass('myHide');
				}
			});
			
			$('#movieTitleEntry').change(function() {
				if ($('#movieTitleEntry').val() == 'random') {
					for_random();
					random_movie();
				} else {
					for_not_random();
					not_random_movie();
					}
			});
			$('#showTitleEntry').change(function() {
				if ($('#showTitleEntry').val() == 'random') {
					random_tv();
					for_random();
					$('#randomEpisode').prop('checked', true);
				} else {
					not_random_tv();
					for_not_random();
					$('#randomEpisode').prop('checked', false);
					}
			});
			
			$('#includeLower').change(function() {
				if (includeLower.checked) {
					$('#includeHigher').prop('checked', false);
				}
			});

			$('#includeHigher').change(function() {
				if (includeHigher.checked) {
					$('#includeLower').prop('checked', false);
				}
			});
			
		  });
		  
		  function for_random() {
			  $('#for_random').removeClass('myHide');
		  }
		  
		  function for_not_random() {
			  $('#for_random').addClass('myHide');
		  }

		  function not_random_movie() {
			  $('#movieTitleEntry').removeClass('myHide');
			  $('#showTitleEntry').addClass('myHide');
			  $('#randomEpisode').addClass('myHide');
			  $('#randomEpisodeLabel').addClass('myHide');
			  $('#rerun').addClass('myHide');
			  $('#rerunLabel').addClass('myHide');
				$('#kevinbacon').addClass('myHide');
				$('#kevinbaconLabel').addClass('myHide');
		  }
		  
		  function random_movie() {
			  $('#movieTitleEntry').removeClass('myHide');
			  $('#showTitleEntry').addClass('myHide');
			  $('#randomEpisode').addClass('myHide');
			  $('#randomEpisodeLabel').addClass('myHide');
			  $('#rerun').addClass('myHide');
			  $('#rerunLabel').addClass('myHide');
				$('#kevinbacon').removeClass('myHide');
				$('#kevinbaconLabel').removeClass('myHide');
				$('#movieRating').removeClass('myHide');
				$('#tvRating').addClass('myHide');
				$('#seasonEpisode').addClass('myHide');
		  }
		  
		  function not_random_tv() {
			  $('#movieTitleEntry').addClass('myHide');
			  $('#showTitleEntry').removeClass('myHide');
			  $('#randomEpisode').removeClass('myHide');
			  $('#randomEpisodeLabel').removeClass('myHide');
			  $('#rerun').removeClass('myHide');
			  $('#rerunLabel').removeClass('myHide');
				$('#kevinbacon').addClass('myHide');
				$('#kevinbaconLabel').addClass('myHide');
		  }
		  
		  function random_tv() {
			  $('#movieTitleEntry').addClass('myHide');
			  $('#showTitleEntry').removeClass('myHide');
			  $('#randomEpisode').removeClass('myHide');
			  $('#randomEpisodeLabel').removeClass('myHide');
			  $('#rerun').removeClass('myHide');
			  $('#rerunLabel').removeClass('myHide');
				$('#kevinbacon').addClass('myHide');
				$('#kevinbaconLabel').addClass('myHide');
				$('#movieRating').addClass('myHide');
				$('#tvRating').removeClass('myHide');
				$('#seasonEpisode').removeClass('myHide');
		  }

		</script>
	</body>
</html>
