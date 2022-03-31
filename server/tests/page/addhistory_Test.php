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
	 * @covers addhistory::outputHtml
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