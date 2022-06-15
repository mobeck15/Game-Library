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
	 * @small
	 * @testdox MakeRecord()
	 * @covers addhistoryPage::MakeRecord
	 * @uses addhistoryPage
	 * @uses CurlRequest
	 * @uses SteamAPI
	 * @uses dataAccess
	 * @uses regroupArray
	 */
	public function test_MakeRecord() {
		$page = new addhistoryPage();
        $dataobject = $this->createStub(dataAccess::class);
		$dataobject->method('getHistoryRecord') 
			->willReturn(["Timestamp"=>"2014-09-05 18:46:58","Status"=>"", "Review"=>"3"]);
		$dataobject->method('getStatusList') 
			->willReturn([["Status"=>"Active"],["Status"=>"Broken"],["Status"=>"Done"],["Status"=>"Inactive"],["Status"=>"Never"],["Status"=>"On Hold"],["Status"=>"Unplayed"]]);
		$pagedata = $this->getPrivateProperty( 'addhistoryPage', 'dataAccessObject' );
		$pagedata->setValue( $page , $dataobject );

		$updatelist["GameID"]="555";
		$updatelist['Time']="23.32";
		$gameIndex[555]=1;
		$games[1]['Title']="Thegame";
		$games[1]['SteamID']="20";
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'gameIndex' );
		$maxID->setValue( $page , $gameIndex );
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'games' );
		$maxID->setValue( $page , $games );
		
        $stub = $this->createStub(SteamAPI::class);
		$schema = array(
			'game'=>array(
				'availableGameStats'=>array(
					'achievements'=>array(
						array(
							"name"=>"name1",
							"displayName"=>"Name 1"
						)
					)
				)
			)
		);
		$achievements = array(
			'playerstats'=>array(
				'achievements'=>array(
					array(
						"achieved"=>1,
						"unlocktime"=>1500000000,
						"apiname"=>"name1"
					)
				)
			)
		);
		
		$map = [
			['GetSchemaForGame', $schema ],
			['GetPlayerAchievements', $achievements]
		];
        $stub->method('GetSteamAPI') 
             ->will($this->returnValueMap($map));
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'steamAPI' );
		$maxID->setValue( $page , $stub );
		
		$this->assertisString($page->MakeRecord($updatelist,1));
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

		$stats=array('playerstats'=>array('gameName'=>"game name 1"));
		$recent=array('response'=>array('games'=>array(
			"appid"=> "20",
			"name"=>  "Half-Life 2: Update",
			"playtime_2weeks"=>  9,
			"playtime_forever"=>  33,
			"img_icon_url"=>  "adbeb780aad083eb92f3fccce82e9f54ab060867",
			"playtime_windows_forever"=>  9,
			"playtime_mac_forever"=>  0,
			"playtime_linux_forever"=>  0
		)));
        $stub = $this->createStub(SteamAPI::class);
		$map = [
			['GetUserStatsForGame', $stats ],
			['GetRecentlyPlayedGames', $recent ]
		];
        $stub->method('GetSteamAPI') 
             ->will($this->returnValueMap($map));
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'steamAPI' );
		$maxID->setValue( $page , $stub );
		
		$this->assertisString($page->steamMode());
	}

	/**
	 * @Small
	 * @testdox getSteamTableData()
	 * @covers addhistoryPage::getSteamTableData
	 * @uses addhistoryPage
	 * @uses timeduration
	 * @testWith [20]
	 *           [10]
	 */
	public function test_getSteamTableData($appid) {
		$page = new addhistoryPage();

		$games[1]['Title']="Thegame";
		$games[1]['SteamID']="20";
		$games[1]['Game_ID']="1";
		$games[1]['GrandTotal']="5";
		$steamindex[20]=1;
		
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'games' );
		$maxID->setValue( $page , $games );
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'steamindex' );
		$maxID->setValue( $page , $steamindex );

		$stats=array('playerstats'=>array('gameName'=>"game name 1"));
        $stub = $this->createStub(SteamAPI::class);
		$map = [
			['GetUserStatsForGame', $stats ]
		];
        $stub->method('GetSteamAPI') 
             ->will($this->returnValueMap($map));
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'steamAPI' );
		$maxID->setValue( $page , $stub );
		
		$row=array(
			"appid"=>  $appid,
			"name"=>  "Half-Life 2: Update",
			"playtime_2weeks"=>  9,
			"playtime_forever"=>  33,
			"img_icon_url"=>  "adbeb780aad083eb92f3fccce82e9f54ab060867",
			"playtime_windows_forever"=>  9,
			"playtime_mac_forever"=>  0,
			"playtime_linux_forever"=>  0
		);

		$lastrecord=array(
			'1'=>array(
				'Time'=>0.65,
				'KeyWords'=>"Minutes",
			)
		);

		$method = $this->getPrivateMethod( 'addhistoryPage', 'getSteamTableData' );
		$result = $method->invokeArgs( $page,array($row, $lastrecord) );
		
		$this->assertisArray($result);
	}
	
	/**
	 * @Small
	 * @testdox getSteamAPI()
	 * @covers addhistoryPage::getSteamAPI
	 * @uses addhistoryPage
	 * @uses CurlRequest
	 * @uses SteamAPI
	 */
	public function test_getSteamAPI() {
		$page = new addhistoryPage();
		
		$method = $this->getPrivateMethod( 'addhistoryPage', 'getSteamAPI' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertThat($result,
			$this->isInstanceOf("SteamAPI")
		);
	}

	/**
	 * @Small
	 * @testdox getGameAttribute()
	 * @covers addhistoryPage::getGameAttribute
	 * @uses addhistoryPage
	 */
	public function test_getGameAttribute() {
		$page = new addhistoryPage();
		
		$games[1]['Title']="Thegame";
		$games[1]['SteamID']="20";
		$games[1]['Game_ID']="1";
		$steamindex[20]=1;
		
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'games' );
		$maxID->setValue( $page , $games );
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'steamindex' );
		$maxID->setValue( $page , $steamindex );

		$method = $this->getPrivateMethod( 'addhistoryPage', 'getGameAttribute' );
		$result = $method->invokeArgs( $page,array(20) );
		$this->assertEquals($result,"1");
	}	

	/**
	 * @Small
	 * @testdox steamAppIDexists()
	 * @covers addhistoryPage::steamAppIDexists
	 * @uses addhistoryPage
	 */
	public function test_steamAppIDexists() {
		$page = new addhistoryPage();
		
		$steamindex[20]=1;
		
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'steamindex' );
		$maxID->setValue( $page , $steamindex );

		$method = $this->getPrivateMethod( 'addhistoryPage', 'steamAppIDexists' );
		$result = $method->invokeArgs( $page,array(20) );
		$this->assertTrue($result);
	}

	/**
	 * @Small
	 * @testdox getUpdateList()
	 * @covers addhistoryPage::getUpdateList
	 * @uses addhistoryPage
	 */
	public function test_getUpdateList() {
		$page = new addhistoryPage();
		
		$games[1]['Title']="Thegame";
		$games[1]['SteamID']="20";
		$games[1]['Game_ID']="1";
		$steamindex[20]=1;
		
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'games' );
		$maxID->setValue( $page , $games );
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'steamindex' );
		$maxID->setValue( $page , $steamindex );

		$row['appid']=20;
		$row['playtime_forever']=60;
		$lastrecord[1]['Time']=2;
		$updatelist=array();
		
		$method = $this->getPrivateMethod( 'addhistoryPage', 'getUpdateList' );
		$result = $method->invokeArgs( $page,array($row,$lastrecord,$updatelist) );
		$this->assertisArray($result);
		$this->assertTrue(count($result)>0);
	}

	/**
	 * @Small
	 * @testdox getRowClass()
	 * @covers addhistoryPage::getRowClass
	 * @uses addhistoryPage
	 * @testWith [20, 1]
	 *           [10, 1]
	 */
	public function test_getRowClass($appid, $time) {
		$page = new addhistoryPage();
		
		$games[1]['Title']="Thegame";
		$games[1]['SteamID']="20";
		$games[1]['Game_ID']="1";
		$steamindex[20]=1;
		
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'games' );
		$maxID->setValue( $page , $games );
		$maxID = $this->getPrivateProperty( 'addhistoryPage', 'steamindex' );
		$maxID->setValue( $page , $steamindex );

		$row['appid']=$appid;
		$row['playtime_forever']=60;
		$lastrecord[1]['Time']=$time;

		$method = $this->getPrivateMethod( 'addhistoryPage', 'getRowClass' );
		$result = $method->invokeArgs( $page,array($row,$lastrecord) );
		$this->assertisString($result);
	}
	
}