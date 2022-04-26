<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";

require_once $GLOBALS['rootpath']."/page/historicchartdata.class.php";
$page = new historicchartdataPage();
echo $page->outputHtml();