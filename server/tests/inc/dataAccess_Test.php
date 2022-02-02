<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\dataAccess.class.php";

/**
 * @group include
 * @group classtest
 */
final class dataAccess_Test extends TestCase
{
	/**
	 * @covers dataAccess::getConnection
	 * @uses dataAccess::__construct
	 * @uses dataAccess::getConnection
	 */
	public function test_getConnection() {
		$dataobject= new dataAccess();
		$connection = $dataobject->getConnection();
		$this->assertisObject($connection);

		$connection2 = $dataobject->getConnection();
		$this->assertEquals($connection,$connection2);
	}

	/**
	 * @covers dataAccess::closeConnection
	 * @uses dataAccess::__construct
	 * @uses dataAccess::getConnection
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
	 * @covers dataAccess::getPurchases
	 * @uses dataAccess::__construct
	 * @uses dataAccess::getConnection
	 */
	public function test_getPurchases() {
		$dataobject= new dataAccess();
		$statement=$dataobject->getPurchases();
		$this->assertisObject($statement);
		$this->assertInstanceOf(PDOStatement::class, $statement);
	}

	/**
	 * @covers dataAccess::getAllRows
	 * @uses dataAccess::getPurchases
	 * @uses dataAccess::__construct
	 * @uses dataAccess::getConnection
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
	 * @covers dataAccess::getAllRows
	 * @uses dataAccess::getPurchases
	 * @uses dataAccess::__construct
	 * @uses dataAccess::getConnection
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
 	 * getPrivateProperty
 	 *
 	 * @author	Joe Sexton <joe@webtipblog.com>
 	 * @param 	string $className
 	 * @param 	string $propertyName
 	 * @return	ReflectionProperty
	 * Source: https://www.webtipblog.com/unit-testing-private-methods-and-properties-with-phpunit/
 	 */
	public function getPrivateProperty( $className, $propertyName ) {
		$reflector = new ReflectionClass( $className );
		$property = $reflector->getProperty( $propertyName );
		$property->setAccessible( true );

		return $property;
	}

	/**
 	 * getPrivateMethod
 	 *
 	 * @author	Joe Sexton <joe@webtipblog.com>
 	 * @param 	string $className
 	 * @param 	string $methodName
 	 * @return	ReflectionMethod
	 * Source: https://www.webtipblog.com/unit-testing-private-methods-and-properties-with-phpunit/
 	 */
	public function getPrivateMethod( $className, $methodName ) {
		$reflector = new ReflectionClass( $className );
		$method = $reflector->getMethod( $methodName );
		$method->setAccessible( true );

		return $method;
	}
	
}