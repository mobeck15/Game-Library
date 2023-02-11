<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/export.class.php";

/**
 * @group pageclass
 */
class exportPage_Test extends TestCase {
	/**
	 * @small
	 * @covers exportPage::buildHtmlBody
	 * @covers exportPage::__construct
	 */
	public function test_outputHtml() {
		$page = new exportPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}