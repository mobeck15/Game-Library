<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getmetastats.inc.php";

/**
 * @group include
 * @group getmetastats
 */
final class getmetastats_Test extends TestCase
{
	/**
	 * @small
	 * @covers getmetastats
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
	 * @small
	 * @covers getmetastats
	 * @uses getStatRow
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
	 * @uses dataSet
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
	 * @small
	 * @covers objectTranslator
	 * @testWith ["LaunchPriceObj", "LaunchVariance"]
	 *           ["HistoricPriceObj", "HistoricVariance"]
	 *           ["MSRPPriceObj", "MSRPperhrbeat"]
	 *           ["PaidPriceObj", "PaidVariance"]
	 *           ["SalePriceObj", "SaleVariance"]
	 *           ["AltPriceObj", "AltLess2"]
	 *           ["someothervalue", "someothervalue"]
	 */
    public function test_objectTranslator(string $objectname,string $statname) {
		$this->assertEquals($objectname,objectTranslator($statname));
	}
	
	/**
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
	 * @small
	 * @covers countrow
	 * @uses getSettings
	 * @testWith [true, "Active", true, true]
	 *           [false, "Active", true, false]
	 *           [true, "Broken", true, false]
	 *           [true, "Active", false, false]
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
	 * @small
	 * @covers getStatRow
	 * @uses timeduration
	 * @testWith ["firstPlayDateTime"]
	 *           ["AltHrs3"]
	 *           ["Review"]
	 *           ["Altperhrbeat"]
	 *           ["AchievementsPct"]
	 *           ["TimeLeftToBeat"]
	 */
    public function test_getStatRow_main($stat) {
		$mockDataSet = array_values([
			3599 => ['Game_ID' => 3599, 'firstPlayDateTime' => new DateTime('@1373752800'),
				'AltHrs3' => 980.5833333333334,
				'Review' => 1,
				'Altperhrbeat' => 0,
				'AchievementsPct' => 0.1221001221001221,
				'TimeLeftToBeat' => 0.061111111111111116],
			3600 => ['Game_ID' => 3600, 'firstPlayDateTime' => new DateTime('@1373580000'),
				'AltHrs3' => 5136.233333333334,
				'Review' => 2,
				'Altperhrbeat' => 0,
				'AchievementsPct' => 0.6493506493506493,
				'TimeLeftToBeat' => 0.01666666666666672],
			3601 => ['Game_ID' => 3601, 'firstPlayDateTime' => new DateTime('@1373580000'),
				'AltHrs3' => 5136.233333333334,
				'Review' => 2,
				'Altperhrbeat' => 0,
				'AchievementsPct' => 0.6493506493506493,
				'TimeLeftToBeat' => 0.01666666666666672],
			3602 => ['Game_ID' => 3602, 'firstPlayDateTime' => new DateTime('@1373580000'),
				'AltHrs3' => 5136.233333333334,
				'Review' => 2,
				'Altperhrbeat' => 0,
				'AchievementsPct' => 0.6493506493506493,
				'TimeLeftToBeat' => 0.01666666666666672]
		]);
		
		$mockVal = [
			'basedata' => [
				1373752800,
				1373580000,
				1277627554,
				1273615200,
			],
			'modedata' => [
				1373752800,
				1373580000,
				1277627554,
				1273615200,
			],
		];

		$makeMock = fn($filter, $statname) => $mockDataSet;
		$getMock = fn($dataset, $statname) => $mockVal;

		$result = getStatRow("All", $stat, $makeMock, $getMock);
		$this->assertIsArray($result);
	}
	
	/**
	 * @small
	 * @covers getStatRow
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
	 * @uses getTimeLeft
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses makeStatDataSet
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses regroupArray
	 * @uses timeduration
	 */
    public function test_getStatRow_set() {
		$GLOBALS["METASTATS"]['othervalue']=array("already set");
		$output = getStatRow("All",'othervalue');
        $this->assertisArray($output);
        $this->assertEquals(array("already set"),$output);
	}

	/**
	 * @small
	 * @covers getStatRow
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
	 * @uses getTimeLeft
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses makeStatDataSet
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses regroupArray
	 * @uses timeduration
	 */
    public function test_getStatRow_null() {
		$output = getStatRow("All",null);
        $this->assertisArray($output);
	}
	
