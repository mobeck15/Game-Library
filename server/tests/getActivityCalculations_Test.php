<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getActivityCalculations.inc.php";

//Time: 00:03.651, Memory: 100.00 MB
//(2 tests, 3 assertions)
final class getActivityCalculations_Test extends TestCase
{
	/**
	 * @group fast
	 * @uses getsettings
	 * @uses get_db_connection
	 * @covers getActivityCalculations
	 * Time: 00:00.451, Memory: 48.00 MB
	 * (1 test, 1 assertion))
	 */
    public function test_getActivityCalculations_base() {
		$GLOBALS["SETTINGS"]['MinPlay']=120;
		$GLOBALS["SETTINGS"]['MinTotal']=120;

		$historytable=array(
			array(
				'GameID'=>1,
				'Game'=>"testgame",
				'FinalCountHours'=>true,
				'Elapsed'=>500,
				'Total'=>500,
				'Timestamp'=>"1/1/2021 10:31 am",
				'Achievements'=>1,
				'Status'=>"Active",
				'Review'=>3,
				'kwBeatGame'=>false,
				'UseGame'=>1,
				'ParentGame'=>1,
				'LaunchDate'=>"1/1/2020"
			),
			array(
				'GameID'=>1,
				'Game'=>"testgame",
				'FinalCountHours'=>true,
				'Elapsed'=>1000,
				'Total'=>1500,
				'Timestamp'=>"1/2/2021 11:32 am",
				'Achievements'=>1,
				'Status'=>"Done",
				'Review'=>3,
				'kwBeatGame'=>true,
				'UseGame'=>1,
				'ParentGame'=>1,
				'LaunchDate'=>"1/1/2020"
			),
			array(
				'GameID'=>2,
				'Game'=>"recentgame",
				'FinalCountHours'=>true,
				'Elapsed'=>500,
				'Total'=>500,
				'Timestamp'=>date("n/j,Y",strtotime("-20 Days")),
				'Achievements'=>2,
				'Status'=>"Active",
				'Review'=>3,
				'kwBeatGame'=>false,
				'UseGame'=>1,
				'ParentGame'=>1,
				'LaunchDate'=>"1/1/2020"
			),
			array(
				'GameID'=>2,
				'Game'=>"recentgame",
				'FinalCountHours'=>true,
				'Elapsed'=>500,
				'Total'=>500,
				'Timestamp'=>date("n/j,Y",strtotime("-2 Days")),
				'Achievements'=>1,
				'Status'=>"Active",
				'Review'=>3,
				'kwBeatGame'=>false,
				'UseGame'=>1,
				'ParentGame'=>1,
				'LaunchDate'=>"1/1/2020"
			)

		);
		
        $this->assertisArray(getActivityCalculations("",$historytable));
   }

	/**
	 * @group fast
	 * @covers getActivityCalculations
	 * @uses getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 * Time: 00:00.342, Memory: 48.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_getActivityCalculations_conn() {
		//TODO: Add default Settings variable.
		$conn=get_db_connection();
        $this->assertisArray(getActivityCalculations("2","",$conn));
   }
   
	/**
	 * @group fast
	 * @covers getActivityCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 * Time: 00:00.243, Memory: 48.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_getActivityCalculations_false() {
		$GLOBALS["SETTINGS"]['MinPlay']=120;
		$GLOBALS["SETTINGS"]['MinTotal']=120;
		
        $this->assertEquals(false,getActivityCalculations(415,false));
   }
}