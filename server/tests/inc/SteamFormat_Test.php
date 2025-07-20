<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath'].'\tests\inc\testprivate.inc.php';
require_once $GLOBALS['rootpath']."\inc\SteamFormat.class.php";

/**
 * @group include
 * @group classtest
 * @group steamformat
 */
final class SteamFormat_Test extends testprivate
{
	private $SteamFormat_obj;
	
	protected function setUp(): void {
        $this->SteamFormat_obj = new SteamFormat();
    }	
	
    protected function tearDown(): void {
        unset($this->SteamFormat_obj);
    }
	
	/**
	 * @small
	 * @covers SteamFormat::formatDetailStat
	 * @uses SteamFormat
	 */
	public function test_formatDetailStat_base(): void
    {
		$method = $this->getPrivateMethod( 'SteamFormat', 'formatDetailStat' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label","value") );

		$this->assertEquals("label: value<br>",$result);
	}
	
	/**
	 * @small
	 * @covers SteamFormat::formatListStat
	 * @uses SteamFormat
	 */
	public function test_formatListStat_base(): void
    {
		$method = $this->getPrivateMethod( 'SteamFormat', 'formatListStat' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label",["value1","value2"]) );
		
		$this->assertisString($result);
		$this->assertNotEquals("",$result);
	}

	/**
	 * @small
	 * @covers SteamFormat::formatListStat
	 * @uses SteamFormat
	 */
	public function test_formatListStat_invalid(): void
    {
		$method = $this->getPrivateMethod( 'SteamFormat', 'formatListStat' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label","Value") );
		
		$this->assertEquals("",$result);
	}

	/**
	 * @small
	 * @covers SteamFormat::formatStat
	 * @uses SteamFormat
	 */
	public function test_formatStat_array(): void
    {
		$method = $this->getPrivateMethod( 'SteamFormat', 'formatStat' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label",["value1","value2"]) );
		
		$this->assertisString($result);
		$this->assertNotEquals("",$result);
	}

	/**
	 * @small
	 * @covers SteamFormat::formatStat
	 * @uses SteamFormat
	 */
	public function test_formatStat_detail(): void
    {
		$method = $this->getPrivateMethod( 'SteamFormat', 'formatStat' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label","value") );
		
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @covers SteamFormat::isempty
	 * @uses SteamFormat
	 */
	public function test_isempty_no(): void
	{
		$method = $this->getPrivateMethod( 'SteamFormat', 'isempty' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("not empty") );
		
		$this->assertEquals(false,$result);		
	}
	
	/**
	 * @small
	 * @covers SteamFormat::isempty
	 * @uses SteamFormat
	 */
	public function test_isempty_yes(): void
	{
		$method = $this->getPrivateMethod( 'SteamFormat', 'isempty' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("") );
		
		$this->assertEquals(true,$result);		
	}
	
	/**
	 * @small
	 * @covers SteamFormat::makehyperlink
	 * @uses SteamFormat
	 */
	public function test_makehyperlink_base(): void
	{
		$method = $this->getPrivateMethod( 'SteamFormat', 'makehyperlink' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("ref","text") );
		
		$this->assertEquals("<a href='ref'>text</a>",$result);		
	}
	
	/**
	 * @small
	 * @covers SteamFormat::formatsupport
	 * @uses SteamFormat
	 */
	public function test_formatsupport_base(): void
	{
		$method = $this->getPrivateMethod( 'SteamFormat', 'formatsupport' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label",["url"=>"url","email"=>"email"]) );

		$this->assertisString($result);		
		$this->assertNotEquals("",$result);		
	}
	
	/**
	 * @small
	 * @covers SteamFormat::formatRecommendations
	 * @uses SteamFormat
	 */
	public function test_formatRecommendations_recsingle(): void
	{
		$method = $this->getPrivateMethod( 'SteamFormat', 'formatRecommendations' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label",[85]) );
		
		$this->assertisString($result);		
	}

	/**
	 * @small
	 * @covers SteamFormat::formatRecommendations
	 * @uses SteamFormat
	 */
	public function test_formatRecommendations_recarray(): void
	{
		$method = $this->getPrivateMethod( 'SteamFormat', 'formatRecommendations' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label",[["total"=>85]]) );
		
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @covers SteamFormat::formatmovies
	 * @uses SteamFormat
	 */
	public function test_formatmovies_base(): void
	{
		$method = $this->getPrivateMethod( 'SteamFormat', 'formatmovies' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label",[["webm"=>["480"=>"value"],"thumbnail"=>"tn","name"=>"name"]]) );
		
		$this->assertisString($result);
		$this->assertnotEquals("",$result);		
	}
	
	/**
	 * @small
	 * @covers SteamFormat::formatscreenshot
	 * @uses SteamFormat
	 */
	public function test_formatscreenshot_base(): void
	{
		$method = $this->getPrivateMethod( 'SteamFormat', 'formatscreenshot' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label",[["path_full"=>"f","path_thumbnail"=>"tn"]]) );
		
		$this->assertisString($result);
		$this->assertnotEquals("",$result);		
	}

	/**
	 * @small
	 * @covers SteamFormat::formatcategory
	 * @uses SteamFormat
	 */
	public function test_formatcategory_base(): void
	{
		$method = $this->getPrivateMethod( 'SteamFormat', 'formatcategory' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label",[["id"=>"id","description"=>"description"]]) );
		
		$this->assertisString($result);
		$this->assertnotEquals("",$result);		
	}

	/**
	 * @small
	 * @covers SteamFormat::formatplatform
	 * @uses SteamFormat
	 * @uses boolText
	 */
	public function test_formatplatform_base(): void
	{
		$method = $this->getPrivateMethod( 'SteamFormat', 'formatplatform' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label",["label2"=>0]) );
		
		$this->assertisString($result);
		$this->assertnotEquals("",$result);		
	}

	/**
	 * @small
	 * @covers SteamFormat::formatpackage
	 * @uses SteamFormat
	 * @uses boolText
	 */
	public function test_formatpackage_base(): void
	{
		$testarray=array(
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
					));
		$method = $this->getPrivateMethod( 'SteamFormat', 'formatpackage' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label",$testarray) );
		
		$this->assertisString($result);
		$this->assertnotEquals("",$result);		
	}

	/**
	 * @small
	 * @covers SteamFormat::formatoverview
	 * @uses SteamFormat
	 */
	public function test_formatoverview_base(): void
	{
		$method = $this->getPrivateMethod( 'SteamFormat', 'formatoverview' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label",["currency"=>"$","initial"=>100,"discount_percent"=>90,"final"=>10]) );
		
		$this->assertisString($result);
		$this->assertnotEquals("",$result);		
	}

	/**
	 * @small
	 * @covers SteamFormat::formatDemos
	 * @uses SteamFormat
	 */
	public function test_formatDemos_base(): void
	{
		$method = $this->getPrivateMethod( 'SteamFormat', 'formatDemos' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label",[["appid"=>"id","description"=>"description"]]) );
		
		$this->assertisString($result);
		$this->assertnotEquals("",$result);		
	}

	/**
	 * @small
	 * @covers SteamFormat
	 * @testWith ["formatscreenshot"]
	 *           ["formatmovies"]
	 *           ["formatRecommendations"]
	 *           ["formatsupport"]
	 *           ["formatStat"]
	 *           ["makehyperlink"]
	 *           ["formatListStat"]
	 *           ["formatDetailStat"]
	 *           ["formatcategory"]
	 *           ["formatplatform"]
	 *           ["formatpackage"]
	 *           ["formatoverview"]
	 *           ["formatDemos"]
	 */
	public function test_formatfunction_empty($functionName): void
	{
		$method = $this->getPrivateMethod( 'SteamFormat', $functionName );
		$result = $method->invokeArgs($this->SteamFormat_obj, array("label",null) );
		
		$this->assertEquals("",$result);		
	}
	
	/**
	 * @small
	 * @covers SteamFormat::formatAppDetails
	 * @uses SteamFormat
	 * @uses boolText
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
				"recommendations" => array("total" => "Total recomendation"),
				"achievements" => array("total" => "Total Achievements"),
				"release_date" => array("date" => "release date"),
				"support_info" => array("url" => "web link", "email" => "email"),
				"background" => "image link"
			)
		);
		
		$output=$this->SteamFormat_obj->formatAppDetails($appdetails);
        $this->assertisString($output);
 		$this->assertNotEquals("",$output);		
   }
   
   /**
	 * @small
	 * @covers SteamFormat::formatAppDetails
	 * @uses SteamFormat
	 * @uses boolText
	 */
	public function test_formatAppDetails_false(): void
    {
		$appdetails=array(
			"success" => false
		);
		
		$output=$this->SteamFormat_obj->formatAppDetails($appdetails);
        $this->assertisString($output);
 		$this->assertEquals("",$output);		
   }
	
	/**
	 * @small
	 * @covers SteamFormat::formatSteamAPI
	 * @uses regroupArray
	 * @uses SteamFormat
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
		
		$output=$this->SteamFormat_obj->formatSteamAPI($SchemaforGame,$UserStatsForGame);
        $this->assertisString($output);
 		$this->assertNotEquals("",$output);		
   }

	/**
	 * @small
	 * @covers SteamFormat::achievementTable
	 * @uses regroupArray
	 * @uses SteamFormat
	 */
	public function test_achievementTable(): void
	{
		$SchemaforGame = array(
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
				)
			);
			
		$UserStats = array(
				array(
					"name"=>"achievement1",
					"achieved"=>1
				), 	array(
					"name"=>"achievement3",
					"achieved"=>1
				)
			);
		
		$method = $this->getPrivateMethod( 'SteamFormat', 'achievementTable' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array($SchemaforGame,$UserStats) );
		
		$this->assertisString($result);
		$this->assertnotEquals("",$result);			
	}

	/**
	 * @small
	 * @covers SteamFormat::statsTable
	 * @uses regroupArray
	 * @uses SteamFormat
	 */
	public function test_statTable(): void
	{
		$SchemaforGame = array(array(
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
				);
			
		$UserStatsForGame = array(
				array(
					"name"=>"stat1",
					"value"=>11
				), 	array(
					"name"=>"stat3",
					"value"=>33
				)
		);
		
		$method = $this->getPrivateMethod( 'SteamFormat', 'statsTable' );
		$result = $method->invokeArgs($this->SteamFormat_obj, array($SchemaforGame,$UserStatsForGame) );
		
		$this->assertisString($result);
		$this->assertnotEquals("",$result);			
	}
	
	/**
	 * @small
	 * @covers SteamFormat::formatSteamLinks
	 */
	public function test_formatSteamLinks(): void
    {
		$output=$this->SteamFormat_obj->formatSteamLinks(289070,123456789);
        $this->assertisString($output);
 		$this->assertNotEquals("",$output);		
   }
	
	/**
	 * @small
	 * @covers SteamFormat::formatnews
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
		
		$output=$this->SteamFormat_obj->formatnews($source);
		$expected="<b>News:</b><p><a href='https://www.google.com' target='_blank'>Article Title</a> by Author on 12/08/2021<br>Lots of text that goes on and on forever.</p><p><a href='https://www.yahoo.com' target='_blank'>Different Article Title</a> on 12/13/2021<br>Lots of more text that goes on and on forever, like really, forever.</p>";
		
        $this->assertisString($output);
		$this->assertEquals($expected,$output);
    }
}