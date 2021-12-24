<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath'].'\inc\scraper.inc.php';

//Time: 00:00.315, Memory: 48.00 MB
//OK (23 tests, 29 assertions)
/**
 * @group include
 */
final class Scraper_Test extends TestCase
{
	/**
	 * @group fast
	 * @covers GetOwnedGames
	 * Time: 00:00.228, Memory: 48.00 MB
	 * (1 test, 1 assertion)
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
	 * @group fast
	 * @covers GetRecentlyPlayedGames
	 * Time: 00:00.221, Memory: 48.00 MB
	 * (1 test, 1 assertion)
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
	 * @group fast
	 * @covers GetPlayerAchievements
	 * Time: 00:00.226, Memory: 48.00 MB
	 * (1 test, 1 assertion)
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
	 * @group fast
	 * @covers GetGameNews
	 * Time: 00:00.221, Memory: 48.00 MB
	 * (1 test, 1 assertion)
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
	 * @group fast
	 * @covers GetUserStatsForGame
	 * Time: 00:00.221, Memory: 48.00 MB
	 * (1 test, 1 assertion)
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
	 * @group fast
	 * @covers GetSchemaForGame
	 * Time: 00:00.221, Memory: 48.00 MB
	 * (1 test, 1 assertion)
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
	 * @group fast
	 * @covers GetAppDetails
	 * Time: 00:00.223, Memory: 48.00 MB
	 * (1 test, 1 assertion)
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
	 * @group fast
	 * @covers GetSteamPICS
	 * Time: 00:00.227, Memory: 48.00 MB
	 * (1 test, 1 assertion)
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
	 * @group fast
	 * @covers scrapeSteamStore
	 * @uses getPageTitle
	 * Time: 00:00.222, Memory: 48.00 MB
	 * (1 test, 1 assertion)
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
	 * @group fast
	 * @covers scrapeSteamStore
	 * @uses getPageTitle
	 * Time: 00:00.225, Memory: 48.00 MB
	 * (2 tests, 2 assertions)
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
	 * @group fast
	 * @covers getPageTitle
	 * Time: 00:00.217, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
	public function test_getPageTitle(): void
    {
        $this->assertEquals("Some Game Title on Steam",getPageTitle("<title>Some Game Title on Steam</title>"));
        $this->assertEquals("",getPageTitle("no title"));
    }
	
	/**
	 * @group fast
	 * @covers parse_game_description
	 * Time: 00:00.223, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
	public function test_parse_game_description(): void
    {
		$source='<div class="game_description_snippet">	Game Description	</div>';
		
        $this->assertEquals("Game Description",parse_game_description($source));
        $this->assertEquals("",parse_game_description("no description"));
    }
	
	/**
	 * @group fast
	 * @covers parse_tags
	 * Time: 00:00.217, Memory: 46.00 MB
	 * (1 test, 1 assertion)
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

	/**
	 * @group fast
	 * @covers parse_details
	 * Time: 00:00.219, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
	public function test_parse_details(): void
    {
		$source='<div class="responsive_block_header responsive_apppage_details_left">Features</div>
						<div class="block responsive_apppage_details_left" id="category_block">

							<a class="game_area_details_specs_ctn" data-panel="{&quot;flow-children&quot;:&quot;column&quot;}" href="https://store.steampowered.com/search/?category2=2&snr=1_5_9__423"><div class="icon"><img class="category_icon" src="https://store.cloudflare.steamstatic.com/public/images/v6/ico/ico_singlePlayer.png"></div><div class="label">Single-player</div></a><a class="game_area_details_specs_ctn" data-panel="{&quot;flow-children&quot;:&quot;column&quot;}" href="https://store.steampowered.com/search/?category2=36&snr=1_5_9__423"><div class="icon"><img class="category_icon" src="https://store.cloudflare.steamstatic.com/public/images/v6/ico/ico_multiPlayer.png"></div><div class="label">Online PvP</div></a><a class="game_area_details_specs_ctn" data-panel="{&quot;flow-children&quot;:&quot;column&quot;}" href="https://store.steampowered.com/search/?category2=47&snr=1_5_9__423"><div class="icon"><img class="category_icon" src="https://store.cloudflare.steamstatic.com/public/images/v6/ico/ico_multiPlayer.png"></div><div class="label">LAN PvP</div></a>';
		
		//$source=scrapeSteamStore(289070,new CurlRequest(""));
		
		$output=parse_details($source);
		$expected=array(
			"list" => "Single-player, Online PvP, LAN PvP",
			"all" => array(
				"single-player"=> "Single-player",
				"online pvp"=> "Online PvP",
				"lan pvp"=> "LAN PvP"
			)
		);
		
        $this->assertEquals($expected,$output);
    }

	/**
	 * @group fast
	 * @covers parse_reviews
	 * Time: 00:00.220, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
	public function test_parse_reviews(): void
    {
		$source='<div class="user_reviews_summary_row" onclick="window.location=\'#app_reviews_hash\'" style="cursor: pointer;" data-tooltip-html="88% of the 4,334 user reviews in the last 30 days are positive.">
										<div class="subtitle column">Recent Reviews:</div>';
		
		//$source=scrapeSteamStore(289070,new CurlRequest(""));
		
		$output=parse_reviews($source);
		$expected=88;
		
        $this->assertEquals($expected,$output);
    }

	/**
	 * @group fast
	 * @covers parse_developer
	 * Time: 00:00.219, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
	public function test_parse_developer(): void
    {
		$source='<div class="subtitle column">Developer:</div>
									<div class="summary column" id="developers_list">
										<a href="https://store.steampowered.com/developer/firaxisgames?snr=1_5_9__2000">Firaxis Games</a>, <a href="https://store.steampowered.com/developer/Aspyr?snr=1_5_9__2000">Aspyr (Mac)</a>, <a href="https://store.steampowered.com/developer/Aspyr?snr=1_5_9__2000">Aspyr (Linux)</a>									</div>
								</div>';
		
		//$source=scrapeSteamStore(289070,new CurlRequest(""));
		
		$output=parse_developer($source);
		$expected="Firaxis Games";
		
        $this->assertEquals($expected,$output);
		
		try {
			$index=parse_developer("");
		} catch (Exception $ex) {
			$this->assertEquals("No data found for : Developer",$ex->getMessage());
		}
    }

	/**
	 * @group fast
	 * @covers parse_publisher
	 * Time: 00:00.219, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
	public function test_parse_publisher(): void
    {
		$source='<div class="dev_row">
										<div class="subtitle column">Publisher:</div>
										<div class="summary column">
										<a href="https://store.steampowered.com/publisher/2K?snr=1_5_9__2000">2K</a>, <a href="https://store.steampowered.com/publisher/Aspyr?snr=1_5_9__2000">Aspyr (Mac)</a>, <a href="https://store.steampowered.com/publisher/Aspyr?snr=1_5_9__2000">Aspyr (Linux)</a>										</div>
									</div>';
		
		//$source=scrapeSteamStore(289070,new CurlRequest(""));
		
		$output=parse_publisher($source);
		$expected="2K";
		
        $this->assertEquals($expected,$output);
		
		try {
			$index=parse_publisher("");
		} catch (Exception $ex) {
			$this->assertEquals("No data found for : Publisher",$ex->getMessage());
		}
    }

	/**
	 * @group fast
	 * @covers parse_releasedate
	 * Time: 00:00.217, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
	public function test_parse_releasedate(): void
    {
		$source='<b>Release Date:</b> Oct 20, 2016<br>';
		
		//$source=scrapeSteamStore(289070,new CurlRequest(""));
		
		$output=parse_releasedate($source);
		$expected="Oct 20, 2016";
		
        $this->assertEquals($expected,$output);
    }

	/**
	 * @group fast
	 * @covers parse_genre
	 * Time: 00:00.221, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
	public function test_parse_genre(): void
    {
		$source='<b>Genre:</b> <span data-panel="{&quot;flow-children&quot;:&quot;row&quot;}"><a href="https://store.steampowered.com/genre/Strategy/?snr=1_5_9__408">Strategy</a></span><br>';
		
		//$source=scrapeSteamStore(289070,new CurlRequest(""));
		
		$output=parse_genre($source);
		$expected=array(
			"list" => "Strategy",
			"all" => array(
				"strategy"=> "Strategy"
			)
		);
		//$expected="Strategy";
		
        $this->assertEquals($expected,$output);
		
		try {
			$index=parse_genre("");
		} catch (Exception $ex) {
			$this->assertEquals("No data found for : Steam Genre",$ex->getMessage());
		}
    }

	/**
	 * @group fast
	 * @covers formatAppDetails
	 * @uses boolText
	 * Time: 00:00.223, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
	public function test_formatAppDetails(): void
    {
		$appdetails=array(
			"data" => array(
				"type" => "apptype",
				"name" => "appname",
				"required_age" => 10,
				"is_free" => true,
				"controller_support" => true,
				"dlc" => array("DLC1","DLC2"),
				"detailed_description" => "A detailed description of the app. Might be kind of long.",
				"about_the_game" => "Another detailed description of the app. Might be kind of long.",
				"supported_languages" => "language",
				"reviews" => "a review",
				"header_image" => "image link",
				"website" => "website link",
				"pc_requirements" => array(
					"minimum" => "first requirement",
					"recomended" => "Second requirement",
					"high" => "Third requirement"
				),
				"mac_requirements" => array(
					"minimum" => "first requirement",
					"recomended" => "Second requirement",
					"high" => "Third requirement"
				),
				"linux_requirements" => array(
					"minimum" => "first requirement",
					"recomended" => "Second requirement",
					"high" => "Third requirement"
				),
				"legal_notice" => "legal notice",
				"publishers" => array("publisher1","publisher2"),
				"demos" => array(
					array("appid"=>1,
					"description"=>"Demo Description")
				),
				"price_overview" => array(
					"currency" => 1,
					"initial" => 1,
					"discount_percent" => 1,
					"final" => 1
				),
				"packages" => array("package1","package2"),
				"package_groups" => array(
					array(
						"name" => "words",
						"title" => "words",
						"description" => "words",
						"selection_text" => "words",
						"save_text" => "words",
						"display_type" => "words",
						"is_recurring_subscription" => "words",
						"subs" => array(array(
							'packageid'=> "words",
							'percent_savings_text'=> "words",
							'percent_savings'=> "words",
							'option_text'=> "words",
							'option_description'=> "words",
							'can_get_free_license'=> "words",
							'is_free_license'=> "words",
							'price_in_cents_with_discount'=> "1099"
						))
					)
				),
				"platforms" => array("platform1","platform2"),
				"metacritic" => array(
					"score" => "99",
					"url" => "web link"
				),
				"categories" => array(
					array("id"=>"1","description"=>"category1"),
					array("id"=>"2","description"=>"category2")
				),
				"genres" => array(
					array("id"=>"1","description"=>"genre1"),
					array("id"=>"2","description"=>"genre2")
				),
				"screenshots" => array(
					array(
						"path_full" => "image path full",
						"path_thumbnail" => "image path thumbnail",
					)
				),
				"movies" => array(
					array(
						"webm" => array("480"=>"movie path full"),
						"thumbnail" => "image path thumbnail",
						"name" => "movie name",
					)
				),
				"recommendations" => array(array("total" => "Total recomendation")),
				"achievements" => array("total" => "Total Achievements"),
				"release_date" => array("date" => "release date"),
				"support_info" => array("url" => "web link", "email" => "email"),
				"background" => "image link"
			)
		);
		
		//var_dump($appdetails);
		
		$output=formatAppDetails($appdetails);
        $this->assertisString($output);
    }
	
	/* *
	 * @covers formatSteamPics
	 * Function not working
	 * /
	public function test_formatSteamPics(): void
    {
		$SteamPics = array();
		$output=formatSteamPics($SteamPics);
        $this->assertisString($output);
    } /* */
	
