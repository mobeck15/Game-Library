<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getCalculations.inc.php";

//Time: 00:37.892, Memory: 264.00 MB
//(3 tests, 5 assertions)
/**
 * @group include
 */
final class getCalculations_Test extends TestCase
{
	/**
	 * @group fast
	 * @small
	 * @covers getCalculations
	 * Time: 00:00.307, Memory: 48.00 MB
	 * (1 test, 2 assertions)
	 */
    public function test_getCalculations_Global() {
		$GLOBALS["CALCULATIONS"]=array("preset calculations");
		
		$output=getCalculations();
        $this->assertisArray($output);
		$this->assertEquals(array("preset calculations"),$output);
	}

	/**
	 * @group fast
	 * @small
	 * @covers getCalculations
	 * @uses CalculateGameRow
	 * @uses PriceCalculation
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
	 * Time: 00:00.307, Memory: 48.00 MB
	 * (1 test, 2 assertions)
	 */
    public function test_getCalculations_Base() {
		$output=getCalculations();
        $this->assertisArray($output);
	} /* */
	
	/**
	 * @group slow
	 * @large
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
	 * Time: 00:18.758, Memory: 262.00 MB
	 * (1 test, 1 assertion)
	 */
	public function test_getCalculations_Connection() {
		$conn=get_db_connection();
        $this->assertisArray(getCalculations("",$conn));
	}
	
	/**
	 * @group fast
	 * @small
	 * @covers getPriceSort
	 * @uses PriceCalculation
	 * Time: 00:00.224, Memory: 46.00 MB
	 * (1 test, 2 assertions)
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