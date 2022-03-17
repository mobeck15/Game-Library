<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath'].'\tests\inc\testprivate.inc.php';
require_once $GLOBALS['rootpath']."\inc\getGames.inc.php";

/**
 * @group include
 * @group getGames
 */
final class getGames_Test extends testprivate
{
	/**
	 * @small
	 * @covers Game::__construct
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
	 * @covers Games::getGames
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
	 * @covers Games::getGames
	 * @covers getGames
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
	 * @covers Games::getGames
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
	 * @covers Games::getGames
	 * @covers getGames
	 * @uses get_db_connection
	 * /
    public function test_getGames_error() {
        $this->expectNotice();
		$this->assertisArray(getGames(";"));
	}
	/* */

	/**
	 * @small
	 * @covers Games::CalculateGameRow
	 * @uses getAllCpi
	 * @uses getGames
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

		$method = $this->getPrivateMethod( 'Games', 'CalculateGameRow' );
		$result = $method->invokeArgs( $GameListObject, array($gamerow) );
		
		$this->assertisArray($result);

		$gamerow["MetascoreID"]="";
		$gamerow["TimeToBeatID"]="";
		
		$result = $method->invokeArgs( $GameListObject, array($gamerow) );
		$this->assertisArray($result);
	}
	/* */
}