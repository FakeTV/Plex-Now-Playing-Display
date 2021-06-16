<!DOCTYPE html>
<?php
session_start();
include('./control.php');
include('./config.php');

?>
<html lang="en" class="no-js" style="height:100%">
	<head>
		<style type="text/css">a {text-decoration: none}</style>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0;">
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script>
			$(document).ready( function() {
					$("#topbar").load("topbar.php");
			});
		</script>
		<script
		src="https://code.jquery.com/jquery-2.2.4.min.js"
		integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
		crossorigin="anonymous">
		</script>
		<script>
			function loadOutput() {
				$(document).ready(function() {
					$("dbUpdate").click(function(){
						<?php databaseUpdate(); ?>
					});
					$.get("output.log",function(txt){
						var lines = txt.responseText.split("\n");
						var last = lines[-1];
						$("#output").html(last);
				});
			}
			setInterval(loadOutput, 1000);
			</script>			
		<script src="js/modernizr.custom.js"></script>
		<script
	    src="https://code.jquery.com/jquery-2.2.4.min.js"
	    integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
	    crossorigin="anonymous">
	    </script>
	</head>
	<body>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<div id="container">
		<div class="container" style="position:absolute;top:60px" scrolling="no"><h3 style="color:white" class="gn-icon gn-icon-earth">Pseudo Channel Web Interface</h3></div>
			</div></br></br></br></br></br></br>
			<div class="dripdrop-header" style="color:white;padding-left:10px">
			<p>Pseudo Channel Database Update</p></div>
			<div id="output" class="dripdrop-paragraph" style="color:white;padding-left:10px"><pre>Loading Output Text . . .</pre></div>
			<form><input type="submit" id="dbUpdate" name="dbUpdate" value="Update Database"</form></br>
			<div class="dripdrop" style="color:white;padding-left:10px"></br>
			<a class="dripdrop-header">Plex Server: </a><a><?php echo $plexServer; ?>:<?php echo $plexport; ?></a></br>
			<a class="dripdrop-header">Pseudo Channel: </a><a><?php echo $pseudochannelMaster; ?></a></div>
			<div id="topbar" name="topbar"></div>
		</div><!-- /container -->
		<script src="js/classie.js"></script>
		<script src="js/gnmenu.js"></script>
		<script>
			new gnMenu( document.getElementById( 'gn-menu' ) );
		</script>	
	</body>
</html>
