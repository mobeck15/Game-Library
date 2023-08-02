<?php // @codeCoverageIgnoreStart
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";

require_once $GLOBALS['rootpath']."/page/viewgame.class.php";
$page = new viewgamePage();
echo $page->outputHtml();
// @codeCoverageIgnoreEnd