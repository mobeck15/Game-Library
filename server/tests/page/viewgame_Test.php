<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/viewgame.class.php";

/**
 * @group pageclass
 * @testdox viewgame_Test.php testing viewgame.class.php
 */
class viewgame_Test extends testprivate
{
	/**
	 * @small
	 * @covers viewgamePage::buildHtmlBody
	 * @covers viewgamePage::__construct
	 * @testdox __construct & buildHtmlBody
	 * @uses get_db_connection
	 * @uses lookupTextBox
	 */
	public function test_outputHtml() {
		$page = new viewgamePage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @large
	 * @covers viewgamePage::buildHtmlBody
	 * @testdox buildHtmlBody() detail
	 * @uses get_db_connection
	 * @uses lookupTextBox
	 * @uses CurlRequest
	 * @uses Games
	 * @uses PriceCalculation
	 * @uses Purchases
	 * @uses SteamAPI
	 * @uses SteamFormat
	 * @uses SteamScrape
	 * @uses boolText
	 * @uses combinedate
	 * @uses dataAccess
	 * @uses daysSinceDate
	 * @uses getActivityCalculations
	 * @uses getAllItems
	 * @uses getCalculations
	 * @uses getCleanStringDate
	 * @uses getGameDetail
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getHrsNextPosition
	 * @uses getHrsToTarget
	 * @uses getKeywords
	 * @uses getNextPosition
	 * @uses getPriceSort
	 * @uses getPriceperhour
	 * @uses getTimeLeft
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses timeduration
	 * @uses viewgamePage
	 */
	public function test_outputHtml_detail() {
		$_GET['id']='262'; //Portal: 262
		$page = new viewgamePage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
	
	/**
	 * @large
	 * @covers viewgamePage::buildHtmlBody
	 * @testdox buildHtmlBody() edit mode
	 * @uses get_db_connection
	 * @uses lookupTextBox
	 * @uses CurlRequest
	 * @uses Games
	 * @uses PriceCalculation
	 * @uses Purchases
	 * @uses SteamAPI
	 * @uses SteamFormat
	 * @uses SteamScrape
	 * @uses boolText
	 * @uses combinedate
	 * @uses dataAccess
	 * @uses daysSinceDate
	 * @uses getActivityCalculations
	 * @uses getAllItems
	 * @uses getCalculations
	 * @uses getCleanStringDate
	 * @uses getGameDetail
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getHrsNextPosition
	 * @uses getHrsToTarget
	 * @uses getKeywords
	 * @uses getNextPosition
	 * @uses getPriceSort
	 * @uses getPriceperhour
	 * @uses getTimeLeft
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses timeduration
	 * @uses viewgamePage
	 */
	public function test_outputHtml_edit() {
		$_GET['id']='262'; //Portal: 262
		$_GET['edit']='1'; 
		$page = new viewgamePage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @testdox buildHtmlBody with POST
	 * @covers viewgamePage::buildHtmlBody
	 * @uses viewgamePage
	 * @uses Page
	 * @uses CurlRequest
	 * @uses Games
	 * @uses PriceCalculation
	 * @uses Purchases
	 * @uses SteamAPI
	 * @uses SteamFormat
	 * @uses SteamScrape
	 * @uses boolText
	 * @uses combinedate
	 * @uses dataAccess
	 * @uses daysSinceDate
	 * @uses getActivityCalculations
	 * @uses getAllItems
	 * @uses getCalculations
	 * @uses getCleanStringDate
	 * @uses getGameDetail
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getHrsNextPosition
	 * @uses getHrsToTarget
	 * @uses getKeywords
	 * @uses getNextPosition
	 * @uses getPriceSort
	 * @uses getPriceperhour
	 * @uses getTimeLeft
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses lookupTextBox
	 * @uses makeIndex
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses timeduration
	 * @uses dataSet
	 */
	public function test_outputHtml_post() {
		$page = new viewgamePage();
	
		$_POST = Array ("ID" => 513 );
		
		$dataAccessMock = $this->createMock(dataAccess::class);
		$dataAccessMock->expects($this->once())
                       ->method('updateGame')
                       ->with($this->anything());
		$dataAccessMock->expects($this->once())
                       ->method('updateKeywords')
                       ->with($this->anything());
		$maxID = $this->getPrivateProperty( 'viewgamePage', 'dataAccessObject' );
		$maxID->setValue( $page , $dataAccessMock );

		$result = $page->buildHtmlBody();
	}
}