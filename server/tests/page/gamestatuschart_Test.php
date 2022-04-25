<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/gamestatuschart.class.php";

/**
 * @group pageclass
 * @testdox gamestatuschart_Test.php testing gamestatuschart.class.php
 */
class gamestatuschart_Test extends TestCase {
	/**
	 * @large
	 * @covers gamestatuschartPage::buildHtmlBody
	 * @covers gamestatuschartPage::__construct
	 * @testdox __construct & buildHtmlBody
	 */
	public function test_outputHtml() {
		$page = new gamestatuschartPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @large
	 * @covers gamestatuschartPage::buildHtmlBody
	 * @uses gamestatuschartPage
	 * @testdox buildHtmlBody() with date range parameters
	 */
	public function test_outputHtml_daterange() {
		$_GET['start']='2022-1-1';
		$_GET['end']='2022-12-31';
		$page = new gamestatuschartPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
}