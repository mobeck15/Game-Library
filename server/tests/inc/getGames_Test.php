<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath'].'\tests\inc\testprivate.inc.php';
require_once $GLOBALS['rootpath']."\inc\getGames.class.php";

/**
 * @group include
 * @group getGames
 */
final class getGames_Test extends testprivate
{
	/**
	 * @small
	 * @covers Game::__construct
	 * @uses Games
	 * @uses dataAccess
	 */
    public function test_Game_construct() {
		$gamerow=[
		"Game_ID"=>1,
		"LaunchDate"=>"5/1/2018"
		];
		$gameobject=new Game($gamerow);
		
		$this->assertInstanceOf(Game::class, $gameobject);
	}

	/**
	 * @small
	 * @covers Games::buildGameArray
	 * @covers getGames
	 * @uses Games
	 * @uses dataAccess
	 * @uses getAllCpi
	 * @uses get_db_connection
	 * @uses timeduration
	 */
    public function test_getGames_base() {
		$result=getGames();
        $this->assertisArray($result);
        $this->assertEquals(true,count($result)>0);
		
        $this->assertisArray(getGames(2));
        $this->assertisArray(getGames(array(2,3)));
	}
	
	/**
	 * @small
	 * @covers Games::buildGameArray
	 * @covers Games::__construct
	 * @uses getGames
	 * @uses Games
	 * @uses dataAccess
	 * @uses getAllCpi
	 * @uses get_db_connection
	 * @uses timeduration
	 * @uses makeIndex
	 */
    public function test_getGames_reindex() {
		$result=getGames();
        $this->assertisArray($result);
        $this->assertEquals(true,count($result)>0);
		$this->assertisArray(makeIndex($result,"Game_ID"));
	}
	
	/**
	 * @small
	 * @covers Games::buildGameArray
	 * @uses getGames
	 * @uses Games
	 * @uses dataAccess
	 * @uses getAllCpi
	 * @uses get_db_connection
	 * @uses timeduration
	 */
    public function test_getGames_conn() {
		$conn=get_db_connection();
		$this->assertisArray(getGames("",$conn));
		$conn->close();
	}

	/**
	 * @small
	 * @covers Games::CalculateGameRow
	 * @uses Games
	 * @uses dataAccess
	 * @uses timeduration
	 */
    public function test_CalculateGameRow() {
		$gamerow=array(
			"Game_ID" => 1,
			"LaunchDate" => "2021/01/01",
			"LowDate" => "2021/02/01",
			"DateUpdated" => "2021/06/01",
			"LaunchPrice" => 9.99,
			"MSRP" => 14.99,
			"CurrentMSRP" => 14.99,
			"HistoricLow" => 2.99,
			"SteamAchievements" => 10,
			"SteamCards" => 5,
			"Metascore" => 81,
			"MetascoreID" => "pc/somegame",
			"UserMetascore" => 85,
			"SteamRating" => 82,
			"SteamID" => 123456,
			"GOGID" => "somegame",
			"DesuraID" => "",
			"Title" => "gametitle",
			"isthereanydealID" => "path\gametitle",
			"TimeToBeatID" => "23415",
			"TimeToBeat" => 5.5,
			"Want" => 3,
			"ParentGameID" => 3,
			"Playable" => 1
		);
		
		$GameListObject=new Games();

		$method = $this->getPrivateMethod( 'Games', 'CalculateGameRow' );
		$result = $method->invokeArgs( $GameListObject, array($gamerow) );
		
		$this->assertisArray($result);
		
		$gamerow["LowDate"]="";
		$gamerow["DateUpdated"]="";
		$gamerow["LaunchPrice"]=0;
		$gamerow["MSRP"]=0;
		$gamerow["CurrentMSRP"]=0;
		$gamerow["HistoricLow"]=0;
		$gamerow["SteamAchievements"]=0;
		$gamerow["SteamCards"]=0;
		$gamerow["Metascore"]=0;
		$gamerow["UserMetascore"]=0;
		$gamerow["SteamRating"]=0;
		$gamerow["SteamID"]=0;
		$gamerow["GOGID"]="";
		$gamerow["DesuraID"]="x";
		$gamerow["isthereanydealID"]="";
		$gamerow["TimeToBeat"]=0;
		$gamerow["ParentGameID"]=3;
		
		$GameListObject=new Games();

		$result = $method->invokeArgs( $GameListObject, array($gamerow) );
		
		$this->assertisArray($result);

		$gamerow["MetascoreID"]="";
		$gamerow["TimeToBeatID"]="";
		
		$result = $method->invokeArgs( $GameListObject, array($gamerow) );
		$this->assertisArray($result);
	}
	
