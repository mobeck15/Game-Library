<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getPurchases.class.php";

/**
 * @group include
 * @group purchases
 */
final class getPurchases_Test extends TestCase
{
	/**
	 * @medium
	 * @covers getPurchases
	 * @uses get_db_connection
	 * @uses CalculateGameRow
	 * @uses getActivityCalculations
	 * @uses getAllCpi
	 * @uses getAllItems
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses timeduration
	 * @uses combinedate
	 * @uses getCleanStringDate
	 */
	public function test_getPurchasesFunction_base() {
		$this->assertisArray(getPurchases());
	}
   
	/**
	 * @medium
	 * @covers getPurchases
	 * @uses get_db_connection
	 * @uses CalculateGameRow
	 * @uses getActivityCalculations
	 * @uses getAllCpi
	 * @uses getAllItems
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses timeduration
	 * @uses combinedate
	 * @uses getCleanStringDate
	 */
    public function test_getPurchasesFunction_conn() {
		$conn=get_db_connection();
        $this->assertisArray(getPurchases("6",$conn));
 		$conn->close();
  }
   
	/**
	 * @medium
	 * @covers Purchases::getPurchases
	 * @uses CalculateGameRow
	 * @uses getActivityCalculations
	 * @uses getAllCpi
	 * @uses getAllItems
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses timeduration
	 * @uses combinedate
	 * @uses getCleanStringDate
	 */
	public function test_getPurchases_base() {
		$purchasObject=new Purchases();
		$this->assertisArray($purchasObject->getPurchases());
	}

//------------------------------------------------------

	/**
	 * @medium
	 * @covers Purchases::__construct
	 */
	public function test_Purchases_constructor_base() {
		$gamearray=array(array("Game_ID"=>1,"Title"=>"gamename"));
		$itemarray=array(array("Item_ID"=>1,"Title"=>"itemname"));
		$purchasObject=new Purchases(null,false,$itemarray,$gamearray);
		
		$this->assertisObject($purchasObject);
	}
 
	/**
	 * @medium
	 * @covers Purchases::__construct
	 */
	public function test_Purchases_constructor_connector() {
		$conn = get_db_connection();
		$purchasObject=new Purchases("",$conn);
		
		$this->assertisObject($purchasObject);
	}
	
	/**
	 * @medium
	 * @covers Purchases::groupItemsByBundle
	 */
	public function test_groupItemsByBundle_base() {
		$purchasObject=new Purchases();

		$property = $this->getPrivateProperty( 'Purchases', 'items' );
		$items = $property->getValue( $purchasObject );
		
		$method = $this->getPrivateMethod( 'Purchases', 'groupItemsByBundle' );
		$result = $method->invokeArgs($purchasObject, array( $items ) );
		
		$this->assertisArray($result);
		//var_dump($result[2547]);
	}
	
	/**
	 * @medium
	 * @covers Purchases::itemRowBundles
	 */
	public function test_itemRowBundles_base() {
		$GLOBALS["SETTINGS"]=array(
		"status"=>array(
			"Done"=>    array("Active"=>"0", "Count"=>"1"),
			"Active"=>  array("Active"=>"1", "Count"=>"1"),
			"Inactive"=>array("Active"=>"1", "Count"=>"1"),
			"Never"=>   array("Active"=>"1", "Count"=>"1"),
			"On Hold"=> array("Active"=>"1", "Count"=>"1"),
			"Broken"=>  array("Active"=>"1", "Count"=>"1"),
			"Unplayed"=>array("Active"=>"1", "Count"=>"1"),
			),
		"CountIdle"=>0,
		"CountCheat"=>0,
		"CountFarm"=>0,
		"CountShare"=>0,
		"MinPlay"=>60,
		"MinTotal"=>60
		);
		$purchasObject=new Purchases();
		
		$row=array(
		"TotalMSRP"=>1,
		"TotalWant"=>1,
		"TotalHrs"=>1,
		"TotalMSRPFormula"=>"x"
		);
		
		$property = $this->getPrivateProperty( 'Purchases', 'items' );
		$items = $property->getValue( $purchasObject );
		
		$method = $this->getPrivateMethod( 'Purchases', 'itemRowBundles' );
		$result = $method->invokeArgs($purchasObject, array( $items[2547],$row ) );
		
		$this->assertisArray($result);
	}
	
