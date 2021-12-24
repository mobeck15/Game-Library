<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getHistoryCalculations.inc.php";

//Time: 00:02.345, Memory: 98.00 MB
//(4 tests, 5 assertions)
/**
 * @group include
 */
final class getHistoryCalculations_Test extends TestCase
{
	/**
	 * @group slow
	 * @group medium
	 * @covers getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 * Time: 00:01.572, Memory: 104.00 MB
	 * (1 test, 2 assertions)
	 */
    public function test_getHistoryCalculations_conn() {
        $this->assertisArray(getHistoryCalculations());

		$conn=get_db_connection();
        $this->assertisArray(getHistoryCalculations("2",$conn,1422463133,1422517133));
	}
   
	/**
	 * @group fast
	 * @covers getHistoryCalculations
	 * @uses getsettings
	 * Time: 00:00.244, Memory: 48.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_getHistoryCalculations_Gameid() {
        $this->assertisArray(getHistoryCalculations(2));
	}

	/**
	 * @group fast
	 * @covers getHistoryCalculations
	 * @uses getsettings
	 * Time: 00:00.277, Memory: 48.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_getHistoryCalculations_daterange() {
		$start=strtotime("2021-01-01");
		$end=strtotime("2021-01-31");
        $this->assertisArray(getHistoryCalculations("",false,$start,$end));
	}
	
	/**
	 * @group fast
	 * @covers getHistoryCalculations
	 * @uses getsettings
	 * Time: 00:00.243, Memory: 48.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_getHistoryCalculations_error() {
        $this->expectNotice();
		$this->assertisArray(getHistoryCalculations(";"));
	}
	
}