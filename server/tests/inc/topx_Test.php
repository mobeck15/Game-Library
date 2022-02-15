<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath'].'\inc\topx.class.php';

/**
 * @group include
 * @group classtest
 * @group topx
 */
final class topx_Test extends TestCase
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
	 */
	public function test_displaytop() {
		$calculations=array(
			1=>array("Status"=>"Active","LaunchDate"=>1,"Title"=>"Game1"),
			2=>array("Status"=>"Active","LaunchDate"=>2,"Title"=>"Game2"),
		);
		$topxObject=new topx($calculations);
		
		$gameids=array(1,2);
		
		$this->assertisString($topxObject->displaytop($gameids,"LaunchDate"));
	}
	
	/**
	 * @small
	 * @covers topx::parseFilter
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