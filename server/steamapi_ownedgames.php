<?php // @codeCoverageIgnoreStart
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";

require_once $GLOBALS['rootpath']."/page/steamapi_ownedgames.class.php";
$page = new steamapi_ownedgamesPage();
echo $page->outputHtml();
// @codeCoverageIgnoreEnd