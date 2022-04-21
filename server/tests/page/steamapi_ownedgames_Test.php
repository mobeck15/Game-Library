<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/steamapi_ownedgames.class.php";

/**
 * @group pageclass
 * @testdox steamapi_ownedgames_Test.php testing cpi.class.php
 */
class steamapi_ownedgames_Test extends TestCase {
	/**
	 * @large
	 * @covers steamapi_ownedgamesPage::buildHtmlBody
	 * @covers steamapi_ownedgamesPage::__construct
	 * @testdox __construct & buildHtmlBody
	 * @uses CurlRequest
	 * @uses Games
	 * @uses PriceCalculation
	 * @uses Purchases
	 * @uses SteamAPI
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
	 * @uses getKeywords
	 * @uses getNextPosition
	 * @uses getPriceSort
	 * @uses getPriceperhour
	 * @uses getTimeLeft
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses regroupArray
	 * @uses timeduration
	 * @uses get_db_connection
	 */
	public function test_outputHtml() {
		$page = new steamapi_ownedgamesPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}