<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require $GLOBALS['rootpath'].'\inc\scraper.inc.php';

final class Scraper_Test extends TestCase
{
	/**
	 * @covers GetOwnedGames
	 */
	public function test_GetOwnedGames(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);

        // Configure the stub.
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');

        // Calling $stub->doSomething() will now return
        $this->assertSame(array('foo'=>'bar'),GetOwnedGames($stub));
    }

	/**
	 * @covers GetRecentlyPlayedGames
	 */
	public function test_GetRecentlyPlayedGames(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);

        // Configure the stub.
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');

        // Calling $stub->doSomething() will now return
        $this->assertSame(array('foo'=>'bar'),GetRecentlyPlayedGames($stub));
    }

	/**
	 * @covers GetPlayerAchievements
	 */
	public function test_GetPlayerAchievements(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);

        // Configure the stub.
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');

        // Calling $stub->doSomething() will now return
        $this->assertSame(array('foo'=>'bar'),GetPlayerAchievements(4088,$stub));
    }
	
	/**
	 * @covers GetGameNews
	 */
	public function test_GetGameNews(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);

        // Configure the stub.
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');

        // Calling $stub->doSomething() will now return
        $this->assertSame(array('foo'=>'bar'),GetGameNews(4088,5,500,$stub));
    }
	
	/**
	 * @covers GetUserStatsForGame
	 */
	public function test_GetUserStatsForGame(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);

        // Configure the stub.
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');

        // Calling $stub->doSomething() will now return
        $this->assertSame(array('foo'=>'bar'),GetUserStatsForGame(4088,$stub));
    }
	
	/**
	 * @covers GetSchemaForGame
	 */
	public function test_GetSchemaForGame(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);

        // Configure the stub.
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');

        // Calling $stub->doSomething() will now return
        $this->assertSame(array('foo'=>'bar'),GetSchemaForGame(4088,$stub));
    }
	
	/**
	 * @covers GetAppDetails
	 */
	public function test_GetAppDetails(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);

        // Configure the stub.
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');

        // Calling $stub->doSomething() will now return
        $this->assertSame(array('foo'=>'bar'),GetAppDetails(4088,$stub));
    }
	
	/**
	 * @covers GetSteamPICS
	 */
	public function test_GetSteamPICS(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);

        // Configure the stub.
        $stub->method('execute')
             ->willReturn('{"foo": "bar"}');

        // Calling $stub->doSomething() will now return
        $this->assertSame(array('foo'=>'bar'),GetSteamPICS(4088,$stub));
    }
	
	/**
	 * @covers scrapeSteamStore
	 * @uses getPageTitle
	 */
	public function test_scrapeSteamStore_false(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);

        // Configure the stub.
        $stub->method('execute')
             ->willReturn('<title>Welcome to Steam</title>');

        // Calling $stub->doSomething() will now return
        $this->assertSame(false,scrapeSteamStore(4088,$stub));
    }
	
	/**
	 * @covers scrapeSteamStore
	 * @uses getPageTitle
	 */
	public function test_scrapeSteamStore(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createStub(CurlRequest::class);
		
        // Configure the stub.
        $stub->method('execute')
             ->willReturn('<title>Some Game Title on Steam</title>');

        // Calling $stub->doSomething() will now return
        $this->assertisString(scrapeSteamStore(4088,$stub));
	}
	
	/**
	 * @covers getPageTitle
	 */
	public function test_getPageTitle(): void
    {
        $this->assertEquals("Some Game Title on Steam",getPageTitle("<title>Some Game Title on Steam</title>"));
        $this->assertEquals("",getPageTitle("no title"));
    }
	
	/**
	 * @covers parse_game_description
	 */
	public function test_parse_game_description(): void
    {
		$source='<div class="game_description_snippet">	Game Description	</div>';
		
        $this->assertEquals("Game Description",parse_game_description($source));
        $this->assertEquals("",parse_game_description("no description"));
    }
	
	/**
	 * @covers parse_tags
	 */
	public function test_parse_tags(): void
    {
		$source='<div class="glance_tags popular_tags" data-appid="1812280">
											<a href="https://store.steampowered.com/tags/en/Adventure/?snr=1_5_9__409" class="app_tag" style="display: none;">
												Adventure												</a><a href="https://store.steampowered.com/tags/en/Puzzle/?snr=1_5_9__409" class="app_tag" style="display: none;">
												Puzzle												</a><div class="app_tag add_button" data-panel="{&quot;focusable&quot;:true,&quot;clickOnActivate&quot;:true}" onclick="ShowAppTagModal( 1812280 )">+</div>
										</div>
									</div>';
									
		$output=parse_tags($source);
		$expected=array(
			"list" => "Adventure, Puzzle",
			"all" => array(
				"adventure"=> "Adventure",
				"puzzle"=> "Puzzle"
			)
		);
		
        $this->assertEquals($expected,$output);
    }
}
