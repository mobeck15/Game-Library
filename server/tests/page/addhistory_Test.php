<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/addhistory.class.php";
//require_once $GLOBALS['rootpath']."\inc\dataAccess.class.php";

/**
 * @testdox addhistory_Test.php testing addhistory.class.php
 * @group pageclass
 * @group addhistory
 */
class addhistory_Test extends testprivate {
	/**
	 * @small
	 * @testdox __construct & buildHtmlBody
	 * @covers addhistoryPage::buildHtmlBody
	 * @covers addhistoryPage::__construct
	 * @uses Get_Header
	 * @uses boolText
	 * @uses dataAccess
	 * @uses get_db_connection
	 * @uses get_navmenu
	 */
	public function test_outputHtml() {
		$_SERVER['QUERY_STRING']="";
		$page = new addhistoryPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @testdox captureInsert()
	 * @covers addhistoryPage::captureInsert
	 * @uses addhistoryPage
	 */
	public function test_outputHtml_update() {
		$page = new addhistoryPage();
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'maxID' );
		$maxID->setValue( $page , 1 );

		$dataAccessMock = $this->createMock(dataAccess::class);
		$dataAccessMock->expects($this->once())
                       ->method('updateHistory')
                       ->with($this->anything(),$this->anything());
		
		//$page->attach($dataAccessMock);
		
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'dataAccessObject' );
		$maxID->setValue( $page , $dataAccessMock );
		
		//$datarow[1]["update"]= "on";
		$datarow[1]["id"]=  "9406";
		//$datarow[1]["ProductID"]=  "3407";
		//$datarow[1]["Title"]= "SOULCALIBUR VI";
		//$datarow[1]["System"]= "Steam";
		//$datarow[1]["Data"]=  "New Total";
		//$datarow[1]["hours"]=  "82";
		//$datarow[1]["notes"]=  "";
		//$datarow[1]["source"]= "Game Library 5";
		//$datarow[1]["achievements"]=  "0";
		//$datarow[1]["status"]=  "Inactive";
		//$datarow[1]["review"]=  "1";
		//$datarow[1]["minutes"]=  "on";
		
		$timestamp=date("Y-m-d H:i:s");
		
		$result = $page->captureInsert($datarow,$timestamp);
	}

	/**
	 * @small
	 * @testdox captureInsert()
	 * @covers addhistoryPage::captureInsert
	 * @uses addhistoryPage
	 */
	public function test_outputHtml_insert() {
		$page = new addhistoryPage();
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'maxID' );
		$maxID->setValue( $page , 1 );

		$dataAccessMock = $this->createMock(dataAccess::class);
		$dataAccessMock->expects($this->once())
                       ->method('insertHistory')
                       ->with($this->anything(),$this->anything());
		
		//$page->attach($dataAccessMock);
		
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'dataAccessObject' );
		$maxID->setValue( $page , $dataAccessMock );
		
		//$datarow[1]["update"]= "on";
		//$datarow[1]["id"]=  "9406";
		$datarow[1]["ProductID"]=  "3407";
		//$datarow[1]["Title"]= "SOULCALIBUR VI";
		//$datarow[1]["System"]= "Steam";
		//$datarow[1]["Data"]=  "New Total";
		//$datarow[1]["hours"]=  "82";
		//$datarow[1]["notes"]=  "";
		//$datarow[1]["source"]= "Game Library 5";
		//$datarow[1]["achievements"]=  "0";
		//$datarow[1]["status"]=  "Inactive";
		//$datarow[1]["review"]=  "1";
		//$datarow[1]["minutes"]=  "on";
		
		$timestamp=date("Y-m-d H:i:s");
		
		$result = $page->captureInsert($datarow,$timestamp);
	}
	
	/**
	 * @small
	 * @testdox UpdateList()
	 * @covers addhistoryPage::UpdateList
	 * @uses addhistoryPage
	 * @uses CurlRequest
	 * @uses SteamAPI
	 * @uses dataAccess
	 */
	public function test_UpdateList() {
		$page = new addhistoryPage();
		$dataobject= new dataAccess();
		$pagedata = $this->getPrivateProperty( 'addhistoryPage', 'dataAccessObject' );
		$pagedata->setValue( $page , $dataobject );
		
		$updatelist[1]["GameID"]="200";
		$updatelist[1]['Time']="23.32";
		$gameIndex[200]=1;
		$games[1]['Title']="Thegame";
		$games[1]['SteamID']="20";
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'gameIndex' );
		$maxID->setValue( $page , $gameIndex );
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'games' );
		$maxID->setValue( $page , $games );
		
		$this->assertisString($page->UpdateList($updatelist));
	}

	/**
	 * @large
	 * @testdox steamMode()
	 * @covers addhistoryPage::steamMode
	 * @uses addhistoryPage
	 * @uses CurlRequest
	 * @uses SteamAPI
	 * @uses dataAccess
	 * @uses Games
	 * @uses PriceCalculation
	 * @uses Purchases
	 * @uses combinedate
	 * @uses daysSinceDate
	 * @uses getActivityCalculations
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
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses regroupArray
	 * @uses timeduration
	 */
	public function test_steamMode() {
		$page = new addhistoryPage();
		$dataobject= new dataAccess();
		$pagedata = $this->getPrivateProperty( 'addhistoryPage', 'dataAccessObject' );
		$pagedata->setValue( $page , $dataobject );
		
		$this->assertisString($page->steamMode());
	}
}