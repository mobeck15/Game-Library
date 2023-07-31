<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/viewbundle.class.php";

/**
 * @group pageclass
 * @group viewbundle
 * @testdox viewbundle_Test.php testing viewbundle.class.php
 */
class viewbundle_Test extends testprivate 
{
	/**
	 * @medium
	 * @covers viewbundlePage::buildHtmlBody
	 * @covers viewbundlePage::__construct
	 * @testdox __construct & buildHtmlBody
	 * @uses Games
	 * @uses Purchases
	 * @uses combinedate
	 * @uses dataAccess
	 * @uses getActivityCalculations
	 * @uses getAllItems
	 * @uses getCleanStringDate
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses lookupTextBox
	 * @uses makeIndex
	 * @uses timeduration
	 */
	public function test_outputHtml() {
		$page = new viewbundlePage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @medium
	 * @covers viewbundlePage
	 * @testdox buildHtmlBody-BundleView
	 */
	public function test_outputHtmlBundle() {
		$page = new viewbundlePage();
		$_GET["id"]=11;
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @medium
	 * @covers viewbundlePage
	 * @testdox buildHtmlBody-Edit
	 */
	public function test_outputHtmlEidtBundle() {
		$page = new viewbundlePage();
		$_GET["id"]=11;
		$_GET["edit"]=1;
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @covers viewbundlePage::makePrompt
	 * @uses viewbundlePage
	 * @uses lookupTextBox
	 * @testdox makePrompt
	 */
	public function test_makePrompt() {
		$page = new viewbundlePage();
		$method = $this->getPrivateMethod( 'viewbundlePage', 'makePrompt' );
		$result = $method->invokeArgs( $page, array() );
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @covers viewbundlePage::makeGamesTable
	 * @uses viewbundlePage
	 * @testdox makeGamesTable
	 */
	public function test_makeGamesTable() {
		$page = new viewbundlePage();
		$_GET["id"]="none";

		$testPurchases = array("none" => "one");
		$testPurchaseIndex = array("none" => "one");

		$property = $this->getPrivateProperty( 'viewbundlePage', 'purchases' );
		$property->setValue( $page, $testPurchases );

		$property2 = $this->getPrivateProperty( 'viewbundlePage', 'purchaseIndex' );
		$property2->setValue( $page, $testPurchaseIndex );

		$method = $this->getPrivateMethod( 'viewbundlePage', 'makeGamesTable' );
		$result = $method->invokeArgs( $page, array() );
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @covers viewbundlePage::makeItemsTable
	 * @uses viewbundlePage
	 * @testdox makeItemsTable
	 */
	public function test_makeItemsTable() {
		$page = new viewbundlePage();
		$_GET["id"]="none";

		$testPurchases = array("none" => "one");
		$testPurchaseIndex = array("none" => "one");

		$property = $this->getPrivateProperty( 'viewbundlePage', 'purchases' );
		$property->setValue( $page, $testPurchases );

		$property2 = $this->getPrivateProperty( 'viewbundlePage', 'purchaseIndex' );
		$property2->setValue( $page, $testPurchaseIndex );

		$method = $this->getPrivateMethod( 'viewbundlePage', 'makeItemsTable' );
		$result = $method->invokeArgs( $page, array() );
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @covers viewbundlePage::makeProductsTable
	 * @uses viewbundlePage
	 * @testdox makeProductsTable
	 */
	public function test_makeProductsTable() {
		$page = new viewbundlePage();
		$_GET["id"]="none";

		$testPurchases = array("none" => "one");
		$testPurchaseIndex = array("none" => "one");

		$property = $this->getPrivateProperty( 'viewbundlePage', 'purchases' );
		$property->setValue( $page, $testPurchases );

		$property2 = $this->getPrivateProperty( 'viewbundlePage', 'purchaseIndex' );
		$property2->setValue( $page, $testPurchaseIndex );

		$method = $this->getPrivateMethod( 'viewbundlePage', 'makeProductsTable' );
		$result = $method->invokeArgs( $page, array() );
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @testdox buildHtmlBody with POST
	 * @covers viewbundlePage::buildHtmlBody
	 * @uses Page
	 * @uses viewbundlePage
	 * @uses lookupTextBox
	 */
	public function test_outputHtml_post() {
		$page = new viewbundlePage();
		
		$_POST = Array ( 
		"TransID" => 513 ,
		"Title" => "PAX 10 Humble Flash Bundle",
		"Store" => "Humble Store ",
		"BundleID" => 513 ,
		"Tier" => 1 ,
		"purchasetime" => "2014-08-28T11:35",
		"Sequence" => 1 ,
		"Price" => 0.00 ,
		"Fees" => "",
		"Paid" => 1.00 ,
		"Credit" => "",
		"Link" => "https://www.humblebundle.com/downloads?key=UbqvNAY5sDVefYrM" 
		);
		
		
		$dataAccessMock = $this->createMock(dataAccess::class);
		$dataAccessMock->expects($this->once())
                       ->method('updateBundle')
                       ->with($this->anything());
		$maxID = $this->getPrivateProperty( 'viewbundlePage', 'dataAccessObject' );
		$maxID->setValue( $page , $dataAccessMock );

		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
}