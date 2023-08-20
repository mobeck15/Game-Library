<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/statistics.class.php";

/**
 * @group pageclass
 * @testdox statistics_Test.php testing cpi.class.php
 */
class statistics_Test extends TestCase {
	/**
	 * @small
	 * @covers statisticsPage::buildHtmlBody
	 * @covers statisticsPage::__construct
	 * @testdox __construct & buildHtmlBody
	 */
	public function test_outputHtml() {
		$page = new statisticsPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @large
	 * @covers statisticsPage::buildHtmlBody
	 * @testdox buildHtmlBody both metastats and filter all
	 */
	public function test_outputHtml_all() {
		$_GET['filter']='All';
		$_GET['meta']='both';
		$_GET['stat']='MSRP';
		$page = new statisticsPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
}