	/**
	 * @small
	 * @covers Games::CalculateGameRow
	 * @uses Games
	 * @uses dataAccess
	 * @uses timeduration
	 */
	public function test_CalculateGameRow_triggersError()
	{
		$gamerow=array(
			"Game_ID" => 1,
			"LaunchDate" => null,
			"LowDate" => "2021/02/01",
			"DateUpdated" => "2021/06/01",
			"LaunchPrice" => 9.99,
			"MSRP" => 14.99,
			"CurrentMSRP" => 14.99,
			"HistoricLow" => 2.99,
			"SteamAchievements" => 10,
			"SteamCards" => 5,
			"Metascore" => 81,
			"MetascoreID" => "pc/somegame",
			"UserMetascore" => 85,
			"SteamRating" => 82,
			"SteamID" => 123456,
			"GOGID" => "somegame",
			"DesuraID" => "",
			"Title" => "gametitle",
			"isthereanydealID" => "path\gametitle",
			"TimeToBeatID" => "23415",
			"TimeToBeat" => 5.5,
			"Want" => 3,
			"ParentGameID" => 3,
			"Playable" => 1
		);
		
		$caughtMessage = null;
		$caughtErrno   = null;

		set_error_handler(function ($errno, $errstr) use (&$caughtMessage, &$caughtErrno) {
			$caughtMessage = $errstr;
			$caughtErrno   = $errno;
			return true; // prevent it from failing the test
		});

		$GameListObject=new Games();

		$GameListObject=new Games();

		$method = $this->getPrivateMethod( 'Games', 'CalculateGameRow' );
		$result = $method->invokeArgs( $GameListObject, array($gamerow) );
		
		$this->assertisArray($result);

		restore_error_handler();

		$this->assertSame(
			"<a href=''>1 - gametitle</a> has no launch date set.",
			$caughtMessage
		);
		$this->assertSame(E_USER_WARNING, $caughtErrno); // or E_USER_WARNING if you switch types
	}

    /** @var Games */
    private $games;

    protected function setUp(): void
    {
        $this->games = new Games();
    }

 	/**
	 * @small
	 * @covers Games::buildGameArray
	 * @uses Games
	 * @uses dataAccess
	 */
   public function testGetGamesReturnsArray()
    {
        // Mock dataAccess to return one row
		$mockDataAccess = $this->createMock(dataAccess::class);
		$mockStmt = $this->createMock(PDOStatement::class);
		$mockStmt->method('fetch')
			->willReturnOnConsecutiveCalls(
				['Game_ID' => 1, 'Title' => 'Test Game'],
				false
			);
		$mockDataAccess->method('getGames')->willReturn($mockStmt);

        // Inject mock into Games via property injection
        // This requires that dataAccess is replaceable or global state
        // For test purposes, we'll replace via Reflection
		$games = $this->getMockBuilder(Games::class)
					  ->setConstructorArgs([$mockDataAccess])
					  ->onlyMethods(['CalculateGameRow'])
					  ->getMock();

		$games->expects($this->once())
			  ->method('CalculateGameRow')
			  ->willReturn(['foo' => 'bar']);

		$result = $games->buildGameArray();
		$this->assertSame([['foo' => 'bar']], $result);
    }

