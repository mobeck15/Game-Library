<?php // @codeCoverageIgnoreStart
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require_once $GLOBALS['rootpath']."/page/calculations.class.php";
$page = new calculationsPage();
echo $page->outputHtml();
// @codeCoverageIgnoreEnd