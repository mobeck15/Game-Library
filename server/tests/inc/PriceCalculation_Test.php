<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\PriceCalculation.class.php";

//Time: 00:00.279, Memory: 46.00 MB
//(18 tests, 32 assertions)
/**
 * @group include
 * @group classtest
 */
final class PriceCalculation_Test extends TestCase
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
	 * @group fast
	 * @small
	 * @covers PriceCalculation::getPrice
	 * @uses PriceCalculation::__construct
	 * Time: 00:00.239, Memory: 46.00 MB
	 * (7 tests, 13 assertions)
	 */
    public function test_getPrice() {
		
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
    }

	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::__construct
	 * Time: 00:00.218, Memory: 46.00 MB
	 * (1 test, 4 assertions)
	 */
    public function test_PriceCalculationConstructor() {
		
		$property = $this->getPrivateProperty( 'PriceCalculation', 'price' );
		$this->assertEquals( $property->getValue( $this->PriceCalculation ), 10 );

		$property = $this->getPrivateProperty( 'PriceCalculation', 'HoursPlayed' );
		$this->assertEquals( $property->getValue( $this->PriceCalculation ), 2 );

		$property = $this->getPrivateProperty( 'PriceCalculation', 'HoursToBeat' );
		$this->assertEquals( $property->getValue( $this->PriceCalculation ), 4 );

		$property = $this->getPrivateProperty( 'PriceCalculation', 'MSRP' );
		$this->assertEquals( $property->getValue( $this->PriceCalculation ), 20 );

		
    }

	/**
 	 * getPrivateProperty
 	 *
 	 * @author	Joe Sexton <joe@webtipblog.com>
 	 * @param 	string $className
 	 * @param 	string $propertyName
 	 * @return	ReflectionProperty
	 * Source: https://www.webtipblog.com/unit-testing-private-methods-and-properties-with-phpunit/
 	 */
	public function getPrivateProperty( $className, $propertyName ) {
		$reflector = new ReflectionClass( $className );
		$property = $reflector->getProperty( $propertyName );
		$property->setAccessible( true );

		return $property;
	}

	/**
 	 * getPrivateMethod
 	 *
 	 * @author	Joe Sexton <joe@webtipblog.com>
 	 * @param 	string $className
 	 * @param 	string $methodName
 	 * @return	ReflectionMethod
	 * Source: https://www.webtipblog.com/unit-testing-private-methods-and-properties-with-phpunit/
 	 */
	public function getPrivateMethod( $className, $methodName ) {
		$reflector = new ReflectionClass( $className );
		$method = $reflector->getMethod( $methodName );
		$method->setAccessible( true );

		return $method;
	}
	
	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::getVarianceFromMSRP
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getVariance
	 * @uses PriceCalculation::printCurrencyFormat
	 * Time: 00:00.222, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
	public function test_getVarianceFromMSRP1() {
		$this->assertEquals(-10,$this->PriceCalculation->getVarianceFromMSRP());
		$this->assertEquals("$-10.00",$this->PriceCalculation->getVarianceFromMSRP(true));
	}

	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::getVarianceFromMSRPpct
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getVariancePct
	 * @uses PriceCalculation::printPercentFormat
	 * Time: 00:00.220, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
	public function test_getVarianceFromMSRPpct() {
		$this->assertEquals(50,$this->PriceCalculation->getVarianceFromMSRPpct());
		$this->assertEquals("50.00%",$this->PriceCalculation->getVarianceFromMSRPpct(true));
	}

	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::getPricePerHourOfTimeToBeat
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getPriceperhour
	 * @uses PriceCalculation::printCurrencyFormat
	 * Time: 00:00.223, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
	public function test_getPricePerHourOfTimeToBeat() {
		$this->assertEquals(2.5,$this->PriceCalculation->getPricePerHourOfTimeToBeat());
		$this->assertEquals("$2.50",$this->PriceCalculation->getPricePerHourOfTimeToBeat(true));
	}

	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::getPricePerHourOfTimePlayed
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getPriceperhour
	 * @uses PriceCalculation::printCurrencyFormat
	 * Time: 00:00.219, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
	public function test_getPricePerHourOfTimePlayed1() {
		$this->assertEquals(10,$this->PriceCalculation->getPricePerHourOfTimePlayed());
		$this->assertEquals("$10.00",$this->PriceCalculation->getPricePerHourOfTimePlayed(true));
	}

	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::getPricePerHourOfTimePlayedReducedAfter1Hour
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getLessXhour
	 * @uses PriceCalculation::printCurrencyFormat
	 * @uses PriceCalculation::getPriceperhour
	 * Time: 00:00.223, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
	public function test_getPricePerHourOfTimePlayedReducedAfter1Hour() {
		$this->assertEquals(5,$this->PriceCalculation->getPricePerHourOfTimePlayedReducedAfter1Hour());
		$this->assertEquals("$5.00",$this->PriceCalculation->getPricePerHourOfTimePlayedReducedAfter1Hour(true));
	}

	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::getHoursTo01LessPerHour
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getHourstoXless
	 * @uses PriceCalculation::printDurationFormat
	 * @uses PriceCalculation::getPriceperhour
	 * @uses PriceCalculation::getHrsToTarget
	 * @uses timeduration
	 * Time: 00:00.218, Memory: 46.00 MB
	 * OK (1 test, 2 assertions)
	 */
	public function test_getHoursTo01LessPerHour() {
		$this->assertEquals(1.0004454454454454,$this->PriceCalculation->getHoursTo01LessPerHour());
		$this->assertEquals("1:00:01",$this->PriceCalculation->getHoursTo01LessPerHour(true));
	}

	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::getHoursToDollarPerHour
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getHrsToTarget
	 * @uses PriceCalculation::printDurationFormat
	 * @uses timeduration
	 * Time: 00:00.218, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
	public function test_getHoursToDollarPerHour() {
		$this->assertEquals(9.999444444444444,$this->PriceCalculation->getHoursToDollarPerHour(1));
		$this->assertEquals("9:59:58",$this->PriceCalculation->getHoursToDollarPerHour(1,true));
	}
	
	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::getVariance
	 * @uses PriceCalculation::__construct
	 * Time: 00:00.221, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
	public function test_getVariance1() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getVariance' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20 ) );
		$this->assertEquals(-10,$result);
	}
	
	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::getVariancePct
	 * @uses PriceCalculation::__construct
	 * Time: 00:00.219, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
	public function test_getVariancePct() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getVariancePct' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20 ) );
		$this->assertEquals(50,$result);
	}
	
	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::getPriceperhour
	 * @uses PriceCalculation::__construct
	 * Time: 00:00.221, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
	public function test_getPriceperhour1() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getPriceperhour' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20*60*60 ) );
		$this->assertEquals(.5,$result);

		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , .9*60*60 ) );
		$this->assertEquals(10,$result);
	}
	
	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::getLessXhour
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getPriceperhour
	 * Time: 00:00.219, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
	public function test_getLessXhour() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getLessXhour' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20*60*60 ) );
		$this->assertEquals(0.023809523809523836,$result);

		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 0, 0 ) );
		$this->assertEquals(0,$result);
	}
	
	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::getHourstoXless
	 * @uses PriceCalculation::__construct
	 * @uses PriceCalculation::getPriceperhour
	 * @uses PriceCalculation::getHrsToTarget
	 * Time: 00:00.226, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
	public function test_getHourstoXless() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getHourstoXless' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20*60*60 ) );
		$this->assertEquals(0.408163265306122,$result);
	}
	
	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::getHrsToTarget
	 * @uses PriceCalculation::__construct
	 * Time: 00:00.220, Memory: 46.00 MB
	 * (1 test, 3 assertions)
	 */
	public function test_getHrsToTarget1() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getHrsToTarget' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 0 , 5) );
		$this->assertEquals(2,$result);

		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 5*60*60 , 5) );
		$this->assertEquals(-3,$result);

		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 5*60*60 , 0) );
		$this->assertEquals(0,$result);
	}

	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::printCurrencyFormat
	 * @uses PriceCalculation::__construct
	 * Time: 00:00.219, Memory: 46.00 MB
	 * OK (1 test, 1 assertion)
	 */
	public function test_printCurrencyFormat() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'printCurrencyFormat' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 12.345 ) );
		$this->assertEquals("$12.35",$result);
	}

	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::printPercentFormat
	 * @uses PriceCalculation::__construct
	 * Time: 00:00.219, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
	public function test_printPercentFormat() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'printPercentFormat' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 12.345 ) );
		$this->assertEquals("12.35%",$result);
	}

	/**
	 * @group fast
	 * @small
	 * @covers PriceCalculation::printDurationFormat
	 * @uses PriceCalculation::__construct
	 * @uses timeduration
	 * Time: 00:00.261, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
	public function test_printDurationFormat() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'printDurationFormat' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 12.345 ) );
		$this->assertEquals("12:20:42",$result);
	}
}