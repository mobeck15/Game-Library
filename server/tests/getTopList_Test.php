<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getTopList.inc.php";

final class getTopList_Test extends TestCase
{
	/**
	 * @covers getTopList
	 * @uses CalculateGameRow
	 * @uses PriceCalculation
	 * @uses combinedate
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
	 * @uses getPriceSort
	 * @uses getPriceperhour
	 * @uses getPurchases
	 * @uses getTimeLeft
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses timeduration
	 * @uses getGames
	 * @uses get_db_connection
	 */
    public function test_getTopList() {
		$output=getTopList("");
        $this->assertisArray($output);
        $this->assertisArray($output[57]);

		$conn=get_db_connection();
        $this->assertisArray(getTopList("",$conn));
		
		$calculations=getCalculations("",$conn);
        $this->assertisArray(getTopList("",$conn,$calculations));
        $this->assertisArray(getTopList("Keyword",$conn,$calculations));
        $this->assertisArray(getTopList("Series",$conn,$calculations));
        $this->assertisArray(getTopList("Series",$conn,$calculations,1));
        $this->assertisArray(getTopList("Store",$conn,$calculations));
        $this->assertisArray(getTopList("Library",$conn,$calculations));
        $this->assertisArray(getTopList("SteamR10",$conn,$calculations));
        $this->assertisArray(getTopList("SteamR",$conn,$calculations));
        $this->assertisArray(getTopList("PYear",$conn,$calculations));
        $this->assertisArray(getTopList("LYear",$conn,$calculations));
	}
}