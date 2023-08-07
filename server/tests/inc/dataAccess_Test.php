<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath'].'\tests\inc\testprivate.inc.php';
require_once $GLOBALS['rootpath']."\inc\dataAccess.class.php";

/**
 * @group include
 * @group classtest
 * @group getGames
 * @group addhistory
 * @group viewbundle
 * @testdox dataAccess_Test.php testing dataAccess.class.php
 */
final class dataAccess_Test extends testprivate
{
	/**
	 * @small
	 * @covers dataAccess::getConnection
	 * @uses dataAccess
	 * @testdox getConnection()
	 */
	public function test_getConnection() {
		$dataobject= new dataAccess();
		$connection = $dataobject->getConnection();
		$this->assertisObject($connection);

		$connection2 = $dataobject->getConnection();
		$this->assertEquals($connection,$connection2);
	}

	/**
	 * @small
	 * @covers dataAccess::closeConnection
	 * @uses dataAccess
	 * @testdox closeConnection()
	 */
	public function test_closeConnection() {
		$dataobject= new dataAccess();
		$property = $this->getPrivateProperty( 'dataAccess', 'dbConnection' );
		$this->assertNull($property->getValue( $dataobject ));
		
		$connection = $dataobject->getConnection();
		$this->assertNotNull($property->getValue( $dataobject ));
		
		$dataobject->closeConnection();
		$this->assertNull($property->getValue( $dataobject ));
	}

	/**
	 * @small
	 * @covers dataAccess::getGames
	 * @uses dataAccess
	 * @testdox getGames() with no parameters
	 */
	public function test_getGames() {
		$dataobject= new dataAccess();
		$statement=$dataobject->getGames();
		$this->assertisObject($statement);
		$this->assertInstanceOf(PDOStatement::class, $statement);
	}

	/**
	 * @small
	 * @covers dataAccess::getGames
	 * @uses dataAccess
	 * @testdox getGames() one specific game
	 */
	public function test_getGames_One() {
		$dataobject= new dataAccess();
		$statement=$dataobject->getGames(111);
		$this->assertisObject($statement);
		$this->assertInstanceOf(PDOStatement::class, $statement);
	}

	/**
	 * @small
	 * @covers dataAccess::getGames
	 * @uses dataAccess
	 * @testdox getGames() multiple specific games
	 */
	public function test_getGames_Many() {
		$dataobject= new dataAccess();
		$statement=$dataobject->getGames([111,101]);
		$this->assertisObject($statement);
		$this->assertInstanceOf(PDOStatement::class, $statement);
	}

	/**
	 * @small
	 * @covers dataAccess::getPurchases
	 * @uses dataAccess
	 * @testdox getPurchases() with no parameters
	 */
	public function test_getPurchases() {
		$dataobject= new dataAccess();
		$statement=$dataobject->getPurchases();
		$this->assertisObject($statement);
		$this->assertInstanceOf(PDOStatement::class, $statement);
	}

	/**
	 * @small
	 * @covers dataAccess::getItems
	 * @uses dataAccess
	 * @testdox getItems() with no parameters
	 * @group additem
	 */
	public function test_getItems() {
		$dataobject= new dataAccess();
		$statement=$dataobject->getItems();
		$this->assertisObject($statement);
		$this->assertInstanceOf(PDOStatement::class, $statement);
	}

	/**
	 * @small
	 * @covers dataAccess::getItems
	 * @uses dataAccess
	 * @testdox getItems() with no parameters
	 * @group additem
	 */
	public function test_getItems_many() {
		$dataobject= new dataAccess();
		$statement=$dataobject->getItems([1,2]);
		$this->assertisObject($statement);
		$this->assertInstanceOf(PDOStatement::class, $statement);
	}

	/**
	 * @small
	 * @covers dataAccess::getPurchases
	 * @uses dataAccess
	 * @testdox getPurchases() one specific bundle
	 */
	public function test_getPurchases_One() {
		$dataobject= new dataAccess();
		$statement=$dataobject->getPurchases(111);
		$this->assertisObject($statement);
		$this->assertInstanceOf(PDOStatement::class, $statement);
	}
	
	/**
	 * @small
	 * @covers dataAccess::getAllRows
	 * @uses dataAccess
	 * @uses simple_html_dom
	 * @testdox getAllRows() with a key provided
	 */
	public function test_getAllRows() {
		$dataobject= new dataAccess();
		$statement=$dataobject->getPurchases();
		$allrows=$dataobject->getAllRows($statement,"TransID");
		$this->assertisArray($allrows);
		$this->assertisArray($allrows[1069]);
		$this->assertEquals("Not Owned",$allrows[1069]["Title"]);
	}
	
