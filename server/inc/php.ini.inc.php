<?php
// @codeCoverageIgnoreStart
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ");
}
$GLOBALS[__FILE__]=1;

date_default_timezone_set("America/Los_Angeles");
setlocale(LC_MONETARY, 'en_US.UTF-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//ini_set('memory_limit' , '512M')
ini_set('memory_limit' , '1G');
// @codeCoverageIgnoreEnd
