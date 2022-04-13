<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/cpi.class.php";

/**
 * @group pageclass
 */
class cpi_Test extends TestCase {
	/**
	 * @small
	 * @covers cpiPage::buildHtmlBody
	 * @covers cpiPage::__construct
	 * @uses getAllCpi
	 * @uses get_db_connection
	 */
	public function test_outputHtml() {
		$page = new cpiPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}