<!DOCTYPE html>
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
<?php
session_start();
include('./control.php');
include('./config.php');
	if ($_GET['update'] == "yes") {
		databaseUpdate();
	}
	else if ($_GET['dailyschedule'] == "yes") {
		generateDaily();
	}
?>
		<script>	
			function HtmlEncode(s)
			{
			  var el = document.createElement("div");
			  el.innerText = el.textContent = s;
			  s = el.innerHTML;
			  return s;
			}
			
			function getQueryVariable(variable)
			{
				   var query = window.location.search.substring(1);
				   var vars = query.split("&");
				   for (var i=0;i<vars.length;i++) {
						   var pair = vars[i].split("=");
						   if(pair[0] == variable){return pair[1];}
				   }
				   return(false);
			}			
		
			function loadOutput() {
				$(document).ready(function() {
					$.get("output.log",function(txt){
						txt = txt.replace(/\x1b\[2K/g,"\n");
						lines = txt.split(/\r?\n/);
						last = lines[lines.length - 1];
						if (last == '') {
							last = lines[lines.length - 2];
						}
						$("#output").html("     <pre>"+last+"</pre>");
						if (last == "NOTICE: Global update COMPLETE") {
							var url = window.location.href;
							url = url.split('?')[0]+"?update=complete";
							window.location.href = url;
						}
						if (last == "ALERT: ALL DAILY SCHEDULE GENERATION COMPLETE") {
							var url = window.location.href;
							url = url.split('?')[0]+"?dailyschedule=complete";
							window.location.href = url;
						}
					});
			});
			}
			showOutput = getQueryVariable("output");
			if (showOutput == "yes") {
				setInterval(loadOutput, 2000);
			}
		</script>			
		<script src="js/modernizr.custom.js"></script>
	</head>
	<body>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<div id="topbar" name="topbar"></div>
		<div id="container">
		<div class="container" style="position:absolute;top:60px" scrolling="no"><h3 style="color:white" class="gn-icon gn-icon-earth">Pseudo Channel Web Interface</h3></div>
			</div></br></br></br></br></br></br>
			<div class="dripdrop-header" style="color:white;padding-left:10px">
			<p>Pseudo Channel Database Update</p></div>
			<div id="output" class="dripdrop-paragraph" style="color:white;padding-left:10px">
			<?php
			if ($_GET['output'] != "yes" && $_GET['update'] != "complete" && $_GET['dailyschedule'] != "complete") : ?>
			<form><input type='hidden' value='yes' name='output' id='output'></input>
			<input type='hidden' value='yes' name='update' id='update'></input>
			<input type='submit' value='Update Database'></input></form>
			<form><input type='hidden' value='yes' name='output' id='output'></input>
			<input type='hidden' value='yes' name='dailyschedule' id='dailyschedule'></input>
			<input type='submit' value='Generate Daily Schedule'></input></form>
			<?php endif ?>
			<?php
			if ($_GET['update'] == "complete") : ?>
			<a href='db-update.php' style='color:yellow'>Database Update Complete</a>
			<form><input type='hidden' value='yes' name='output' id='output'></input>
			<input type='hidden' value='yes' name='dailyschedule' id='dailyschedule'></input>
			<input type='submit' value='Generate Daily Schedule'></input></form>
			<?php endif ?>			
			<?php
			if ($_GET['dailyschedule'] == "complete") : ?>
			<form><input type='hidden' value='yes' name='output' id='output'></input>
			<input type='hidden' value='yes' name='update' id='update'></input>
			<input type='submit' value='Update Database'></input></form>			
			<a href='db-update.php' style='color:yellow'>Daily Schedule Generation Complete</a>
			<?php endif ?>		
			<?php
			if ($_GET['output'] == "yes") : ?>
			</br></br>
			<?php endif ?>
			</div>
			<div class='dripdrop-paragraph' style='color:white;padding-left:10px'>
			<?php
			if ($_GET['output'] == "yes") : ?>
			<form><input type='hidden' value='no' name='output' id='output'></input>
			<input type='submit' value='Hide Output'></input></form></div>
			<?php endif ?>
			<?php
			if ($_GET['output'] != "yes") : ?>
			<form><input type='hidden' value='yes' name='output' id='output'></input>
			<input type='submit' value='Show Output'></input></form>
			<?php endif ?>
			</div></br>
			<div class="dripdrop" style="color:white;padding-left:10px">
			<a class="dripdrop-header">Plex Server: </a><a><?php echo $plexServer; ?>:<?php echo $plexport; ?></a></br>
			<a class="dripdrop-header">Pseudo Channel: </a><a><?php echo $pseudochannelMaster; ?></a></div></br></br>
			
		</div><!-- /container -->
		<script src="js/classie.js"></script>
		<script src="js/gnmenu.js"></script>
		<script>
			new gnMenu( document.getElementById( 'gn-menu' ) );
		</script>	
	</body>
</html>
