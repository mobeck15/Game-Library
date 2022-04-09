<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require_once $GLOBALS['rootpath']."/inc/functions.inc.php";
require_once $GLOBALS['rootpath']."/inc/topx.class.php";

$title="Play Next";
echo Get_Header($title);

$topxobj = new topx(reIndexArray(getCalculations(),"Game_ID"));

if(isset($_GET["mode"]) && $_GET["mode"] == "Active"){
	$topxobj->setfilter("Playable,eq,0,Status,ne,Active");
}

$totalranks=$topxobj->getTotalRanks();

echo "<a href='?mode=Active'>Active</a> | <a href='?'>All</a>";
echo "<table><tr><td valign=top>";
echo $topxobj->makeDetailTable($totalranks);
echo "</td><td valign=top><details><summary><b>Details</b></summary>";
echo $topxobj->makeSourceCloud("main");
echo "</summary></td></tr></table>";

echo Get_Footer(); 


?>