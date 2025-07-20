<?php // @codeCoverageIgnoreStart
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";

require_once $GLOBALS['rootpath']."/page/gl6.class.php";
$page = new gl6Page();
echo $page->outputHtml();
// @codeCoverageIgnoreEnd