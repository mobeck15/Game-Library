<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/_page.class.php";
require_once $GLOBALS['rootpath'].'\tests\inc\testprivate.inc.php';

/**
 * @group pageclass
 * @testdox page_Test.php testing _page.class.php
 */
class page_Test extends testprivate {
	/**
	 * @testdox outputHtml
	 * @small
	 * @covers Page
	 * @uses Get_Footer
	 * @uses Get_Header
	 * @uses get_navmenu
	 * @uses read_memory_usage
	 */
	public function test_outputHtml() {
		$page = new Page();
        ob_start();
		$result1 = $page->outputHtml();
		$result = ob_get_clean();
		$this->assertisString($result);
		$this->assertEquals("",$result1);
	}

	/**
	 * @testdox buildHtmlBody
	 * @small
	 * @covers Page
	 */
	public function test_buildHtmlBody() {
		$page = new Page();
		$page->buildHtmlBody();
		//$property = $this->getPrivateProperty( 'Page', 'body' );
		$this->assertEquals("Page Body",$page->outputBody());
	}
	
	/**
	 * @small
	 * @testdox getDataAccessObject()
	 * @covers Page::getDataAccessObject
	 * @uses Page
	 * @uses dataAccess
	 */
	public function test_getDataAccessObject() {
		$page = new Page();
		
		$method = $this->getPrivateMethod( 'Page', 'getDataAccessObject' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertThat($result,
			$this->isInstanceOf("dataAccess")
		);
	}
	
	/**
	 * @small
	 * @testdox getSettings()
	 * @covers Page::getSettings
	 * @uses Page
	 * @uses get_db_connection
	 * @uses getsettings
	 */
	public function test_getSettings() {
		$page = new Page();
		
		$method = $this->getPrivateMethod( 'Page', 'getSettings' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertisArray($result);
	}
}