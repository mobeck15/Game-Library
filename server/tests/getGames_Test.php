<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getGames.inc.php";

final class getGames_Test extends TestCase
{
	/**
	 * @covers getGames
	 * @uses get_db_connection
	 * @uses CalculateGameRow
	 * @uses getAllCpi
	 * @uses timeduration
	 */
    public function test_getGames() {
        $this->assertisArray(getGames());
		
        $this->assertisArray(getGames(2));
        $this->assertisArray(getGames(array(2,3)));

		$conn=get_db_connection();
		$this->assertisArray(getGames("",$conn));
	}
	
	/**
	 * @covers getGames
	 * @uses getsettings
	 */
    public function test_getGames_error() {
        $this->expectNotice();
		$this->assertisArray(getGames(";"));
	}

	/**
	 * @covers CalculateGameRow
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