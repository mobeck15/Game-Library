<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/additem.class.php";

/**
 * @testdox additem_Test.php testing additem.class.php
 * @group pageclass
 * @group additem
 */
class additem_Test extends testprivate 
{
	/**
	 * @testdox __construct & buildHtmlBody
	 * @small
	 * @covers additemPage::buildHtmlBody
	 * @covers additemPage::__construct
	 * @uses Get_Header
	 * @uses boolText
	 * @uses dataAccess
	 * @uses get_db_connection
	 * @uses get_navmenu
	 * @uses getsettings
	 */
	public function test_outputHtml() {
		$page = new additemPage();
		$result = $page->buildHtmlBody();
		
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @testdox getDataAccessObject()
	 * @covers additemPage::getDataAccessObject
	 * @uses additemPage
	 */
	public function test_getDataAccessObject() {
		$page = new additemPage();
		
		$method = $this->getPrivateMethod( 'additemPage', 'getDataAccessObject' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertThat($result,
			$this->isInstanceOf("dataAccess")
		);
	}
	
	/**
	 * @small
	 * @testdox buildHtmlBody with POST
	 * @covers additemPage::buildHtmlBody
	 * @uses additemPage
	 * @uses get_db_connection
	 */
	public function test_outputHtml_post() {
		$page = new additemPage();
	
		$_POST = Array ( 
		"ItemID"          => 513 ,
		"ProductID"       => 514,
		"TransID"         => 515,
		"ParentProductID" => 516 ,
		"Tier"            => 1 ,
		"Notes"           => "Notes" ,
		"SizeMB"          => 123 ,
		"DRM"             => "DRM",
		"OS"              => "OS" ,
		"ActivationKey"   => "",
		"DateAdded"       => "2014-08-28T11:35",
		"Time_Added"      => "2014-08-28T11:35",
		"Sequence"        => "1" ,
		"Library"         => "Library",
		
		"Product_ckbx"    => "on",
		
		"Game_ID"         => 1000,
		"Title"           => "Test Game 2",
		"Series"          => "Test Game",
		"LaunchDate"      => date("Y-m-d"),
		"SteamID"         => 1,
		"Want"            => 4,
		"Playable"        => 1,
		"Type"            => "Game",
		"ParentGameID"    => 1000,
		"ParentGame"      => "Parent Game"
		);
		
		$dataAccessMock = $this->createMock(dataAccess::class);
		$dataAccessMock->expects($this->once())
                       ->method('insertItem')
                       ->with($this->anything());
		$dataAccessMock->expects($this->once())
                       ->method('insertGame2')
                       ->with($this->anything());
		$maxID = $this->getPrivateProperty( 'additemPage', 'dataAccessObject' );
		$maxID->setValue( $page , $dataAccessMock );

		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
}