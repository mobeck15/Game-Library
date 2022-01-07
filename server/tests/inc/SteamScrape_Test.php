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
	 * Time: 
	 */
    public function test_getStorePage_base() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
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
	 * Time: 
	 */
    public function test_getStorePage_title() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn(' <title>Welcome to Steam</title> ');

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

		$this->assertFalse($this->SteamScrape_obj->getStorePage($stub));
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getPage
	 * @uses SteamScrape::__construct
	 * Time: 
	 */
    public function test_getPage_base() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn(' <title>Welcome to Steam</title> ');

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
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn(' <title>Welcome to Steam</title> ');

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
	 * Time: 
	 */
    public function test_getPageTitle_base() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn(' <title>Welcome to Steam</title> ');

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

		$result=$this->SteamScrape_obj->getPageTitle();
		$this->assertisString($result);
		$this->assertEquals("Welcome to Steam",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getPageTitle
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * @uses SteamScrape::getPage
	 * Time: 
	 */
    public function test_getPageTitle_blank() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn(' Welcome to Steam ');

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

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
	 * Time: 
	 */
    public function test_getDescription_base() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn('<div class="game_description_snippet">	Game description text	</div>');

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

		$result=$this->SteamScrape_obj->getDescription();
		$this->assertisString($result);
		$this->assertEquals("Game description text",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getDescription
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * Time: 
	 */
    public function test_getDescription_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "Game description text" );

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

		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
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
	 * Time: 
	 */
    public function test_getTags_base() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn('<div class="glance_tags_label">Popular user-defined tags for this product:</div>
										<div class="glance_tags popular_tags" data-appid="17390">
											<a href="https://store.steampowered.com/tags/en/God%20Game/?snr=1_5_9__409" class="app_tag" style="display: none;">
												Open World	</a><a href="https://store.steampowered.com/tags/en/Exploration/?snr=1_5_9__409" class="app_tag" style="display: none;">
												Exploration	</a><a href="https://store.steampowered.com/tags/en/Casual/?snr=1_5_9__409" class="app_tag" style="display: none;">
												Casual	</a><div class="app_tag add_button" data-panel="{&quot;focusable&quot;:true,&quot;clickOnActivate&quot;:true}" onclick="ShowAppTagModal( 17390 )">+</div>
										</div>
									</div>');

		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );
		
		$expected=array("open world"=>"Open World","exploration"=>"Exploration","casual"=>"Casual");
		
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
	 * Time: 
	 */
    public function test_getTags_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "Open World, Exploration, Casual" );

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

		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "Game description text" );

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

		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "Game description text" );

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
	 * Time: 
	 */
    public function test_getDetails_base() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn('<a class="game_area_details_specs_ctn" data-panel="{&quot;flow-children&quot;:&quot;column&quot;}" href="https://store.steampowered.com/search/?category2=2&snr=1_5_9__423"><div class="icon"><img class="category_icon" src="https://store.akamai.steamstatic.com/public/images/v6/ico/ico_singlePlayer.png"></div><div class="label">Single-player</div></a><a class="game_area_details_specs_ctn" data-panel="{&quot;flow-children&quot;:&quot;column&quot;}" href="https://store.steampowered.com/search/?category2=29&snr=1_5_9__423"><div class="icon"><img class="category_icon" src="https://store.akamai.steamstatic.com/public/images/v6/ico/ico_cards.png"></div><div class="label">Steam Trading Cards</div></a>');

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
	 * Time: 
	 */
    public function test_getDetails_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "Single-player, Steam Trading Cards" );

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

		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "Game description text" );

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

		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "Game description text" );

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
	 * Time: 
	 */
    public function test_getGenre_base() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn('<b>Genre:</b> <span data-panel="{&quot;flow-children&quot;:&quot;row&quot;}"><a href="https://store.steampowered.com/genre/Action/?snr=1_5_9__408">Action</a>, <a href="https://store.steampowered.com/genre/Adventure/?snr=1_5_9__408">Adventure</a>, <a href="https://store.steampowered.com/genre/Casual/?snr=1_5_9__408">Casual</a>, <a href="https://store.steampowered.com/genre/RPG/?snr=1_5_9__408">RPG</a>, <a href="https://store.steampowered.com/genre/Simulation/?snr=1_5_9__408">Simulation</a>, <a href="https://store.steampowered.com/genre/Strategy/?snr=1_5_9__408">Strategy</a></span><br>');

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
	 * Time: 
	 */
    public function test_getGenre_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "Action,Casual,Simulation,Strategy,Adventure,RPG" );

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

		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "Game description text" );

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

		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "Game description text" );

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
	 * Time: 
	 */
    public function test_getReview_base() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn('<div class="user_reviews_summary_row" onclick="window.location=\'#app_reviews_hash\'" style="cursor: pointer;" data-tooltip-html="91% of the 37,281 user reviews for this game are positive." itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"><div class="subtitle column all">All Reviews:</div>');

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
	 * Time: 
	 */
    public function test_getReview_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "Game review missing" );

		$result=$this->SteamScrape_obj->getReview();
		$this->assertisString($result);
		$this->assertEquals("",$result);
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

		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "Game description text" );

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
	 * Time: 
	 */
    public function test_getReleaseDate_base() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn('<b>Release Date:</b> Dec 19, 2008<br>');

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
	 * Time: 
	 */
    public function test_getReleaseDate_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "Game date missing" );

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

		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "Game description text" );

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
	 * Time: 
	 */
    public function test_getDeveloper_base() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn('<div class="dev_row"><b>Developer:</b><a href="https://store.steampowered.com/developer/EA?snr=1_5_9__408">Maxis™</a></div>');

		//TODO: Test data should also include scenatios for /curator/ and others. Regex looks for developer in the URL when it should look for the <b> tag preceeding.
		
		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

		$result=$this->SteamScrape_obj->getDeveloper();
		$this->assertisString($result);
		$this->assertEquals("Maxis™",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getDeveloper
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * Time: 
	 */
    public function test_getDeveloper_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "developer missing" );

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

		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "Game description text" );

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
	 * Time: 
	 */
    public function test_getPublisher_base() {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
        // Configure the stub.
        $stub->method('execute')
             ->willReturn('<div class="dev_row"><b>Publisher:</b><a href="https://store.steampowered.com/publisher/EA?snr=1_5_9__408">Maxis™</a></div>');

		//TODO: Test data should also include scenatios for /curator/ and others. Regex looks for publisher in the URL when it should look for the <b> tag preceeding.
		
		$property = $this->getPrivateProperty( 'SteamScrape', 'curlHandle' );
		$property->setValue( $this->SteamScrape_obj, $stub );

		$result=$this->SteamScrape_obj->getPublisher();
		$this->assertisString($result);
		$this->assertEquals("Maxis™",$result);
    }
	
	/**
	 * @group fast
	 * @small
	 * @covers SteamScrape::getPublisher
	 * @uses SteamScrape::__construct
	 * @uses SteamScrape::getStorePage
	 * Time: 
	 */
    public function test_getPublisher_blank() {
		$property = $this->getPrivateProperty( 'SteamScrape', 'rawPageText' );
		$property->setValue( $this->SteamScrape_obj, "publisher missing" );

		try {
			$result=$this->SteamScrape_obj->getPublisher();
		} catch (Exception $ex) {
			$this->assertEquals("No data found for : Publisher",$ex->getMessage());
		}
		
		//$this->assertisString($result);
		//$this->assertEquals("",$result);
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
		$property->setValue( $this->SteamScrape_obj, "Game description text" );

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