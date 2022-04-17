<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/gl6.class.php";

/**
 * @group pageclass
 */
class gl6_Test extends TestCase {
	/**
	 * @small
	 * @covers gl6Page::buildHtmlBody
	 * @covers gl6Page::__construct
	 * @uses SteamFormat
	 * @uses get_db_connection
	 * @uses get_navmenu
	 * @uses getsettings
	 * @uses gl6Page
	 */
	public function test_outputHtml() {
		$page = new gl6Page();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}