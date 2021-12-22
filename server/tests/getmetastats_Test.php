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
    public function test_getmetastats_global() {
        $this->assertisArray(getmetastats("All"));
        $this->assertisArray(getmetastats("All"));
	}
	
	/**
	 * @covers makeDetailTable
	 * @uses makeStatDataSet
	 * @uses getStatRow
	 * @uses DetailDataTable
	 * @uses arrayTable
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
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getHrsNextPosition
	 * @uses getHrsToTarget
	 * @uses getKeywords
	 * @uses getNextPosition
	 * @uses getOnlyValues
	 * @uses getPriceSort
	 * @uses getPriceperhour
	 * @uses getPurchases
	 * @uses getTimeLeft
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses timeduration
	 */
    public function test_makeDetailTable() {
        $this->assertisString(makeDetailTable("All","PurchaseDateTime"));
	}

	/**
	 * @covers objectTranslator
	 */
    public function test_objectTranslator() {
        $this->assertEquals("LaunchPriceObj",objectTranslator("LaunchVariance"));
        $this->assertEquals("HistoricPriceObj",objectTranslator("HistoricVariance"));
        $this->assertEquals("MSRPPriceObj",objectTranslator("MSRPperhrbeat"));
        $this->assertEquals("PaidPriceObj",objectTranslator("PaidVariance"));
        $this->assertEquals("SalePriceObj",objectTranslator("SaleVariance"));
        $this->assertEquals("AltPriceObj",objectTranslator("AltLess2"));
        $this->assertEquals("someothervalue",objectTranslator("someothervalue"));
	}
	
	/**
	 * @covers methodTranslator
	 * @uses PriceCalculation
	 */
    public function test_methodTranslator() {
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
		$row["statRowKey"]=new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
		$row["alternateStatkey"]=123;
        $this->assertEquals(-10,methodTranslator("LaunchVariance","statRowKey",$row));
        $this->assertEquals(50,methodTranslator("HistoricVariancePct","statRowKey",$row));
        $this->assertEquals(2.5,methodTranslator("MSRPperhrbeat","statRowKey",$row));
        $this->assertEquals(10,methodTranslator("Paidperhr","statRowKey",$row));
        $this->assertEquals(5,methodTranslator("SaleLess1","statRowKey",$row));
        $this->assertEquals(1.0004454454454454,methodTranslator("AltLess2","statRowKey",$row));
        $this->assertEquals(123,methodTranslator("someothervalue","alternateStatkey",$row));
        $this->assertEquals(null,methodTranslator("someothervalue","missingkey",$row));
	}
	
	/**
	 * @covers countrow
	 * @uses getSettings
	 */
    public function test_countrow() {
		$GLOBALS["SETTINGS"]['CountFree']=true;
		$GLOBALS["SETTINGS"]['status']['Active']['Count']=true;
		$GLOBALS["SETTINGS"]['status']['Broken']['Count']=false;
		
		$row["Playable"]=true;
		$row['Status']="Active";
		$row['Paid']=0;
		
        $this->assertEquals(true,countrow($row));

		$GLOBALS["SETTINGS"]['CountFree']=false;

        $this->assertEquals(false,countrow($row));

		$GLOBALS["SETTINGS"]['CountFree']=true;
		$row['Status']="Broken";

        $this->assertEquals(false,countrow($row));
		
		$row["Playable"]=false;
		$row['Status']="Active";
		
        $this->assertEquals(false,countrow($row));
	}

	/**
	 * @covers getStatRow
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
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getHrsNextPosition
	 * @uses getHrsToTarget
	 * @uses getKeywords
	 * @uses getNextPosition
	 * @uses getOnlyValues
	 * @uses getPriceSort
	 * @uses getPriceperhour
	 * @uses getPurchases
	 * @uses getTimeLeft
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses makeStatDataSet
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses regroupArray
	 * @uses timeduration
	 */
    public function test_getStatRow() {
		
        $this->assertisArray(getStatRow("All",'firstPlayDateTime'));
        $this->assertisArray(getStatRow("All",'firstPlayDateTime'));
        $this->assertisArray(getStatRow("All",'AltHrs3'));
        $this->assertisArray(getStatRow("All",'Review'));
        $this->assertisArray(getStatRow("All",'Altperhrbeat'));
        $this->assertisArray(getStatRow("All",'AchievementsPct'));
        $this->assertisArray(getStatRow("All",'TimeLeftToBeat'));
		
		//Total not set?
        //$this->assertisArray(getStatRow("All",'othervalue'));
        //$this->assertisArray(getStatRow("All",'launchhrsavg'));
	}
	
	/**
	 * @covers getStatRow
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
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getHrsNextPosition
	 * @uses getHrsToTarget
	 * @uses getKeywords
	 * @uses getNextPosition
	 * @uses getOnlyValues
	 * @uses getPriceSort
	 * @uses getPriceperhour
	 * @uses getPurchases
	 * @uses getTimeLeft
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses makeStatDataSet
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses regroupArray
	 * @uses timeduration
	 */
    public function test_getStatRow_error() {
		$this->expectNotice();
		$this->expectNoticeMessage('othervalue Total not set');
		
        $this->assertisArray(getStatRow("All",'othervalue'));
	}	

	/**
	 * @covers countgames
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
    public function test_countgames() {
        $this->assertisNumeric(countgames("All"));
	}	
	
	/**
	 * @covers makeStatTable
	 * @uses CalculateGameRow
	 * @uses PriceCalculation
	 * @uses combinedate
	 * @uses countgames
	 * @uses countrow
	 * @uses daysSinceDate
	 * @uses getActivityCalculations
	 * @uses getAllCpi
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
	 * @uses getPurchases
	 * @uses getStatRow
	 * @uses getTimeLeft
	 * @uses getmetastats
	 * @uses getsettings
	 * @uses makeGameCountRow
	 * @uses makeHeaderRow
	 * @uses makeIndex
	 * @uses makeStatDataSet
	 * @uses makeStatRow
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses printStatRow2
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses timeduration
	 */
    public function test_makeStatTable() {
		$_GET['filter']="All";
		$_GET['meta']="both";
        $this->assertisString(makeStatTable("both","All"));
	}
	
	/**
	 * @covers makeGameCountRow
	 * @uses CalculateGameRow
	 * @uses PriceCalculation
	 * @uses combinedate
	 * @uses countgames
	 * @uses countrow
	 * @uses daysSinceDate
	 * @uses getActivityCalculations
	 * @uses getAllCpi
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
	 * @uses getPurchases
	 * @uses getTimeLeft
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses regroupArray
	 * @uses timeduration
	 */
    public function test_makeGameCountRow() {
        $this->assertisString(makeGameCountRow("both","blue1"));
	}
	
	/**
	 * @covers makeStatRow
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
	 * @uses getGames
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
	 * @uses getmetastats
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses makeStatDataSet
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses printStatRow2
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses timeduration
	 */
    public function test_makeStatRow() {
		$_GET['filter']="All";
		$_GET['meta']="both";
        $this->assertisString(makeStatRow("All","Price","SalePrice","yellow1","Sale Price",7));
	}	

	/**
	 * @covers makeHeaderRow
	 */
    public function test_makeHeaderRow() {
        $this->assertEquals("<tr><th colspan=100><hr>Some header</th></tr>",makeHeaderRow("Some header"));
	}	

	/**
	 * @covers makeStatDataSet
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
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses regroupArray
	 * @uses timeduration
	 */
    public function test_makeStatDataSet() {
        $this->assertisArray(makeStatDataSet("All",'Review'));
	}	

	/**
	 * @covers valueTranslator
	 * @uses PriceCalculation
	 * @uses objectTranslator
	 * @uses methodTranslator
	 */
    public function test_valueTranslator() {
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
		$row["LaunchPriceObj"]=new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
        $this->assertEquals(-10,valueTranslator($row, "LaunchVariance"));
        $this->assertEquals(null,valueTranslator($row, "LaunchVariancePct"));
	}	


}