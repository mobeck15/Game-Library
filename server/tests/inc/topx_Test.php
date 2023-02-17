<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath'].'\tests\inc\testprivate.inc.php';
require_once $GLOBALS['rootpath'].'\inc\topx.class.php';

/**
 * @group include
 * @group classtest
 * @group topx
 */
final class topx_Test extends testprivate
{
	/**
	 * @small
	 * @covers topx::__construct
	 */
	public function test_constructor() {
		$topxObject=new topx();
		
		$this->assertisObject($topxObject);
	}
	
	/**
	 * @small
	 * @covers topx::displaytop
	 * @uses topx
	 * @uses timeduration
	 * @testWith ["Active"]
	 *			 ["All"]
	 */
	public function test_displaytop($mode) {
		$calculations=array(
			1=>array("Status"=>"Active","Launchperhr"=>1,"Title"=>"Game1","LaunchHrsNext1"=>4,"LaunchHrsNext2"=>9),
			2=>array("Status"=>"Active","Launchperhr"=>2,"Title"=>"Game2","LaunchHrsNext1"=>5,"LaunchHrsNext2"=>8),
		);
		$topxObject=new topx($calculations);
		
		$gameids=array(1,2);
		
		$stat = array(
			"stat"=>"Launchperhr",
			"alt"=> array("LaunchHrsNext1"),
			"sortdir"=>SORT_DESC,
			"filter"=>",Launchperhr,eq,0",
			"header"=>"Launch Price $/hr",
		);
		
		$this->assertisString($topxObject->displaytop($gameids,$stat,$mode));
	}
	
	/**
	 * @small
	 * @covers topx::setfilter
	 * @uses topx::__construct
	 */
	public function test_setfilter() {
		$topxObject=new topx();
		
		$topxObject->setfilter("FILTER");
		
		$property = $this->getPrivateProperty( 'topx', 'filter' );
		$result = $property->getValue( $topxObject );
		
		$this->assertEquals("FILTER",$result);
	}
	
	/**
	 * @small
	 * @covers topx::setxvalue
	 * @uses topx::__construct
	 */
	public function test_setxvalue() {
		$topxObject=new topx();
		
		$topxObject->setxvalue(10);
		
		$property = $this->getPrivateProperty( 'topx', 'xvalue' );
		$result = $property->getValue( $topxObject );
		
		$this->assertEquals(10,$result);
	}
	
	/**
	 * @small
	 * @covers topx::statlist2
	 * @uses topx::__construct
	 */
	public function test_statlist() {
		$topxObject=new topx();
		
		$result = $topxObject->statlist2();
		
		$this->assertisArray($result);
	} 

	/**
	 * @small
	 * @covers topx::gettopx
	 * @uses topx
	 * @testWith ["GrandTotal"]
	 *			 ["LastPlayORPurchase"]
	 */
	public function test_gettopx($stat) {
		$topxObject=new topx();
		
		$statArray = array(
			"stat"=>$stat,
			"alt"=> array("LaunchHrsNext1"),
			"sortdir"=>SORT_DESC,
			"filter"=>",Launchperhr,eq,0",
			"header"=>"Launch Price $/hr",
		);
		
		$calculations=$this->tesdata_Calculations();
		
		$property = $this->getPrivateProperty( 'topx', 'calculations' );
		$property->setValue( $topxObject, $calculations );
		
		$result = $topxObject->gettopx($statArray);
		
		$this->assertisArray($result);
	}
	
	/**
	 * @small
	 * @covers topx::statformat
	 * @uses timeduration
	 * @uses topx::__construct
	 * @testWith [2,"AltLess1","$2.00"]
	 *			 [2,"TimeLeftToBeat","2:00:00"]
	 *			 [300,"GrandTotal","0:05:00"]
	 *			 [2,"AchievementsPct","2.00%"]
	 */
	public function test_statformat($value, $statname , $expectedresult) {
		$topxObject=new topx();
		
		$method = $this->getPrivateMethod( 'topx', 'statformat' );
		$result = $method->invokeArgs($topxObject, array( $value, $statname ) );
		
		$this->assertEquals($expectedresult,$result);
	}
	
