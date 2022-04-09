<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/activity.class.php";

/**
 * @group page
 */
class activity_Test extends TestCase {
	/**
	 * @medium
	 * @covers activityPage::buildHtmlBody
	 * @covers activityPage::__construct
	 * @uses activityPage
	 * @uses getActivityCalculations
	 * @uses getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses timeduration
	 */
	public function test_buildHtmlBody() {
		$page = new activityPage();
		$result = $page->buildHtmlBody();
		
		$this->assertisString($result);
	}

}