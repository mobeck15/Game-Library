<?php // @codeCoverageIgnoreStart
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";

require_once $GLOBALS['rootpath']."/page/playnext.class.php";
$page = new playnextPage();
echo $page->outputHtml();
// @codeCoverageIgnoreEnd