	/**
	 * @small
	 * @covers topx::sortbystat
	 * @uses topx::__construct
	 * @testWith ["PurchaseDate",[0,2,3,1]]
	 *			 ["LaunchDate",[2,1,3,0]]
	 *			 ["LastPlayORPurchase",[1,3,2,0]]
	 *			 ["Paid",[0,2,3,1]]
	 */
	public function test_sortbystat($stat, $sortrow) {
		$topxObject=new topx();
	
		$statArray = array(
			"stat"=>$stat,
			"alt"=> array("LaunchHrsNext1"),
			"sortdir"=>SORT_DESC,
			"filter"=>",Launchperhr,eq,0",
			"header"=>"Launch Price $/hr",
		);

		$calculations=$this->tesdata_Calculations();
		
		$property = $this->getPrivateProperty( 'topx', 'calculations' );
		$property->setValue( $topxObject, $calculations );
		
		$method = $this->getPrivateMethod( 'topx', 'sortbystat' );
		$sortdir=SORT_ASC;
		$result = $method->invokeArgs($topxObject, array( $statArray, $sortdir ) );
		
		foreach ($result as $key => $row) {
			$this->assertEquals($calculations[$sortrow[$key]],$row);
		}
	}
	
	public function data_simpleCalcArray() {
		
	}
	
	/**
	 * @small
	 * @covers topx::hideLoop
	 * @uses topx::__construct
	 * @testWith [2,"gt",1,true]
	 *			 [1,"gt",2,false]
	 *			 [1,"gt",1,true]
	 *			 [2,"gte",1,true]
	 *			 [1,"gte",2,false]
	 *			 [1,"gte",1,false]
	 *			 [2,"eq",1,true]
	 *			 [1,"eq",2,true]
	 *			 [1,"eq",1,false]
	 *			 [2,"lte",1,false]
	 *			 [1,"lte",2,true]
	 *			 [1,"lte",1,false]
	 *			 [2,"lt",1,false]
	 *			 [1,"lt",2,true]
	 *			 [1,"lt",1,true]
	 *			 [2,"ne",1,false]
	 *			 [1,"ne",2,false]
	 *			 [1,"ne",1,true]
	 */
	public function test_hideLoop($filtervalue,$operator,$gamevalue,$expectedresult) {
		$gamedata=array("LaunchDate"=>$gamevalue);
		$filter=array(array("field"=>"LaunchDate","operator"=>$operator,"value"=>$filtervalue));
		
		$topxObject=new topx();
		
		$method = $this->getPrivateMethod( 'topx', 'hideLoop' );
		$result = $method->invokeArgs($topxObject, array( $gamedata, $filter ) );
		
		$this->assertEquals($expectedresult,$result);
	}
	
	/**
	 * @small
	 * @covers topx::parseFilter
	 * @uses topx
	 */
	public function test_parseFilter() {
		$filterstring="Playable,eq,0,Status,eq,Never,Status,eq,Done,Status,eq,Broken,Review,eq,1,Review,eq,2";
		$filterarray=array(
			array("field"=>"Playable","operator"=>"eq","value"=>"0"),
			array("field"=>"Status","operator"=>"eq","value"=>"Never"),
			array("field"=>"Status","operator"=>"eq","value"=>"Done"),
			array("field"=>"Status","operator"=>"eq","value"=>"Broken"),
			array("field"=>"Review","operator"=>"eq","value"=>"1"),
			array("field"=>"Review","operator"=>"eq","value"=>"2"),
		);
		$topxObject=new topx();
		
		$method = $this->getPrivateMethod( 'topx', 'parseFilter' );
		$result = $method->invokeArgs($topxObject, array( $filterstring ) );
		
		//$result=$topxObject->parseFilter($filterstring);
		$this->assertisArray($result);
		$this->assertEquals($filterarray,$result);
	}
	
	/**
	 * @small
	 * @covers topx::getTotalRanks
	 * @uses topx
	 * @uses reIndexArray
	 */
	public function test_getTotalRanks() {
		$topxObject = new topx(reIndexArray($this->tesdata_Calculations(),"Game_ID"));

		$result=$topxObject->getTotalRanks();

		$this->assertisArray($result);
	}
	
