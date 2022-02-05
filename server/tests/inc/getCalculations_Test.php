<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getCalculations.inc.php";

/**
 * @group include
 * @group purchases
 */
final class getCalculations_Test extends TestCase
{
	/**
	 * @small
	 * @covers getCalculations
	 */
    public function test_getCalculations_Global() {
		$GLOBALS["CALCULATIONS"]=array("preset calculations");
		
		$output=getCalculations();
        $this->assertisArray($output);
		$this->assertEquals(array("preset calculations"),$output);
	}

	/**
	 * @medium
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
	 * @uses get_db_connection
	 */
    public function test_getCalculations_Base() {
		$output=getCalculations();
        $this->assertisArray($output);
	} 
	
	/**
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
	 */
	public function test_getCalculations_Connection() {
		$conn=get_db_connection();
        $this->assertisArray(getCalculations("",$conn));
		$conn->close();
	}
	
	/**
	 * @small
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

	/**
	 * @group integration
	 * @medium
	 * @coversNothing
	 * @testWith [30]
	 *           [2269]
	 * 30   = Wizardry 7
	 * 2269 = Darksiders 2 Deathinitive edition
	 */
	public function test_multiplebundles($useproductid) {
		$conn=get_db_connection();
		$settings=getsettings($conn);
		$calculations=reIndexArray(getCalculations("",$conn),"Game_ID");
		$purchases=reIndexArray(getPurchases("",$conn), "TransID");
		
		foreach ($calculations[$useproductid]["TopBundleIDs"] as $bundle) {
			$totalMSRP=0;
			$totalSale=0;
			foreach ($purchases[$bundle]["GamesinBundle"] as $gamein){
				if($settings["status"][$calculations[$gamein["GameID"]]["Status"]]["Count"] == 1) {
					$totalMSRP+=$calculations[$gamein["GameID"]]["MSRP"];
					$totalSale+=$calculations[$gamein["GameID"]]["SalePrice"];
				}				
			}
			
			$totalExpectedSale=0;
			foreach ($purchases[$bundle]["GamesinBundle"] as $gamein){
				$expectedSale=($calculations[$gamein["GameID"]]["MSRP"]/$totalMSRP)*$purchases[$bundle]["Paid"];
				if($settings["status"][$calculations[$gamein["GameID"]]["Status"]]["Count"] == 1) {
					$totalExpectedSale += $expectedSale;
				}
				$this->assertEquals($expectedSale,$calculations[$gamein["GameID"]]["SalePrice"]);
			}
			$this->assertEquals($totalExpectedSale,$totalSale);
		}
	}

}