	/**
	 * @medium
	 * @covers Purchases::itemRowBundles
	 */
	public function test_itemRowBundles_nocount() {
		$GLOBALS["SETTINGS"]=array(
		"status"=>array(
			"Done"=>    array("Active"=>"0", "Count"=>"0"),
			"Active"=>  array("Active"=>"1", "Count"=>"0"),
			"Inactive"=>array("Active"=>"1", "Count"=>"0"),
			"Never"=>   array("Active"=>"1", "Count"=>"0"),
			"On Hold"=> array("Active"=>"1", "Count"=>"0"),
			"Broken"=>  array("Active"=>"1", "Count"=>"0"),
			"Unplayed"=>array("Active"=>"1", "Count"=>"0"),
			),
		"CountIdle"=>0,
		"CountCheat"=>0,
		"CountFarm"=>0,
		"CountShare"=>0,
		"MinPlay"=>60,
		"MinTotal"=>60
		);
		$purchasObject=new Purchases();
		
		$row=array(
		"TotalMSRP"=>1,
		"TotalWant"=>1,
		"TotalHrs"=>1,
		"TotalMSRPFormula"=>"x"
		);
		
		$property = $this->getPrivateProperty( 'Purchases', 'items' );
		$items = $property->getValue( $purchasObject );

		$property = $this->getPrivateProperty( 'Purchases', 'activity' );
		$activity = $property->getValue( $purchasObject );
		//var_dump($activity[1162]);
		unset($activity[1162]);
		$property->setValue( $purchasObject, $activity );
		
		$method = $this->getPrivateMethod( 'Purchases', 'itemRowBundles' );
		$result = $method->invokeArgs($purchasObject, array( $items[2547],$row ) );
		
		$this->assertisArray($result);
	}
	
	/**
	 * @medium
	 * @covers Purchases::calculateSalePrice
	 */
	public function test_calculateSalePrice_base() {
		$purchasObject=new Purchases();
		
		$row=array(
		"TotalMSRP"=>1,
		"TotalWant"=>1,
		"TotalHrs"=>1,
		"GamesinBundle"=>array(
			1162=>array(
				"Want"=>1,
				"MSRP"=>1
			)
		),
		"TotalMSRPFormula"=>"x",
		"Paid"=>1
		);
		
		$property = $this->getPrivateProperty( 'Purchases', 'items' );
		$items = $property->getValue( $purchasObject );
		
		$method = $this->getPrivateMethod( 'Purchases', 'calculateSalePrice' );
		$result = $method->invokeArgs($purchasObject, array( $items[2547],$row ) );
		
		$this->assertisArray($result);
	}
	
	/**
	 * @medium
	 * @covers Purchases::calculateAltSalePrice
	 */
	public function test_calculateAltSalePrice_base() {
		$purchasObject=new Purchases();
		
		$row=array(
		"TotalMSRP"=>1,
		"TotalWant"=>1,
		"TotalHrs"=>1,
		"GamesinBundle"=>array(
			1162=>array(
				"Want"=>1,
				"MSRP"=>1
			)
		),
		"TotalMSRPFormula"=>"x",
		"Paid"=>1
		);
		
		$property = $this->getPrivateProperty( 'Purchases', 'items' );
		$items = $property->getValue( $purchasObject );
		
		$method = $this->getPrivateMethod( 'Purchases', 'calculateAltSalePrice' );
		$result = $method->invokeArgs($purchasObject, array( $items[2547],$row ) );
		
		$this->assertisArray($result);
	}
	
	/**
	 * @medium
	 * @covers Purchases::calculateAltSalePrice
	 */
	public function test_calculateAltSalePrice_zeroweight() {
		$GLOBALS["SETTINGS"]=array(
		"status"=>array(
			"Done"=>    array( "Active"=>"0", "Count"=>"1" ),
			"Active"=>  array( "Active"=>"0", "Count"=>"1" ),
			"Inactive"=>array( "Active"=>"0", "Count"=>"1" ),
			"Never"=>   array( "Active"=>"0", "Count"=>"1" ),
			"On Hold"=> array( "Active"=>"0", "Count"=>"1" ),
			"Broken"=>  array( "Active"=>"0", "Count"=>"1" ),
			"Unplayed"=>array( "Active"=>"0", "Count"=>"1" ),
			),
		"CountIdle"=>0,
		"CountShare"=>0,
		"CountCheat"=>0,
		"CountFarm"=>0,
		"MinPlay"=>60,
		"MinTotal"=>60,
		"WeightWant"=>0,
		"WeightPlay"=>0,
		"WeightMSRP"=>0,
		);
		
		$purchasObject=new Purchases();
		
		$row=array(
		"TotalMSRP"=>1,
		"TotalWant"=>1,
		"TotalHrs"=>1,
		"GamesinBundle"=>array(
			1162=>array(
				"Want"=>1,
				"MSRP"=>1
			)
		),
		"TotalMSRPFormula"=>"x",
		"Paid"=>1
		);
		
		$property = $this->getPrivateProperty( 'Purchases', 'items' );
		$items = $property->getValue( $purchasObject );
		
		$method = $this->getPrivateMethod( 'Purchases', 'calculateAltSalePrice' );
		$result = $method->invokeArgs($purchasObject, array( $items[2547],$row ) );
		
		$this->assertisArray($result);
	}
	
