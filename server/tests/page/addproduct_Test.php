<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/addproduct.class.php";

/**
 * @testdox addproduct_Test.php testing addproduct.class.php
 * @group pageclass
 */
class addproduct_Test extends testprivate 
{
	/**
	 * @testdox __construct & buildHtmlBody
	 * @small
	 * @covers addproductPage::buildHtmlBody
	 * @covers addproductPage::__construct
	 * @uses Page
	 * @uses addproductPage
	 * @uses dataAccess
	 */
	public function test_outputHtml() {
		//$_SERVER['QUERY_STRING']="";
		$page = new addproductPage();
		$result = $page->buildHtmlBody();
		
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @testdox buildHtmlBody with POST
	 * @covers addproductPage::buildHtmlBody
	 * @uses addproductPage
	 * @uses Page
	 */
	public function test_outputHtml_post() {
		$page = new addproductPage();
	
		$_POST = Array ( 
		"Game_ID"           => 1000,
		"Title"             => "Test Game 2",
		"Series"            => "Test Game",
		"LaunchDate"        => date("Y-m-d"),
		"LaunchPrice"       => 1.23,
		"MSRP"              => 1.23,
		"CurrentMSRP"       => 1.23,
		"HistoricLow"       => 1.23,
		"LowDate"           => date("Y-m-d"),
		"SteamAchievements" => 0,
		"SteamCards"        => 0,
		"TimeToBeat"        => 1.6,
		"Metascore"         => 34,
		"UserMetascore"     => 56,
		"SteamRating"       => 78,
		"SteamID"           => 1234567890,
		"GOGID"             => "gogID",
		"isthereanydealID"  => "dealID",
		"TimeToBeatID"      => 1234546,
		"MetascoreID"       => "metaID",
		"DateUpdated"       => date("Y-m-d"),
		"Want"              => 4,
		"Playable"          => 1,
		"Type"              => "Game",
		"ParentGameID"      => 1000,
		"ParentGame"        => "Parent Game",
		"Developer"         => "Developer",
		"Publisher"         => "Publisher"
		);

		$dataAccessMock = $this->createMock(dataAccess::class);
		$dataAccessMock->expects($this->once())
                       ->method('insertGame')
                       ->with($this->anything());
		$maxID = $this->getPrivateProperty( 'addproductPage', 'dataAccessObject' );
		$maxID->setValue( $page , $dataAccessMock );

		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
}