	/**
	 * @small
	 * @covers getStatRow
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
	 * @uses getTimeLeft
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses makeStatDataSet
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses regroupArray
	 * @uses timeduration
	 * @uses dataSet
	 * @uses reIndexArray
	 * @doesNotPerformAssertions
	 */
    public function test_getStatRow_total0() {
		
		$GLOBALS["SETTINGS"]['CountFree']=true;
		$GLOBALS["SETTINGS"]['status']['Active']['Count']=true;
		
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

		set_error_handler(function ($severity, $message, $file, $line) {
        	if ($severity & E_USER_ERROR) {
        		throw new \ErrorException($message, 0, $severity, $file, $line);
			}
        });

		$output = getStatRow("All",'Review2');
        //$this->assertisArray($output);
		restore_error_handler();
	}
	
	/**
	 * @large
	 * @covers countgames
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
	 * @small
	 * @covers makeStatTable
	 */
	public function test_makeStatTable() {
		$_GET['filter'] = 'All';
		$_GET['meta'] = 'both';

		// mock makeStatRow just returns a string
		$fakeStatRowFn = function ($filter, $rowname, $datakey, $color, $Heading = "", $height = 1) {
			return "<tr><td>$rowname</td><td>$datakey</td></tr>";
		};

		// mock header row
		$fakeHeaderRowFn = function ($label) {
			return "<tr class='header'><th colspan=2>$label</th></tr>";
		};

		// mock game count row
		$fakeGameCountRowFn = function ($filter, $color) {
			return "<tr class='$color'><td>Game Count</td></tr>";
		};

		$html = makeStatTable("both", "All", $fakeStatRowFn, $fakeHeaderRowFn, $fakeGameCountRowFn);

		$this->assertIsString($html);
		$this->assertStringContainsString("<table", $html);
		$this->assertStringContainsString("Release", $html);
		$this->assertStringContainsString("LaunchDate", $html);
	}

	
	/**
	 * @large
	 * @covers makeGameCountRow
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
	 * @small
	 * @covers makeStatRow
	 */
	public function test_makeStatRow() {
		$_GET['filter'] = "All";
		$_GET['meta'] = "both";

		$fakeStats = [
			'SalePrice' => ['Title' => 'SalePrice', 'Value' => 123]
		];

		$getMetaStatsMock = function ($filter) use ($fakeStats) {
			return $fakeStats;
		};

		$printStatRow2Mock = function ($row) {
			return "<td>" . $row['Value'] . "</td>";
		};

		$result = makeStatRow("All", "Price", "SalePrice", "yellow1", "Sale Price", 7, $getMetaStatsMock, $printStatRow2Mock);

		$this->assertIsString($result);
		$this->assertStringContainsString('<tr class=\'yellow1\'>', $result);
		$this->assertStringContainsString('<th rowspan=7 class=\'yellow1\'>Sale Price</th>', $result);
		$this->assertStringContainsString('<td>123</td>', $result);
	}

	/**
	 * @small
	 * @covers makeHeaderRow
	 */
    public function test_makeHeaderRow() {
        $this->assertEquals("<tr><th colspan=100><hr>Some header</th></tr>",makeHeaderRow("Some header"));
	}

	/**
	 * @large
	 * @covers makeStatDataSet
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
	 * @small
	 * @covers valueTranslator
	 * @uses PriceCalculation
	 * @uses objectTranslator
	 * @uses methodTranslator
	 * @testWith [10,2,4,20,"LaunchVariance",-10]
	 *           [10,2,4,20,"MSRPVariancePct",null]
	 */
    public function test_valueTranslator($price,$HoursPlayed,$HoursToBeat,$MSRP,$value,$expected) {
		$row["LaunchPriceObj"]=new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
        $this->assertEquals($expected,valueTranslator($row, $value));
	}

	/**
	 * @large
	 * @covers printStatRow2
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
	 */
    public function test_printStatRow2() {
		$statrow=getStatRow("All",'SteamRating');
        $this->assertisString(printStatRow2($statrow));
	}	

	/**
	 * @large
	 * @covers DetailDataTable
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
	 * @testWith ["firstPlayDateTime"]
	 *           ["AltHrs3"]
	 */
    public function test_DetailDataTable($stat) {
		$dataset=makeStatDataSet("All",$stat);
		$statrow=getStatRow("All",$stat);
		
        $this->assertisString(DetailDataTable($dataset,$statrow));
	}

	/**
	 * @large
	 * @covers getOnlyValues
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
	 * @uses getTimeLeft
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses makeStatDataSet
	 * @uses methodTranslator
	 * @uses objectTranslator
	 * @uses regroupArray
	 * @uses timeduration
	 * @testWith ["firstPlayDateTime"]
	 *           ["Review"]
	 *           ["Altperhrbeat"]
	 *           ["AltHrs3"]
	 *           ["AchievementsPct"]
	 */
    public function test_getOnlyValues($stat) {
		$statrow=makeStatDataSet("All",$stat);
        $this->assertisArray(getOnlyValues($statrow,$stat));
	}	
}