<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/calculations.class.php";

/**
 * @group pageclass
 * @group calculationsPage
 */
class calculations_Test extends testprivate {
	/**
	 * @small
	 * @covers calculationsPage::buildHtmlBody
	 * @covers calculationsPage::__construct
	 * @uses Games 
	 * @uses PriceCalculation
	 * @uses calculationsPage
	 * @uses Page
	 * @uses boolText
	 * @uses timeduration
	 * @testWith ["Default"]
	 *           ["Custom"]
	 */
	public function test_outputHtml($fav) {
		$page = new calculationsPage();
		
		$_GET['fav']=$fav;
		$_GET['col'] = "Title"; 
		$_GET['sort'] = "Title";
		$_GET['hide'] = "Want,lt,4";

		$calculations[1] = array(
			"Title" => "title",
			"lastplaySort" => 1,
			"Status" => "Active",
			"Want" => 4,
			"Game_ID" => 1,
			"ParentGameID" => 1,
			"TimeToBeat" => 1,
			"PrintBundles" => "",
			"Metascore" => 1,
			"UserMetascore" => 1,
			"allKeywords" => "",
			"MetascoreID" => 1,
			"LaunchDate" => date_create(),
			"MSRP" => 1,
			"SteamAchievements" => 1,
			"HistoricLow" => 1,
			"Paid" => 1,
			"SteamCards" => 1,
			"TimeToBeatLink2" => "",
			"SalePrice" => 1,
			"totalHrs" => 1,
			"TimeLeftToBeat" => 1,
			"MetascoreLinkCritic" => 1,
			"MetascoreLinkUser" => 1,
			"GrandTotal" => 1,
			"LaunchPriceObj" => new PriceCalculation(1,2),
			"DateUpdated" => "",
			"lastplay" => "",
			"MSRPLess2" => 1,
			"CurrentLess2" => 1,
			"HistoricLess2" => 1,
			"PaidLess2" => 1,
			"SaleLess2" => 1,
			"AltLess2" => 1,
			"LaunchHrsNext1" => 1,
			"MSRPHrsNext1" => 1,
			"CurrentHrsNext1" => 1,
			"HistoricHrsNext1" => 1,
			"PaidHrsNext1" => 1,
			"SaleHrsNext1" => 1,
			"AltHrsNext1" => 1,
			"LaunchHrsNext2" => 1,
			"MSRPHrsNext2" => 1,
			"CurrentHrsNext2" => 1,
			"HistoricHrsNext2" => 1,
			"PaidHrsNext2" => 1,
			"SaleHrsNext2" => 1,
			"AltHrsNext2" => 1
		);

		$dataStub = $this->createStub(dataSet::class);
		$dataStub->method('getCalculations')
				 ->willReturn($calculations);

		$property = $this->getPrivateProperty( 'chartdataPage', 'data' );
		$property->setValue( $page, $dataStub );
		
		$result = $page->buildHtmlBody();
		
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @testdox defaultFilter()
	 * @covers calculationsPage::defaultFilter
	 * @uses calculationsPage
	 */
	public function test_defaultFilter() {
		$page = new calculationsPage();
		
		$method = $this->getPrivateMethod( 'calculationsPage', 'defaultFilter' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertIsArray($result);
	}
	
	/**
	 * @small
	 * @testdox allColumnsFilter()
	 * @covers calculationsPage::allColumnsFilter
	 * @uses calculationsPage
	 */
	public function test_allColumnsFilter() {
		$page = new calculationsPage();
		
		$method = $this->getPrivateMethod( 'calculationsPage', 'allColumnsFilter' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertIsArray($result);
	}
	
	/**
	 * @small
	 * @testdox showgame()
	 * @covers calculationsPage::showgame
	 * @uses calculationsPage
	 */
	public function test_showgame() {
		$page = new calculationsPage();
		
		$filter['HideRows'][] = array('operator' => "gt", 'field' => 'one', 'value' => 1);
		$filter['HideRows'][] = array('operator' => "gte", 'field' => 'one', 'value' => 1);
		$filter['HideRows'][] = array('operator' => "eq", 'field' => 'one', 'value' => 2);
		$filter['HideRows'][] = array('operator' => "lte", 'field' => 'one', 'value' => 3);
		$filter['HideRows'][] = array('operator' => "lt", 'field' => 'one', 'value' => 3);
		$filter['HideRows'][] = array('operator' => "ne", 'field' => 'one', 'value' => 1);
		
		$game=array('one'=>2);
		
		$method = $this->getPrivateMethod( 'calculationsPage', 'showgame' );
		$result = $method->invokeArgs( $page,array($filter,$game) );
		$this->assertEquals($result,false);
	}
	
	/**
	 * @small
	 * @testdox customFilter()
	 * @covers calculationsPage::customFilter
	 * @uses calculationsPage
	 * @testWith ["PurchaseDate",1]
	 *           ["LaunchDateValue",0]
	 *           ["other",4]
	 */
	public function test_customFilter($sortby,$sordir) {
		$page = new calculationsPage();
		
		$getdata = array('col' => "one", 
			'sort' => $sortby,
			'dir' => $sordir,
			'hide' => "1,2,3");
		
		$method = $this->getPrivateMethod( 'calculationsPage', 'customFilter' );
		$result = $method->invokeArgs( $page,array($getdata) );
		$this->assertIsArray($result);
	}
	
	/**
	 * @small
	 * @testdox sortCalculations()
	 * @covers calculationsPage::sortCalculations
	 * @uses calculationsPage
	 * @testWith ["PurchaseDate"]
	 *           ["LaunchDate"]
	 *           ["LastPlayORPurchase"]
	 *           ["other"]
	 */
	public function test_sortCalculations($sortby) {
		$page = new calculationsPage();
		
		$calculations = array(
			array("PurchaseDate"=> date_create(), 
				'AddedDateTime' => date_create(),
				"LaunchDate" => date_create(),
				"LastPlayORPurchase" => date("Y-m-d H:i:s"),
				"other" => 1
				)
		);
		
		$method = $this->getPrivateMethod( 'calculationsPage', 'sortCalculations' );
		$result = $method->invokeArgs( $page,array($calculations,$sortby,SORT_DESC) );
		$this->assertIsArray($result);
	}	

	/**
	 * @small
	 * @testdox makeGameRow()
	 * @covers calculationsPage::makeGameRow
	 * @uses calculationsPage
	 * @uses PriceCalculation
	 * @uses boolText
	 * @uses timeduration
	 */
	public function test_makeGameRow() {
		$page = new calculationsPage();
		
		$Columns = array(
			"Game_ID",
			"Title",
			"TitleEdit",
			"Series",
			"OtherLibrary",
			"ParentGame",
			"All Bundles",
			"Platforms",
			"Keywords",
			"LaunchDate",
			"Review",
			"AltLess1",
			"PurchaseDate",
			"Achievements",
			"Cards",
			"TimeToBeat",
			"Metascore",
			"MetaUser",
			"Hours",
			"GrandTotal",
			"AchievementsEarned",
			"Status",
			"LastPlayORPurchase",
			"TimeLeftToBeat",
			"LaunchLess2"
		);
		$game=array(
			"Game_ID"=>1,
			"Title"=>2,
			"Series"=>3,
			"OtherLibrary"=>false,
			"ParentGameID"=>4,
			'PrintBundles'=>"5",
			"Platforms"=>"6",
			'allKeywords'=>"7",
			"LaunchDate"=>date_create(),
			"Review"=>8,
			"AltLess1"=>9,
			'AddedDateTime'=>date_create(),
			'SteamAchievements'=>10,
			'SteamCards'=>11,
			'TimeToBeatLink2'=>12,
			'MetascoreLinkCritic'=>13,
			'MetascoreLinkUser'=>14,
			'totalHrs'=>15,
			"GrandTotal"=>16,
			'Achievements'=>17,
			"Status"=>"19",
			"LastPlayORPurchase"=>"20",
			"TimeLeftToBeat"=>21,
			'LaunchPriceObj'=>new PriceCalculation(1,2)
		);
		
		$method = $this->getPrivateMethod( 'calculationsPage', 'makeGameRow' );
		$result = $method->invokeArgs( $page,array($game,$Columns) );
		$this->assertIsString($result);
	}
	
	/**
	 * @small
	 * @testdox totalCounts()
	 * @covers calculationsPage::totalCounts
	 * @uses calculationsPage
	 */
	public function test_totalCounts() {
		$page = new calculationsPage();
		
		$counters = array(
			'data'=> array(3,1,2,5.5,true),
			'countall'=>5,
			'Mode'=>0
		);
		
		$method = $this->getPrivateMethod( 'calculationsPage', 'totalCounts' );
		$result = $method->invokeArgs( $page,array($counters) );
		$this->assertIsArray($result);
	}

	/**
	 * @small
	 * @testdox totalCounts() empty
	 * @covers calculationsPage::totalCounts
	 * @uses calculationsPage
	 */
	public function test_totalCounts_empty() {
		$page = new calculationsPage();
		
		$counters = array(
			'data'=> array(""),
			'countall'=>2,
			'Mode'=>0
		);
		
		$method = $this->getPrivateMethod( 'calculationsPage', 'totalCounts' );
		$result = $method->invokeArgs( $page,array($counters) );
		$this->assertIsArray($result);
	}

	/**
	 * @small
	 * @testdox totalCounts() date
	 * @covers calculationsPage::totalCounts
	 * @uses calculationsPage
	 */
	public function test_totalCounts_date() {
		$page = new calculationsPage();
		
		$counters = array(
			'data'=> array(date_create(),date_create()),
			'countall'=>2,
			'Mode'=>0
		);
		
		$method = $this->getPrivateMethod( 'calculationsPage', 'totalCounts' );
		$result = $method->invokeArgs( $page,array($counters) );
		$this->assertIsArray($result);
	}
}