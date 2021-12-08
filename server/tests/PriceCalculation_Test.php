<?php 
declare(strict_types=1);
//command> phpunit .\htdocs\Game-Library\server\tests

/*
Getting PHPunit to work in XAMPP:
1) Download PHPunit.phar from this site
   https://phpunit.de/getting-started/phpunit-9.html
2) Copy the file to XAMPP/PHP folder
3) Rename or delete the existing phpunit file (no extention)
4) Rename the downloaded file to phpunit (no extention)
*/

use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = "htdocs\Game-Library\server";
require $GLOBALS['rootpath']."\inc\PriceCalculation.class.php";

final class PriceCalculation_Test extends TestCase
{
	private $PriceCalculation;
	
	/**
	 * @covers PriceCalculation::getPrice
	 */
    public function test_getPrice() {
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
        $this->PriceCalculation = new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
		
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
    }

	/**
	 * @covers PriceCalculation::getVarianceFromMSRPpct
	 * /
	public function test_getVarianceFromMSRPpct() {
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
	}

	/**
	 * @covers PriceCalculation::getPricePerHourOfTimeToBeat
	 * /
	public function test_getPricePerHourOfTimeToBeat() {
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
	}

	/**
	 * @covers PriceCalculation::getPricePerHourOfTimePlayed
	 * /
	public function test_getPricePerHourOfTimePlayed() {
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
	}

	/**
	 * @covers PriceCalculation::getPricePerHourOfTimePlayedReducedAfter1Hour
	 * /
	public function test_getPricePerHourOfTimePlayedReducedAfter1Hour() {
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
	}

	/**
	 * @covers PriceCalculation::getHoursTo01LessPerHour
	 * /
	public function test_getHoursTo01LessPerHour() {
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
	}

	/**
	 * @covers PriceCalculation::getHoursToDollarPerHour
	 * /
	public function test_getHoursToDollarPerHour() {
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
	}
	
	/**
	 * @covers PriceCalculation::getVariance
	 */
	public function test_getVariance() {
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
        $object = new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'getVariance' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $object, array( 10 , 20 ) );
		
		$this->assertEquals(-10,$result);
	}
	
	/**
	 * @covers PriceCalculation::getVariancePct
	 */
	public function test_getVariancePct() {
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
        $object = new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'getVariancePct' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $object, array( 10 , 20 ) );
		
		$this->assertEquals(50,$result);
	}
	
	/**
	 * @covers PriceCalculation::getPriceperhour
	 */
	public function test_getPriceperhour() {
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
        $object = new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'getPriceperhour' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $object, array( 10 , 20*60*60 ) );
		
		$this->assertEquals(.5,$result);
	}
	
	/**
	 * @covers PriceCalculation::getLessXhour
	 */
	public function test_getLessXhour() {
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
        $object = new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'getLessXhour' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $object, array( 10 , 20*60*60 ) );
		
		$this->assertEquals(0.023809523809523836,$result);
	}
	
	/**
	 * @covers PriceCalculation::getHourstoXless
	 */
	public function test_getHourstoXless() {
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
        $object = new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'getHourstoXless' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $object, array( 10 , 20*60*60 ) );
		
		$this->assertEquals(0.408163265306122,$result);
	}
	
	/**
	 * @covers PriceCalculation::getHrsToTarget
	 */
	public function test_getHrsToTarget() {
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
        $object = new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'getHrsToTarget' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $object, array( 10 , 0 , 5) );
		
		$this->assertEquals(2,$result);
	}

	/**
	 * @covers PriceCalculation::printCurrencyFormat
	 */
	public function test_printCurrencyFormat() {
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
        $object = new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'printCurrencyFormat' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $object, array( 12.345 ) );
		
		$this->assertEquals("$12.35",$result);
	}

	/**
	 * @covers PriceCalculation::printPercentFormat
	 */
	public function test_printPercentFormat() {
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
        $object = new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'printPercentFormat' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $object, array( 12.345 ) );
		
		$this->assertEquals("12.35%",$result);
	}

	/**
	 * @covers PriceCalculation::printDurationFormat
	 */
	public function test_printDurationFormat() {
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
        $object = new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP);
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'printDurationFormat' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $object, array( 12.345 ) );
		
		$this->assertEquals("12:20:42",$result);
	}
}