<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getsettings.inc.php";
require_once $GLOBALS['rootpath']."\inc\utility.inc.php";

/**
 * @testdox getsettings_Test.php testing getsettings.inc.php
 * @group include
 */
final class getsettings_Test extends TestCase
{
	/**
	 * @small
	 * @covers getsettings
	 * @uses get_db_connection
	 * @testdox getsettings with no parameters
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
	 * @testdox getsettings with connection provided
	 */
    public function test_getsettings_conn() {
		$_GET['CountFree']=0;
		
		$conn=get_db_connection();
		$this->assertIsArray(getsettings($conn));
		$conn->close();
    }
}