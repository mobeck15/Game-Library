<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getmetastats.inc.php";

final class getmetastats_Test extends TestCase
{
	/**
	 * @covers getmetastats
	 * @uses CalculateGameRow
	 * @uses PriceCalculation
	 * @uses combinedate
	 * @uses countrow
	 * @uses daysSinceDate
	 * @uses getActivityCalculations
	 * @uses getAllCpi
	 * @uses getAllItems
	 * @uses getCalculations
	 * @uses getCleanStringDate
	 * @uses getHistoryCalculations
	 * @uses getHrsNextPosition
	 * @uses getHrsToTarget
	 * @uses getKeywords
	 * @uses getNextPosition
	 * @uses getOnlyValues
	 * @uses getPriceSort
	 * @uses getPriceperhour
	 * @uses getPurchases
	 * @uses getStatRow
	 * @uses getTimeLeft
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses makeStatDataSet
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses regroupArray
	 * @uses timeduration
	 * @uses getGames
	 */
    public function test_getmetastats() {
        $this->assertisArray(getmetastats(""));
	}
}