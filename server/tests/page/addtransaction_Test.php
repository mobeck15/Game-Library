<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/addtransaction.class.php";

/**
 * @group pageclass
 */
class addtranaction_Test extends TestCase {
	/**
	 * @small
	 * @covers addtransactionPage::buildHtmlBody
	 * @covers addtransactionPage::__construct
	 * @uses get_db_connection
	 * @uses getsettings
	 */
	public function test_outputHtml() {
		$page = new addtransactionPage();
		$result = $page->buildHtmlBody();
		
		$this->assertisString($result);
	}

}