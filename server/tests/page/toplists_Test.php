<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/toplists.class.php";

/**
 * @group pageclass
 * @testdox toplists_Test.php testing toplists.class.php
 */
class toplists_Test extends testprivate {
	/**
	 * @small
	 * @covers toplistsPage::buildHtmlBody
	 * @covers toplistsPage::__construct
	 * @testdox __construct & buildHtmlBody
	 * @uses toplistsPage
	 * @uses Page
	 * @uses timeduration
	 * @testWith [0]
	 *           [6]
	 */
	public function test_outputHtml($getdata) {
		$page = new toplistsPage();
		
		$_GET['topx']=$getdata;
		
		$onegame = array(
				'GrandTotal' => 1,
				'CountGame' => true,
				'Playable' => true,
				'Paid' => 1,
				'totalHrs' => 1,
				'ParentGameID' => 1,
				'Game_ID' => 1,
				'TimeLeftToBeat' => -1,
				'Status' => "Active",
				'Title' => "Title");
				
		$twogame = array(
				'GrandTotal' => 1,
				'CountGame' => true,
				'Playable' => true,
				'Paid' => 1,
				'totalHrs' => 1,
				'ParentGameID' => 1,
				'Game_ID' => 1,
				'TimeLeftToBeat' => -1,
				'Status' => "Done",
				'Title' => "Title");
				
		$calculations = array(
			1 => $onegame,
			2 => $twogame,
			3 => $onegame,
			4 => $onegame,
			5 => $onegame,
			6 => $onegame,
			7 => $onegame,
			8 => $onegame,
			9 => $onegame,
			10 => $onegame,
			11 => $onegame,
			12 => $onegame,
			13 => $onegame,
			14 => $onegame,
			15 => $onegame,
			15 => $onegame,
			16 => $onegame,
			17 => $onegame,
			18 => $onegame,
			19 => $onegame,
			20 => $onegame,
			21 => $onegame,
			22 => $onegame,
			23 => $onegame,
			24 => $onegame,
			25 => $onegame,
			26 => $onegame
		);
		
		$settings = array(
			"CountFree" => 0
		);
		
		$dataStub = $this->createStub(dataSet::class);
		$dataStub->method('getCalculations')
				 ->willReturn($calculations);
		$dataStub->method('getSettings')
				 ->willReturn($settings);
		$value = $this->getPrivateProperty( 'toplistsPage', 'data' );
		$value->setValue( $page , $dataStub );
		
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}