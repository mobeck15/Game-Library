<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/statistics.class.php";

/**
 * @group pageclass
 * @testdox statistics_Test.php testing cpi.class.php
 */
class statistics_Test extends TestCase {
	/**
	 * @small
	 * @covers statisticsPage::buildHtmlBody
	 * @covers statisticsPage::__construct
	 * @testdox __construct & buildHtmlBody
	 */
	public function test_outputHtml() {
		$page = new statisticsPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @covers statisticsPage::buildHtmlBody
	 * @testdox __construct & buildHtmlBody
	 * @uses Games
	 * @uses PriceCalculation
	 * @uses Purchases
	 * @uses combinedate
	 * @uses countgames
	 * @uses countrow
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
	 * @uses getOnlyValues
	 * @uses getPriceSort
	 * @uses getPriceperhour
	 * @uses getStatRow
	 * @uses getTimeLeft
	 * @uses get_db_connection
	 * @uses getmetastats
	 * @uses getsettings
	 * @uses makeGameCountRow
	 * @uses makeHeaderRow
	 * @uses makeIndex
	 * @uses makeStatDataSet
	 * @uses makeStatRow
	 * @uses makeStatTable
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses printStatRow2
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses statisticsPage
	 * @uses timeduration
	 */
	public function test_outputHtml_all() {
		$_GET['filter']='All';
		$_GET['meta']='both';
		$page = new statisticsPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
}