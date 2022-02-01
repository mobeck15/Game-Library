<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getHistoryCalculations.inc.php";

/**
 * @group include
 */
final class getHistoryCalculations_Test extends TestCase
{
	/**
	 * @small
	 * @covers getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 */
    public function test_getHistoryCalculations_detail() {
		$output=getHistoryCalculations();
        $this->assertisArray($output);
		foreach($output as $row){
			$this->assertisNumeric($row['Elapsed']);
		}
	}

	/**
	 * @small
	 * @covers getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 */
    public function test_getHistoryCalculations_conn() {
		$conn=get_db_connection();
		$start=strtotime("2021-01-01");
		$end=strtotime("2021-01-31");
		$this->assertisArray(getHistoryCalculations("2",$conn,$start,$end));
		$conn->close();
	}
	
	/**
	 * @small
	 * @covers getHistoryCalculations
	 * @uses getsettings
	 * @uses get_db_connection
	 */
    public function test_getHistoryCalculations_Gameid() {
        $this->assertisArray(getHistoryCalculations(2));
	}

	/**
	 * @small
	 * @covers getHistoryCalculations
	 * @uses getsettings
	 * @uses get_db_connection
	 */
    public function test_getHistoryCalculations_daterange() {
		$start=strtotime("2021-01-01");
		$end=strtotime("2021-01-31");
        $this->assertisArray(getHistoryCalculations("",false,$start,$end));
	}
	
	/**
	 * @small
	 * @covers getHistoryCalculations
	 * @uses getsettings
	 * @uses get_db_connection
	 */
    public function test_getHistoryCalculations_error() {
        $this->expectNotice();
		$this->assertisArray(getHistoryCalculations(";"));
	}
	
}