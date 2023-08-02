<?php // @codeCoverageIgnoreStart
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";

require_once $GLOBALS['rootpath']."/page/waste.class.php";
$page = new wastePage();
echo $page->outputHtml();
// @codeCoverageIgnoreEnd