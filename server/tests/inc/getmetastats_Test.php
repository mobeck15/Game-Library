<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getmetastats.inc.php";

//Time: 03:49.664, Memory: 264.00 MB
//(18 tests, 48 assertions)
/**
 * @group include
 */
final class getmetastats_Test extends TestCase
{
	/**
	 * @group fast
	 * @small
	 * @covers getmetastats
	 * Time: 00:00.221, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
    public function test_getmetastats_global() {
		//ARRANGE
		$testarry=array(1,2,3,4,5,6,7,8,9,
		10,11,12,13,14,15,16,17,18,19,
		20,21,22,23,24,25,26,27,28,29,
		30,31,32,33,34,35,36,37,38,39,
		40,41,42,43,44,45,46,47,48,49,
		50,51,52,53,54,55,56,57,58,59,
		60,61,62,63,64,65,66,67,68,69,
		70,71,72,73,74,75,76,77,78,79,
		80,81,82,83,84,85,86,87,88,89,
		90,91,92,93,94,95,96,97,98);
		$GLOBALS["METASTATS"]=$testarry;
		
		//ACT
		$output=getmetastats("All");
		
		//ASSERT
        $this->assertisArray($output);
		$this->assertEquals($testarry,$output);
	}
	
	/**
	 * @group fast
	 * @small
	 * @covers getmetastats
	 * @uses getStatRow
	 * Time: 00:00.221, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_getmetastats_main() {
		//ARRANGE
		$testarry=array(		'althrsgame'=>1,		'althrsmedian'=>2,			'althrsmean'=>3,		'althrsavg'=>4,
		'salehrsgame'=>5,		'salehrsmedian'=>6,		'salehrsmean'=>7,			'salehrsavg'=>8,		'paidhrsgame'=>9,
		'paidhrsmedian'=>10,	'paidhrsmean'=>11,		'paidhrsavg'=>12,			'histhrsgame'=>13,		'histhrsmedian'=>14,
		'histhrsmean'=>15,		'histhrsavg'=>16,		'msrphrsgame'=>17,			'msrphrsmedian'=>18,	'msrphrsmean'=>19,
		'msrphrsavg'=>20,		'launchhrsmedian'=>21,	'launchhrsgame'=>22,		'launchhrsavg'=>23,		'PurchaseDateTime'=>24,
		'LaunchDate'=>25,		'AddedDateTime'=>26,	'firstPlayDateTime'=>27,	'lastPlayDateTime'=>28,	'SteamAchievements'=>29,	
		'Achievements'=>30,		'AchievementsPct'=>31,	'AchievementsLeft'=>32,		'totalHrs'=>33,			'GrandTotal'=>34,
		'TimeToBeat'=>35,		'TimeLeftToBeat'=>36,	'Metascore'=>37,			'UserMetascore'=>38,	'SteamRating'=>39,
		'Review'=>40,			'LaunchPrice'=>41,		'LaunchVariancePct'=>43,	'LaunchVariance'=>42,	'Launchperhr'=>44,
		'LaunchLess1'=>45,		'LaunchLess2'=>46,		'Launchperhrbeat'=>47,		'MSRP'=>48,				'MSRPperhr'=>49,
		'MSRPLess1'=>50,		'MSRPLess2'=>51,		'MSRPperhrbeat'=>52,		'HistoricLow'=>53,		'HistoricVariance'=>54,
		'Historicperhr'=>56,	'HistoricLess1'=>57,	'HistoricVariancePct'=>55,	'HistoricLess2'=>58,	'Historicperhrbeat'=>59,
		'Paid'=>60,				'PaidVariance'=>61,		'PaidVariancePct'=>62,		'Paidperhr'=>63,		'PaidLess1'=>64,
		'PaidLess2'=>65,		'Paidperhrbeat'=>66,	'SalePrice'=>67,			'SaleVariance'=>68,		'SaleVariancePct'=>69,
		'Saleperhr'=>70,		'SaleLess1'=>71,		'SaleLess2'=>72,			'Saleperhrbeat'=>73,	'AltSalePrice'=>74,
		'AltSaleVariance'=>75,	'Altperhr'=>77,			'AltSaleVariancePct'=>76,	'AltLess1'=>78,			'AltLess2'=>79,
		'Altperhrbeat'=>80,		'LaunchHrsNext1'=>81,	'LaunchHrsNext2'=>82,		'LaunchHrs5'=>82,		'MSRPHrsNext1'=>83,
		'MSRPHrsNext2'=>84,		'MSRPHrs3'=>85,			'HistoricHrsNext1'=>86,		'HistoricHrsNext2'=>87,	'HistoricHrs3'=>88,
		'PaidHrsNext1'=>90,		'PaidHrsNext2'=>91,		'PaidHrs3'=>92,				'SaleHrsNext1'=>93,		'SaleHrsNext2'=>94,
		'SaleHrs3'=>95,			'AltHrsNext1'=>96,		'AltHrsNext2'=>97,			'AltHrs3'=>98,			99);
		
		$GLOBALS["METASTATS"]=$testarry;
		
		//ACT
		$output=getmetastats("All");
		
		//ASSERT
        $this->assertisArray($output);
	}
	
	/**
	 * @group fast
	 * @small
	 * @covers makeDetailTable
	 * @uses DetailDataTable
	 * @uses arrayTable
	 * @uses countrow
	 * @uses getCalculations
	 * @uses getStatRow
	 * @uses getsettings
	 * @uses makeStatDataSet
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses reIndexArray
	 * Time: 00:00.221, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_makeDetailTable() {
		//Arrange
		$GLOBALS["SETTINGS"]['CountFree']=true;
		$GLOBALS["SETTINGS"]['status']['Active']['Count']=true;
		
		$testarry=array('Review'=>array(
			'Title'=>'Review',	'Total'=>1,		'Sum'=>1,
			'Average'=>1,		'AverageGameID'=>5,
			'Median'=>1,		'MedianGameID'=>6,
			'Mode'=>1,			'ModeGameID'=>7,		'HarMean'=>1,
			'Max1'=>1,			'Max1GameID'=>1,
			'Max2'=>1,			'Max2GameID'=>2,
			'Min1'=>1,			'Min1GameID'=>3,
			'Min2'=>1,			'Min2GameID'=>4,
			'Print'=>array(
				'Total'=>1,		'Average'=>1,
				'Median'=>1,	'Mode'=>1,				'HarMean'=>1,
				'Max1'=>1,		'Max2'=>1,
				'Min1'=>1,		'Min2'=>1,
				)
			)
		);
		$GLOBALS["METASTATS"]=$testarry;
		
		$testcalcs=array(
			array(
				'Game_ID'=>1,
				'Title'=>'TestGame1',
				'Playable'=>true,
				'Paid'=>1,
				'Status'=>"Active",
				'Review'=>1
			),
			array(
				'Game_ID'=>2,
				'Title'=>'TestGame2',
				'Playable'=>true,
				'Paid'=>1,
				'Status'=>"Active",
				'Review'=>1
			),
		);
		$GLOBALS["CALCULATIONS"]=$testcalcs;
		
		//Act
		$output=makeDetailTable("All","Review");
		
		//Assert
        $this->assertisString($output);
	}

	/**
	 * @group fast
	 * @small
	 * @covers objectTranslator
	 * @testWith ["LaunchPriceObj", "LaunchVariance"]
	 *           ["HistoricPriceObj", "HistoricVariance"]
	 *           ["MSRPPriceObj", "MSRPperhrbeat"]
	 *           ["PaidPriceObj", "PaidVariance"]
	 *           ["SalePriceObj", "SaleVariance"]
	 *           ["AltPriceObj", "AltLess2"]
	 *           ["someothervalue", "someothervalue"]
	 * Time: 00:00.240, Memory: 46.00 MB
	 * OK (7 tests, 7 assertions)
	 */
    public function test_objectTranslator(string $objectname,string $statname) {
		$this->assertEquals($objectname,objectTranslator($statname));
	}
	
