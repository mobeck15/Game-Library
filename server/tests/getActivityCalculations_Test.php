<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getActivityCalculations.inc.php";
//require_once $GLOBALS['rootpath']."\inc\utility.inc.php";

final class getActivityCalculations_Test extends TestCase
{
	/**
	 * @covers getActivityCalculations
	 * @uses getHistoryCalculations
	 * @uses getsettings
	 * @uses get_db_connection
	 */
    public function test_getActivityCalculations() {
        $this->assertisArray(getActivityCalculations());

		$conn=get_db_connection();
        $this->assertisArray(getActivityCalculations("","",$conn));
   }
	
	/**
	 * @covers getActivityCalculations
	 * @uses getsettings
	 */
    public function test_getActivityCalculations_false() {
        $this->assertEquals(false,getActivityCalculations(415,false));
   }
}