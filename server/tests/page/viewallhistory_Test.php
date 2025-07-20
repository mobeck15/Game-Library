<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/viewallhistory.class.php";

/**
 * @group pageclass
 * @testdox viewallhistory_Test.php testing viewallhistory.class.php
 */
class viewallhistory_Test extends testprivate {
	/**
	 * @small
	 * @covers viewallhistoryPage::buildHtmlBody
	 * @covers viewallhistoryPage::__construct
	 * @testdox __construct & buildHtmlBody - prompt
	 * @uses viewallhistoryPage
	 */
	public function test_outputHtml() {
		$page = new viewallhistoryPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @medium
	 * @covers viewallhistoryPage::buildHtmlBody
	 * @testdox buildHtmlBody - table
	 * @uses viewallhistoryPage
	 * @uses boolText
	 * @uses getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses timeduration
	 */
	public function test_outputHtmlTable() {
		$_GET['num']="1";
		$_GET['Sort']="Played";
		$page = new viewallhistoryPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @covers viewallhistoryPage::makeDataCell
	 * @testdox makeDataCell()
	 * @uses viewallhistoryPage
	 */
	public function test_makeDataCell() {
		$page = new viewallhistoryPage();
		
		$method = $this->getPrivateMethod( 'viewallhistoryPage', 'makeDataCell' );
		$result = $method->invokeArgs( $page,array("1","2") );
		$this->assertEquals($result, "<td class='1'>2</td>");
	}

	/**
	 * @small
	 * @covers viewallhistoryPage::makeDataRow
	 * @testdox makeDataRow()
	 * @uses viewallhistoryPage
	 * @uses boolText
	 * @uses timeduration
	 */
	public function test_makeDataRow() {
		$page = new viewallhistoryPage();
		
		$row["HistoryID"]="0";
		$row["Timestamp"]="0";
		$row["FinalStatus"]="0";
		$row["GameID"]="0";
		$row["Game"]="0";
		$row["System"]="0";
		$row["Data"]="0";
		$row["Time"]="0";
		$row["Notes"]="0";
		$row["KeyWords"]="0";
		$row["Elapsed"]="0";
		$row["totalSys"]="0";
		$row["Total"]="0";
		$row["finalRating"]="0";
		$row["FinalCountHours"]="0";
		$row["UseGame"]="0";
		
		$method = $this->getPrivateMethod( 'viewallhistoryPage', 'makeDataRow' );
		$result = $method->invokeArgs( $page,array($row) );
		$this->assertIsString($result);
	}

	/**
	 * @medium
	 * @covers viewallhistoryPage::sortHistory
	 * @testdox sortHistory()
	 * @uses viewallhistoryPage
	 * @uses getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 */
	public function test_sortHistory() {
		$page = new viewallhistoryPage();
		
		$method = $this->getPrivateMethod( 'viewallhistoryPage', 'sortHistory' );
		$result = $method->invokeArgs( $page,array("Played") );
		
		$property = $this->getPrivateProperty( 'viewallhistoryPage', 'historytable' );
		$result2 = $property->getValue( $page );
		
		$this->assertIsArray($result2);
	}

	/**
	 * @small
	 * @covers viewallhistoryPage::tableHeader
	 * @testdox tableHeader()
	 * @uses viewallhistoryPage
	 */
	public function test_tableHeader() {
		$page = new viewallhistoryPage();
		
		$method = $this->getPrivateMethod( 'viewallhistoryPage', 'tableHeader' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertIsString($result);
	}

	/**
	 * @small
	 * @covers viewallhistoryPage::prompt
	 * @testdox prompt()
	 * @uses viewallhistoryPage
	 */
	public function test_prompt() {
		$page = new viewallhistoryPage();
		
		$method = $this->getPrivateMethod( 'viewallhistoryPage', 'prompt' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertIsString($result);
	}

	/**
	 * @medium
	 * @covers viewallhistoryPage::buildHistoryTable
	 * @testdox buildHistoryTable()
	 * @uses viewallhistoryPage
	 */
	public function test_buildHistoryTable() {
		$page = new viewallhistoryPage();
		
		$method = $this->getPrivateMethod( 'viewallhistoryPage', 'buildHistoryTable' );
		$result = $method->invokeArgs( $page,array(1) );
		$this->assertIsString($result);
	}
	
	/**
	 * @medium
	 * @testdox getHistory()
	 * @covers viewallhistoryPage::getHistory
	 * @uses viewallhistoryPage
	 * @uses getHistoryCalculations
	 * @uses get_db_connection
	 * @uses getsettings
	 */
	public function test_getHistory() {
		$page = new viewallhistoryPage();

		$method = $this->getPrivateMethod( 'viewallhistoryPage', 'getHistory' );
		$result = $method->invokeArgs( $page,array() );
		$this->assertisArray($result);
	}
}