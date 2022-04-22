<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";

require_once $GLOBALS['rootpath']."/page/toplevel.class.php";
$page = new toplevelPage();
echo $page->outputHtml();