	/**
	 * @small
	 * @covers topx::makeDetailTable
	 * @uses topx
	 * @uses reIndexArray
	 * @uses timeduration
	 */
	public function test_makeDetailTable() {
		$topxObject = new topx(reIndexArray($this->tesdata_Calculations(),"Game_ID"));
		$totalranks=[["id"=>1,"metastatname"=>"GrandTotal","ranks"=>.3],["id"=>2,"metastatname"=>"GrandTotal","ranks"=>1.7]];

		$result=$topxObject->makeDetailTable($totalranks);

		$this->assertisString($result);
	}	
	
	/**
	 * @small
	 * @covers topx::makeSourceCloud
	 * @uses topx
	 * @uses reIndexArray
	 * @uses timeduration
	 */
	public function test_makeSourceCloud() {
		$topxObject = new topx(reIndexArray($this->tesdata_Calculations(),"Game_ID"));

		$result=$topxObject->makeSourceCloud();

		$this->assertisString($result);
	}	
	
	public function tesdata_Calculations(){
		return array(
			0=>array(
				"AddedDateTime" => new DateTime('2011-01-10T15:03:01.012345Z'), 
				"LaunchDate" => new DateTime('2011-01-13T15:03:01.012345Z'), 
				"LastPlayORPurchase" => "2011-01-13 3:03:01 PM", 
				"Paid" => 12.50,
				"GrandTotal" => 12.50, "TimeLeftToBeat" => 1,
				"Playable" => true,
				"Status" => "Active",
				"Review" => 4,
				"Game_ID" => 1,
				"Title" => "Game 1",
				"DaysSinceLastPlayORPurchase" => 10, "Want" => 1,
				"SaleLess1" => 1.23,	"SaleLess2" => 2.34,	"Saleperhr" => 3.45,	"SaleHrsNext2" => 1, "SaleHrsNext1" => 1, 
				"AltLess1" => 1,	"AltLess2" => 1,	"Altperhr" => 1,	"AltHrsNext2" => 1, "AltHrsNext1" => 1, 
				"LaunchLess1" => 1,	"LaunchLess2" => 1,	"Launchperhr" => 1,	"LaunchHrsNext2" => 1, "LaunchHrsNext1" => 1,
				"MSRPLess1" => 1,	"MSRPLess2" => 1,	"MSRPperhr" => 1,	"MSRPHrsNext2" => 1, "MSRPHrsNext1" => 1, 
				"HistoricLess1" => 1,	"HistoricLess2" => 1,	"Historicperhr" => 1,	"HistoricHrsNext2" => 1, "HistoricHrsNext1" => 1,
				"PaidLess1" => 1,	"PaidLess2" => 1,	"Paidperhr" => 1,	"PaidHrsNext2" => 1, "PaidHrsNext1" => 1,
				"AchievementsLeft" => 1,	"AchievementsPct"=>89,	"Metascore" => 1,	"UserMetascore"=> 1,	"SteamRating" => 1,	"Review"=>3.
			),
			1=>array(
				"AddedDateTime" => new DateTime('2011-01-13T15:03:01.012345Z'), 
				"LaunchDate" => new DateTime('2011-01-11T15:03:01.012345Z'), 
				"LastPlayORPurchase" => "2011-01-10 3:03:01 PM", 
				"Paid" => 12.53,
				"GrandTotal" => 12.53, "TimeLeftToBeat" => 1,
				"Playable" => true,
				"Status" => "Active",
				"Review" => 4,
				"Game_ID" => 2,
				"Title" => "Game 2",
				"DaysSinceLastPlayORPurchase" => 10, "Want" => 1,
				"SaleLess1" => 1.23,	"SaleLess2" => 2.34,	"Saleperhr" => 3.45,	"SaleHrsNext2" => 1, "SaleHrsNext1" => 1, 
				"AltLess1" => 1,	"AltLess2" => 1,	"Altperhr" => 1,	"AltHrsNext2" => 1, "AltHrsNext1" => 1, 
				"LaunchLess1" => 1,	"LaunchLess2" => 1,	"Launchperhr" => 1,	"LaunchHrsNext2" => 1, "LaunchHrsNext1" => 1,
				"MSRPLess1" => 1,	"MSRPLess2" => 1,	"MSRPperhr" => 1,	"MSRPHrsNext2" => 1, "MSRPHrsNext1" => 1, 
				"HistoricLess1" => 1,	"HistoricLess2" => 1,	"Historicperhr" => 1,	"HistoricHrsNext2" => 1, "HistoricHrsNext1" => 1,
				"PaidLess1" => 1,	"PaidLess2" => 1,	"Paidperhr" => 1,	"PaidHrsNext2" => 1, "PaidHrsNext1" => 1,
				"AchievementsLeft" => 1,	"AchievementsPct"=>89,	"Metascore" => 1,	"UserMetascore"=> 1,	"SteamRating" => 1,	"Review"=>3.
			),
			2=>array(
				"AddedDateTime" => new DateTime('2011-01-11T15:03:01.012345Z'), 
				"LaunchDate" => new DateTime('2011-01-10T15:03:01.012345Z'), 
				"LastPlayORPurchase" => "2011-01-12 3:03:01 PM", 
				"Paid" => 12.51,
				"GrandTotal" => 12.51, "TimeLeftToBeat" => 1,
				"Playable" => true,
				"Status" => "Active",
				"Review" => 4,
				"Game_ID" => 3,
				"Title" => "Game 3",
				"DaysSinceLastPlayORPurchase" => 10, "Want" => 1,
				"SaleLess1" => 1.23,	"SaleLess2" => 2.34,	"Saleperhr" => 3.45,	"SaleHrsNext2" => 1, "SaleHrsNext1" => 1, 
				"AltLess1" => 1,	"AltLess2" => 1,	"Altperhr" => 1,	"AltHrsNext2" => 1, "AltHrsNext1" => 1, 
				"LaunchLess1" => 1,	"LaunchLess2" => 1,	"Launchperhr" => 1,	"LaunchHrsNext2" => 1, "LaunchHrsNext1" => 1,
				"MSRPLess1" => 1,	"MSRPLess2" => 1,	"MSRPperhr" => 1,	"MSRPHrsNext2" => 1, "MSRPHrsNext1" => 1, 
				"HistoricLess1" => 1,	"HistoricLess2" => 1,	"Historicperhr" => 1,	"HistoricHrsNext2" => 1, "HistoricHrsNext1" => 1,
				"PaidLess1" => 1,	"PaidLess2" => 1,	"Paidperhr" => 1,	"PaidHrsNext2" => 1, "PaidHrsNext1" => 1,
				"AchievementsLeft" => 1,	"AchievementsPct"=>89,	"Metascore" => 1,	"UserMetascore"=> 1,	"SteamRating" => 1,	"Review"=>3.
			),
			3=>array(
				"AddedDateTime" => new DateTime('2011-01-12T15:03:01.012345Z'), 
				"LaunchDate" => new DateTime('2011-01-12T15:03:01.012345Z'), 
				"LastPlayORPurchase" => "2011-01-11 3:03:01 PM", 
				"Paid" => 12.52,
				"GrandTotal" => 12.52, "TimeLeftToBeat" => 1,
				"Playable" => true,
				"Status" => "Active",
				"Review" => 4,
				"Game_ID" => 4,
				"Title" => "Game 4",
				"DaysSinceLastPlayORPurchase" => 10, "Want" => 1,
				"SaleLess1" => 1.23,	"SaleLess2" => 2.34,	"Saleperhr" => 3.45,	"SaleHrsNext2" => 1, "SaleHrsNext1" => 1, 
				"AltLess1" => 1,	"AltLess2" => 1,	"Altperhr" => 1,	"AltHrsNext2" => 1, "AltHrsNext1" => 1, 
				"LaunchLess1" => 1,	"LaunchLess2" => 1,	"Launchperhr" => 1,	"LaunchHrsNext2" => 1, "LaunchHrsNext1" => 1,
				"MSRPLess1" => 1,	"MSRPLess2" => 1,	"MSRPperhr" => 1,	"MSRPHrsNext2" => 1, "MSRPHrsNext1" => 1, 
				"HistoricLess1" => 1,	"HistoricLess2" => 1,	"Historicperhr" => 1,	"HistoricHrsNext2" => 1, "HistoricHrsNext1" => 1,
				"PaidLess1" => 1,	"PaidLess2" => 1,	"Paidperhr" => 1,	"PaidHrsNext2" => 1, "PaidHrsNext1" => 1,
				"AchievementsLeft" => 1,	"AchievementsPct"=>89,	"Metascore" => 1,	"UserMetascore"=> 1,	"SteamRating" => 1,	"Review"=>3.
			),
		);
	}
}