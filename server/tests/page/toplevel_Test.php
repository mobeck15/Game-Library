<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/toplevel.class.php";

/**
 * @group pageclass
 * @testdox toplevel_Test.php testing toplevel.class.php
 */
class toplevel_Test extends TestCase {
	/**
	 * @large
	 * @covers toplevelPage::buildHtmlBody
	 * @covers toplevelPage::__construct
	 * @covers toplevelPage::echoRow
	 * @testdox __construct, buildHtmlBody & echoRow
	 * @uses Games
	 * @uses PriceCalculation
	 * @uses Purchases
	 * @uses boolText
	 * @uses combinedate
	 * @uses dataAccess
	 * @uses daysSinceDate
	 * @uses getActivityCalculations
	 * @uses getAllItems
	 * @uses getCalculations
	 * @uses getCleanStringDate
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getHrsNextPosition
	 * @uses getHrsToTarget
	 * @uses getNextPosition
	 * @uses getPriceSort
	 * @uses getPriceperhour
	 * @uses getTimeLeft
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses timeduration
	 * @uses toplevelPage
	 */
	public function test_outputHtml() {
		$page = new toplevelPage();
		
		$GLOBALS["SETTINGS"]=array(
		"CountDupes"=>0,
		);
		
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @large
	 * @testdox buildHtmlBody() with Group parameter & detail
	 * @covers toplevelPage::buildHtmlBody
	 */
	public function test_outputHtml_detail() {
		$_GET['Group']="Bundle";
		$_GET['Detail']="88";
		
		$GLOBALS["SETTINGS"]=array(
		"CountDupes"=>0,
		);
		
		$page = new toplevelPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
}