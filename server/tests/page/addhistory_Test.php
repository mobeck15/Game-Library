<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/addhistory.class.php";

/**
 * @group page
 * @coversNothing
 */
class addhistory_Test extends TestCase {
	/**
	 * @small
	 * @covers addhistoryPage::outputHtml
	 * @uses Get_Footer
	 * @uses Get_Header
	 * @uses addhistoryPage
	 * @uses boolText
	 * @uses dataAccess
	 * @uses get_db_connection
	 * @uses get_navmenu
	 * @uses read_memory_usage
	 */
	public function test_outputHtml() {
		$_SERVER['QUERY_STRING']="";
		$page = new addhistoryPage();
		ob_start();
		$page->outputHtml();
        $result = ob_get_clean();
		
		$this->assertisString($result);
	}

}