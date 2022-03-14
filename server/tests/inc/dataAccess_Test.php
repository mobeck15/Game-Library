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
}