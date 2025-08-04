<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getTopList.inc.php";

/**
 * @group include
 */
final class getTopList_Test extends TestCase
{
	private $Connection;
	
    /**
     * @beforeClass
     */
	protected function makeconnection(): void
    {
		$this->Connection=get_db_connection();
    }	
	
    /**
     * @afterClass
     */
    protected function closeconnection(): void
    {
        $this->Connection->close;
    }
	
	/**
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
	 */
    public function test_getTopList_base() {
		$output=getTopList("");
        $this->assertisArray($output);
        $this->assertisArray($output[57]);

		$conn=get_db_connection();
		//$conn=$this->Connection;
        $this->assertisArray(getTopList("",$conn));
		$conn->close();
	}

	/**
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
	 * @uses dataSet
	 */
    public function test_getTopList_Alt() {
		$conn=get_db_connection();
		//$conn=$this->Connection;
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