	/**
	 * @group fast
	 * @small
	 * @covers methodTranslator
	 * @uses PriceCalculation
	 * @testWith [-10, "LaunchVariance", "statRowKey"]
	 *           [50, "HistoricVariancePct", "statRowKey"]
	 *           [2.5, "MSRPperhrbeat", "statRowKey"]
	 *           [10, "Paidperhr", "statRowKey"]
	 *           [5, "SaleLess1", "statRowKey"]
	 *           [1.0004454454454454, "AltLess2", "statRowKey"]
	 *           [123, "someothervalue", "alternateStatkey"]
	 *           [null, "someothervalue", "missingkey"]
	 * Time: 00:00.244, Memory: 46.00 MB
	 * (8 tests, 8 assertions)
	 */
    public function test_methodTranslator($expected, string $Statname, string $key) {
		//ARRANGE
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
		$row["statRowKey"]=new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
		$row["alternateStatkey"]=123;
		
		//ACT
		//ASSERT
        $this->assertEquals($expected,methodTranslator($Statname,$key,$row));
	}
	
	/**
	 * @group fast
	 * @small
	 * @covers countrow
	 * @uses getSettings
	 * @testWith [true, "Active", true, true]
	 *           [false, "Active", true, false]
	 *           [true, "Broken", true, false]
	 *           [true, "Active", false, false]
	 * Time: 00:00.231, Memory: 46.00 MB
	 * OK (4 tests, 4 assertions)
	 */
    public function test_countrow($countfree,$status,$playable,$expected) {
		//ARRANGE
		$GLOBALS["SETTINGS"]['CountFree']=$countfree; //true;
		$GLOBALS["SETTINGS"]['status']['Active']['Count']=true;
		$GLOBALS["SETTINGS"]['status']['Broken']['Count']=false;
		
		$row["Playable"]=$playable; //true;
		$row['Status']=$status; //"Active";
		$row['Paid']=0;
		
		//ACT
		//ASSERT
        $this->assertEquals($expected,countrow($row));
	}

