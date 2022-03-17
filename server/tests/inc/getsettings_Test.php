<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getsettings.inc.php";
require_once $GLOBALS['rootpath']."\inc\utility.inc.php";

/**
 * @group include
 */
final class getsettings_Test extends TestCase
{
	/**
	 * @small
	 * @covers getsettings
	 * @uses get_db_connection
	 */
    public function test_getsettings_global() {
        $this->assertisArray(getsettings());
		//Test again to verify global settings function
        $this->assertisArray(getsettings());
    }
	
	/**
	 * @small
	 * @covers getsettings
	 * @uses get_db_connection
	 */
    public function test_getsettings_conn() {
		$_GET['CountFree']=0;
		
		$conn=get_db_connection();
		$this->assertIsArray(getsettings($conn));
		$conn->close();
    }
	
	/**
	 * @small
	 * @covers getsettings
	 * @uses get_db_connection
	 */
    public function test_getsettings_debug() {
		$GLOBALS['Debug_Enabled']=true;
		
		$this->expectNotice();
		$this->expectNoticeMessage('Settings loaded, values shown below.');
		
		getsettings();
    }
}