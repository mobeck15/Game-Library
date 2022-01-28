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
	 * @group fast
	 * @small
	 * @covers SteamScrape::getStorePage
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getPage
	 * @uses SteamScrape::getPageTitle
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getStorePage_base() {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
             ->willReturn('steam website code...');
		
		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

		$output=$this->SteamScrape_obj->getStorePage();

		$this->assertisString($output);
		$this->assertEquals('steam website code...',$output);
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
	 * @group fast
	 * @small
	 * @covers SteamScrape::getStorePage
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getPage
	 * @uses SteamScrape::getPageTitle	 
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getStorePage_title() {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
             ->willReturn(file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam.htm"));

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

		$this->assertFalse($this->SteamScrape_obj->getStorePage());
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getPage
	 * @uses SteamScrape::__construct
	 * Time: 
	 */
    public function test_getPage_base() {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
			 ->willReturn(file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam.htm"));

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

		$method = $this->getPrivateMethod( 'SteamScrape', 'getPage' );
		$result = $method->invokeArgs( $this->SteamScrape_obj, array("localhost", $stub) );
		$this->assertisString($result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getPage
	 * @uses SteamScrape::__construct
	 * Time: 
	 */
    public function test_getPage_null() {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
             ->willReturn(file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam.htm"));

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

		$method = $this->getPrivateMethod( 'SteamScrape', 'getPage' );
		$result = $method->invokeArgs( $this->SteamScrape_obj, array(null,$stub) );
		$this->assertNull($result);
    }

	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getPageTitle
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getPage
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getPageTitle_base() {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
             ->willReturn(file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam17390.htm"));

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

		$result=$this->SteamScrape_obj->getPageTitle();
		$this->assertisString($result);
		$this->assertEquals("SPORE™ on Steam",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getPageTitle
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getPage
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getPageTitle_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, ' Welcome to Steam ' );

		$result=$this->SteamScrape_obj->getPageTitle();
		$this->assertisString($result);
		$this->assertEquals("",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getDescription
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getPage
	 * @uses SteamScrape::getPageTitle	 
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getDescription_base() {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
			 ->willReturn(file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam17390.htm"));

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

		$result=$this->SteamScrape_obj->getDescription();
		$this->assertisString($result);
		$this->assertEquals("Be the architect of your own universe with Spore, an exciting single-player adventure. From Single Cell to Galactic God, evolve your creature in a universe of your own creations.",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getDescription
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getDescription_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam.htm") );

		$result=$this->SteamScrape_obj->getDescription();
		$this->assertisString($result);
		$this->assertEquals("",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getDescription
	 * @uses SteamScrape::__construct
	 * Time: 
	 */
    public function test_getDescription_exists() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'description' );
		$property->setValue( $this->SteamScrape_obj, "Game description text" );

		$result=$this->SteamScrape_obj->getDescription();
		$this->assertisString($result);
		$this->assertEquals("Game description text",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getTags
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getPage
	 * @uses SteamScrape::getPageTitle	 
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getTags_base() {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
			 ->willReturn(file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam17390.htm"));

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );
		
		$expected=array(
			'god game' => 'God Game',
			'open world' => 'Open World',
			'exploration' => 'Exploration',
			'casual' => 'Casual',
			'colony sim' => 'Colony Sim',
			'cute' => 'Cute',
			'sandbox' => 'Sandbox',
			'simulation' => 'Simulation',
			'space' => 'Space',
			'science' => 'Science',
			'funny' => 'Funny',
			'sci-fi' => 'Sci-fi',
			'aliens' => 'Aliens',
			'colorful' => 'Colorful',
			'cartoony' => 'Cartoony',
			'family friendly' => 'Family Friendly',
			'adventure' => 'Adventure',
			'comedy' => 'Comedy',
			'resource management' => 'Resource Management',
			'cartoon' => 'Cartoon');
		
		$result=$this->SteamScrape_obj->getTags();
		$this->assertisArray($result);
		$this->assertEquals($expected,$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getTags
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getTags_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam.htm") );

		$result=$this->SteamScrape_obj->getTags();
		$this->assertisArray($result);
		$this->assertEquals(array(),$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getTags
	 * @uses SteamScrape::__construct
	 * Time: 
	 */
    public function test_getTags_exists() {
		$expected=array("open world"=>"Open World","exploration"=>"Exploration","casual"=>"Casual");
		
		$property = $this->getPrivateProperty( 'SteamScrape', 'keywords' );
		$property->setValue( $this->SteamScrape_obj, $expected );

		$result=$this->SteamScrape_obj->getTags();
		$this->assertisArray($result);
		$this->assertEquals($expected,$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getTagList
	 * @uses SteamScrape::getTags
	 * @uses SteamScrape::__construct
	 * Time: 
	 */
    public function test_getTagList() {
		$source=array("open world"=>"Open World","exploration"=>"Exploration","casual"=>"Casual");
		$expected="Open World,Exploration,Casual";
		
		$property = $this->getPrivateProperty( 'SteamScrape', 'keywords' );
		$property->setValue( $this->SteamScrape_obj, $source );

		$result=$this->SteamScrape_obj->getTagList();
		$this->assertisString($result);
		$this->assertEquals($expected,$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getDetails
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getPage
	 * @uses SteamScrape::getPageTitle	 
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getDetails_base() {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
			 ->willReturn(file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam17390.htm"));

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );
		
		$expected=array("single-player"=>"Single-player","steam trading cards"=>"Steam Trading Cards");
		
		$result=$this->SteamScrape_obj->getDetails();
		$this->assertisArray($result);
		$this->assertEquals($expected,$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getDetails
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getDetails_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam.htm") );

		$result=$this->SteamScrape_obj->getDetails();
		$this->assertisArray($result);
		$this->assertEquals(array(),$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getDetails
	 * @uses SteamScrape::__construct
	 * Time: 
	 */
    public function test_getDetails_exists() {
		$expected=array("single-player"=>"Single-player","steam trading cards"=>"Steam Trading Cards");
		
		$property = $this->getPrivateProperty( 'SteamScrape', 'details' );
		$property->setValue( $this->SteamScrape_obj, $expected );

		$result=$this->SteamScrape_obj->getDetails();
		$this->assertisArray($result);
		$this->assertEquals($expected,$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getDetailList
	 * @uses SteamScrape::getDetails
	 * @uses SteamScrape::__construct
	 * Time: 
	 */
    public function test_getDetailList() {
		$source=array("single-player"=>"Single-player","steam trading cards"=>"Steam Trading Cards");
		$expected="Single-player,Steam Trading Cards";
		
		$property = $this->getPrivateProperty( 'SteamScrape', 'details' );
		$property->setValue( $this->SteamScrape_obj, $source );

		$result=$this->SteamScrape_obj->getDetailList();
		$this->assertisString($result);
		$this->assertEquals($expected,$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getGenre
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getPage
	 * @uses SteamScrape::getPageTitle	 
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getGenre_base() {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
			 ->willReturn(file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam17390.htm"));

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );
		
		$expected=array("action"=>"Action","casual"=>"Casual","simulation"=>"Simulation","strategy"=>"Strategy","adventure"=>"Adventure","rpg"=>"RPG",);
		
		$result=$this->SteamScrape_obj->getGenre();
		$this->assertisArray($result);
		$this->assertEquals($expected,$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getGenre
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getGenre_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam.htm") );

		$result=$this->SteamScrape_obj->getGenre();
		$this->assertisArray($result);
		$this->assertEquals(array(),$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getGenre
	 * @uses SteamScrape::__construct
	 * Time: 
	 */
    public function test_getGenre_exists() {
		$expected=array("action"=>"Action","casual"=>"Casual","simulation"=>"Simulation","strategy"=>"Strategy","adventure"=>"Adventure","rpg"=>"RPG",);
		
		$property = $this->getPrivateProperty( 'SteamScrape', 'genre' );
		$property->setValue( $this->SteamScrape_obj, $expected );

		$result=$this->SteamScrape_obj->getGenre();
		$this->assertisArray($result);
		$this->assertEquals($expected,$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getGenreList
	 * @uses SteamScrape::getGenre
	 * @uses SteamScrape::__construct
	 * Time: 
	 */
    public function test_getGenreList() {
		$source=array("action"=>"Action","casual"=>"Casual","simulation"=>"Simulation","strategy"=>"Strategy","adventure"=>"Adventure","rpg"=>"RPG",);
		$expected="Action,Casual,Simulation,Strategy,Adventure,RPG";
		
		$property = $this->getPrivateProperty( 'SteamScrape', 'genre' );
		$property->setValue( $this->SteamScrape_obj, $source );

		$result=$this->SteamScrape_obj->getGenreList();
		$this->assertisString($result);
		$this->assertEquals($expected,$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getReview
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getPage
	 * @uses SteamScrape::getPageTitle	 
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getReview_base() {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
			 ->willReturn(file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam17390.htm"));

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

		$result=$this->SteamScrape_obj->getReview();
		$this->assertisString($result);
		$this->assertEquals("91",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getReview
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getReview_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam.htm") );

		$result=$this->SteamScrape_obj->getReview();
		$this->assertisString($result);
		$this->assertEquals("",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getReview
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getReview_1entry() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam10110.htm") );

		$result=$this->SteamScrape_obj->getReview();
		$this->assertisString($result);
		$this->assertEquals("84",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getReview
	 * @uses SteamScrape::__construct
	 * Time: 
	 */
    public function test_getReview_exists() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'review' );
		$property->setValue( $this->SteamScrape_obj, "91" );

		$result=$this->SteamScrape_obj->getReview();
		$this->assertisString($result);
		$this->assertEquals("91",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getReleaseDate
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getPage
	 * @uses SteamScrape::getPageTitle	 
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getReleaseDate_base() {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
			 ->willReturn(file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam17390.htm"));

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

		$result=$this->SteamScrape_obj->getReleaseDate();
		$this->assertisString($result);
		$this->assertEquals("Dec 19, 2008",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getReleaseDate
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getReleaseDate_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam.htm") );

		$result=$this->SteamScrape_obj->getReleaseDate();
		$this->assertisString($result);
		$this->assertEquals("",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getReleaseDate
	 * @uses SteamScrape::__construct
	 * Time: 
	 */
    public function test_getReleaseDate_exists() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'releaseDate' );
		$property->setValue( $this->SteamScrape_obj, "Dec 19, 2008" );

		$result=$this->SteamScrape_obj->getReleaseDate();
		$this->assertisString($result);
		$this->assertEquals("Dec 19, 2008",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getDeveloper
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getPage
	 * @uses SteamScrape::getPageTitle	 
	 * @uses SteamScrape::getdom
	 * @testWith ["steam17390.htm", "Maxis™"]
	 *           ["steam1807120.htm", "Unfrozen"]
	 * Time: 
	 */
    public function test_getDeveloper_base(string $testfilename, string $expecteddev) {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
			 ->willReturn(file_get_contents($GLOBALS['rootpath']."/tests/testdata/".$testfilename));

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

		$result=$this->SteamScrape_obj->getDeveloper();
		$this->assertisString($result);
		$this->assertEquals($expecteddev,$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getDeveloper
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getDeveloper_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam.htm") );

		try {
			$result=$this->SteamScrape_obj->getDeveloper();
		} catch (Exception $ex) {
			$this->assertEquals("No data found for : Developer",$ex->getMessage());
		}
		
		//$this->assertisString($result);
		//$this->assertEquals("",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getDeveloper
	 * @uses SteamScrape::__construct
	 * Time: 
	 */
    public function test_getDeveloper_exists() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'developer' );
		$property->setValue( $this->SteamScrape_obj, "Maxis™" );

		$result=$this->SteamScrape_obj->getDeveloper();
		$this->assertisString($result);
		$this->assertEquals("Maxis™",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getPublisher
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getPage
	 * @uses SteamScrape::getPageTitle	 
	 * @uses SteamScrape::getdom
	 * @testWith ["steam17390.htm", "Electronic Arts"]
	 *           ["steam1807120.htm", "Daedalic Entertainment"]
	 * Time: 
	 */
    public function test_getPublisher_base(string $testfilename, string $expectedpub) {
        $stub = $this->createStub(CurlRequest::class);
        $stub->method('execute')
			 ->willReturn(file_get_contents($GLOBALS['rootpath']."/tests/testdata/".$testfilename));

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

		$result=$this->SteamScrape_obj->getPublisher();
		$this->assertisString($result);
		$this->assertEquals($expectedpub,$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getPublisher
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getdom
	 * Time: 
	 */
    public function test_getPublisher_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam.htm") );

		$result="";
		try {
			$result=$this->SteamScrape_obj->getPublisher();
		} catch (Exception $ex) {
			$this->assertEquals("No data found for : Publisher",$ex->getMessage());
		}
		
		$this->assertisString($result);
		$this->assertEquals(0,strlen($result));
		$this->assertEquals("",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getPublisher
	 * @uses SteamScrape::__construct
	 * Time: 
	 */
    public function test_getPublisher_exists() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'publisher' );
		$property->setValue( $this->SteamScrape_obj, "Maxis™" );

		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam17390.htm") );

		$result=$this->SteamScrape_obj->getPublisher();
		$this->assertisString($result);
		$this->assertEquals("Maxis™",$result);
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