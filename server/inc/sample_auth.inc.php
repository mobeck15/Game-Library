<?php
//DONE: add control function to prevent loading multiple times.
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ");
}
$GLOBALS[__FILE__]=1;

if($_SERVER['SERVER_NAME']=="localhost"){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "";
} else {
	$servername = "";
	$username = "isaacguerrero";
	$password = "";
	$dbname = "";
	$tableprefix = "";
}

//DONE: Move API codes to seperate file.
//$SteamAPIwebkey="71CBF878CA78EF8459DACF1E7F08C210";
//$SteamProfileID="76561198024968605";
include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/authapi.inc.php";
?>