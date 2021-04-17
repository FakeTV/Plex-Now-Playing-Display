<?php
function Channel() {
include('config.php');
if(empty($_GET["tv"]) || $_GET["tv"] == $configClientName) {
        $ps = "$pseudochannelMaster";
} else {
	$pseudochannel = substr($pseudochannel, 0, -1);
        $ps = "$pseudochannelTrim" . "_" . $_GET["tv"];
}
	$channel_number = $_GET["num"];
	ob_start();
	echo exec("ps aux | grep '[c]ontrol.py'", $o);
	if(count($o) <= 0){	
		echo exec("python -u " . "$ps" . "/controls.py -c " . "$channel_number > ./control.txt 2>&1 &", $output, $err);
	}
	ob_end_clean();
}
function stopAllChannels() {
include('config.php');
if(empty($_GET["tv"]) || $_GET["tv"] == $configClientName) {
        $ps = "$pseudochannelMaster";
} else {
	$pseudochannel = substr($pseudochannel, 0, -1);
        $ps = "$pseudochannelTrim" . "_" . $_GET["tv"];
}
	ob_start();
	echo exec("ps aux | grep '[c]ontrol.py'", $o);
	if(count($o) <= 0){	
		echo exec("python -u " . "$ps" . "/controls.py -s > /dev/null 2>&1 &");
	}
	ob_end_clean();
}
function channel_down() {
include('config.php');
if(empty($_GET["tv"]) || $_GET["tv"] == $configClientName) {
        $ps = "$pseudochannelMaster";
} else {
	$pseudochannel = substr($pseudochannel, 0, -1);
        $ps = "$pseudochannelTrim" . "_" . $_GET["tv"];
}
	ob_start();
	echo exec("ps aux | grep '[c]ontrol.py'", $o);
	if(count($o) <= 0){	
		echo exec("python -u " . "$ps" . "/controls.py -dn > /dev/null 2>&1 &", $output, $err);
	}
	ob_end_clean();
}
function channel_up() {
include('config.php');
if(empty($_GET["tv"]) || $_GET["tv"] == $configClientName) {
        $ps = "$pseudochannelMaster";
} else {
	$pseudochannel = substr($pseudochannel, 0, -1);
        $ps = "$pseudochannelTrim" . "_" . $_GET["tv"];
}
	ob_start();
	echo exec("ps aux | grep '[c]ontrol.py'", $o);
	if(count($o) <= 0){	
		echo exec("python -u " . "$ps" . "/controls.py -up > /dev/null 2>&1 &", $output, $err);
	}
	ob_end_clean();
}
function last() {
include('config.php');
if(empty($_GET["tv"]) || $_GET["tv"] == $configClientName) {
        $ps = "$pseudochannelMaster";
} else {
	$pseudochannel = substr($pseudochannel, 0, -1);
        $ps = "$pseudochannelTrim" . "_" . $_GET["tv"];
}
	ob_start();
	echo exec("ps aux | grep '[c]ontrol.py'", $o);
	if(count($o) <= 0){	
		echo exec("python -u " . "$ps" . "/controls.py -l > /dev/null 2>&1 &", $output, $err);
	}
	ob_end_clean();
}
function restart() {
include('config.php');
if(empty($_GET["tv"]) || $_GET["tv"] == $configClientName) {
        $ps = "$pseudochannelMaster";
} else {
	$pseudochannel = substr($pseudochannel, 0, -1);
        $ps = "$pseudochannelTrim" . "_" . $_GET["tv"];
}
	ob_start();
	echo exec("ps aux | grep '[c]ontrol.py'", $o);
	if(count($o) <= 0){	
		echo exec("python -u " . "$ps" . "/controls.py -r > /dev/null 2>&1 &", $output, $err);
	}
	ob_end_clean();
}
function purge_favicon_cache() {
	ob_start();
        echo exec("sudo rm -rf ./logos");
	ob_end_clean();
}
function databaseUpdate() {
include('config.php');
$ps = "$pseudochannelMaster";
	ob_start();
	echo exec("python -u " . "$ps" . "/controls.py -u >| output.log 2>&1 &", $output, $err);
	ob_end_clean();
}
function generateDaily() {
include('config.php');
$ps = "$pseudochannelMaster";
	ob_start();
	echo exec("python -u " . "$ps" . "/controls.py -g > output.log 2>&1 &", $output, $err);
	ob_end_clean();
}
if(isset($_GET['action'])){
	switch($_GET['action']) {
        case 'stop':
                stopAllChannels();
        break;
		case 'channel':
			Channel();
		break;
		case 'down':
			channel_down();
		break;
		case 'up':
			channel_up();
		break;
		case 'update':
			databaseUpdate();
		break;
		case 'purgefaviconcache':
			purge_favicon_cache();
		break;
	}
}

?>
