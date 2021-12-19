<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getHistoryCalculations.inc.php";

final class getHistoryCalculations_Test extends TestCase
{
	/**
	 * @covers getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 */
    public function test_getHistoryCalculations() {
        $this->assertisArray(getHistoryCalculations());

		$conn=get_db_connection();
        $this->assertisArray(getHistoryCalculations("",$conn));
	}
   
	/**
	 * @covers getHistoryCalculations
	 * @uses getsettings
	 */
    public function test_getHistoryCalculations_Gameid() {
        $this->assertisArray(getHistoryCalculations(2));
	}

	/**
	 * @covers getHistoryCalculations
	 * @uses getsettings
	 */
    public function test_getHistoryCalculations_daterange() {
		$start=strtotime("2021-01-01");
		$end=strtotime("2021-01-31");
        $this->assertisArray(getHistoryCalculations("",false,$start,$end));
	}
	
	/**
	 * @covers getHistoryCalculations
	 * @uses getsettings
	 */
    public function test_getHistoryCalculations_error() {
        $this->expectNotice();
		$this->assertisArray(getHistoryCalculations(";"));
	}
	
}