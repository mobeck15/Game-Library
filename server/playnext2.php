<?php // @codeCoverageIgnoreStart
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";

require_once $GLOBALS['rootpath']."/page/playnext2.class.php";
$page = new playnext2Page();
echo $page->outputHtml();
// @codeCoverageIgnoreEnd