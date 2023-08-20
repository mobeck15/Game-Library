<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."/page/chartdata.class.php";

/**
 * @testdox chartdata_Test.php testing chartdata.class.php
 * @group pageclass
 */
class chartdata_Test extends testprivate {
	/**
	 * @large
	 * @testdox __construct & buildHtmlBody
	 * @covers chartdataPage::buildHtmlBody
	 * @covers chartdataPage::__construct
	 * @uses chartdataPage
	 * @uses Page
	 * @testWith ["month"]
	 *           ["year"]
	 */
	public function test_outputHtml($group) {
		$page = new chartdataPage();
		
		$_GET['group']=$group;
		
		$dates = array(
			1 => array(
				'Year' => "1970",
				'Month' => "1"
			)
		);
		
		$transactions = array(
			1 => array(
				'PurchaseDate' => "1970",
				'BundleID' => 1,
				'TransID' => 1,
				'Paid' => 0
			),
			2 => array(
				'PurchaseDate' => "1970",
				'BundleID' => 2,
				'TransID' => 2,
				'Paid' => 1
			)
		);
		
		$calculations = array(
			1 => array(
				'CountGame' => true,
				'Playable' => true,
				'Paid' => 1,
				'AddedDateTime' => date_create("1970"),
				'firstplay' => "",
				'DateUpdated' => ""
			)
		);
		$history = array(
			1 => array(
				'Elapsed' => 1,
				'FinalCountHours' => true,
				'Timestamp' => "1970"
			)
		);
		$settings = array(
			'CountFree'=>1
		);

		$dataStub = $this->createStub(dataAccess::class);
		$dataStub->method('getAllRows')
				 ->willReturn($dates,$transactions);
		$maxID = $this->getPrivateProperty( 'chartdataPage', 'dataAccessObject' );
		$maxID->setValue( $page , $dataStub );

		$dataStub2 = $this->createStub(dataSet::class);
		$dataStub2->method('getCalculations')
				 ->willReturn($calculations);
		$dataStub2->method('getHistory')
				 ->willReturn($history);
		$dataStub2->method('getSettings')
				 ->willReturn($settings);
		$maxID2 = $this->getPrivateProperty( 'chartdataPage', 'data' );
		$maxID2->setValue( $page , $dataStub2 );
		
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @testdox buildForm()
	 * @covers chartdataPage::buildForm
	 * @uses chartdataPage
	 * @uses Page
	 * @uses dataSet
	 * @testWith [true]
	 *           [false]
	 */
	public function test_buildForm($group) {
		$page = new chartdataPage();

		$dataStub = $this->createStub(dataSet::class);
		$dataStub->method('getSettings')
				 ->willReturn(array("CountFree"=>1));

		$property = $this->getPrivateProperty( 'chartdataPage', 'data' );
		$property->setValue( $page, $dataStub );
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'buildForm' );
		$result = $method->invokeArgs( $page,array($group) );
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @testdox buildTableHeader()
	 * @covers chartdataPage::buildTableHeader
	 * @uses chartdataPage
	 * @testWith [true]
	 *           [false]
	 */
	public function test_buildTableHeader($group) {
		$page = new chartdataPage();
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'buildTableHeader' );
		$result = $method->invokeArgs( $page,array($group) );
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @testdox buildTableFooter()
	 * @covers chartdataPage::buildTableFooter
	 * @uses chartdataPage
	 * @uses timeduration
	 * @testWith [true]
	 *           [false]
	 */
	public function test_buildTableFooter($group) {
		$page = new chartdataPage();
		
		$Total = array(
			'Spending' => 1,
			'Games' => 1,
			'lastrow' => array (
				'AvgSpent' => 1,
				'AvgGames' => 1,
				'AvgTotal' => 1
			),
			'Play' => 1,
			'Spent' => 1,
			'Earned' => 1,
			'Hours' => 1,
			'lastBalance' => 1,
			'lastLeft' => 1,
			'lastData' => 1,
			'lastDataLeft' => 1
		);
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'buildTableFooter' );
		$result = $method->invokeArgs( $page,array($Total,$group) );
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @testdox buildDetailTablePlay()
	 * @covers chartdataPage::buildDetailTablePlay
	 * @uses timeduration
	 * @uses chartdataPage
	 */
	public function test_buildDetailTablePlay() {
		$page = new chartdataPage();
		
		$detail = array(
			1 => array(
				'Time' => 1,
				'Game' => 2
			),
			2 => array(
				'Time' => 1,
				'Game' => 2
			)
		);
		$purchasedgames = array(1);
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'buildDetailTablePlay' );
		$result = $method->invokeArgs( $page,array($detail,$purchasedgames) );
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @testdox buildDetailTablePurchase()
	 * @covers chartdataPage::buildDetailTablePurchase
	 * @uses chartdataPage
	 */
	public function test_buildDetailTablePurchase() {
		$page = new chartdataPage();
		
		$detail = array(
			1 => array(
				'Game' => 2,
				'ID' => 1,
				'SteamID' => 1,
				'Played' => 1,
				'MainLibrary' => 1
			),
			2 => array(
				'Game' => 2,
				'ID' => 2,
				'SteamID' => 0,
				'Played' => 1,
				'MainLibrary' => 1
			)
		);
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'buildDetailTablePurchase' );
		$result = $method->invokeArgs( $page,array($detail) );
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @testdox getPurchasedList()
	 * @covers chartdataPage::getPurchasedList
	 * @uses chartdataPage
	 */
	public function test_getPurchasedList() {
		$page = new chartdataPage();
		
		$detail = array(
			1 => array(
				'Game' => 2
			),
			2 => array(
				'Game' => 2
			)
		);
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'getPurchasedList' );
		$result = $method->invokeArgs( $page,array($detail) );
		$this->assertisArray($result);
	}
	
