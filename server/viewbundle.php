<?php // @codeCoverageIgnoreStart
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";

require_once $GLOBALS['rootpath']."/page/viewbundle.class.php";
$page = new viewbundlePage();
echo $page->outputHtml();
// @codeCoverageIgnoreEnd