<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/playnext2.class.php";

/**
 * @group pageclass
 * @testdox playnext2_Test.php testing playnext2.class.php
 */
class playnext2_Test extends testprivate {
	/**
	 * @small
	 * @covers playnext2Page::buildHtmlBody
	 * @covers playnext2Page::__construct
	 * @uses playnext2Page
	 * @testdox __construct & buildHtmlBody
	 * @testWith ["Active"]
	 *           ["no"]
	 */
	public function test_outputHtml($getvalue) {
		$page = new playnext2Page();
		$_GET["mode"]=$getvalue;
		
		$dataStub2 = $this->createStub(topx::class);
		$maxID2 = $this->getPrivateProperty( 'playnext2Page', 'topxobj' );
		$maxID2->setValue( $page , $dataStub2 );
		
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @testdox getTopxObject()
	 * @covers playnext2Page::getTopxObject
	 * @uses Page
	 * @uses topx
	 * @uses playnext2Page
	 */
	public function test_getTopxObject() {
		$page = new playnext2Page();
		
		$dataStub2 = $this->createStub(dataSet::class);
		$dataStub2->method('getCalculations')
				 ->willReturn(array());
		$maxID2 = $this->getPrivateProperty( 'playnext2Page', 'data' );
		$maxID2->setValue( $page , $dataStub2 );
		
		$method = $this->getPrivateMethod( 'playnext2Page', 'getTopxObject' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertThat($result,
			$this->isInstanceOf("topx")
		);
	}
}