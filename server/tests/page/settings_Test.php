<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/settings.class.php";

/**
 * @group pageclass
 * @testdox settings_Test.php testing settings.class.php
 */
class settings_Test extends TestCase {
	/**
	 * @small
	 * @covers settingsPage::buildHtmlBody
	 * @covers settingsPage::__construct
	 * @testdox __construct & buildHtmlBody
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses timeduration
	 */
	public function test_outputHtml() {
		$page = new settingsPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @covers settingsPage::buildHtmlBody
	 * @uses settingsPage
	 * @uses Page
	 * @uses dataAccess
	 * @testdox buildHtmlBody post
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses timeduration
	 */
	public function test_outputHtml_post() {
		$_POST = array( 
			"Tax"            => "8.59" ,
			"TrackHours"     => "20",
			"LessStat"       => "0.01",
			"XhourGet"       => "1",
			"StartStats"     => "2005-08-22",
			"CountFarm"      => "on" ,
			"CountCheat"     => "on" ,
			"MinPlay"        => "60" ,
			"MinTotal"       => "60",
			"CountFree"      => "on", 
			"WantX"          => "0",
			"CountWantX"     => "on",
			"Active-Active"  => "on",
			"Active-Count"   => "on",
			"Done-Count"     => "on",
			"Inactive-Count" => "on",
			"On_Hold-Count"  => "on",
			"Unplayed-Count" => "on" );
		
		$page = new settingsPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
}