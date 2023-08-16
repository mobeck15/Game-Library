<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/waste.class.php";

/**
 * @group pageclass
 * @group WastePage
 * @testdox waste_Test.php testing waste.class.php
 */
class waste_Test extends testprivate {
	/**
	 * @small
	 * @covers wastePage::buildHtmlBody
	 * @covers wastePage::__construct
	 * @testdox __construct & buildHtmlBody
	 * @uses wastePage
	 * @uses combinedate
	 * @uses getCleanStringDate
	 * @uses reIndexArray
	 * @uses timeduration
	 * @uses wasteStats
	 */
	public function test_outputHtml() {
		$page = new wastePage();
		
		$calculations[1] = array(
			"Game_ID" => 1,
			'Status' => "Never",
			'GrandTotal' => 1,
			'Paid' => 2
		);
		$topList[] = array(
			'GameCount' => 1,
			'UnplayedCount' => 2,
			'TotalHistoricPlayed' => 3,
			'ModPaid' => 4,
			'Title' => 'Title',
			'PurchaseDate' => 12,
			'PurchaseTime' => 12,
			'PurchaseSequence' => 12,
			'RawData' => array(
				'GamesinBundle'=>array(
					array(
						'GameID' => 1,
						'Title' => 'Title',
						'HistoricLow' => 12.34 
					)
				) 
			)
		);
		$items = array();
		
		$property = $this->getPrivateProperty( 'wastePage', 'calculations' );
		$property->setValue( $page, $calculations );
		
		$property2 = $this->getPrivateProperty( 'wastePage', 'topList' );
		$property2->setValue( $page, $topList );

		$property3 = $this->getPrivateProperty( 'wastePage', 'items' );
		$property3->setValue( $page, $items );
		
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @covers wasteStats::countBundles
	 * @covers wasteStats::__construct
	 * @uses wasteStats
	 * @testdox __construct & countBundles
	 */
	 public function test_countBundles() {
		$page = new wasteStats();
		
		$topList[] = array(
			'TotalHistoricPlayed' => 1,
			'ModPaid' => 2,
			'GameCount' => 1,
			'UnplayedCount' => 2
		);
		
		$page->countBundles($topList);
		$this->assertTrue($page->BundleCount > 0);
	}
	
	/**
	 * @small
	 * @covers wasteStats::countGames
	 * @uses wasteStats
	 * @testdox countGames
	 */
	 public function test_countGames() {
		$page = new wasteStats();
		
		$topList[] = array(
			'Status' => "Never",
			'GrandTotal' => 10,
			'Paid' => 0
		);
		
		$page->countGames($topList);
		$this->assertTrue($page->NeverHours > 0);
	}
	
	/**
	 * @small
	 * @covers wasteStats::countItems
	 * @uses wasteStats
	 * @testdox countItems
	 */
	 public function test_countItems() {
		$page = new wasteStats();
		
		$topList[] = array(
			'Library' => "Inactive"
		);
		
		$page->countItems($topList);
		$this->assertTrue($page->DupeCount > 0);
	}

	/**
	 * @small
	 * @covers wastePage::buildBigUnplayedTable
	 * @uses wastePage
	 * @uses wasteStats
	 * @testdox buildBigUnplayedTable
	 */
	 public function test_buildBigUnplayedTable() {
		$page = new wastePage();
		
		$UnPlayedList[] = array(
			'BundleKey' => 1
		);
		
		$topList[1] = array(
			'UnplayedCount' => 1,
			'Title' => 'Title',
			'ModPaid' => 12.34
		);

		$method = $this->getPrivateMethod( 'wastePage', 'buildBigUnplayedTable' );
		$result = $method->invokeArgs( $page,array($UnPlayedList,$topList) );
		
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @covers wastePage::buildOldUnplayedTable
	 * @uses wastePage
	 * @uses wasteStats
	 * @uses combinedate
	 * @uses getCleanStringDate
	 * @testdox buildOldUnplayedTable
	 */
	 public function test_buildOldUnplayedTable() {
		$page = new wastePage();
		
		$UnPlayedList[] = array(
			'BundleKey' => 1
		);
		
		$topList[1] = array(
			'UnplayedCount' => 1,
			'Title' => 'Title',
			'PurchaseDate' => 12,
			'PurchaseTime' => 12,
			'PurchaseSequence' => 12
		);

		$method = $this->getPrivateMethod( 'wastePage', 'buildOldUnplayedTable' );
		$result = $method->invokeArgs( $page,array($UnPlayedList,$topList) );
		
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @covers wastePage::buildUnplayedTable
	 * @uses wastePage
	 * @uses wasteStats
	 * @testdox buildUnplayedTable
	 * @testWith [1,2,3]
	 *           [0,0,0]
	 */
	 public function test_buildUnplayedTable($upBundleCount,$upGames,$upSpent) {
		$page = new wastePage();
		
		$UnPlayedList[] = array(
			'BundleKey' => 1
		);
		
		$method = $this->getPrivateMethod( 'wastePage', 'buildUnplayedTable' );
		$result = $method->invokeArgs( $page,array($upBundleCount,$upGames,$upSpent) );
		
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @covers wastePage::buildOverpaidTable
	 * @uses wastePage
	 * @uses wasteStats
	 * @uses timeduration
	 * @testdox buildOverpaidTable
	 */
	 public function test_buildOverpaidTable() {
		$page = new wastePage();
		$waste = new wasteStats();
		
		$method = $this->getPrivateMethod( 'wastePage', 'buildOverpaidTable' );
		$result = $method->invokeArgs( $page,array($waste) );
		
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @covers wastePage::buildBigOverpaidTable
	 * @uses wastePage
	 * @uses wasteStats
	 * @testdox buildBigOverpaidTable
	 */
	 public function test_buildBigOverpaidTable() {
		$page = new wastePage();
		$waste = new wasteStats();
		
		$OverPaidList[] = array(
			'BundleKey' => 1
		);
		
		$topList[1] = array(
			'UnplayedCount' => 1,
			'RawData' => array(
				'GamesinBundle'=>array(
					array(
						'GameID' => 1,
						'Title' => 'Title',
						'HistoricLow' => 12.34 
					)
				) 
			),
			'diff' => 2,
			'ModPaid' => 3,
			'TotalHistoricPlayed' => 4,
			'Title' => 'Title'
		);
		
		$calculations[1] = array(
			'Title' => 'Title',
			'HistoricLow' => 12.34,
			'GrandTotal' => 0
		);
		
		$method = $this->getPrivateMethod( 'wastePage', 'buildBigOverpaidTable' );
		$result = $method->invokeArgs( $page,array($OverPaidList,$topList,$calculations) );
		
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @covers wastePage::buildOverpaidGamesTable
	 * @uses wastePage
	 * @uses wasteStats
	 * @testdox buildOverpaidGamesTable
	 */
	 public function test_buildOverpaidGamesTable() {
		$page = new wastePage();
		$waste = new wasteStats();
		
		$GamesfromOverpaid[] = array(
			'GameID' => 1
		);
		
		$calculations[1] = array(
			'Title' => 'Title',
			'HistoricLow' => 12.34
		);
		
		$method = $this->getPrivateMethod( 'wastePage', 'buildOverpaidGamesTable' );
		$result = $method->invokeArgs( $page,array($GamesfromOverpaid,$calculations) );
		
		$this->assertisString($result);
	}
	
	/**
	 * @large
	 * @testdox getCalculations()
	 * @covers wastePage::getCalculations
	 * @uses wastePage
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
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses regroupArray
	 * @uses timeduration
	 */
	public function test_getCalculations() {
		$page = new wastePage();
		
		$method = $this->getPrivateMethod( 'wastePage', 'getCalculations' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertisArray($result);
	}
	
	/**
	 * @large
	 * @testdox getTopList()
	 * @covers wastePage::getTopList
	 * @uses wastePage
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
	 */
	public function test_getTopList() {
		$page = new wastePage();
		
		$method = $this->getPrivateMethod( 'wastePage', 'getTopList' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertisArray($result);
	}
	
	/**
	 * @large
	 * @testdox getAllItems()
	 * @covers wastePage::getAllItems
	 * @uses wastePage
	 * @uses getAllItems
	 * @uses get_db_connection
	 */
	public function test_getAllItems() {
		$page = new wastePage();
		
		$method = $this->getPrivateMethod( 'wastePage', 'getAllItems' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertisArray($result);
	}
}