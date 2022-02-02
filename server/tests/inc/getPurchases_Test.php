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