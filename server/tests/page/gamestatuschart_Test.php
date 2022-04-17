<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/gamestatuschart.class.php";

/**
 * @group pageclass
 */
class gamestatuschart_Test extends TestCase {
	/**
	 * @medium
	 * @covers gamestatuschartPage::buildHtmlBody
	 * @covers gamestatuschartPage::__construct
	 */
	public function test_outputHtml() {
		$page = new gamestatuschartPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}