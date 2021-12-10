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
	 * @covers PriceCalculation::__construct
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
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getVariance' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20 ) );
		$this->assertEquals(-10,$result);
	}
	
	/**
	 * @covers PriceCalculation::getVariancePct
	 * @uses PriceCalculation::__construct
	 */
	public function test_getVariancePct() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getVariancePct' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20 ) );
		$this->assertEquals(50,$result);
	}
	
	/**
	 * @covers PriceCalculation::getPriceperhour
	 * @uses PriceCalculation::__construct
	 */
	public function test_getPriceperhour() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getPriceperhour' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20*60*60 ) );
		$this->assertEquals(.5,$result);
	}
	
	/**
	 * @covers PriceCalculation::getLessXhour
	 * @uses PriceCalculation::__construct
	 */
	public function test_getLessXhour() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getLessXhour' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20*60*60 ) );
		$this->assertEquals(0.023809523809523836,$result);
	}
	
	/**
	 * @covers PriceCalculation::getHourstoXless
	 * @uses PriceCalculation::__construct
	 */
	public function test_getHourstoXless() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getHourstoXless' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 20*60*60 ) );
		$this->assertEquals(0.408163265306122,$result);
	}
	
	/**
	 * @covers PriceCalculation::getHrsToTarget
	 * @uses PriceCalculation::__construct
	 */
	public function test_getHrsToTarget() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'getHrsToTarget' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 10 , 0 , 5) );
		$this->assertEquals(2,$result);
	}

	/**
	 * @covers PriceCalculation::printCurrencyFormat
	 * @uses PriceCalculation::__construct
	 */
	public function test_printCurrencyFormat() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'printCurrencyFormat' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 12.345 ) );
		$this->assertEquals("$12.35",$result);
	}

	/**
	 * @covers PriceCalculation::printPercentFormat
	 * @uses PriceCalculation::__construct
	 */
	public function test_printPercentFormat() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'printPercentFormat' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 12.345 ) );
		$this->assertEquals("12.35%",$result);
	}

	/**
	 * @covers PriceCalculation::printDurationFormat
	 * @uses PriceCalculation::__construct
 */
	public function test_printDurationFormat() {
		$method = $this->getPrivateMethod( 'PriceCalculation', 'printDurationFormat' );
		$result = $method->invokeArgs( $this->PriceCalculation, array( 12.345 ) );
		$this->assertEquals("12:20:42",$result);
	}
}