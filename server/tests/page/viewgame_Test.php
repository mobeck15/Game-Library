<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/viewgame.class.php";

/**
 * @group pageclass
 * @testdox viewgame_Test.php testing viewgame.class.php
 */
class viewgame_Test extends TestCase {
	/**
	 * @small
	 * @covers viewgamePage::buildHtmlBody
	 * @covers viewgamePage::__construct
	 * @testdox __construct & buildHtmlBody
	 * @uses get_db_connection
	 * @uses lookupTextBox
	 */
	public function test_outputHtml() {
		$page = new viewgamePage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @covers viewgamePage::buildHtmlBody
	 * @testdox __construct & buildHtmlBody
	 * @uses get_db_connection
	 * @uses lookupTextBox
	 * @uses CurlRequest
	 * @uses Games
	 * @uses PriceCalculation
	 * @uses Purchases
	 * @uses SteamAPI
	 * @uses SteamFormat
	 * @uses SteamScrape
	 * @uses boolText
	 * @uses combinedate
	 * @uses dataAccess
	 * @uses daysSinceDate
	 * @uses getActivityCalculations
	 * @uses getAllItems
	 * @uses getCalculations
	 * @uses getCleanStringDate
	 * @uses getGameDetail
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getHrsNextPosition
	 * @uses getHrsToTarget
	 * @uses getKeywords
	 * @uses getNextPosition
	 * @uses getPriceSort
	 * @uses getPriceperhour
	 * @uses getTimeLeft
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses timeduration
	 * @uses viewgamePage
	 */
	public function test_outputHtml_detail() {
		$_GET['id']='262'; //Portal: 262
		$page = new viewgamePage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
}