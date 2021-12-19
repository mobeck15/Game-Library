<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getsettings.inc.php";
require_once $GLOBALS['rootpath']."\inc\utility.inc.php";

final class getsettings_Test extends TestCase
{
	/**
	 * @covers getsettings
	 */
    public function test_getsettings_global() {
        $this->assertisArray(getsettings());
		//Test again to verify global settings function
        $this->assertisArray(getsettings());
    }
	
	/**
	 * @covers getsettings
	 * @uses get_db_connection
	 */
    public function test_getsettings_conn() {
		$_GET['CountFree']=0;
		
		$conn=get_db_connection();
		$this->assertIsArray(getsettings($conn));
    }
	
	/**
	 * @covers getsettings
	 */
    public function test_getsettings_debug() {
		$GLOBALS['Debug_Enabled']=true;
		
		$this->expectNotice();
		$this->expectNoticeMessage('Settings loaded, values shown below.');
		
		getsettings();
    }
}