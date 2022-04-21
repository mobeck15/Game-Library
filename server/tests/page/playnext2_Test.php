<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/playnext2.class.php";

/**
 * @group pageclass
 * @testdox playnext2_Test.php testing playnext2.class.php
 */
class playnext2_Test extends TestCase {
	/**
	 * @large
	 * @covers playnext2Page::buildHtmlBody
	 * @covers playnext2Page::__construct
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
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses timeduration
	 * @uses topx
	 * @testdox __construct & buildHtmlBody
	 */
	public function test_outputHtml() {
		$page = new playnext2Page();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}