<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/chartdata.class.php";

/**
 * @group pageclass
 */
class chartdata_Test extends TestCase {
	/**
	 * @large
	 * @covers chartdataPage::buildHtmlBody
	 * @covers chartdataPage::__construct
	 * @uses Games
	 * @uses PriceCalculation
	 * @uses Purchases
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
		$page = new chartdataPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}