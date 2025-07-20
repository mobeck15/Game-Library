<?php // @codeCoverageIgnoreStart
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";

require_once $GLOBALS['rootpath']."/page/ratings.class.php";
$page = new ratingsPage();
echo $page->outputHtml();
// @codeCoverageIgnoreEnd