<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\dataSet.class.php";
require_once $GLOBALS['rootpath'].'\tests\inc\testprivate.inc.php';

/**
 * @group dataSet
 * @testdox dataSet_Test.php testing dataSet.class.php
 */
class dataSet_Test extends testprivate {
	/**
	 * @large
	 * @testdox getCalculations()
	 * @covers dataSet::getCalculations
	 * @uses dataSet
	 * @uses Games
	 * @uses PriceCalculation
	 * @uses Purchases
	 * @uses combinedate
	 * @uses dataAccess
	 * @uses daysSinceDate
	 * @uses getActivityCalculations
	 * @uses getAllItems
	 * @uses getCalculations
	 * @uses getCleanStringDate
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getHrsNextPosition
	 * @uses getHrsToTarget
	 * @uses getNextPosition
	 * @uses getPriceSort
	 * @uses getPriceperhour
	 * @uses getTimeLeft
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses timeduration
	 */
	public function test_getCalculations() {
		$page = new dataSet();
		
		$method = $this->getPrivateMethod( 'dataSet', 'getCalculations' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertisArray($result);
	}

	/**
	 * @large
	 * @testdox getTopBundles()
	 * @covers dataSet::getTopBundles
	 * @uses dataSet
	 */
	public function test_getTopBundles() {
		$testData = $this->getTestDataSet();
		$page = new dataSet(calculations: $testData->getCalculations());
		
		$method = $this->getPrivateMethod( 'dataSet', 'getTopBundles' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertisArray($result);
	}

	/**
	 * @large
	 * @testdox getHistory()
	 * @covers dataSet::getHistory
	 * @uses dataSet
	 */
	public function test_getHistory() {
		$page = new dataSet();
		
		$method = $this->getPrivateMethod( 'dataSet', 'getHistory' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertisArray($result);
	}

	/**
	 * @small
	 * @testdox getSettings()
	 * @covers dataSet::getSettings
	 * @covers dataSet::__construct
	 * @uses dataSet
	 * @uses get_db_connection
	 * @uses getsettings
	 */
	public function test_getSettings() {
		$page = new dataSet();
		
		$method = $this->getPrivateMethod( 'dataSet', 'getSettings' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertisArray($result);
	}

	/**
	 * @large
	 * @testdox getAllItems()
	 * @covers dataSet::getAllItems
	 * @uses dataSet
	 * @uses getAllItems
	 * @uses get_db_connection
	 */
	public function test_getAllItems() {
		$page = new dataSet();
		
		$method = $this->getPrivateMethod( 'dataSet', 'getAllItems' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertisArray($result);
	}
	
	/**
	 * @small
	 * @testdox getPurchases()
	 * @covers dataSet::getPurchases
	 * @uses dataSet
	 */
	public function test_getPurchases() {
		// Fake data the Purchases mock will return
		$fakePurchases = [
			['TransID' => 1, 'BundleID' => 1, 'ProductsInBundle' => []],
			['TransID' => 2, 'BundleID' => 2, 'ProductsInBundle' => []]
		];

		// Create Purchases mock
		$purchasesMock = $this->createMock(Purchases::class);
		$purchasesMock
			->expects($this->once())
			->method('getPurchases')
			->willReturn($fakePurchases);

		// Create partial mock of dataSet to inject Purchases mock
		$dataSetMock = $this->getMockBuilder(dataSet::class)
			->onlyMethods(['createPurchasesInstance']) // helper we'll add in dataSet
			->getMock();

		$dataSetMock
			->expects($this->once())
			->method('createPurchasesInstance')
			->willReturn($purchasesMock);

		// Call method
		$result = $dataSetMock->getPurchases();

		// Assertions
		$this->assertIsArray($result);
		$this->assertSame($fakePurchases, $result);
	}
	
	/**
	 * @small
	 * @testdox getKeywords()
	 * @covers dataSet::getKeywords
	 * @uses dataSet
	 * @uses Keywords
	 * @uses dataAccess
	 */
	public function test_getKeywords() {
		$testData = $this->getTestDataSet();
		$page = new dataSet(calculations: $testData->getCalculations());
		
		$method = $this->getPrivateMethod( 'dataSet', 'getKeywords' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertInstanceOf( Keywords::class, $result);
	}

	/**
	 * @small
	 * @testdox getKeywords() with override
	 * @covers dataSet::getKeywords
	 * @uses dataSet
	 * @uses Keywords
	 * @uses dataAccess
	 */
	public function test_getKeywords_override() {
		$kwobject = new Keywords();
		$useData = ["one"];
		
		$property = $this->getPrivateProperty( 'Keywords', 'data' );
		$data = $property->SetValue( $kwobject, $useData);
		
		$page = new dataSet(keywords: $kwobject);
		
		$result = $page->getKeywords();
		
		$this->assertInstanceOf( Keywords::class, $result);
	}
	
	
	/**
	 * @small
	 * @testdox createPurchasesInstance()
	 * @covers dataSet::createPurchasesInstance
	 * @uses dataSet
	 * @uses Purchases
	 * @uses combinedate
	 * @uses dataAccess
	 * @uses getCleanStringDate
	 * @uses makeIndex
	 */
	public function test_createPurchasesInstance() {
		$page = new dataSet();
		$method = $this->getPrivateMethod( 'dataSet', 'createPurchasesInstance' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertInstanceOf( Purchases::class, $result);
	}
	
	/**
	 * @small
	 * @testdox createTopBundlesInstance()
	 * @covers dataSet::createTopBundlesInstance
	 * @uses dataSet
	 * @uses TopList
	 */
	public function test_createTopBundlesInstance() {
		$page = new dataSet();
		$method = $this->getPrivateMethod( 'dataSet', 'createTopBundlesInstance' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertInstanceOf( TopList::class, $result);
	}

}