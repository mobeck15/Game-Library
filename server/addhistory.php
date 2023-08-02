<?php // @codeCoverageIgnoreStart
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";

require_once $GLOBALS['rootpath']."/page/addhistory.class.php";
$page = new addhistoryPage();
echo $page->outputHtml();
// @codeCoverageIgnoreEnd