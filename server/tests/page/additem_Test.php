<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/additem.class.php";

/**
 * @group page
 */
class additem_Test extends TestCase {
	/**
	 * @small
	 * @covers additemPage::buildHtmlBody
	 * @covers additemPage::__construct
	 * @uses Get_Header
	 * @uses boolText
	 * @uses dataAccess
	 * @uses get_db_connection
	 * @uses get_navmenu
	 * @uses getsettings
	 */
	public function test_outputHtml() {
		$_SERVER['QUERY_STRING']="";
		$page = new additemPage();
		ob_start();
		$page->buildHtmlBody();
        $result = ob_get_clean();
		
		$this->assertisString($result);
	}

}