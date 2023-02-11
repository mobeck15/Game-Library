<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath'].'\inc\template.inc.php';

/**
 * @group include
 * @testdox template_Test.php testing template.inc.php
 */
final class Template_Test extends TestCase
{
	/**
	 * @testdox Get_Header with no parameters
	 * @small
	 * @covers Get_Header
	 * @uses get_navmenu
	 */
    public function test_Get_Header_base() {
        $this->assertisString(Get_Header());
    }

	/**
	 * @testdox Get_Header with title
	 * @small
	 * @covers Get_Header
	 * @uses get_navmenu
	 */
    public function test_Get_Header_title() {
        $this->assertisString(Get_Header("Page Title"));
    }

	/**
	 * @testdox Get_Header with title and WIP flag
	 * @small
	 * @covers Get_Header
	 * @uses get_navmenu
	 */
    public function test_Get_Header_wip() {
        $this->assertisString(Get_Header("Page Title", true));
    }

	/**
	 * @testdox Get_Footer with no parameters
	 * @small
	 * @covers Get_Footer
	 * @uses read_memory_usage
	 */
    public function test_Get_Footer_base() {
        $this->assertisString(Get_Footer());
    }

	/**
	 * @testdox Get_Footer with WIP flag
	 * @small
	 * @covers Get_Footer
	 * @uses read_memory_usage
	 */
    public function test_Get_Footer_wip() {
        $this->assertisString(Get_Footer(true));
    }

	/**
	 * @testdox get_navmenu with no parameters
	 * @small
	 * @covers get_navmenu
	 */
    public function test_get_navmenu_base() {
        $this->assertisString(get_navmenu());
    }

	/**
	 * @testdox get_navmenu with dropbar flag
	 * @small
	 * @covers get_navmenu
	 */
    public function test_get_navmenu_dropbar() {
        $this->assertisString(get_navmenu(false));
    }

	/**
	 * @testdox get_navmenu with SERVER_NAME set
	 * @small
	 * @covers get_navmenu
	 */
    public function test_get_navmenu_servername() {
		$_SERVER['SERVER_NAME']="website";
        $this->assertisString(get_navmenu());
    }
}
