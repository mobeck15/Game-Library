<?php 
declare(strict_types=1);
//command> phpunit .\htdocs\Game-Library\server\tests
//phpunit --coverage-text .\htdocs\Game-Library\server\tests

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
require_once $GLOBALS['rootpath']."\inc\PriceCalculation.class.php";

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
	 * @covers PriceCalculation::getPrice
	 * @uses PriceCalculation::__construct
	 */
    public function test_getPrice() {
		
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
    }

	/**
	 * @covers PriceCalculation::getVarianceFromMSRPpct
	 * @uses PriceCalculation::__construct
	 * /
	public function test_getVarianceFromMSRPpct() {
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
	}

	/**
	 * @covers PriceCalculation::getPricePerHourOfTimeToBeat
	 * @uses PriceCalculation::__construct
	 * /
	public function test_getPricePerHourOfTimeToBeat() {
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
	}

	/**
	 * @covers PriceCalculation::getPricePerHourOfTimePlayed
	 * @uses PriceCalculation::__construct
	 * /
	public function test_getPricePerHourOfTimePlayed() {
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
	}

	/**
	 * @covers PriceCalculation::getPricePerHourOfTimePlayedReducedAfter1Hour
	 * @uses PriceCalculation::__construct
	 * /
	public function test_getPricePerHourOfTimePlayedReducedAfter1Hour() {
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
	}

	/**
	 * @covers PriceCalculation::getHoursTo01LessPerHour
	 * @uses PriceCalculation::__construct
	 * /
	public function test_getHoursTo01LessPerHour() {
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
	}

	/**
	 * @covers PriceCalculation::getHoursToDollarPerHour
	 * @uses PriceCalculation::__construct
	 * /
	public function test_getHoursToDollarPerHour() {
		$this->assertEquals($this->PriceCalculation->getPrice(), 10);
	}
	
	/**
	 * @covers PriceCalculation::getVariance
	 * @uses PriceCalculation::__construct
	 */
	public function test_getVariance() {
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'getVariance' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20 ) );
		
		$this->assertEquals(-10,$result);
	}
	
	/**
	 * @covers PriceCalculation::getVariancePct
	 * @uses PriceCalculation::__construct
	 */
	public function test_getVariancePct() {
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'getVariancePct' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20 ) );
		
		$this->assertEquals(50,$result);
	}
	
	/**
	 * @covers PriceCalculation::getPriceperhour
	 * @uses PriceCalculation::__construct
	 */
	public function test_getPriceperhour() {
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'getPriceperhour' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20*60*60 ) );
		
		$this->assertEquals(.5,$result);
	}
	
	/**
	 * @covers PriceCalculation::getLessXhour
	 * @uses PriceCalculation::__construct
	 */
	public function test_getLessXhour() {
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'getLessXhour' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20*60*60 ) );
		
		$this->assertEquals(0.023809523809523836,$result);
	}
	
	/**
	 * @covers PriceCalculation::getHourstoXless
	 * @uses PriceCalculation::__construct
	 */
	public function test_getHourstoXless() {
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'getHourstoXless' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20*60*60 ) );
		
		$this->assertEquals(0.408163265306122,$result);
	}
	
	/**
	 * @covers PriceCalculation::getHrsToTarget
	 * @uses PriceCalculation::__construct
	 */
	public function test_getHrsToTarget() {
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'getHrsToTarget' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 0 , 5) );
		
		$this->assertEquals(2,$result);
	}

	/**
	 * @covers PriceCalculation::printCurrencyFormat
	 * @uses PriceCalculation::__construct
	 */
	public function test_printCurrencyFormat() {
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'printCurrencyFormat' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $this->PriceCalculation, array( 12.345 ) );
		
		$this->assertEquals("$12.35",$result);
	}

	/**
	 * @covers PriceCalculation::printPercentFormat
	 * @uses PriceCalculation::__construct
	 */
	public function test_printPercentFormat() {
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'printPercentFormat' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $this->PriceCalculation, array( 12.345 ) );
		
		$this->assertEquals("12.35%",$result);
	}

	/**
	 * @covers PriceCalculation::printDurationFormat
	 * @uses PriceCalculation::__construct
 */
	public function test_printDurationFormat() {
		$reflector = new ReflectionClass( 'PriceCalculation' );
		$method = $reflector->getMethod( 'printDurationFormat' );
		$method->setAccessible( true );
		
		$result = $method->invokeArgs( $this->PriceCalculation, array( 12.345 ) );
		
		$this->assertEquals("12:20:42",$result);
	}
}