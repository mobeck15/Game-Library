<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getActivityCalculations.inc.php";

/**
 * @group include
 */
final class getActivityCalculations_Test extends TestCase
{
	/**
	 * @small
	 * @uses getsettings
	 * @uses get_db_connection
	 * @covers getActivityCalculations
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
	 * @group dbconnect
	 * @small
	 * @covers getActivityCalculations
	 * @uses getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 */
    public function test_getActivityCalculations_conn() {
		//TODO: Add default Settings variable.
		$conn=get_db_connection();
        $this->assertisArray(getActivityCalculations("2","",$conn));
		$conn->close();
   }
   
	/**
	 * @group dbconnect
	 * @small
	 * @covers getActivityCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 */
    public function test_getActivityCalculations_false() {
		$GLOBALS["SETTINGS"]['MinPlay']=120;
		$GLOBALS["SETTINGS"]['MinTotal']=120;
		
        $this->assertEquals(false,getActivityCalculations(415,false));
   }
}