	/**
	 * @small
	 * @covers dataAccess::getAllRows
	 * @uses dataAccess
	 * @testdox getAllRows() no key provided
	 */
	public function test_getAllRows_index() {
		$dataobject= new dataAccess();
		$statement=$dataobject->getPurchases();
		$allrows=$dataobject->getAllRows($statement);
		$this->assertisArray($allrows);
		$this->assertisArray($allrows[0]);
		$this->assertEquals("Not Owned",$allrows[0]["Title"]);
	}
	
	/**
	 * @small
	 * @covers dataAccess::getMaxHistoryId
	 * @uses dataAccess
	 * @testdox getMaxHistoryId()
	 */
	public function test_getMaxHistoryId() {
		$dataobject= new dataAccess();
		$maxID=$dataobject->getMaxHistoryId();
		$this->assertIsNumeric($maxID);
	}
	
	/**
	 * @small
	 * @covers dataAccess::getMaxItemId
	 * @uses dataAccess
	 * @testdox getMaxItemId()
	 */
	public function test_getMaxItemId() {
		$dataobject= new dataAccess();
		$maxID=$dataobject->getMaxItemId();
		$this->assertIsNumeric($maxID);
	}

	/**
	 * @small
	 * @covers dataAccess::getMaxProductId
	 * @uses dataAccess
	 * @testdox getMaxProductId()
	 */
	public function test_getMaxProductId() {
		$dataobject= new dataAccess();
		$maxID=$dataobject->getMaxProductId();
		$this->assertIsNumeric($maxID);
	}

	/**
	 * @small
	 * @covers dataAccess::getMaxTableId
	 * @uses dataAccess
	 * @testdox getMaxTableId() $table
	 * @testWith ["gl_history"]
	 *           ["gl_items"]
	 *           ["gl_products"]
	 *           ["gl_transactions"]
	 */
	public function test_getMaxTableId($table) {
		$dataobject= new dataAccess();
		$maxID=$dataobject->getMaxTableId($table);
		$this->assertIsNumeric($maxID);
	}
	
	/**
	 * @small
	 * @covers dataAccess::getMaxTableId
	 * @uses dataAccess
	 * @testdox getMaxTableId() Error $table
	 * @testWith ["nothing"]
	 */
	public function test_getMaxTableId_error($table) {
		$dataobject= new dataAccess();
		$maxID=$dataobject->getMaxTableId($table);
		$this->assertEquals(false,$maxID);
	}

	/**
	 * @small
	 * @covers dataAccess::isGameStarted
	 * @uses dataAccess
	 * @testdox isGameStarted() false
	 */
	public function test_isGameStarted_false1() {
		$historyArray=['Data'=>'New Total'];
		$dataobject= new dataAccess();
		$result=$dataobject->isGameStarted($historyArray);
		$this->assertFalse($result);
	}

	/**
	 * @small
	 * @covers dataAccess::isGameStarted
	 * @uses dataAccess
	 * @testdox isGameStarted() true
	 */
	public function test_isGameStarted_true() {
		$historyArray=['Data'=>'Start/Stop','GameID'=>200];
		$dataobject= new dataAccess();
		
		$returnvalue["c"]="3";
		
		$statementStub = $this->createStub(PDOStatement::class);
		$statementStub->method('fetch')
					  ->willReturn($returnvalue);
			 
		$dataStub = $this->createStub(PDO::class);
		$dataStub->method('prepare')
				 ->willReturn($statementStub);
			 
		$property = $this->getPrivateProperty( 'dataAccess', 'dbConnection' );
		$property->setValue( $dataobject, $dataStub );
			 
		$result=$dataobject->isGameStarted($historyArray);
		$this->assertEquals(200,$result);
	}

	/**
	 * @small
	 * @covers dataAccess::isGameStarted
	 * @uses dataAccess
	 * @testdox isGameStarted() false
	 */
	public function test_isGameStarted_false2() {
		$historyArray=['Data'=>'Start/Stop','GameID'=>200];
		$dataobject= new dataAccess();
		
		$returnvalue["c"]="4";
		
		$statementStub = $this->createStub(PDOStatement::class);
		$statementStub->method('fetch')
					  ->willReturn($returnvalue);
			 
		$dataStub = $this->createStub(PDO::class);
		$dataStub->method('prepare')
				 ->willReturn($statementStub);
			 
		$property = $this->getPrivateProperty( 'dataAccess', 'dbConnection' );
		$property->setValue( $dataobject, $dataStub );
			 
		$result=$dataobject->isGameStarted($historyArray);
		$this->assertFalse($result);
	}

