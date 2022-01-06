<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\SteamScrape.class.php";

/**
 * @group include
 * @group classtest
 */
final class SteamScrape_Test extends TestCase
{
	private $SteamScrape_obj;
	
	protected function setUp(): void
    {
		//807120 = Iratus: Lord of the Dead (Developer not loading)
		//17390 = Spore (all good)
		
        $this->SteamScrape_obj = new SteamScrape(17390);
    }	
	
    protected function tearDown(): void
    {
        unset($this->SteamScrape_obj);
    }
	
	/**
	 * @group slow
	 * @medium
	 * @covers SteamScrape::getStorePage
	 * @uses SteamScrape::__construct
	 * @uses CurlRequest
	 * @uses SteamScrape::getPage
	 * @uses SteamScrape::getPageTitle
	 * Time: 
	 */
    public function test_getStorePage() {
		$this->assertisString($this->SteamScrape_obj->getStorePage());
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getStorePage
	 * @covers SteamScrape::__construct
	 * Time: 
	 */
    public function test_getStorePage_null() {
		$scrapeobj=new SteamScrape();
		$this->assertNull($scrapeobj->getStorePage());
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getStorePage
	 * @uses SteamScrape::__construct
	 * Time: 
	 */
    public function test_getStorePage_pagetext() {
		$scrapeobj=new SteamScrape(17390);
		
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $scrapeobj, "raw text" );
		
		$this->assertEquals("raw text",$scrapeobj->getStorePage());
    }

	
	/**
	 * @group slow
	 * @medium
	 * @covers SteamScrape::getStorePage
	 * @uses SteamScrape::__construct
	 * @uses CurlRequest
	 * @uses SteamScrape::getPage
	 * @uses SteamScrape::getPageTitle	 
	 * Time: 
	 */
    public function test_getStorePage_title() {
		$scrapeobj=new SteamScrape(17390);
		
		$property = $this->getPrivateProperty( 'SteamScrape', 'pageTitle' );
		$property->setValue( $scrapeobj, "Welcome to Steam" );
		
		$this->assertFalse($scrapeobj->getStorePage());
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getPage
	 * @uses SteamScrape::__construct
	 * @uses CurlRequest
	 * Time: 
	 */
    public function test_getPage() {
		$method = $this->getPrivateMethod( 'SteamScrape', 'getPage' );
		$result = $method->invokeArgs( $this->SteamScrape_obj, array("localhost") );
		$this->assertisString($result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getPage
	 * @uses SteamScrape::__construct
	 * @uses CurlRequest
	 * Time: 
	 */
    public function test_getPage_null() {
		$method = $this->getPrivateMethod( 'SteamScrape', 'getPage' );
		$result = $method->invokeArgs( $this->SteamScrape_obj, array(null) );
		$this->assertNull($result);
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
	
}