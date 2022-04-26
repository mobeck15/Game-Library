<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/viewallhistory.class.php";

/**
 * @group pageclass
 * @testdox viewallhistory_Test.php testing viewallhistory.class.php
 */
class viewallhistory_Test extends TestCase {
	/**
	 * @small
	 * @covers viewallhistoryPage::buildHtmlBody
	 * @covers viewallhistoryPage::__construct
	 * @testdox __construct & buildHtmlBody
	 * @uses get_db_connection
	 */
	public function test_outputHtml() {
		$page = new viewallhistoryPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}