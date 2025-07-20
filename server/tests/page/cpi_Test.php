<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/cpi.class.php";

/**
 * @group pageclass
 * @testdox cpi_Test.php testing cpi.class.php
 */
class cpi_Test extends testprivate {
	/**
	 * @small
	 * @covers cpiPage::buildHtmlBody
	 * @covers cpiPage::__construct
	 * @uses Page
	 * @uses dataAccess
	 * @testdox __construct & buildHtmlBody
	 */
	public function test_outputHtml() {
		$page = new cpiPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @testdox buildHtmlBody with POST
	 * @covers cpiPage::buildHtmlBody
	 * @uses cpiPage
	 * @uses Page
	 */
	public function test_outputHtml_post() {
		$page = new cpiPage();
	
		$_POST = Array ( 
		"CPI" => 513,
		"Year" => 513,
		"Month" => 513
		);
		
		$cpi = array(
			"Current" => "292.2960",
			1913 => array (
				1 => "9.8000",
				2 => "9.8000",
				3 => "9.8000",
				4 => "9.8000",
				5 => "9.8000",
				6 => "9.8000",
				7 => "9.8000",
				8 => "9.8000",
				9 => "9.8000",
				10 => "9.8000",
				11 => "9.8000",
				12 => "9.8000"
			)
		);
		
		$dataAccessMock = $this->createMock(dataAccess::class);
		$dataAccessMock->expects($this->once())
                       ->method('addCPI')
                       ->with($this->anything());
		$dataAccessMock->method('getAllCPI')
					   ->willReturn($cpi);
		$maxID = $this->getPrivateProperty( 'cpiPage', 'dataAccessObject' );
		$maxID->setValue( $page , $dataAccessMock );

		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
}