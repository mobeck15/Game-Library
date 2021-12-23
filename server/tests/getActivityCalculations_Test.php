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
	 * @covers getActivityCalculations
	 * @uses getHistoryCalculations
	 * @uses getsettings
	 * @uses get_db_connection
	 * Time: 00:03.641, Memory: 100.00 MB
	 * (2 tests, 3 assertions)
	 */
    public function test_getActivityCalculations() {
        $this->assertisArray(getActivityCalculations());

		$conn=get_db_connection();
        $this->assertisArray(getActivityCalculations("","",$conn));
   }
	
	/**
	 * @covers getActivityCalculations
	 * @uses getsettings
	 * Time: 00:00.243, Memory: 48.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_getActivityCalculations_false() {
        $this->assertEquals(false,getActivityCalculations(415,false));
   }
}