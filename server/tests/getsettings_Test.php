<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getsettings.inc.php";
require_once $GLOBALS['rootpath']."\inc\utility.inc.php";

//Time: 00:00.267, Memory: 48.00 MB
//(3 tests, 5 assertions)
final class getsettings_Test extends TestCase
{
	/**
	 * @covers getsettings
	 * Time: 00:00.244, Memory: 48.00 MB
	 * (1 test, 2 assertions)
	 */
    public function test_getsettings_global() {
        $this->assertisArray(getsettings());
		//Test again to verify global settings function
        $this->assertisArray(getsettings());
    }
	
	/**
	 * @covers getsettings
	 * @uses get_db_connection
	 * Time: 00:00.236, Memory: 48.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_getsettings_conn() {
		$_GET['CountFree']=0;
		
		$conn=get_db_connection();
		$this->assertIsArray(getsettings($conn));
    }
	
	/**
	 * @covers getsettings
	 * Time: 00:00.239, Memory: 48.00 MB
	 * (1 test, 2 assertions)
	 */
    public function test_getsettings_debug() {
		$GLOBALS['Debug_Enabled']=true;
		
		$this->expectNotice();
		$this->expectNoticeMessage('Settings loaded, values shown below.');
		
		getsettings();
    }
}