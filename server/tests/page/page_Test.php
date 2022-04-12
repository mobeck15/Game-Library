<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/_page.class.php";
require_once $GLOBALS['rootpath'].'\tests\inc\testprivate.inc.php';

/**
 * @group pageclass
 */
class page_Test extends testprivate {
	/**
	 * @covers Page
	 * @uses Get_Footer
	 * @uses Get_Header
	 * @uses get_navmenu
	 * @uses read_memory_usage
	 */
	public function test_outputHtml() {
		$page = new Page();
		$result = $page->outputHtml();
		$this->assertisString($result);
	}

	/**
	 * @covers Page
	 */
	public function test_buildHtmlBody() {
		$page = new Page();
		$page->buildHtmlBody();
		//$property = $this->getPrivateProperty( 'Page', 'body' );
		$this->assertEquals("Page Body",$page->outputBody());
	}
}