	/**
	 * @group slow
	 * @large
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
	 * Time: 00:19.653, Memory: 262.00 MB
	 * (1 test, 7 assertions)
	 */
    public function test_getStatRow_main() {
		
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
	 * @group slow
	 * @large
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
	 * Time: 00:18.917, Memory: 262.00 MB
	 * (1 test, 2 assertions)
	 */
    public function test_getStatRow_error() {
		$this->expectNotice();
		$this->expectNoticeMessage('othervalue Total not set');
		
        $this->assertisArray(getStatRow("All",'othervalue'));
	}	

	/**
	 * @group slow
	 * @large
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
	 * Time: 00:18.778, Memory: 262.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_countgames() {
        $this->assertisNumeric(countgames("All"));
	}	
	
	/**
	 * @group slow
	 * @large
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
	 * Time: 00:30.697, Memory: 262.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_makeStatTable() {
		$_GET['filter']="All";
		$_GET['meta']="both";
        $this->assertisString(makeStatTable("both","All"));
	}
	
	/**
	 * @group slow
	 * @large
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
	 * Time: 00:18.599, Memory: 262.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_makeGameCountRow() {
        $this->assertisString(makeGameCountRow("both","blue1"));
	}
	
	/**
	 * @group slow
	 * @large
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
	 * Time: 00:30.205, Memory: 262.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_makeStatRow() {
		$_GET['filter']="All";
		$_GET['meta']="both";
        $this->assertisString(makeStatRow("All","Price","SalePrice","yellow1","Sale Price",7));
	}	

	/**
	 * @group fast
	 * @small
	 * @covers makeHeaderRow
	 * Time: 00:00.219, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_makeHeaderRow() {
        $this->assertEquals("<tr><th colspan=100><hr>Some header</th></tr>",makeHeaderRow("Some header"));
	}	

	/**
	 * @group slow
	 * @large
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
	 * Time: 00:18.707, Memory: 262.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_makeStatDataSet() {
        $this->assertisArray(makeStatDataSet("All",'Review'));
	}	

	/**
	 * @group fast
	 * @small
	 * @covers valueTranslator
	 * @uses PriceCalculation
	 * @uses objectTranslator
	 * @uses methodTranslator
	 * Time: 00:00.227, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
    public function test_valueTranslator() {
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
		$row["LaunchPriceObj"]=new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
        $this->assertEquals(-10,valueTranslator($row, "LaunchVariance"));
        $this->assertEquals(null,valueTranslator($row, "MSRPVariancePct"));
	}	

	/**
	 * @group slow
	 * @large
	 * @covers printStatRow2
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
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses makeStatDataSet
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses timeduration
	 * Time: 00:18.734, Memory: 262.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_printStatRow2() {
		$statrow=getStatRow("All",'SteamRating');
        $this->assertisString(printStatRow2($statrow));
	}	

	/**
	 * @group slow
	 * @large
	 * @covers DetailDataTable
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
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses makeStatDataSet
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses timeduration
	 * Time: 00:19.394, Memory: 262.00 MB
	 * (1 test, 2 assertions)
	 */
    public function test_DetailDataTable() {
		$dataset=makeStatDataSet("All",'AltHrs3');
		$statrow=getStatRow("All",'AltHrs3');
		
        $this->assertisString(DetailDataTable($dataset,$statrow));

		$dataset=makeStatDataSet("All",'firstPlayDateTime');
		$statrow=getStatRow("All",'firstPlayDateTime');
		
        $this->assertisString(DetailDataTable($dataset,$statrow));
	}

	/**
	 * @group slow
	 * @large
	 * @covers getOnlyValues
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
	 * @uses makeStatDataSet
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses regroupArray
	 * @uses timeduration
	 * Time: 00:19.246, Memory: 262.00 MB
	 * (1 test, 5 assertions)
	 */
    public function test_getOnlyValues() {
		$statrow=makeStatDataSet("All",'firstPlayDateTime');
        $this->assertisArray(getOnlyValues($statrow,'firstPlayDateTime'));
		
		$statrow=makeStatDataSet("All",'Review');
        $this->assertisArray(getOnlyValues($statrow,'Review'));
		
		$statrow=makeStatDataSet("All",'Altperhrbeat');
        $this->assertisArray(getOnlyValues($statrow,'Altperhrbeat'));
		
		$statrow=makeStatDataSet("All",'AltHrs3');
        $this->assertisArray(getOnlyValues($statrow,'AltHrs3'));
		
		$statrow=makeStatDataSet("All",'AchievementsPct');
        $this->assertisArray(getOnlyValues($statrow,'AchievementsPct'));
	}	
}