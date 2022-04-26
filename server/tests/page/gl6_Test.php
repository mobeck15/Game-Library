<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/gl6.class.php";

/**
 * @group pageclass
 * @testdox gl6_Test.php testing gl6.class.php
 */
class gl6_Test extends TestCase {
	/**
	 * @large
	 * @covers gl6Page::buildHtmlBody
	 * @covers gl6Page::__construct
	 * @covers gl6Page::dirToArray
	 * @covers gl6Page::readFileLines
	 * @uses SteamFormat
	 * @uses get_db_connection
	 * @uses get_navmenu
	 * @uses getsettings
	 * @uses gl6Page
	 * @testdox __construct & buildHtmlBody
	 */
	public function test_outputHtml() {
		$page = new gl6Page();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}