<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";

require_once $GLOBALS['rootpath']."/page/statistics.class.php";
$page = new statisticsPage();
echo $page->outputHtml();