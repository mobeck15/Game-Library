<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/datacheck.class.php";

/**
 * @group pageclass
 */
class datacheck_Test extends TestCase {
	/**
	 * @small
	 * @covers datacheckPage::buildHtmlBody
	 * @covers datacheckPage::__construct
	 * @uses combinedate
	 * @uses findgaps
	 * @uses getCleanStringDate
	 * @uses get_db_connection
	 */
	public function test_outputHtml() {
		$page = new datacheckPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}