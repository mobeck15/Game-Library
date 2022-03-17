<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath'].'\tests\inc\testprivate.inc.php';
require_once $GLOBALS['rootpath']."\inc\PriceCalculation.class.php";

/**
 * @group include
 * @group classtest
 */
final class PriceCalculation_Test extends testprivate
{
	private $PriceCalculation;
	
	protected function setUp(): void
    {
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
        $this->PriceCalculation = new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
    }	
	
    protected function tearDown(): void
    {
        unset($this->PriceCalculation);
    }
	
	/**
	 * @small
	 * @covers PriceCalculation::getPrice
	 * @uses PriceCalculation::__construct
	 */
    public function test_getPrice() {
		
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
    }

	/**
	 * @small
	 * @covers PriceCalculation::__construct
	 * @testWith ["price", 10]
	 *           ["HoursPlayed", 2]
	 *           ["HoursToBeat", 4]
	 *           ["MSRP", 20]
	 */
    public function test_PriceCalculationConstructor($pricetype,$value) {
		$property = $this->getPrivateProperty( 'PriceCalculation', $pricetype );
		$this->assertEquals( $property->getValue( $this->PriceCalculation ), $value );
    }

	/**
	 * @small
	 * @covers PriceCalculation::getVarianceFromMSRP
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getVariance
	 * @uses PriceCalculation::printCurrencyFormat
	 * @testWith [-10, false]
	 *           ["$-10.00", true]
	 */
	public function test_getVarianceFromMSRP1($expected, $print) {
		$this->assertEquals($expected,$this->PriceCalculation->getVarianceFromMSRP($print));
	}

	/**
	 * @small
	 * @covers PriceCalculation::getVarianceFromMSRPpct
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getVariancePct
	 * @uses PriceCalculation::printPercentFormat
	 * @testWith [50, false]
	 *           ["50.00%", true]
	 */
	public function test_getVarianceFromMSRPpct($expected, $print) {
		$this->assertEquals($expected,$this->PriceCalculation->getVarianceFromMSRPpct($print));
	}

	/**
	 * @small
	 * @covers PriceCalculation::getPricePerHourOfTimeToBeat
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getPriceperhour
	 * @uses PriceCalculation::printCurrencyFormat
	 * @testWith [2.5, false]
	 *           ["$2.50", true]
	 */
	public function test_getPricePerHourOfTimeToBeat($expected, $print) {
		$this->assertEquals($expected,$this->PriceCalculation->getPricePerHourOfTimeToBeat($print));
	}

	/**
	 * @small
	 * @covers PriceCalculation::getPricePerHourOfTimePlayed
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getPriceperhour
	 * @uses PriceCalculation::printCurrencyFormat
	 * @testWith [10, false]
	 *           ["$10.00", true]
	 */
	public function test_getPricePerHourOfTimePlayed1($expected, $print) {
		$this->assertEquals($expected,$this->PriceCalculation->getPricePerHourOfTimePlayed($print));
		//$this->assertEquals("$10.00",$this->PriceCalculation->getPricePerHourOfTimePlayed(true));
	}

	/**
	 * @small
	 * @covers PriceCalculation::getPricePerHourOfTimePlayedReducedAfter1Hour
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getLessXhour
	 * @uses PriceCalculation::printCurrencyFormat
	 * @uses PriceCalculation::getPriceperhour
	 * @testWith [5, false]
	 *           ["$5.00", true]
	 */
	public function test_getPricePerHourOfTimePlayedReducedAfter1Hour($expected, $print) {
		$this->assertEquals($expected,$this->PriceCalculation->getPricePerHourOfTimePlayedReducedAfter1Hour($print));
	}

	/**
	 * @small
	 * @covers PriceCalculation::getHoursTo01LessPerHour
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getHourstoXless
	 * @uses PriceCalculation::printDurationFormat
	 * @uses PriceCalculation::getPriceperhour
	 * @uses PriceCalculation::getHrsToTarget
	 * @uses timeduration
	 * @testWith [1.0004454454454454, false]
	 *           ["1:00:01", true]
	 */
	public function test_getHoursTo01LessPerHour($expected, $print) {
		$this->assertEquals($expected,$this->PriceCalculation->getHoursTo01LessPerHour($print));
	}

	/**
	 * @small
	 * @covers PriceCalculation::getHoursToDollarPerHour
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getHrsToTarget
	 * @uses PriceCalculation::printDurationFormat
	 * @uses timeduration
	 * @testWith [9.999444444444444, 1, false]
	 *           ["9:59:58", 1, true]
	 */
	public function test_getHoursToDollarPerHour($expected, $target, $print) {
		$this->assertEquals($expected,$this->PriceCalculation->getHoursToDollarPerHour($target,$print));
	}
	
	/**
	 * @small
	 * @covers PriceCalculation::getVariance
	 * @uses PriceCalculation::__construct
	 */
	public function test_getVariance1() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getVariance' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20 ) );
		$this->assertEquals(-10,$result);
	}
	
	/**
	 * @small
	 * @covers PriceCalculation::getVariancePct
	 * @uses PriceCalculation::__construct
	 */
	public function test_getVariancePct() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getVariancePct' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20 ) );
		$this->assertEquals(50,$result);
	}
	
	/**
	 * @small
	 * @covers PriceCalculation::getPriceperhour
	 * @uses PriceCalculation::__construct
	 * @testWith [10, 72000, 0.5]
	 *           [10, 3240, 10]
	 */
	public function test_getPriceperhour1($price, $seconds, $expected) {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getPriceperhour' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( $price , $seconds ) );
		$this->assertEquals($expected,$result);
	}
	
	/**
	 * @small
	 * @covers PriceCalculation::getLessXhour
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getPriceperhour
	 * @testWith [10,72000,1,0.023809523809523836]
	 *           [10,0,0,0]
	 */
	public function test_getLessXhour($price,$seconds,$xhour,$expected) {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getLessXhour' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( $price , $seconds, $xhour ) );
		$this->assertEquals($expected,$result);
	}
	
	/**
	 * @small
	 * @covers PriceCalculation::getHourstoXless
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getPriceperhour
	 * @uses PriceCalculation::getHrsToTarget
	 */
	public function test_getHourstoXless() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getHourstoXless' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20*60*60 ) );
		$this->assertEquals(0.408163265306122,$result);
	}
	
	/**
	 * @small
	 * @covers PriceCalculation::getHrsToTarget
	 * @uses PriceCalculation::__construct
	 * @testWith [10,0,5,2]
	 *           [10,18000,5,-3]
	 *           [10,18000,0,0]
	 */
	public function test_getHrsToTarget1($value, $seconds, $target, $expected) {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getHrsToTarget' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( $value , $seconds , $target) );
		$this->assertEquals($expected,$result);
	}	

	/**
	 * @small
	 * @covers PriceCalculation::printCurrencyFormat
	 * @uses PriceCalculation::__construct
	 */
	public function test_printCurrencyFormat() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'printCurrencyFormat' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 12.345 ) );
		$this->assertEquals("$12.35",$result);
	}

	/**
	 * @small
	 * @covers PriceCalculation::printPercentFormat
	 * @uses PriceCalculation::__construct
	 */
	public function test_printPercentFormat() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'printPercentFormat' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 12.345 ) );
		$this->assertEquals("12.35%",$result);
	}

	/**
	 * @small
	 * @covers PriceCalculation::printDurationFormat
	 * @uses PriceCalculation::__construct
	 * @uses timeduration
	 */
	public function test_printDurationFormat() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'printDurationFormat' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 12.345 ) );
		$this->assertEquals("12:20:42",$result);
	}
}