  	/**
	 * @small
	 * @covers Games::intToNull
	 * @uses Games
	 * @uses dataAccess
	 */
   public function testIntToNullCoversZeroAndNonZero()
    {
        $row = ['field' => 0];
        $result = $this->invokePrivate('intToNull', [['field' => 0]], 'field');
        $this->assertNull($result['field']);

        $result = $this->invokePrivate('intToNull', [['field' => 5]], 'field');
        $this->assertSame(5, $result['field']);
    }

  	/**
	 * @small
	 * @covers Games::normalizePrice
	 * @uses Games
	 * @uses dataAccess
	 */
    public function testNormalizePriceCoversZeroAndNonZero()
    {
        $row = ['price' => 0];
        $result = $this->invokePrivate('normalizePrice', [['price' => 0]], 'price', 9.99);
        $this->assertSame('9.99', $result['price']);

        $result = $this->invokePrivate('normalizePrice', [['price' => 5]], 'price', 9.99);
        $this->assertSame('5.00', $result['price']);
    }

  	/**
	 * @small
	 * @covers Games::normalizeLaunchDate
	 * @covers Games::triggerGameError
	 * @uses Games
	 * @uses dataAccess
	 */
    public function testNormalizeLaunchDateCoversNullAndValid()
    {
        // Null launch date triggers error
        $caughtMessage = null;
		$caughtErrno   = null;

		set_error_handler(function ($errno, $errstr) use (&$caughtMessage, &$caughtErrno) {
			$caughtMessage = $errstr;
			$caughtErrno   = $errno;
			return true; // prevent PHPUnit from treating it as an error
		});

		$result = $this->invokePrivate('normalizeLaunchDate', [['LaunchDate' => null, 'Game_ID' => 1, 'Title' => 'Test']]);

		restore_error_handler();

		$this->assertInstanceOf(DateTime::class, $result['LaunchDate']);
		$this->assertSame(E_USER_WARNING, $caughtErrno);
		$this->assertStringContainsString('has no launch date set.', $caughtMessage);
    }

  	/**
	 * @small
	 * @covers Games::normalizeDate
	 * @uses Games
	 * @uses dataAccess
	 */
	public function testNormalizeDateCoversEmptyAndValid()
	{
		// Empty
		$row = ['LowDate' => ''];
		$result = $this->invokePrivate('normalizeDate', [$row, 'LowDate']);
		$this->assertSame('', $result['LowDate']);

		// With sortField empty
		$row = ['DateUpdated' => ''];
		$result = $this->invokePrivate('normalizeDate', [$row, 'DateUpdated', 'Sort']);
		$this->assertSame('', $result['DateUpdated']);
		$this->assertSame('', $result['Sort']);

		// Valid without sortField
		$row = ['LowDate' => '2020-01-01'];
		$result = $this->invokePrivate('normalizeDate', [$row, 'LowDate']);
		$this->assertSame('1/1/2020', $result['LowDate']);

		// ✅ Valid with sortField (covers `$row[$sortField] = $row[$field];`)
		$row = ['DateUpdated' => '2020-01-01'];
		$result = $this->invokePrivate('normalizeDate', [$row, 'DateUpdated', 'Sort']);
		$this->assertSame('1/1/2020', $result['DateUpdated']);
		$this->assertSame('2020-01-01', $result['Sort']); // original unformatted date stored in sort field
	}

   	/**
	 * @small
	 * @covers Games::normalizeScoreLink
	 * @uses Games
	 * @uses dataAccess
	 */
   public function testNormalizeScoreLinkCoversAllBranches()
    {
        // Score = 0, ID empty
        $row = ['Metascore' => 0, 'MetascoreID' => ''];
        $result = $this->invokePrivate('normalizeScoreLink', [$row, 'Metascore', 'Link', 'MetascoreID', 'base/']);
        $this->assertNull($result['Metascore']);

        // Score = 0, ID not empty
        $row = ['Metascore' => 0, 'MetascoreID' => '123'];
        $result = $this->invokePrivate('normalizeScoreLink', [$row, 'Metascore', 'Link', 'MetascoreID', 'base/']);
        $this->assertStringContainsString('N/A', $result['Link']);

        // Score != 0
        $row = ['Metascore' => 5, 'MetascoreID' => '123'];
        $result = $this->invokePrivate('normalizeScoreLink', [$row, 'Metascore', 'Link', 'MetascoreID', 'base/']);
        $this->assertStringContainsString('5', $result['Link']);
    }

