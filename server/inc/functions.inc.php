<?php
/*
 * Use this variable to enable or disable debug textdomain
 * All debug text should be wrapped in IFs like below
 * if($Debug_Enabled) {}
 */
$GLOBALS['Debug_Enabled']=false;
$GLOBALS['Debug_Enabled']=true;

date_default_timezone_set("America/Los_Angeles");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//ini_set('memory_limit' , '512M')
//ini_set('memory_limit' , '-1')

if(!isset($GLOBALS['rootpath'])) {$GLOBALS['rootpath']=".";}

include_once $GLOBALS['rootpath']."/inc/template.inc.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getsettings.inc.php";
include_once $GLOBALS['rootpath']."/inc/getGames.inc.php";
include_once $GLOBALS['rootpath']."/inc/getPurchases.inc.php";
include_once $GLOBALS['rootpath']."/inc/getActivityCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getHistoryCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/scraper.inc.php";
include_once $GLOBALS['rootpath']."/inc/getTopList.inc.php";

//DONE: add control function to prevent loading multiple times.
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ");
}
$GLOBALS[__FILE__]=1;

?>