	/**
	 * @small
	 * @covers dataAccess::getLatestHistory
	 * @uses dataAccess
	 * @testdox getLatestHistory()
	 */
	public function test_getLatestHistory() {
		$dataobject= new dataAccess();
		$result=$dataobject->getLatestHistory();
		$this->assertisArray($result);
	}
	
	/**
	 * @small
	 * @covers dataAccess::getStartedGame
	 * @uses dataAccess
	 * @testdox getStartedGame()
	 */
	public function test_getStartedGame() {
		$dataobject= new dataAccess();
		$result=$dataobject->getStartedGame();
		$this->assertFalse($result);
	}

	/**
	 * @small
	 * @covers dataAccess::logFileName
	 * @uses dataAccess
	 * @testdox logFileName()
	 */
	public function test_logFileName() {
		$dataobject= new dataAccess();
		$result=$dataobject->logFileName();
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @covers dataAccess::insertlog
	 * @uses dataAccess
	 * @testdox insertlog() new file
	 */
	public function test_insertlog_new() {
		$dataobject= new dataAccess();
		$file = $GLOBALS['rootpath'].'\tests\testdata\insertlogtest.txt';
		if(file_exists($file)){
			unlink($file);
		}

		$this->assertFileDoesNotExist($file);
		
		$dataobject->insertlog("Insert1",$file);
		
		$this->assertFileExists($file);
		$content = file_get_contents($file);
		$this->assertEquals("Insert1\r\n\r\n",$content);
	}
	
	/**
	 * @small
	 * @covers dataAccess::insertlog
	 * @uses dataAccess
	 * @testdox insertlog() existing file
	 */
	public function test_insertlog_append() {
		$dataobject= new dataAccess();
		$file = $GLOBALS['rootpath'].'\tests\testdata\insertlogtest.txt';
		if(file_exists($file)){
			unlink($file);
		}

		$this->assertFileDoesNotExist($file);
		
		$dataobject->insertlog("Insert1",$file);
		$dataobject->insertlog("Insert2",$file);
		
		$this->assertFileExists($file);
		$content = file_get_contents($file);
		$this->assertEquals("Insert1\r\n\r\nInsert2\r\n\r\n",$content);
	}
		
	/**
	 * @small
	 * @covers dataAccess::updateBundle
	 * @uses dataAccess
	 * @testdox updateBundle()
	 */
	public function test_updateBundle() {
		$dataobject= new dataAccess();
		$statement = $dataobject->getPurchases(1);
		$allrows=$dataobject->getAllRows($statement,"TransID");
		
		$insertrow['TransID']=$allrows[1]["TransID"];
		$insertrow['Title']=$allrows[1]["Title"];
		$insertrow['Store']=$allrows[1]["Store"];
		$insertrow['BundleID']=$allrows[1]["BundleID"];
		$insertrow['Tier']=$allrows[1]["Tier"];
		$insertrow['purchasetime']=date("Y-m-d H:i:s");
		$insertrow['Sequence']=$allrows[1]["Sequence"];
		$insertrow['Price']=$allrows[1]["Price"];
		$insertrow['Fees']=$allrows[1]["Fees"];
		$insertrow['Paid']=$allrows[1]["Paid"];
		$insertrow['Credit']=$allrows[1]["Credit Used"];
		$insertrow['Link']=$allrows[1]["Bundle Link"];
		
		$dataobject->updateBundle($insertrow);

		$statement = $dataobject->getPurchases(1);
		$allrows2=$dataobject->getAllRows($statement,"TransID");
		$this->assertEquals($allrows2[1]["PurchaseTime"],date("H:i:00",strtotime($insertrow['purchasetime'])));
	}
	
	/**
	 * @small
	 * @covers dataAccess::updateItem
	 * @uses dataAccess
	 * @testdox updateItem()
	 */
	public function test_updateItem() {
		$dataobject= new dataAccess();
		$statement = $dataobject->getItems(1);
		$allrows=$dataobject->getAllRows($statement,"ItemID");

		$insertrow['ItemID']          = $allrows[1]["ItemID"];
		$insertrow['ProductID']       = $allrows[1]["ProductID"];
		$insertrow['TransID']         = $allrows[1]["TransID"];
		$insertrow['ParentProductID'] = $allrows[1]["ParentProductID"];
		$insertrow['Notes']           = $allrows[1]["Notes"];
		$insertrow['Tier']            = $allrows[1]["Tier"];
		$insertrow['ActivationKey']   = $allrows[1]["ActivationKey"];
		$insertrow['SizeMB']          = $allrows[1]["SizeMB"];
		$insertrow['Library']         = $allrows[1]["Library"];
		$insertrow['DRM']             = $allrows[1]["DRM"];
		$insertrow['OS']              = $allrows[1]["OS"];
		$insertrow['purchasetime']    = date("Y-m-d H:i:s");
		$insertrow['Sequence']        = $allrows[1]["Sequence"];
		
		$dataobject->updateItem($insertrow);

		$statement = $dataobject->getItems(1);
		$allrows2=$dataobject->getAllRows($statement,"ItemID");
		$this->assertEquals($allrows2[1]["Time Added"],date("H:i:00",strtotime($insertrow['purchasetime'])));
	}
	
	/**
	 * @small
	 * @covers dataAccess::updateItem
	 * @uses dataAccess
	 * @testdox updateItem with Nulls
	 */
	public function test_updateItem_null() {
		$dataobject= new dataAccess();
		$statement = $dataobject->getItems(1);
		$allrows=$dataobject->getAllRows($statement,"ItemID");

		$insertrow['ItemID']          = $allrows[1]["ItemID"];
		$insertrow['ProductID']       = null;
		$insertrow['TransID']         = $allrows[1]["TransID"];
		$insertrow['ParentProductID'] = $allrows[1]["ParentProductID"];
		$insertrow['Notes']           = $allrows[1]["Notes"];
		$insertrow['Tier']            = $allrows[1]["Tier"];
		$insertrow['ActivationKey']   = $allrows[1]["ActivationKey"];
		$insertrow['SizeMB']          = null;
		$insertrow['Library']         = $allrows[1]["Library"];
		$insertrow['DRM']             = $allrows[1]["DRM"];
		$insertrow['OS']              = $allrows[1]["OS"];
		$insertrow['purchasetime']    = date("Y-m-d H:i:s");
		$insertrow['Sequence']        = $allrows[1]["Sequence"];
		
		try{
			$dataobject->updateItem($insertrow);

			$statement = $dataobject->getItems(1);
			$allrows2=$dataobject->getAllRows($statement,"ItemID");
			$this->assertEquals($allrows2[1]["Time Added"],date("H:i:00",strtotime($insertrow['purchasetime'])));
		} finally {
			$insertrow['ProductID']       = $allrows[1]["ProductID"];
			$insertrow['SizeMB']          = $allrows[1]["SizeMB"];
			$dataobject->updateItem($insertrow);
		}
	}
	
	/**
	 * @small
	 * @covers dataAccess::updateAllSettings
	 * @uses dataAccess
	 * @uses get_db_connection
	 * @uses getsettings
	 * @testdox updateAllSettings()
	 */
	public function test_updateAllSettings() {
		$dataobject= new dataAccess();
		//$statement = $dataobject->getItems(1);
		//$allrows=$dataobject->getAllRows($statement,"ItemID");
		$settings = getsettings();

		$insertrow = array( 
			"Tax"            => "8.59" ,
			"TrackHours"     => "20",
			"LessStat"       => "0.01",
			"XhourGet"       => "1",
			"StartStats"     => "2005-08-22",
			"CountFarm"      => "on" ,
			"CountCheat"     => "on" ,
			"MinPlay"        => "60" ,
			"MinTotal"       => "60",
			"CountFree"      => "on", 
			"WantX"          => "0",
			"CountWantX"     => "on",
			"Active-Active"  => "on",
			"Active-Count"   => "on",
			"Done-Count"     => "on",
			"Inactive-Count" => "on",
			"On_Hold-Count"  => "on",
			"Unplayed-Count" => "on" );
		
		$dataobject->updateAllSettings($insertrow);
		$settings2 = getsettings();
		//$statement = $dataobject->getItems(1);
		//$allrows2=$dataobject->getAllRows($statement,"ItemID");
		$this->assertEquals($settings,$settings2);
	}
	
	/**
	 * @small
	 * @covers dataAccess::insertItem
	 * @uses dataAccess
	 * @testdox insertItem()
	 * @group additem
	 */
	public function test_insertItem() {
		$dataobject= new dataAccess();
		try {
			$testItemID = 20004;
			
			$insertrow['ItemID']=$testItemID;
			$insertrow['ProductID']=($testItemID+1);
			$insertrow['TransID']=($testItemID+2);
			$insertrow['ParentProductID']=($testItemID+1);
			$insertrow['Tier']=1;
			$insertrow['Notes']="Notes";
			$insertrow['SizeMB']=123;
			$insertrow['DRM']="DRM";
			$insertrow['OS']="OS";
			$insertrow['ActivationKey']="ACTIVATION-KEY";
			$insertrow['DateAdded']=date("Y-m-d");
			$insertrow['Time_Added']=date("H:i:s");
			$insertrow['Sequence']=1;
			$insertrow['Library']="Library";
			
			$dataobject->insertItem($insertrow);

			$statement = $dataobject->getItems($testItemID,"ItemID");
			$allrows2=$dataobject->getAllRows($statement);
			$this->assertEquals($allrows2[0]["Time Added"],date("H:i:00",strtotime($insertrow['Time_Added'])));
		} finally {		
			$teardownSQL = "DELETE FROM gl_items WHERE `gl_items`.`ItemID` = " . $testItemID;
			$query = $dataobject->getConnection()->prepare($teardownSQL);
			$query->execute();
		}
	}

	/**
	 * @small
	 * @covers dataAccess::insertGame
	 * @uses dataAccess
	 * @testdox insertGame()
	 * @group additem
	 */
	public function test_insertGame() {
		$dataobject= new dataAccess();
		try {
			$testGameID = 20004;

			$insertrow['Game_ID']           = $testGameID;
			$insertrow['Title']             = "Test Game 2";
			$insertrow['Series']            = "Test Game";
			$insertrow['LaunchDate']        = date("Y-m-d");
			$insertrow['LaunchPrice']       = 1.23;
			$insertrow['MSRP']              = 1.23;
			$insertrow['CurrentMSRP']       = 1.23;
			$insertrow['HistoricLow']       = 1.23;
			$insertrow['LowDate']           = date("Y-m-d");
			$insertrow['SteamAchievements'] = 0;
			$insertrow['SteamCards']        = 0;
			$insertrow['TimeToBeat']        = 1.6;
			$insertrow['Metascore']         = 34;
			$insertrow['UserMetascore']     = 56;
			$insertrow['SteamRating']       = 78;
			$insertrow['SteamID']           = 1234567890;
			$insertrow['GOGID']             = "gogID";
			$insertrow['isthereanydealID']  = "dealID";
			$insertrow['TimeToBeatID']      = 1234546;
			$insertrow['MetascoreID']       = "metaID";
			$insertrow['DateUpdated']       = date("Y-m-d");
			$insertrow['Want']              = 4;
			$insertrow['Playable']          = 1;
			$insertrow['Type']              = "Game";
			$insertrow['ParentGameID']      = $testGameID;
			$insertrow['ParentGame']        = "Parent Game";
			$insertrow['Developer']         = "Developer";
			$insertrow['Publisher']         = "Publisher";
			
			$dataobject->insertGame($insertrow);

			$statement = $dataobject->getGames($testGameID,"Game_ID");
			$allrows2=$dataobject->getAllRows($statement);
			$this->assertEquals($allrows2[0]["Title"],$insertrow['Title']);
		} finally {
			$teardownSQL = "DELETE FROM gl_products WHERE `gl_products`.`Game_ID` = " . $testGameID;
			$query = $dataobject->getConnection()->prepare($teardownSQL);
			$query->execute();
		}
	}
	
	/**
	 * @small
	 * @covers dataAccess::insertGame2
	 * @uses dataAccess
	 * @testdox insertGame2()
	 * @group additem
	 */
	public function test_insertGame2() {
		$dataobject= new dataAccess();
		try {
			$testGameID = 20004;

			$insertrow['Game_ID']     = $testGameID;
			$insertrow['Title']       = "Test Game 2";
			$insertrow['Series']      = "Test Game";
			$insertrow['LaunchDate']  = date("Y-m-d");
			$insertrow['SteamID']     = 1;
			$insertrow['Want']        = 4;
			$insertrow['Playable']    = 1;
			$insertrow['Type']        = "Game";
			$insertrow['ParentGameID']= $testGameID;
			$insertrow['ParentGame']  = "Parent Game";
			
			$dataobject->insertGame2($insertrow);

			$statement = $dataobject->getGames($testGameID,"Game_ID");
			$allrows2=$dataobject->getAllRows($statement);
			$this->assertEquals($allrows2[0]["Title"],$insertrow['Title']);
		} finally {
			$teardownSQL = "DELETE FROM gl_products WHERE `gl_products`.`Game_ID` = " . $testGameID;
			$query = $dataobject->getConnection()->prepare($teardownSQL);
			$query->execute();
		}
	}

	/**
	 * @small
	 * @covers dataAccess::insertTransaction
	 * @uses dataAccess
	 * @testdox insertTransaction()
	 * @group additem
	 */
	public function test_insertTransaction() {
		$dataobject= new dataAccess();
		try {
			$testTransID = 20004;
			
			$insertrow['TransID']      = $testTransID;
			$insertrow['Title']        = "Test Bundle";
			$insertrow['Store']        = "Test Store";
			$insertrow['BundleID']     = $testTransID;
			$insertrow['Tier']         = 1;
			$insertrow['PurchaseDate'] = date("Y-m-d");
			$insertrow['PurchaseTime'] = date("H:i:s");
			$insertrow['Sequence']     = "Game";
			$insertrow['Price']        = 1.23;
			$insertrow['Fees']         = 2.34;
			$insertrow['Paid']         = 3.45;
			$insertrow['CreditUsed']   = 4.56;
			$insertrow['BundleLink']   = "https://link.link";
			
			$dataobject->insertTransaction($insertrow);

			$statement = $dataobject->getPurchases($testTransID,"TransID");
			$allrows2=$dataobject->getAllRows($statement);
			$this->assertEquals($allrows2[0]["Title"],$insertrow['Title']);
		} finally {
			$teardownSQL = "DELETE FROM gl_transactions WHERE `gl_transactions`.`TransID` = " . $testTransID;
			$query = $dataobject->getConnection()->prepare($teardownSQL);
			$query->execute();
		}
	}

	/**
	 * @small
	 * @covers dataAccess::updateHistory
	 * @uses dataAccess
	 * @testdox updateHistory()
	 */
	public function test_updateHistory() {
		$dataobject= new dataAccess();
		$history = $dataobject->getLatestHistory();
		//var_dump($history);
		$insertrow['Title']=$history["Game"];
		$insertrow['System']=$history["System"];
		$insertrow['Data']=$history["Data"];
		$insertrow['hours']=$history["Time"];
		$insertrow['notes']=$history["Notes"];
		$insertrow['source']=$history["RowType"];
		$insertrow['achievements']=$history["Achievements"];
		$insertrow['status']=$history["Status"];
		$insertrow['review']=$history["Review"];
		$insertrow['basegame']=$history["BaseGame"] == "1" ? "on" : "";
		$insertrow['minutes']=$history["kwMinutes"] == "1" ? "on" : "";
		$insertrow['idle']=$history["kwIdle"] == "1" ? "on" : "";
		$insertrow['cardfarming']=$history["kwCardFarming"] == "1" ? "on" : "";
		$insertrow['cheating']=$history["kwCheating"] == "1" ? "on" : "";
		$insertrow['beatgame']=$history["kwBeatGame"] == "1" ? "on" : "";
		$insertrow['share']=$history["kwShare"] == "1" ? "on" : "";
		$insertrow['ProductID']=$history["GameID"];
		$insertrow['id']=$history["HistoryID"];
		$timestamp=$history["Timestamp"];
		
		$insertrow2=$insertrow;
		$insertrow2["Title"] .= " Test";
		
		$dataobject->updateHistory($insertrow2,$timestamp);
		$history2 = $dataobject->getLatestHistory();
		
		$this->assertEquals($insertrow2["Title"],$history2["Game"]);
		
		$dataobject->updateHistory($insertrow,$timestamp);
		$history2 = $dataobject->getLatestHistory();
		$this->assertEquals($history,$history2);
	}
	
	/**
	 * @small
	 * @covers dataAccess::insertHistory
	 * @uses dataAccess
	 * @testdox insertHistory() timestamp provided
	 */
	public function test_insertHistory_timeprovided() {
		$dataobject= new dataAccess();
		try{
			$history = $dataobject->getLatestHistory();
			$this->assertNotEquals("PHPUNIT Test",$history["RowType"]);
			$maxID=$dataobject->getMaxHistoryId();
			
			$insertrow['Title']="Test Entry";
			$insertrow['System']=$history["System"];
			$insertrow['Data']=$history["Data"];
			$insertrow['hours']=$history["Time"];
			$insertrow['notes']="Insert History Test";
			$insertrow['source']="PHPUNIT Test";
			$insertrow['achievements']=$history["Achievements"];
			$insertrow['status']=$history["Status"];
			$insertrow['review']=$history["Review"];
			$insertrow['basegame']=$history["BaseGame"] == "1" ? "on" : "";
			$insertrow['minutes']=$history["kwMinutes"] == "1" ? "on" : "";
			$insertrow['idle']=$history["kwIdle"] == "1" ? "on" : "";
			$insertrow['cardfarming']=$history["kwCardFarming"] == "1" ? "on" : "";
			$insertrow['cheating']=$history["kwCheating"] == "1" ? "on" : "";
			$insertrow['beatgame']=$history["kwBeatGame"] == "1" ? "on" : "";
			$insertrow['share']=$history["kwShare"] == "1" ? "on" : "";
			$insertrow['ProductID']=$history["GameID"];
			$insertrow['update']="on";
			$timestamp=date("Y-m-d H:i:s");
			
			$insertrow2=$insertrow;
			$insertrow2["Title"] .= " Test2";

			$insertrow3=$insertrow;
			$insertrow3["Title"] .= " Test3";
			$insertrow3["id"] = $maxID+3;

			$datarow=[$insertrow,$insertrow2,$insertrow3];
			//var_dump([$insertrow,$insertrow2]);
			$dataobject->insertHistory($datarow,$timestamp,$maxID);
			$history2 = $dataobject->getLatestHistory();
			
			$this->assertEquals($insertrow3["Title"],$history2["Game"]);
		} finally {
			$CleanupQuery=$dataobject->getConnection()->prepare('DELETE FROM `gl_history` WHERE `gl_history`.`RowType` = "PHPUNIT Test"');
			$CleanupQuery->execute();
		}
	}
	
	/**
	 * @small
	 * @covers dataAccess::insertHistory
	 * @uses dataAccess
	 * @testdox insertHistory() use current time
	 */
	public function test_insertHistory_current() {
		$dataobject= new dataAccess();
		$_POST['currenttime']="on";
		try{
			$history = $dataobject->getLatestHistory();
			$this->assertNotEquals("PHPUNIT Test",$history["RowType"]);
			$maxID=$dataobject->getMaxHistoryId();
			
			$insertrow['Title']="Test Entry";
			$insertrow['System']=$history["System"];
			$insertrow['Data']=$history["Data"];
			$insertrow['hours']=$history["Time"];
			$insertrow['notes']="Insert History Test";
			$insertrow['source']="PHPUNIT Test";
			$insertrow['achievements']=$history["Achievements"];
			$insertrow['status']=$history["Status"];
			$insertrow['review']=$history["Review"];
			$insertrow['basegame']=$history["BaseGame"] == "1" ? "on" : "";
			$insertrow['minutes']=$history["kwMinutes"] == "1" ? "on" : "";
			$insertrow['idle']=$history["kwIdle"] == "1" ? "on" : "";
			$insertrow['cardfarming']=$history["kwCardFarming"] == "1" ? "on" : "";
			$insertrow['cheating']=$history["kwCheating"] == "1" ? "on" : "";
			$insertrow['beatgame']=$history["kwBeatGame"] == "1" ? "on" : "";
			$insertrow['share']=$history["kwShare"] == "1" ? "on" : "";
			$insertrow['ProductID']=$history["GameID"];
			$insertrow['update']="on";
			$timestamp=strtotime(date("Y-m-d H:i:s"));
			
			$insertrow2=$insertrow;
			$insertrow2["Title"] .= " Test2";

			$insertrow3=$insertrow;
			$insertrow3["Title"] .= " Test3";
			$insertrow3["id"] = $maxID+3;

			$datarow=[$insertrow,$insertrow2,$insertrow3];
			//var_dump([$insertrow,$insertrow2]);
			$dataobject->insertHistory($datarow,$timestamp,$maxID);
			$history2 = $dataobject->getLatestHistory();
			
			$this->assertEquals($insertrow3["Title"],$history2["Game"]);
		} finally {
			$CleanupQuery=$dataobject->getConnection()->prepare('DELETE FROM `gl_history` WHERE `gl_history`.`RowType` = "PHPUNIT Test"');
			$CleanupQuery->execute();
		}
	}

	/**
	 * @small
	 * @covers dataAccess::countGameStartStop
	 * @uses dataAccess
	 * @testdox countGameStartStop()
	 */
	public function test_countGameStartStop() {
		$dataobject= new dataAccess();
		$this->assertisNumeric($dataobject->countGameStartStop(2));
	}
	
	/**
	 * @small
	 * @covers dataAccess::isEven
	 * @uses dataAccess
	 * @testdox isEven() $number returns $expectedresult
	 * @testWith [5,0]
	 *           [6,1]
	 */
	public function test_isEven($number,$expectedresult) {
		$dataobject= new dataAccess();
		$this->assertEquals($expectedresult,$dataobject->isEven($number));
	}
	
	/**
	 * @small
	 * @covers dataAccess::isOdd
	 * @uses dataAccess
	 * @testdox isOdd() $number returns $expectedresult
	 * @testWith [5,1]
	 *           [6,0]
	 */
	public function test_isOdd($number,$expectedresult) {
		$dataobject= new dataAccess();
		$this->assertEquals($expectedresult,$dataobject->isOdd($number));
	}

	/**
	 * @small
	 * @covers dataAccess::fillIfBlank
	 * @uses dataAccess
	 * @testdox fillIfBlank() '$target' and '$value' provided, '$output' given
	 * @testWith ["","good","good"]
	 *           ["good","","good"]
	 */
	public function test_fillIfBlank($target,$value,$output) {
		$dataobject= new dataAccess();
		$this->assertEquals($output,$dataobject->fillIfBlank($target,$value));
	}

	/**
	 * @small
	 * @covers dataAccess::getHistoryRecord
	 * @uses dataAccess
	 * @testdox getHistoryRecord()
	 */
	public function test_getHistoryRecord() {
		$historyArray=['Data'=>'Start/Stop','GameID'=>200];
		$dataobject= new dataAccess();
		
		$returnvalue1["Review"]="";
		$returnvalue1["Status"]="4";

		$returnvalue2["Review"]="4";
		$returnvalue2["Status"]="";

		$returnvalue3["Review"]="1";
		$returnvalue3["Status"]="1";
		
		$returnvalue4["Review"]="";
		$returnvalue4["Status"]="";

		$expectedvalue["Review"]="4";
		$expectedvalue["Status"]="4";

		$statementStub = $this->createStub(PDOStatement::class);
		$statementStub->method('fetch')
					  ->will($this->onConsecutiveCalls($returnvalue1, $returnvalue2, $returnvalue3, $returnvalue4));
		
		$dataStub = $this->createStub(PDO::class);
		$dataStub->method('prepare')
				 ->willReturn($statementStub);
		
		$property = $this->getPrivateProperty( 'dataAccess', 'dbConnection' );
		$property->setValue( $dataobject, $dataStub );
		
		$result=$dataobject->getHistoryRecord(1);
		$this->assertEquals($expectedvalue,$result);
	}
	
	/**
	 * @small
	 * @covers dataAccess::getStatusList
	 * @uses dataAccess
	 * @testdox getStatusList()
	 */
	public function test_getStatusList(){
		$dataobject= new dataAccess();
		$result=$dataobject->getStatusList();
		$this->assertIsArray($result);
	}
	
	/**
	 * @small
	 * @covers dataAccess::getProductTitle
	 * @uses dataAccess
	 * @testdox getProductTitle()
	 */
	public function test_getProductTitle(){
		$dataobject= new dataAccess();
		$result=$dataobject->getProductTitle(111);
		$this->assertIsString($result);
	}
	
	/**
	 * @small
	 * @covers dataAccess::getHistoryRecrod
	 * @uses dataAccess
	 * @testdox getHistoryRecrod()
	 */
	public function test_getHistoryRecrod(){
		$dataobject= new dataAccess();
		$result=$dataobject->getHistoryRecrod(111);
		$this->assertIsArray($result);
	}
	
	/**
	 * @small
	 * @covers dataAccess::getHistoryDataTypes
	 * @uses dataAccess
	 * @testdox getHistoryDataTypes()
	 */
	public function test_getHistoryDataTypes(){
		$dataobject= new dataAccess();
		$result=$dataobject->getHistoryDataTypes();
		$this->assertIsArray($result);
	}
	
	/**
	 * @small
	 * @covers dataAccess::getSystemList
	 * @uses dataAccess
	 * @testdox getSystemList()
	 */
	public function test_getSystemList(){
		$dataobject= new dataAccess();
		$result=$dataobject->getSystemList();
		$this->assertIsArray($result);
	}
	
}