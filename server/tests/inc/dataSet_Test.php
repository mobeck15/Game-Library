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
	 * @uses getKeywords
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
		$page = new dataSet();
		
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
}