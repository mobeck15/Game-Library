<?php
if(($_SERVER['SERVER_NAME'] ?? "") == "yourservername") {
	$servername = "";
	$username = "";
	$password = "";
	$dbname = "";
	$tableprefix = "";
} else {
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "";
}

$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? "..";
include $GLOBALS['rootpath']."/inc/authapi.inc.php";
?>