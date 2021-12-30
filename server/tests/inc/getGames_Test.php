<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getGames.inc.php";

//Time: 00:00.979, Memory: 58.00 MB
//(4 tests, 8 assertions)
/**
 * @group include
 */
final class getGames_Test extends TestCase
{
	/**
	 * @group fast
	 * @small
	 * @covers getGames
	 * @uses get_db_connection
	 * @uses CalculateGameRow
	 * @uses getAllCpi
	 * @uses timeduration
	 * Time: 00:00.610, Memory: 70.00 MB
	 * (1 test, 3 assertions)
	 */
    public function test_getGames_base() {
        $this->assertisArray(getGames());
		
        $this->assertisArray(getGames(2));
        $this->assertisArray(getGames(array(2,3)));
	}
	
	/**
	 * @group fast
	 * @small
	 * @covers getGames
	 * @uses CalculateGameRow
	 * @uses getAllCpi
	 * @uses get_db_connection
	 * @uses timeduration
	 * Time: 00:00.571, Memory: 70.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_getGames_conn() {
		$conn=get_db_connection();
		$this->assertisArray(getGames("",$conn));
		$conn->close();
	}
	
	/**
	 * @group fast
	 * @small
	 * @covers getGames
	 * @uses getsettings
	 * @uses get_db_connection
	 * Time: 00:00.234, Memory: 48.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_getGames_error() {
        $this->expectNotice();
		$this->assertisArray(getGames(";"));
	}

	/**
	 * @group fast
	 * @small
	 * @covers CalculateGameRow
	 * @uses getAllCpi
	 * @uses getGames
	 * @uses timeduration
	 * Time: 00:00.221, Memory: 46.00 MB
	 * (1 test, 3 assertions)
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
		
		$this->assertisArray(CalculateGameRow($gamerow));
		
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
		
		$this->assertisArray(CalculateGameRow($gamerow));

		$gamerow["MetascoreID"]="";
		$gamerow["TimeToBeatID"]="";
		
		$this->assertisArray(CalculateGameRow($gamerow));
	}
}