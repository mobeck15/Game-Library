<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/addtransaction.class.php";

/**
 * @testdox addtransaction_Test.php testing addtransaction.class.php
 * @group pageclass
 */
class addtransaction_Test extends testprivate 
{
	/**
	 * @testdox __construct & buildHtmlBody
	 * @small
	 * @covers addtransactionPage::buildHtmlBody
	 * @covers addtransactionPage::__construct
	 * @uses Page
	 * @uses dataAccess
	 */
	public function test_outputHtml() {
		$page = new addtransactionPage();
		$result = $page->buildHtmlBody();
		
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @testdox buildHtmlBody with POST
	 * @covers addtransactionPage::buildHtmlBody
	 * @uses addtransactionPage
	 * @uses Page
	 */
	public function test_outputHtml_post() {
		$page = new addtransactionPage();

		$_POST = Array ( 
		"TransID"      => 513 ,
		"Title"        => "Test Bundle",
		"Store"        => "Test Store",
		"BundleID"     => 513 ,
		"Tier"         => 1 ,
		"PurchaseDate" => "2014-08-28T11:35" ,
		"PurchaseTime" => "2014-08-28T11:35" ,
		"Sequence"     => "Game",
		"Price"        => 1.23 ,
		"Fees"         => 2.34,
		"Paid"         => 3.45,
		"CreditUsed"   => 4.56,
		"BundleLink"   => "https://link.link" 
		);
		
		$dataAccessMock = $this->createMock(dataAccess::class);
		$dataAccessMock->expects($this->once())
                       ->method('insertTransaction')
                       ->with($this->anything());
		$maxID = $this->getPrivateProperty( 'addtransactionPage', 'dataAccessObject' );
		$maxID->setValue( $page , $dataAccessMock );

		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
}