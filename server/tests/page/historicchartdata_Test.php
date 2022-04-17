<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/historicchartdata.class.php";

/**
 * @group pageclass
 */
class historicchartdata_Test extends TestCase {
	/**
	 * @small
	 * @covers historicchartdataPage::buildHtmlBody
	 * @covers historicchartdataPage::__construct
	 * @uses getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses reIndexArray
	 * @uses timeduration
	 */
	public function test_outputHtml() {
		$page = new historicchartdataPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

}