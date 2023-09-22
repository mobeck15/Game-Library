<?php // @codeCoverageIgnoreStart
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";

require_once $GLOBALS['rootpath']."/page/totals.class.php";
$page = new totalsPage();
echo $page->outputHtml();
// @codeCoverageIgnoreEnd