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
	 * @uses topx::__construct
	 * @uses topx::getHeaderText
	 * @uses topx::statformat
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
	 * @covers topx::sortbystat
	 */
	//public function test_sortbystat() {
		
	//}
	
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
	 * @uses topx::__construct
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