	/**
	 * @medium
	 * @covers Purchases::getPurchases
	 * @uses get_db_connection
	 * @uses CalculateGameRow
	 * @uses getActivityCalculations
	 * @uses getAllCpi
	 * @uses getAllItems
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses timeduration
	 * @uses combinedate
	 * @uses getCleanStringDate
	 */
	public function test_getPurchases_conn() {
		$conn=get_db_connection();
		$purchasObject=new Purchases("6",$conn);
		$this->assertisArray($purchasObject->getPurchases());
		$conn->close();
	}

	/**
	 * @medium
	 * @covers Purchases::purchaseRowFirstPass
	 * @uses combinedate
	 * @uses dataAccess
	 * @uses getActivityCalculations
	 * @uses getAllCpi
	 * @uses getAllItems
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses timeduration
	 * @uses getCleanStringDate
	 */
	public function test_purchaseRowFirstPass_blanks() {
		//$dataobject= new dataAccess();
		//$statement=$dataobject->getPurchases();
		//$row = $statement->fetch(PDO::FETCH_ASSOC);
		//var_dump($row);
		
		$testrow=array(
			"TransID"=>"1069",
			"Title"=>"Not Owned",
			"Store"=>"None",
			"BundleID"=>"1069",
			"Tier"=>null,
			"PurchaseDate"=>NULL,
			"PurchaseTime"=>NULL,
			"Sequence"=>NULL,
			"Price"=>NULL,
			"Fees"=>NULL,
			"Paid"=>null,
			"Credit Used"=>NULL,
			"Bundle Link"=>NULL
		);
		
		$purchasObject=new Purchases(null,false,array(),array());

		$method = $this->getPrivateMethod( 'Purchases', 'purchaseRowFirstPass' );
		$result = $method->invokeArgs( $purchasObject, array($testrow) );

		//$newrow=$purchasObject->purchaseRowFirstPass($testrow);
		$this->assertisArray($result);
		//var_dump($result);
	}
	
	/**
	 * @medium
	 * @covers Purchases::purchaseRowFirstPass
	 * @uses Purchases::__construct
	 * @uses combinedate
	 * @uses dataAccess
	 * @uses getActivityCalculations
	 * @uses getAllCpi
	 * @uses getAllItems
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses timeduration
	 * @uses getCleanStringDate
	 */
	public function test_purchaseRowFirstPass_values() {
		$testrow=array(
			"TransID"=>"1069",
			"Title"=>"Not Owned",
			"Store"=>"None",
			"BundleID"=>"1069",
			"Tier"=>1,
			"PurchaseDate"=>"01/12/2020",
			"PurchaseTime"=>"10:30:00 AM",
			"Sequence"=>"2",
			"Price"=>"1.010",
			"Fees"=>"1.010",
			"Paid"=>"1.010",
			"Credit Used"=>"1.010",
			"Bundle Link"=>"link"
		);
		
		$purchasObject=new Purchases(null,false,array(),array());

		$method = $this->getPrivateMethod( 'Purchases', 'purchaseRowFirstPass' );
		$result = $method->invokeArgs( $purchasObject, array($testrow) );

		//$newrow=$purchasObject->purchaseRowFirstPass($testrow);
		$this->assertisArray($result);
		//var_dump($result);
	}
	
	/**
	 * @medium
	 * @covers Purchases::setvalue
	 * @uses dataAccess
	 * @uses Purchases
	 * @uses CalculateGameRow
	 * @uses combinedate
	 * @uses getActivityCalculations
	 * @uses getAllCpi
	 * @uses getAllItems
	 * @uses getCleanStringDate
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses timeduration
	 * @testWith [true, 20, 10, 20]
	 *           [false, 20, 10, 10]
	 */	
	public function test_setvalue($test,$value,$altvalue,$expected) {
		$gamearray=array(array("Game_ID"=>1,"Title"=>"gamename"));
		$itemarray=array(array("Item_ID"=>1,"Title"=>"itemname"));
		$purchasObject=new Purchases(null,false,$itemarray,$gamearray);

		$method = $this->getPrivateMethod( 'Purchases', 'setvalue' );
		$result = $method->invokeArgs( $purchasObject, array($test,$value,$altvalue) );
		
		//$result = $purchasObject->setvalue($test,$value,$altvalue);
		$this->assertEquals($expected,$result);
	}

	/**
	 * @medium
	 * @covers Purchases::divzero
	 * @uses dataAccess
	 * @uses Purchases
	 * @uses CalculateGameRow
	 * @uses combinedate
	 * @uses getActivityCalculations
	 * @uses getAllCpi
	 * @uses getAllItems
	 * @uses getCleanStringDate
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses timeduration
	 * @testWith [10, 2, 20, 5]
	 *           [10, 0, 20, 20]
	 */	
	public function test_divzero($numerator, $denominator, $ifzero, $expected) {
		$purchasObject=new Purchases(null,false,array(),array());

		$method = $this->getPrivateMethod( 'Purchases', 'divzero' );
		$result = $method->invokeArgs( $purchasObject, array($numerator,$denominator,$ifzero) );
		
		//$result = $purchasObject->divzero($numerator,$denominator,$ifzero);
		$this->assertEquals($expected,$result);
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