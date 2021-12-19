<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getCalculations.inc.php";

final class getCalculations_Test extends TestCase
{
	/**
	 * @covers getCalculations
	 * @uses get_db_connection
	 * @uses PriceCalculation
	 * @uses CalculateGameRow
	 * @uses combinedate
	 * @uses daysSinceDate
	 * @uses getActivityCalculations
	 * @uses getAllCpi
	 * @uses getAllItems
	 * @uses getCleanStringDate
	 * @uses getGames
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
	 * @uses regroupArray
	 * @uses timeduration
	 */
    public function test_getCalculations_Global() {
        $this->assertisArray(getCalculations());
        $this->assertisArray(getCalculations());
	}

	/**
	 * @covers getCalculations
	 * @uses get_db_connection
	 * @uses PriceCalculation
	 * @uses CalculateGameRow
	 * @uses combinedate
	 * @uses daysSinceDate
	 * @uses getActivityCalculations
	 * @uses getAllCpi
	 * @uses getAllItems
	 * @uses getCleanStringDate
	 * @uses getGames
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
	 * @uses regroupArray
	 * @uses timeduration
	 */
	public function test_getCalculations_Connection() {
		$conn=get_db_connection();
        $this->assertisArray(getCalculations("",$conn));
	}
	
	/**
	 * @covers getPriceSort
	 * @uses PriceCalculation
	 */
	public function test_getPriceSort() {
		
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
		$sourceArray=array(
			1 => array(
				"Active" => false,
				"PriceObject" => new PriceCalculation(10,$HoursPlayed,$HoursToBeat,$MSRP) 
			),
			2 => array(
				"Active" => true,
				"PriceObject" => new PriceCalculation(11,$HoursPlayed,$HoursToBeat,$MSRP) 
			),
			3 => array(
				"Active" => true,
				"PriceObject" => new PriceCalculation(5,$HoursPlayed,$HoursToBeat,$MSRP) 
			)
		);
		
        $this->assertisArray(getPriceSort($sourceArray,"PriceObject"));
        $this->assertisArray(getPriceSort($sourceArray,"PriceObject",true));
	}

}