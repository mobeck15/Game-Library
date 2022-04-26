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
 */
final class dataAccess_Test extends testprivate
{
	/**
	 * @small
	 * @covers dataAccess::getConnection
	 * @uses dataAccess
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
	 */
	public function test_getPurchases() {
		$dataobject= new dataAccess();
		$statement=$dataobject->getPurchases();
		$this->assertisObject($statement);
		$this->assertInstanceOf(PDOStatement::class, $statement);
	}

	/**
	 * @small
	 * @covers dataAccess::getPurchases
	 * @uses dataAccess
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
	 */
	public function test_getMaxHistoryId() {
		$dataobject= new dataAccess();
		$maxID=$dataobject->getMaxHistoryId();
		$this->assertIsNumeric($maxID);
	}	

	/**
	 * @small
	 * @covers dataAccess::isGameStarted
	 * @uses dataAccess
	 */
	public function test_isGameStarted_false() {
		$historyArray=['Data'=>'New Total'];
		$dataobject= new dataAccess();
		$result=$dataobject->isGameStarted($historyArray);
		$this->assertFalse($result);
	}
	/**
	 * @small
	 * @covers dataAccess::isGameStarted
	 * @uses dataAccess
	 */
	public function test_isGameStarted_true() {
		$historyArray=['Data'=>'Start/Stop','GameID'=>200];
		$dataobject= new dataAccess();
		$result=$dataobject->isGameStarted($historyArray);
		$this->assertEquals(200,$result);
	}

	/**
	 * @small
	 * @covers dataAccess::getLatestHistory
	 * @uses dataAccess
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
	 * @covers dataAccess::updateHistory
	 * @uses dataAccess
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
		$timestamp=strtotime($history["Timestamp"]);
		
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
	 * @covers dataAccess::insertHistory
	 * @uses dataAccess
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
}