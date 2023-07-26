<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/viewbundle.class.php";

/**
 * @group pageclass
 * @group viewbundle
 * @testdox viewbundle_Test.php testing viewbundle.class.php
 */
class viewbundle_Test extends TestCase {
	/**
	 * @medium
	 * @covers viewbundlePage::buildHtmlBody
	 * @covers viewbundlePage::__construct
	 * @testdox __construct & buildHtmlBody
	 * @uses Games
	 * @uses Purchases
	 * @uses combinedate
	 * @uses dataAccess
	 * @uses getActivityCalculations
	 * @uses getAllItems
	 * @uses getCleanStringDate
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses lookupTextBox
	 * @uses makeIndex
	 * @uses timeduration
	 */
	public function test_outputHtml() {
		$page = new viewbundlePage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @medium
	 * @covers viewbundlePage
	 * @testdox buildHtmlBody-BundleView
	 */
	public function test_outputHtmlBundle() {
		$page = new viewbundlePage();
		$_GET["id"]=11;
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @medium
	 * @covers viewbundlePage
	 * @testdox buildHtmlBody-Edit
	 */
	public function test_outputHtmlEidtBundle() {
		$page = new viewbundlePage();
		$_GET["id"]=11;
		$_GET["edit"]=1;
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
}