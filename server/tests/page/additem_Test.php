<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/additem.class.php";

/**
 * @group pageclass
 */
class additem_Test extends TestCase {
	/**
	 * @small
	 * @covers additemPage::buildHtmlBody
	 * @covers additemPage::__construct
	 * @uses Get_Header
	 * @uses boolText
	 * @uses dataAccess
	 * @uses get_db_connection
	 * @uses get_navmenu
	 * @uses getsettings
	 */
	public function test_outputHtml() {
		$_SERVER['QUERY_STRING']="";
		$page = new additemPage();
		$result = $page->buildHtmlBody();
		
		$this->assertisString($result);
	}

}