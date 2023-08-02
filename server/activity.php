<?php // @codeCoverageIgnoreStart
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";

require_once $GLOBALS['rootpath']."/page/activity.class.php";
$page = new activityPage();
echo $page->outputHtml();
// @codeCoverageIgnoreEnd