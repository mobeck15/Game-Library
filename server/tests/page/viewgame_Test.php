<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/viewgame.class.php";

/**
 * @group pageclass
 * @testdox viewgame_Test.php testing viewgame.class.php
 */
class viewgame_Test extends TestCase {
	/**
	 * @small
	 * @covers viewgamePage::buildHtmlBody
	 * @covers viewgamePage::__construct
	 * @testdox __construct & buildHtmlBody
	 * @uses get_db_connection
	 * @uses lookupTextBox
	 */
	public function test_outputHtml() {
		$page = new viewgamePage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}