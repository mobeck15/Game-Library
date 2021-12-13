<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require $GLOBALS['rootpath'].'\inc\template.inc.php';

final class Template_Test extends TestCase
{
	/**
	 * @covers Get_Header
	 * @uses get_navmenu
	 */
    public function test_Get_Header() {
        $this->assertisString(Get_Header());
        $this->assertisString(Get_Header("Page Title"));
        $this->assertisString(Get_Header("Page Title", true));
    }

	/**
	 * @covers Get_Footer
	 * @uses read_memory_usage
	 */
    public function test_Get_Footer() {
        $this->assertisString(Get_Footer());
        $this->assertisString(Get_Footer(true));
    }

	/**
	 * @covers get_navmenu
	 */
    public function test_get_navmenu() {
        $this->assertisString(get_navmenu());
        $this->assertisString(get_navmenu(false));
		
		$_SERVER['SERVER_NAME']="website";
        $this->assertisString(get_navmenu());
    }

}
