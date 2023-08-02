<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/playnext.class.php";

/**
 * @group pageclass
 * @testdox playnext_Test.php testing playnext.class.php
 */
class playnext_Test extends testprivate 
{
	/**
	 * @large
	 * @covers playnextPage::buildHtmlBody
	 * @covers playnextPage::__construct
	 * @uses playnextPage
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
	 * @uses getKeywords
	 * @uses getNextPosition
	 * @uses getPriceSort
	 * @uses getPriceperhour
	 * @uses getTimeLeft
	 * @uses getTopList
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses reIndexArray
	 * @uses regroupArray
	 * @uses timeduration
	 * @testdox __construct & buildHtmlBody
	 */
	public function test_outputHtml() {
		$page = new playnextPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @testdox unplayedlist()
	 * @covers playnextPage::unplayedlist
	 * @uses playnextPage
	 */
	public function test_unplayedlist() {
		$page = new playnextPage();
		
		$UnPlayedList = array(array('BundleKey' => "key1"));
		$topList = array("key1" => array("Title"=>"title1", "UnplayedCount"=>"5"));
		
		$method = $this->getPrivateMethod( 'playnextPage', 'unplayedlist' );
		$result = $method->invokeArgs( $page,array($UnPlayedList,"Unplayed Bundles",$topList) );
		$this->assertIsString($result);
	}
	
	/**
	 * @small
	 * @testdox playnexttable()
	 * @covers playnextPage::playnexttable
	 * @uses playnextPage
	 */
	public function test_playnexttable_check() {
		$page = new playnextPage();
		
		$Sortby = array(
			"key1" => 1
		);
		$AllGamesList = array(
			1 => array(
				"GameID"=>1,
				"points"=>5,
				"Title"=>"Title1"
			)
		);
		$calculations = array(
			1 => array(
				"points"=>5,
				"Title"=>"Title1"
			)
		);
		
		$method = $this->getPrivateMethod( 'playnextPage', 'playnexttable' );
		$result = $method->invokeArgs( $page,array($Sortby,$AllGamesList,"Sort Points","Points","points",$calculations) );
		$this->assertIsString($result);
	}
	
	/**
	 * @small
	 * @testdox playnexttable()
	 * @covers playnextPage::playnexttable
	 * @uses playnextPage
	 */
	public function test_playnexttable_nocheck() {
		$page = new playnextPage();
		
		$Sortby = array(
			"key1" => 1
		);
		$AllGamesList = array(
			1 => array(
				"GameID"=>1,
				"Title"=>"Title1"
			)
		);
		$calculations = array(
			1 => array(
				"points"=>5,
				"Title"=>"Title1"
			)
		);
		
		$method = $this->getPrivateMethod( 'playnextPage', 'playnexttable' );
		$result = $method->invokeArgs( $page,array($Sortby,$AllGamesList,"Sort Points","Points","points",$calculations) );
		$this->assertIsString($result);
	}
}