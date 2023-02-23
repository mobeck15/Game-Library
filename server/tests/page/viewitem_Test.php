<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/viewitem.class.php";

/**
 * @group pageclass
 * @testdox viewitem_Test.php testing viewitem.class.php
 */
class viewitem_Test extends TestCase {
	/**
	 * @medium
	 * @covers viewitemPage::buildHtmlBody
	 * @covers viewitemPage::__construct
	 * @testdox __construct & buildHtmlBody
	 * @uses Games
	 * @uses Purchases
	 * @uses combinedate
	 * @uses dataAccess
	 * @uses getActivityCalculations
	 * @uses getAllItems
	 * @uses getCleanStringDate
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses timeduration
	 */
	public function test_outputHtml() {
		$page = new viewitemPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @medium
	 * @covers viewitemPage::buildHtmlBody
	 * @testdox buildHtmlBody-Item
	 */
	public function test_outputHtmlItem() {
		$page = new viewitemPage();
		$_GET["id"]=11;
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
}