	/**
	 * @small
	 * @testdox buildDetailTable()
	 * @covers chartdataPage::buildDetailTable
	 * @uses chartdataPage
	 */
	public function test_buildDetailTable() {
		$page = new chartdataPage();
		
		$detail = array(
			1 => array(
				'Time' => 1,
				'Game' => 2,
				'ID' => 1,
				'SteamID' => 1,
				'Played' => 1,
				'MainLibrary' => 1
			),
			2 => array(
				'Time' => 1,
				'Game' => 2,
				'ID' => 2,
				'SteamID' => 0,
				'Played' => 1,
				'MainLibrary' => 1
			)
		);
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'buildDetailTable' );
		$result = $method->invokeArgs( $page,array($detail,"x") );
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @testdox renderGoogleChart()
	 * @covers chartdataPage::renderGoogleChart
	 * @uses chartdataPage
	 * @testWith [true]
	 *           [false]
	 */
	public function test_renderGoogleChart($groupbyyear) {
		$page = new chartdataPage();
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'renderGoogleChart' );
		$result = $method->invokeArgs( $page,array("",$groupbyyear) );
		$this->assertisString($result);
	}
	
	/**
	 * @small
	 * @testdox buildChartData()
	 * @covers chartdataPage::buildChartData
	 * @uses chartdataPage
	 */
	public function test_buildChartData() {
		$page = new chartdataPage();
		
		$chart = array(
			1 => array(
				'Year' => 2020,
				'MonthNum' => 1,
				'Spending' => 1,
				'Games' => 1,
				'AvgSpent' => 1,
				'AvgGames' => 1,
				'NewPlay' => 1
			)
		);
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'buildChartData' );
		$result = $method->invokeArgs( $page,array($chart) );
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @testdox countTotals()
	 * @covers chartdataPage::countTotals
	 * @uses chartdataPage
	 */
	public function test_countTotals() {
		$page = new chartdataPage();
		
		$chart = array(
			1 => array(
				'Year' => 2020,
				'MonthNum' => 1,
				'Games' => 1,
				'AvgSpent' => 1,
				'AvgGames' => 1,
				'NewPlay' => 1,
				'Balance' => 2,
				'leftThisMonth' => 2,
				'DataBalance' => 2,
				'DataleftThisMonth' => 2
			)
		);
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'countTotals' );
		$result = $method->invokeArgs( $page,array($chart) );
		$this->assertisArray($result);
	}
	
	/**
	 * @small
	 * @testdox buildChartTableBody()
	 * @covers chartdataPage::buildChartTableBody
	 * @uses chartdataPage
	 * @uses timeduration
	 * @testWith [true,2,1]
	 *           [false,2,null]
	 */
	public function test_buildChartTableBody($groupbyyear,$detail,$countfree) {
		$page = new chartdataPage();
		
		$chart = array(
			1 => array(
				'Year' => 2020,
				'Month' => 1,
				'Date' => 1,
				'MonthNum' => 1,
				'Games' => 1,
				'Spending' => 1,
				'Spent' => 1,
				'Earned' => 1,
				'Hours' => 1,
				'CostHour' => 1,
				'Variance' => 1,
				'DataVariance' => 1,
				'Games' => 1,
				'AvgSpent' => 1,
				'AvgGames' => 1,
				'NewPlay' => 1,
				'Balance' => 2,
				'Avg' => 2,
				'AvgTotal' => 2,
				'TotalSpend' => 2,
				'leftThisMonth' => 2,
				'DataBalance' => 2,
				'DataleftThisMonth' => 2
			),
			2 => array(
				'Year' => 2020,
				'Month' => 1,
				'Date' => 1,
				'MonthNum' => 1,
				'Games' => 1,
				'Spending' => 1,
				'Spent' => 1,
				'Earned' => 1,
				'Hours' => 1,
				'CostHour' => 1,
				'Variance' => -1,
				'DataVariance' => -1,
				'Games' => 1,
				'AvgSpent' => 1,
				'AvgGames' => 1,
				'NewPlay' => 1,
				'Balance' => 0,
				'Avg' => 2,
				'AvgTotal' => 2,
				'TotalSpend' => 2,
				'leftThisMonth' => 0,
				'DataBalance' => 0,
				'DataleftThisMonth' => 0
			)
		);
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'buildChartTableBody' );
		$result = $method->invokeArgs( $page,array($chart,$groupbyyear,$detail,$countfree) );
		$this->assertisString($result);
	}

	/**
	 * @small
	 * @testdox updateChart()
	 * @covers chartdataPage::updateChart
	 * @uses chartdataPage
	 */
	public function test_updateChart() {
		$page = new chartdataPage();
		
		$chart = array(
			1 => array(
				'Year' => 2020,
				'MonthNum' => 1,
				'Games' => 0,
				'AvgSpent' => 1,
				'AvgGames' => 1,
				'NewPlay' => 1,
				'Balance' => 2,
				'leftThisMonth' => 2,
				'DataBalance' => 2,
				'DataleftThisMonth' => 2
			),
			2 => array(
				'Year' => 2020,
				'MonthNum' => 1,
				'Games' => 1,
				'Spending' => 1,
				'Hours' => 1,
				'AvgSpent' => 1,
				'AvgGames' => 1,
				'NewPlay' => 1,
				'Balance' => 2,
				'leftThisMonth' => 2,
				'DataBalance' => 2,
				'DataleftThisMonth' => 2
			)
		);
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'updateChart' );
		$result = $method->invokeArgs( $page,array($chart) );
		$this->assertisArray($result);
	}
	
	/**
	 * @small
	 * @testdox addChartBalance()
	 * @covers chartdataPage::addChartBalance
	 * @uses chartdataPage
	 */
	public function test_addChartBalance() {
		$page = new chartdataPage();
		
		$chart = array(
			1 => array(
				'Year' => 2020,
				'MonthNum' => 1,
				'Variance' => 1,
				'DataVariance' => 1
			)
		);
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'addChartBalance' );
		$result = $method->invokeArgs( $page,array($chart) );
		$this->assertisArray($result);
	}

	/**
	 * @small
	 * @testdox addChartVariance()
	 * @covers chartdataPage::addChartVariance
	 * @uses chartdataPage
	 */
	public function test_addChartVariance() {
		$page = new chartdataPage();
		
		$chart = array(
			1 => array(
				'NewData' => 2,
				'NewPlay' => 1,
				'Games' => 1
			)
		);
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'addChartVariance' );
		$result = $method->invokeArgs( $page,array($chart) );
		$this->assertisArray($result);
	}

	/**
	 * @small
	 * @testdox addChartHistory()
	 * @covers chartdataPage::addChartHistory
	 * @uses chartdataPage
	 */
	public function test_addChartHistory() {
		$page = new chartdataPage();
		
		$history = array(
			1 => array(
				'FinalCountHours' => true,
				'Timestamp' => "1/1/1970",
				'GameID' => 1,
				'Elapsed' => 1,
				'Game' => 1
			),
			2 => array(
				'FinalCountHours' => true,
				'Timestamp' => "1/1/1970",
				'GameID' => 1,
				'Elapsed' => 1,
				'Game' => 1,
				'Time' => 1
			)
		);
		$dateformat = array(1=>"Y",2=>"Y");
		
		$chart = array(
			1 => array(
				'NewData' => 2,
				'NewPlay' => 1,
				'Games' => 1
			)
		);
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'addChartHistory' );
		$result = $method->invokeArgs( $page,array($history,$chart,$dateformat) );
		$this->assertisArray($result);
	}
	
	/**
	 * @small
	 * @testdox addDetailHistory()
	 * @covers chartdataPage::addDetailHistory
	 * @uses chartdataPage
	 */
	public function test_addDetailHistory() {
		$page = new chartdataPage();
		
		$history = array(
			1 => array(
				'FinalCountHours' => true,
				'Timestamp' => "1/1/1970",
				'GameID' => 1,
				'Elapsed' => 1,
				'Game' => 1
			),
			2 => array(
				'FinalCountHours' => true,
				'Timestamp' => "1/1/1970",
				'GameID' => 1,
				'Elapsed' => 1,
				'Game' => 1,
				'Time' => 1
			)
		);
		$dateformat = array(1=>"Y",2=>"Y");
		$detail = array(
			
		);
		$showDetail = 1970;
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'addDetailHistory' );
		$result = $method->invokeArgs( $page,array($history,$dateformat,$detail,$showDetail) );
		$this->assertisArray($result);
	}
	
	/**
	 * @small
	 * @testdox setChartDefaults()
	 * @covers chartdataPage::setChartDefaults
	 * @uses chartdataPage
	 */
	public function test_setChartDefaults() {
		$page = new chartdataPage();
		
		$chart = array(
			1 => array()
		);
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'setChartDefaults' );
		$result = $method->invokeArgs( $page,array($chart) );
		$this->assertisArray($result);
	}
	
	/**
	 * @small
	 * @testdox addChartCalculations()
	 * @covers chartdataPage::addChartCalculations
	 * @uses chartdataPage
	 */
	public function test_addChartCalculations() {
		$page = new chartdataPage();
		
		$calculations = array(
			1970 => array(
				'AddedDateTime' => date_create("1970"),
				'DateUpdated' => "1970",
				'CountGame' => true,
				'Playable' => true,
				'Paid' => 1,
				'Game_ID' => 1,
				'Title' => 1,
				'SteamID' => 1,
				'MainLibrary' => 1,
				'firstplay' => "1970"
			)
		);
		$dateformat = array(1=>"Y",2=>"Y");
		
		$chart = array(
			1 => array(
				'NewData' => 2,
				'NewPlay' => 1,
				'Games' => 1
			)
		);
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'addChartCalculations' );
		$result = $method->invokeArgs( $page,array($calculations,$chart,$dateformat) );
		$this->assertisArray($result);
	}

	/**
	 * @small
	 * @testdox addDetailCalculations()
	 * @covers chartdataPage::addDetailCalculations
	 * @uses chartdataPage
	 */
	public function test_addDetailCalculations() {
		$page = new chartdataPage();
		
		$calculations = array(
			1970 => array(
				'AddedDateTime' => date_create("1970"),
				'DateUpdated' => "1970",
				'CountGame' => true,
				'Playable' => true,
				'Paid' => 1,
				'Game_ID' => 1,
				'Title' => 1,
				'SteamID' => 1,
				'MainLibrary' => 1,
				'firstplay' => "1970"
			)
		);
		$dateformat = array(1=>"Y",2=>"Y");
		$showDetail = 1970;
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'addDetailCalculations' );
		$result = $method->invokeArgs( $page,array($calculations,$dateformat,$showDetail) );
		$this->assertisArray($result);
	}

	/**
	 * @small
	 * @testdox addChartTransactions()
	 * @covers chartdataPage::addChartTransactions
	 * @uses chartdataPage
	 * @uses Page
	 */
	public function test_addChartTransactions() {
		$page = new chartdataPage();
		
		$dateformat = array(1=>"Y",2=>"Y");
		$chart = array(
		);
		
		$transactions = array(
			1 => array(
				'PurchaseDate' => "1970",
				'BundleID' => 1,
				'TransID' => 1,
				'Paid' => 0
			),
			2 => array(
				'PurchaseDate' => "1970",
				'BundleID' => 2,
				'TransID' => 2,
				'Paid' => 1
			)
		);
		
		$dataStub = $this->createStub(dataAccess::class);
		$dataStub->method('getAllRows')
				 ->willReturn($transactions);
		$maxID = $this->getPrivateProperty( 'chartdataPage', 'dataAccessObject' );
		$maxID->setValue( $page , $dataStub );
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'addChartTransactions' );
		$result = $method->invokeArgs( $page,array($chart,$dateformat) );
		$this->assertisArray($result);
	}
	
	/**
	 * @small
	 * @testdox addChartItems()
	 * @covers chartdataPage::addChartItems
	 * @uses chartdataPage
	 * @uses Page
	 * @testWith [true]
	 *           [false]
	 */
	public function test_addChartItems($groupbyyear) {
		$page = new chartdataPage();
		
		$dateformat = array(1=>"Y",2=>"Y");
		$chart = array();
		
		$dates = array(
			1 => array(
				'Year' => "1970",
				'Month' => "1"
			)
		);
		
		$dataStub = $this->createStub(dataAccess::class);
		$dataStub->method('getAllRows')
				 ->willReturn($dates);
		$maxID = $this->getPrivateProperty( 'chartdataPage', 'dataAccessObject' );
		$maxID->setValue( $page , $dataStub );
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'addChartItems' );
		$result = $method->invokeArgs( $page,array($dateformat,$groupbyyear) );
		$this->assertisArray($result);
	}
}