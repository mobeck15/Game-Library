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
	
}