   	/**
	 * @small
	 * @covers Games::setStoreOrSearchLink
	 * @uses Games
	 * @uses dataAccess
	 */
    public function testSetStoreOrSearchLinkCoversBothBranches()
    {
        // With ID
        $row = ['SteamID' => '123', 'Title' => 'Game'];
        $result = $this->invokePrivate('setStoreOrSearchLink', [$row, 'SteamID', 'Link', 'store/', 'search/']);
        $this->assertStringContainsString('store/123', $result['Link']);

        // Without ID
        $row = ['SteamID' => '', 'Title' => 'Game'];
        $result = $this->invokePrivate('setStoreOrSearchLink', [$row, 'SteamID', 'Link', 'store/', 'search/']);
        $this->assertStringContainsString('search/Game', $result['Link']);
    }

   	/**
	 * @small
	 * @covers Games::normalizeTimeToBeat
	 * @uses Games
	 * @uses dataAccess
	 * @uses timeduration
	 */
    public function testNormalizeTimeToBeatCoversAllBranches()
    {
        // No ID
        $row = ['TimeToBeatID' => 0];
        $result = $this->invokePrivate('normalizeTimeToBeat', [$row]);
        $this->assertSame('', $result['TimeToBeatLink']);

        // With ID and no time
        $row = ['TimeToBeatID' => 5, 'TimeToBeat' => 0];
        $result = $this->invokePrivate('normalizeTimeToBeat', [$row]);
        $this->assertStringContainsString('N/A', $result['TimeToBeatLink2']);

        // With ID and time
        $row = ['TimeToBeatID' => 5, 'TimeToBeat' => 10];
        $result = $this->invokePrivate('normalizeTimeToBeat', [$row]);
        $this->assertStringContainsString('10:00:00', $result['TimeToBeatLink2']);
    }

   	/**
	 * @small
	 * @covers Games::normalizeMetascoreLinks
	 * @uses Games
	 * @uses dataAccess
	 */
    public function testNormalizeMetascoreLinksCoversBothBranches()
    {
        // With ID
        $row = ['MetascoreID' => '123', 'Title' => 'Game'];
        $result = $this->invokePrivate('normalizeMetascoreLinks', [$row]);
        $this->assertStringContainsString('123', $result['MetascoreLink']);

        // Without ID
        $row = ['MetascoreID' => '', 'Title' => 'Game'];
        $result = $this->invokePrivate('normalizeMetascoreLinks', [$row]);
        $this->assertStringContainsString('Search', $result['MetascoreLinkCritic']);
    }

   	/**
	 * @small
	 * @covers Games::normalizeIntField
	 * @covers Games::normalizeBoolField
	 * @uses Games
	 * @uses dataAccess
	 */
    public function testNormalizeIntAndBoolFields()
    {
        $row = ['field' => '5'];
        $result = $this->invokePrivate('normalizeIntField', [$row, 'field']);
        $this->assertSame(5, $result['field']);

        $row = ['flag' => 1];
        $result = $this->invokePrivate('normalizeBoolField', [$row, 'flag']);
        $this->assertTrue($result['flag']);
    }

    /**
     * Helper to invoke private/protected methods
     */
    private function invokePrivate($method, array $params, ...$extra)
    {
        $ref = new ReflectionMethod(Games::class, $method);
        $ref->setAccessible(true);
        return $ref->invokeArgs($this->games, array_merge($params, $extra));
    }
}