<?php // @codeCoverageIgnoreStart
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";

require_once $GLOBALS['rootpath']."/page/viewallhistory.class.php";
$page = new viewallhistoryPage();
echo $page->outputHtml();
// @codeCoverageIgnoreEnd