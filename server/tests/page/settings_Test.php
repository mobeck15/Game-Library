<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/settings.class.php";

/**
 * @group pageclass
 * @testdox settings_Test.php testing settings.class.php
 */
class settings_Test extends TestCase {
	/**
	 * @small
	 * @covers settingsPage::buildHtmlBody
	 * @covers settingsPage::__construct
	 * @testdox __construct & buildHtmlBody
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses timeduration
	 */
	public function test_outputHtml() {
		$page = new settingsPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}