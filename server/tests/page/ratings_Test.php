<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/ratings.class.php";

/**
 * @group pageclass
 * @testdox ratings_Test.php testing ratings.class.php
 */
class ratings_Test extends TestCase {
	/**
	 * @large
	 * @covers ratingsPage::buildHtmlBody
	 * @covers ratingsPage::__construct
	 * @testdox __construct & buildHtmlBody
	 * @uses Games
	 * @uses PriceCalculation
	 * @uses Purchases
	 * @uses RatingsChartData
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
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses regroupArray
	 * @uses timeduration
	 */
	public function test_outputHtml() {
		$page = new ratingsPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}