	/**
	 * @group fast
	 * @covers formatSteamAPI
	 * @uses regroupArray
	 * Time: 00:00.219, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
	public function test_formatSteamAPI(): void
    {
		$SchemaforGame = array("game" => array(
			"gameName"=>"Name of Game",
			"gameVersion"=>"110",
			"availableGameStats" => array(
				"stats" => array(array(
					"name"=> "stat1",
					"defaultvalue"=>0,
					"displayName"=>"Display Name 1"
					),array(
					"name"=> "stat2",
					"defaultvalue"=>0,
					"displayName"=>"Display Name 2"
					),array(
					"name"=> "stat3",
					"defaultvalue"=>0,
					"displayName"=>""
					),array(
					"name"=> "stat4",
					"defaultvalue"=>0,
					"displayName"=>"Display Name 4"
					)
				),
			"achievements" => array(
				array(
					"name" => "achievement1",
					"defaultvalue" => 0,
					"displayName" => "Achievement #1",
					"hidden" => 0,
					"description" => "get achievement 1",
					"icon" => "",
					"icongray" => ""
				), array(
					"name" => "achievement2",
					"defaultvalue" => 0,
					"displayName" => "Achievement #2",
					"hidden" => 0,
					"description" => "get achievement 2",
					"icon" => "",
					"icongray" => ""
				), array(
					"name" => "achievement3",
					"defaultvalue" => 0,
					"displayName" => "Achievement #3",
					"hidden" => 0,
					"description" => "get achievement 3",
					"icon" => "",
					"icongray" => ""
				), array(
					"name" => "achievement4",
					"defaultvalue" => 0,
					"displayName" => "Achievement #4",
					"hidden" => 0,
					"description" => "get achievement 4",
					"icon" => "",
					"icongray" => ""
				), array(
					"name" => "achievement5",
					"defaultvalue" => 0,
					"displayName" => "Achievement #5",
					"hidden" => 0,
					"description" => "get achievement 5",
					"icon" => "",
					"icongray" => ""
				), array(
					"name" => "achievement6",
					"defaultvalue" => 0,
					"displayName" => "Achievement #6",
					"hidden" => 0,
					"description" => "get achievement 6",
					"icon" => "",
					"icongray" => ""
				), array(
					"name" => "achievement7",
					"defaultvalue" => 0,
					"displayName" => "Achievement #7",
					"hidden" => 0,
					"description" => "get achievement 7",
					"icon" => "",
					"icongray" => ""
				)
			)
			),
			"playerstats" => array("stats" => array("name"=>"")),
			));
			
		$UserStatsForGame = array("playerstats"=> array(
			"SteamID" => "1234567",
			"gamename" => "Name of Game",
			"achievements" => array(
				array(
					"name"=>"achievement1",
					"achieved"=>1
				), 	array(
					"name"=>"achievement3",
					"achieved"=>1
				)
			),
			"stats" => array(
				array(
					"name"=>"stat1",
					"value"=>11
				), 	array(
					"name"=>"stat3",
					"value"=>33
				)
		)));
		
		//$UserStatsForGame=GetUserStatsForGame(289070);
		//$SchemaforGame=GetSchemaForGame(289070);
		
		$output=formatSteamAPI($SchemaforGame,$UserStatsForGame);
        $this->assertisString($output);
    }

	/**
	 * @group fast
	 * @covers formatSteamLinks
	 * Time: 00:00.217, Memory: 46.00 MB
	 * (1 test, 1 assertion)
	 */
	public function test_formatSteamLinks(): void
    {
		$output=formatSteamLinks(289070,123456789);
        $this->assertisString($output);
    }
	
