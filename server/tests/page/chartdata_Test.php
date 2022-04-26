<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/chartdata.class.php";

/**
 * @testdox chartdata_Test.php testing chartdata.class.php
 * @group pageclass
 */
class chartdata_Test extends TestCase {
	/**
	 * @large
	 * @testdox __construct & buildHtmlBody
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

	/**
	 * @large
	 * @testdox buildHtmlBody() grouped by Year, Detail & exclude Free
	 * @covers chartdataPage::buildHtmlBody
	 * @uses chartdataPage
	 */
	public function test_outputHtml_year() {
		$_GET['group']='year';
		$_GET['CountFree']='0';
		$_GET['detail']='2013';
		$page = new chartdataPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
	
	/**
	 * @large
	 * @testdox buildHtmlBody() 
	 * @covers chartdataPage::buildHtmlBody
	 * @uses chartdataPage
	 * /
	public function test_outputHtml_detail() {
		$_GET['countfree']='0';
		$_GET['detail']='2010-5';
		$page = new chartdataPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
	/* */
}