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
	 * @covers topx::defaultSortDir
	 * @uses topx::__construct
	 * @testWith ["GrandTotal","SORT_ASC"]
	 *			 ["Paid","SORT_DESC"]
	 */
	public function test_defaultSortDir($stat, $expectedresult) {
		$topxObject=new topx();
		
		$resutls=array(
			"SORT_ASC"=>SORT_ASC,
			"SORT_DESC"=>SORT_DESC
		);
		
		$method = $this->getPrivateMethod( 'topx', 'defaultSortDir' );
		$result = $method->invokeArgs($topxObject, array( $stat ) );
		
		$this->assertEquals($resutls[$expectedresult],$result);
	}
	
	/**
	 * @small
	 * @covers topx::setfilter
	 * @uses topx::__construct
	 */
	public function test_setfilter() {
		$topxObject=new topx();
		
		$topxObject->setfilter("FILTER");
		
		$property = $this->getPrivateProperty( 'topx', 'filter' );
		$result = $property->getValue( $topxObject );
		
		$this->assertEquals("FILTER",$result);
	}
	
	/**
	 * @small
	 * @covers topx::setxvalue
	 * @uses topx::__construct
	 */
	public function test_setxvalue() {
		$topxObject=new topx();
		
		$topxObject->setxvalue(10);
		
		$property = $this->getPrivateProperty( 'topx', 'xvalue' );
		$result = $property->getValue( $topxObject );
		
		$this->assertEquals(10,$result);
	}
	
	/**
	 * @small
	 * @covers topx::statlist
	 * @uses topx::__construct
	 */
	public function test_statlist() {
		$topxObject=new topx();
		
		$result = $topxObject->statlist();
		
		$this->assertisArray($result);
	}

	/**
	 * @small
	 * @covers topx::getHeaderText
	 * @uses topx::__construct
	 */
	public function test_getHeaderText() {
		$topxObject=new topx();
		
		$method = $this->getPrivateMethod( 'topx', 'getHeaderText' );
		$result = $method->invokeArgs($topxObject, array( "ParentGame" ) );
		
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @covers topx::gettopx
	 * @uses topx
	 * @testWith ["GrandTotal"]
	 *			 ["LastPlayORPurchase"]
	 */
	public function test_gettopx($stat) {
		$topxObject=new topx();
		
		$calculations=array(
			0=>array(
				"AddedDateTime" => new DateTime('2011-01-10T15:03:01.012345Z'), 
				"LaunchDate" => new DateTime('2011-01-13T15:03:01.012345Z'), 
				"LastPlayORPurchase" => "2011-01-13 3:03:01 PM", 
				"GrandTotal" => 12.50,
				"Playable" => true,
				"Status" => "Active",
				"Review" => 4,
				"Game_ID" => 1,
			),
			1=>array(
				"AddedDateTime" => new DateTime('2011-01-13T15:03:01.012345Z'), 
				"LaunchDate" => new DateTime('2011-01-11T15:03:01.012345Z'), 
				"LastPlayORPurchase" => "2011-01-10 3:03:01 PM", 
				"GrandTotal" => 12.53,
				"Playable" => true,
				"Status" => "Active",
				"Review" => 4,
				"Game_ID" => 2,
			),
			2=>array(
				"AddedDateTime" => new DateTime('2011-01-11T15:03:01.012345Z'), 
				"LaunchDate" => new DateTime('2011-01-10T15:03:01.012345Z'), 
				"LastPlayORPurchase" => "2011-01-12 3:03:01 PM", 
				"GrandTotal" => 12.51,
				"Playable" => true,
				"Status" => "Active",
				"Review" => 4,
				"Game_ID" => 3,
			),
			3=>array(
				"AddedDateTime" => new DateTime('2011-01-12T15:03:01.012345Z'), 
				"LaunchDate" => new DateTime('2011-01-12T15:03:01.012345Z'), 
				"LastPlayORPurchase" => "2011-01-11 3:03:01 PM", 
				"GrandTotal" => 12.52,
				"Playable" => true,
				"Status" => "Active",
				"Review" => 4,
				"Game_ID" => 4,
			),
		);
		
		$property = $this->getPrivateProperty( 'topx', 'calculations' );
		$property->setValue( $topxObject, $calculations );
		
		$result = $topxObject->gettopx($stat);
		
		$this->assertisArray($result);
	}

	
	/**
	 * @small
	 * @covers topx::defaultFilterString
	 * @uses topx::__construct
	 * @testWith ["GrandTotal"]
	 *			 ["TimeLeftToBeat"]
	 */
	public function test_defaultFilterString($stat) {
		$topxObject=new topx();
		
		$method = $this->getPrivateMethod( 'topx', 'defaultFilterString' );
		$result = $method->invokeArgs($topxObject, array( $stat ) );
		
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @covers topx::statformat
	 * @uses timeduration
	 * @uses topx::__construct
	 * @testWith [2,"AltLess1","$2.00"]
	 *			 [2,"TimeLeftToBeat","2:00:00"]
	 *			 [300,"GrandTotal","0:05:00"]
	 */
	public function test_statformat($value, $statname , $expectedresult) {
		$topxObject=new topx();
		
		$method = $this->getPrivateMethod( 'topx', 'statformat' );
		$result = $method->invokeArgs($topxObject, array( $value, $statname ) );
		
		$this->assertEquals($expectedresult,$result);
	}
	
	/**
	 * @small
	 * @covers topx::sortbystat
	 * @uses topx::__construct
	 * @testWith ["PurchaseDate",[0,2,3,1]]
	 *			 ["LaunchDate",[2,1,3,0]]
	 *			 ["LastPlayORPurchase",[1,3,2,0]]
	 *			 ["Paid",[0,2,3,1]]
	 */
	public function test_sortbystat($stat, $sortrow) {
		$topxObject=new topx();
		
		$calculations=array(
			0=>array(
				"AddedDateTime" => new DateTime('2011-01-10T15:03:01.012345Z'), 
				"LaunchDate" => new DateTime('2011-01-13T15:03:01.012345Z'), 
				"LastPlayORPurchase" => "2011-01-13 3:03:01 PM", 
				"Paid" => 12.50,
			),
			1=>array(
				"AddedDateTime" => new DateTime('2011-01-13T15:03:01.012345Z'), 
				"LaunchDate" => new DateTime('2011-01-11T15:03:01.012345Z'), 
				"LastPlayORPurchase" => "2011-01-10 3:03:01 PM", 
				"Paid" => 12.53,
			),
			2=>array(
				"AddedDateTime" => new DateTime('2011-01-11T15:03:01.012345Z'), 
				"LaunchDate" => new DateTime('2011-01-10T15:03:01.012345Z'), 
				"LastPlayORPurchase" => "2011-01-12 3:03:01 PM", 
				"Paid" => 12.51,
			),
			3=>array(
				"AddedDateTime" => new DateTime('2011-01-12T15:03:01.012345Z'), 
				"LaunchDate" => new DateTime('2011-01-12T15:03:01.012345Z'), 
				"LastPlayORPurchase" => "2011-01-11 3:03:01 PM", 
				"Paid" => 12.52,
			),
		);
		
		$property = $this->getPrivateProperty( 'topx', 'calculations' );
		$property->setValue( $topxObject, $calculations );
		
		$method = $this->getPrivateMethod( 'topx', 'sortbystat' );
		$sortdir=SORT_ASC;
		$result = $method->invokeArgs($topxObject, array( $stat, $sortdir ) );
		
		foreach ($result as $key => $row) {
			$this->assertEquals($calculations[$sortrow[$key]],$row);
		}
	}
	
	public function data_simpleCalcArray() {
		
	}
	
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