	/**
	 * @group fast
	 * @covers formatnews
	 * Time: 00:00.221, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
	public function test_formatnews(): void
    {
		$source=array(
			'appnews' => array(
				"appid"=> 289070,
				'newsitems' => array(
					0 => array(
						"gid" => "4224936215406363380",
						"title" => "Article Title",
						"url" => "https://www.google.com",
						"is_external_url" => true,
						"author" => "Author",
						"contents" => "Lots of text that goes on and on forever.",
						"feedlabel" => "Label",
						"date" => 1638988713,
						"feedname" => "Feed",
						"feed_type" => 1,
						"appid" => 289070
					),
					1 => array(
						"gid" => "4224936215406363381",
						"title" => "Different Article Title",
						"url" => "https://www.yahoo.com",
						"is_external_url" => true,
						"author" => "",
						"contents" => "Lots of more text that goes on and on forever, like really, forever.",
						"feedlabel" => "Label",
						"date" => 1639423818,
						"feedname" => "Feed",
						"feed_type" => 1,
						"appid" => 289070
					)
				)
			)
		);

        //$source=GetGameNews(289070,5,500,new CurlRequest(""));
		//var_dump($source);
		
		$output=formatnews($source);
		$expected="<b>News:</b><p><a href='https://www.google.com' target='_blank'>Article Title</a> by Author on 12/08/2021<br>Lots of text that goes on and on forever.</p><p><a href='https://www.yahoo.com' target='_blank'>Different Article Title</a> on 12/13/2021<br>Lots of more text that goes on and on forever, like really, forever.</p>";
		
        $this->assertisString($output);
		$this->assertEquals($expected,$output);
    }


}
