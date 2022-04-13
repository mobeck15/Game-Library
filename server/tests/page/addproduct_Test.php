<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/addproduct.class.php";

/**
 * @testdox addproduct_Test.php testing addproduct.class.php
 * @group pageclass
 */
class addproduct_Test extends TestCase {
	/**
	 * @testdox __construct & buildHtmlBody
	 * @small
	 * @covers addproductPage::buildHtmlBody
	 * @covers addproductPage::__construct
	 * @uses get_db_connection
	 * @uses getsettings
	 */
	public function test_outputHtml() {
		//$_SERVER['QUERY_STRING']="";
		$page = new addproductPage();
		$result = $page->buildHtmlBody();
		
		$this->assertisString($result);
	}

}