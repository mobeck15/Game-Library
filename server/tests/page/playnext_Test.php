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
	 * @small
	 * @covers playnextPage::buildHtmlBody
	 * @covers playnextPage::__construct
	 * @uses playnextPage
	 * @uses Page
	 * @testdox __construct & buildHtmlBody
	 */
	public function test_outputHtml() {
		$page = new playnextPage();
		
		$dataStub = $this->createStub(dataSet::class);
		$dataStub->method('getCalculations')
				 ->willReturn(array());
		$dataStub->method('getTopBundles')
				 ->willReturn(array());

		$property = $this->getPrivateProperty( 'playnextPage', 'data' );
		$property->setValue( $page, $dataStub );
		
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

	/**
	 * @small
	 * @testdox buildAllGamesTable()
	 * @covers playnextPage::buildAllGamesTable
	 * @uses playnextPage
	 */
	public function test_buildAllGamesTable() {
		$page = new playnextPage();

		$AllGamesList = array(
			1 => array(
				"GameID"=>1,
				"Title"=>"Title1",
				"TotalMetascore"=>"Title1",
				"Bundles"=>"Title1",
				"Unplayed"=>"Title1",
				"points"=>1,
				"PlayVPay"=>1
			)
		);
		$calculations = array(
			1 => array(
				"points"=>5,
				"Title"=>"Title1",
				"Metascore"=>"Title1",
				"UserMetascore"=>"Title1",
				"HistoricLow"=>"Title1"
			)
		);
		
		$method = $this->getPrivateMethod( 'playnextPage', 'buildAllGamesTable' );
		$result = $method->invokeArgs( $page,array($AllGamesList,$calculations) );
		$this->assertIsString($result);
	}
	
	/**
	 * @small
	 * @testdox makeOverPaidList()
	 * @covers playnextPage::makeOverPaidList
	 * @uses playnextPage
	 */
	public function test_makeOverPaidList() {
		$page = new playnextPage();

		$topList = array(
			1 => array(
			"TotalHistoricPlayed" => 1,
			"ModPaid" =>2,
			"UnplayedCount" => 3
			)
		);
		
		$method = $this->getPrivateMethod( 'playnextPage', 'makeOverPaidList' );
		$result = $method->invokeArgs( $page,array($topList) );
		$this->assertIsArray($result);
	}
	
	/**
	 * @small
	 * @testdox makeUnPlayedList()
	 * @covers playnextPage::makeUnPlayedList
	 * @uses playnextPage
	 */
	public function test_makeUnPlayedList() {
		$page = new playnextPage();

		$topList = array(
			1 => array(
			"GameCount" => 1,
			"UnplayedCount" => 3
			)
		);
		
		$method = $this->getPrivateMethod( 'playnextPage', 'makeUnPlayedList' );
		$result = $method->invokeArgs( $page,array($topList) );
		$this->assertIsArray($result);
	}
		
	/**
	 * @small
	 * @testdox makeBeatAvgList()
	 * @covers playnextPage::makeBeatAvgList
	 * @uses playnextPage
	 */
	public function test_makeBeatAvgList() {
		$page = new playnextPage();

		$topList = array(
			1 => array(
			"BeatAvg" => 1
			)
		);
		
		$method = $this->getPrivateMethod( 'playnextPage', 'makeBeatAvgList' );
		$result = $method->invokeArgs( $page,array($topList) );
		$this->assertIsArray($result);
	}
	
	/**
	 * @small
	 * @testdox makeBeatAvg2List()
	 * @covers playnextPage::makeBeatAvg2List
	 * @uses playnextPage
	 */
	public function test_makeBeatAvg2List() {
		$page = new playnextPage();

		$topList = array(
			1 => array(
			"BeatAvg2" => 1
			)
		);
		
		$method = $this->getPrivateMethod( 'playnextPage', 'makeBeatAvg2List' );
		$result = $method->invokeArgs( $page,array($topList) );
		$this->assertIsArray($result);
	}
	
	/**
	 * @small
	 * @testdox makeOneUnPlayedList()
	 * @covers playnextPage::makeOneUnPlayedList
	 * @uses playnextPage
	 */
	public function test_makeOneUnPlayedList() {
		$page = new playnextPage();

		$topList = array(
			1 => array(
			"UnplayedCount" => 1
			)
		);
		
		$method = $this->getPrivateMethod( 'playnextPage', 'makeOneUnPlayedList' );
		$result = $method->invokeArgs( $page,array($topList) );
		$this->assertIsArray($result);
	}
	
	/**
	 * @small
	 * @testdox makeSortKeys()
	 * @covers playnextPage::makeSortKeys
	 * @uses playnextPage
	 */
	public function test_makeSortKeys() {
		$page = new playnextPage();

		$AllGamesList = array(
			1 => array(
			"GameID" => 1,
			"TotalMetascore" => 1,
			"points" => 1
			)
		);
		
		$calculations = array(
			1 => array(
			"Metascore" => 1,
			"UserMetascore" => 1
			)
		);
		
		$method = $this->getPrivateMethod( 'playnextPage', 'makeSortKeys' );
		$result = $method->invokeArgs( $page,array($AllGamesList,$calculations) );
		$this->assertIsArray($result);
	}
	
	/**
	 * @small
	 * @testdox createAllGamesList()
	 * @covers playnextPage::createAllGamesList
	 * @uses playnextPage
	 */
	public function test_createAllGamesList() {
		$page = new playnextPage();

		$topList = array(
			1 => array(
			"TotalHistoricPlayed" => 2,
			"ModPaid" => 2,
			"UnplayedCount" => 1,
			"RawData" => array(
				"GamesinBundle" => array(
					array(
						"GameID" => 1
						)
					)
				),
			"Title" => "title",
			"PlayVPay" => 1,
			"UnplayedCount" => 1,
			"BeatAvg" => 0,
			"BeatAvg2" => 1,
			"GameCount" => 2
			),
			2 => array(
			"TotalHistoricPlayed" => 2,
			"ModPaid" => 2,
			"UnplayedCount" => 1,
			"RawData" => array(
				"GamesinBundle" => array(
					array(
						"GameID" => 1
						)
					)
				),
			"Title" => "title",
			"PlayVPay" => 1,
			"UnplayedCount" => 1,
			"BeatAvg" => 0,
			"BeatAvg2" => 1,
			"GameCount" => 2
			)
		);
		
		$calculations = array(
			1 => array(
			"GrandTotal" => 0,
			"Playable" => true,
			"CountGame" => true,
			"Playable" => 1,
			"Playable" => 1,
			"Metascore" => 1,
			"UserMetascore" => 1,
			"HistoricLow" => 1
			)
		);
		
		$method = $this->getPrivateMethod( 'playnextPage', 'createAllGamesList' );
		$result = $method->invokeArgs( $page,array($topList,$calculations) );
		$this->assertIsArray($result);
	}
	
	/**
	 * @small
	 * @testdox updateAllGamesList()
	 * @covers playnextPage::updateAllGamesList
	 * @uses playnextPage
	 */
	public function test_updateAllGamesList() {
		$page = new playnextPage();

		$AllGamesList = array(
			1 => array(
				"GameID" => 1,
				"PlayVPay" => 1,
				"Unplayed" => 1
			),
			2 => array(
				"GameID" => 2,
				"PlayVPay" => 1,
				"Unplayed" => 1
			),
			3 => array(
				"GameID" => 3,
				"PlayVPay" => 1,
				"Unplayed" => 1
			)
		);
		
		$calculations = array(
			1 => array(
			"GrandTotal" => 0,
			"Playable" => true,
			"CountGame" => true,
			"Playable" => 1,
			"Playable" => 1,
			"Metascore" => 0, 
			"UserMetascore" => 0, 
			"HistoricLow" => 1
			),
			2 => array(
			"GrandTotal" => 0,
			"Playable" => true,
			"CountGame" => true,
			"Playable" => 1,
			"Playable" => 1,
			"Metascore" => 1, 
			"UserMetascore" => 0, 
			"HistoricLow" => 1
			),
			3 => array(
			"GrandTotal" => 0,
			"Playable" => true,
			"CountGame" => true,
			"Playable" => 1,
			"Playable" => 1,
			"Metascore" => 1, 
			"UserMetascore" => 1, 
			"HistoricLow" => 0
			)
		);
		
		$method = $this->getPrivateMethod( 'playnextPage', 'updateAllGamesList' );
		$result = $method->invokeArgs( $page,array($AllGamesList,$calculations) );
		$this->assertIsArray($result);
	}

	/**
	 * @small
	 * @testdox makeAllGamesList()
	 * @covers playnextPage::makeAllGamesList
	 * @uses playnextPage
	 */
	public function test_makeAllGamesList() {
		$page = new playnextPage();

		$topList = array(
			1 => array(
			"TotalHistoricPlayed" => 2,
			"ModPaid" => 2,
			"UnplayedCount" => 1,
			"RawData" => array(
				"GamesinBundle" => array(
					array(
						"GameID" => 1
						)
					)
				),
			"Title" => "title",
			"PlayVPay" => 1,
			"UnplayedCount" => 1,
			"BeatAvg" => 0,
			"BeatAvg2" => 1,
			"GameCount" => 2
			)
		);
		
		$calculations = array(
			1 => array(
			"GrandTotal" => 0,
			"Playable" => true,
			"CountGame" => true,
			"Playable" => 1,
			"Playable" => 1,
			"Metascore" => 1,
			"UserMetascore" => 1,
			"HistoricLow" => 1
			)
		);
		
		$method = $this->getPrivateMethod( 'playnextPage', 'makeAllGamesList' );
		$result = $method->invokeArgs( $page,array($topList,$calculations) );
		$this->assertIsArray($result);
	}
}