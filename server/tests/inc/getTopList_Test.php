<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getTopList.inc.php";

/**
 * @group include
 * @group topList
 */
final class getTopList_Test extends TestCase
{
	private $Connection;
	
 	/**
	 * @large
	 * @CoversNothing
	 * /
    public function test_getTopList_base() {
		$output=getTopList("");
        $this->assertisArray($output);
        $this->assertisArray($output[57]);

		$conn=get_db_connection();
        $this->assertisArray(getTopList("",$conn));
		$conn->close();
	}

	/**
	 * @large
	 * @CoversNothing
	 * /
    public function test_getTopList_Alt() {
		$conn=get_db_connection();
		$data = new dataSet();
		$calculations=$data->getCalculations();
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
		$conn->close();
	}
	
	 /**
	 * @small
	 * @covers TopList::buildTopListArray
	 * @uses TopList
	 * @uses dataSet
	 * @testWith [""]
	 *           ["Series"]
	 *           ["Store"]
	 *           ["Library"]
	 *           ["SteamR10"]
	 *           ["SteamR"]
	 *           ["PYear"]
	 *           ["Keyword"]
	 */
	public function test_getTopList_new($listType) {
		$purchases[0] = array(
				"Title" => "FirstTitle",
				"TransID" => 1,
				"BundleID" => 1,
				"PurchaseDate" => 1,
				"PurchaseTime" => 1,
				"Sequence" => 1,
				"Paid" => 1,
				"Store" => "Steam",
				"ProductsinBunde" => array(
					"0", "1"
				)
			);
		$purchases[1] = $purchases[0];
		$purchases[1]["Title"] = "SecondTitle";
		$purchases[1]["TransID"] = 2;
		$purchases[1]["BundleID"] = 2;

		$calculations[]=array(
				"Title" => "FirstGame",
				"Series" => "FirstSeries",
				"AltSalePrice" => 1,
				"Game_ID" => 0,
				"CountGame" => 1,
				"Playable" => true,
				"Active" => true,
				"LaunchPrice" => 1,
				"MSRP" => 1,
				"HistoricLow" => 1,
				"GrandTotal" => 1,
				"Want" => 1,
				"Paid" => 1,
				"PurchaseDateTime" => new DateTime(),
				"SteamRating" => 1,
				"Status" => "Active",
				"Library" => array("Steam","GOG")
		);
		$calculations[1] = $calculations[0];
		$calculations[1]["Game_ID"] = 1;
		$calculations[1]["Series"] = "FirstSeries";
		
		$data = new dataSet(purchases: $purchases, calculations: $calculations);
		$topListObject=new TopList($data);
		
		$calculations=array();
		$this->assertisArray($topListObject->buildTopListArray($listType,false,$calculations));
	}
	
	/*
	 */	 
	
	/* *
	 * @large
	 * @covers getTopList
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
	 * @uses getTimeLeft
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses timeduration
	 * @uses getGames
	 * @uses get_db_connection
	 * @testWith ["",2]
	 *           ["Keyword", 2]
	 *           ["Series", 2]
	 *           ["Series", 1]
	 *           ["Store", 2]
	 *           ["Library", 2]
	 *           ["SteamR10", 2]
	 *           ["SteamR", 2]
	 *           ["PYear", 2]
	 *           ["LYear", 2]
	 * /
    public function test_getTopList_keywords($group,$size) {
		//ARRANGE
		$conn=get_db_connection();
		$calculations=array(
			array(
				'Game_ID'=>1,
				'Title'=>'TestGame1',
				'Playable'=>true,
				'Paid'=>1,
				'Status'=>"Active",
				'Review'=>1,
				'PurchaseDateTime'=>new DateTime("2021/01/01"),
				'AltSalePrice'=>1,
				'allKeywords'=>"kewords, all of them...",
				'Series'=>"X",
				'DRM'=>"DRM",
				'OS'=>"Windows",
				'Library'=>"TestLib",
				'Review'=>1,
				'Want'=>1,
				'Metascore'=>1,
				'UserMetascore'=>1,
				'SteamRating'=>1,
				'LaunchDate'=>new DateTime("2021/01/01"),
				'CountGame'=>true,
				'LaunchPrice'=>1,
				'MSRP'=>1,
				'HistoricLow'=>1,
				'GrandTotal'=>1,
				'Type'=>"Game",
				'Active'=>true
			),
			array(
				'Game_ID'=>2,
				'Title'=>'TestGame2',
				'Playable'=>true,
				'Paid'=>1,
				'Status'=>"Active",
				'Review'=>1,
				'PurchaseDateTime'=>new DateTime("2021/01/01"),
				'AltSalePrice'=>1,
				'allKeywords'=>"kewords, all of them...",
				'Series'=>"X",
				'DRM'=>"DRM",
				'OS'=>"Windows",
				'Library'=>array("TestLib"),
				'Review'=>1,
				'Want'=>1,
				'Metascore'=>1,
				'UserMetascore'=>1,
				'SteamRating'=>1,
				'LaunchDate'=>new DateTime("2021/01/01"),
				'CountGame'=>true,
				'LaunchPrice'=>1,
				'MSRP'=>1,
				'HistoricLow'=>1,
				'GrandTotal'=>1,
				'Type'=>"Game",
				'Active'=>true
			),
		);
		$calculations=getCalculations("",$conn);
		
		//ACT
		//ASSERT
        $this->assertisArray(getTopList($group,$conn,$calculations,$size));
		$conn->close();
	}	/* */	
}