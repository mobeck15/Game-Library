<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/viewitem.class.php";

/**
 * @group pageclass
 * @testdox viewitem_Test.php testing viewitem.class.php
 */
class viewitem_Test extends testprivate 
{
	/**
	 * @medium
	 * @covers viewitemPage::buildHtmlBody
	 * @covers viewitemPage::__construct
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
	 * @uses makeIndex
	 * @uses timeduration
	 */
	public function test_outputHtml() {
		$page = new viewitemPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @medium
	 * @covers viewitemPage::buildHtmlBody
	 * @testdox buildHtmlBody-Item
	 */
	public function test_outputHtmlItem() {
		$page = new viewitemPage();
		$_GET["id"]=11;
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
	
	/**
	 * @medium
	 * @covers viewitemPage::buildHtmlBody
	 * @testdox buildHtmlBody-edit
	 */
	public function test_outputHtmlEdit() {
		$page = new viewitemPage();
		$_GET["id"]=11;
		$_GET["edit"]=1;
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @medium
	 * @testdox buildHtmlBody with POST
	 * @covers viewitemPage::buildHtmlBody
	 * @uses viewitemPage
	 * @uses Page
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
	 * @uses makeIndex
	 * @uses timeduration
	 */
	public function test_outputHtml_post() {
		$page = new viewitemPage();

		$_POST = Array ( 
		"ItemID"          => 513 ,
		"ProductID"       => 514,
		"TransID"         => 515,
		"ParentProductID" => 516 ,
		"Notes"           => "Notes" ,
		"Tier"            => "Tier" ,
		"ActivationKey"   => "",
		"SizeMB"          => 123 ,
		"Library"         => "Library",
		"DRM"             => "DRM",
		"OS"              => "OS" ,
		"purchasetime"    => "2014-08-28T11:35",
		"Sequence"        => "1" 
		);
		
		$dataAccessMock = $this->createMock(dataAccess::class);
		$dataAccessMock->expects($this->once())
                       ->method('updateItem')
                       ->with($this->anything());
		$maxID = $this->getPrivateProperty( 'viewitemPage', 'dataAccessObject' );
		$maxID->setValue( $page , $dataAccessMock );

		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
}