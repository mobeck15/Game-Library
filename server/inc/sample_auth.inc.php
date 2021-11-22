<?php
if($_SERVER['SERVER_NAME'] == "yourservername") {
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


include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/authapi.inc.php";
?>