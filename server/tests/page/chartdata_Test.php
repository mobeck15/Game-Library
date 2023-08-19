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
	 * @uses Games
	 * @uses PriceCalculation
	 * @uses Purchases
	 * @uses combinedate
	 * @uses dataAccess
	 * @uses daysSinceDate
	 * @uses getActivityCalculations
	 * @uses getAllItems
	 * @uses getCalculations
	 * @uses getCleanStringDate
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getHrsNextPosition
	 * @uses getHrsToTarget
	 * @uses getKeywords
	 * @uses getNextPosition
	 * @uses getPriceSort
	 * @uses getPriceperhour
	 * @uses getTimeLeft
	 * @uses get_db_connection
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses regroupArray
	 * @uses timeduration
	 */
	public function test_outputHtml() {
		$page = new chartdataPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}

	/**
	 * @large
	 * @testdox buildHtmlBody() grouped by Year, Detail & exclude Free
	 * @covers chartdataPage::buildHtmlBody
	 * @uses chartdataPage
	 */
	public function test_outputHtml_year() {
		$_GET['group']='year';
		$_GET['CountFree']='0';
		$_GET['detail']='2013';
		$page = new chartdataPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
	
	/**
	 * @large
	 * @testdox buildHtmlBody() 
	 * @covers chartdataPage::buildHtmlBody
	 * @uses chartdataPage
	 * /
	public function test_outputHtml_detail() {
		$_GET['countfree']='0';
		$_GET['detail']='2010-5';
		$page = new chartdataPage();
		$result = $page->buildHtmlBody();
		$this->assertisString($result);
	}
	/* */
	
	/**
	 * @small
	 * @testdox buildForm()
	 * @covers chartdataPage::buildForm
	 * @uses chartdataPage
	 * @uses Page
	 * @uses dataSet
	 * @testWith ["month"]
	 *           ["year"]
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
	 * @testWith ["month"]
	 *           ["year"]
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
	 * @testWith ["month"]
	 *           ["year"]
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
		$detail = array(
			
		);
		$showDetail = 1970;
		
		$chart = array(
			1 => array(
				'NewData' => 2,
				'NewPlay' => 1,
				'Games' => 1
			)
		);
		
		$method = $this->getPrivateMethod( 'chartdataPage', 'addChartHistory' );
		$result = $method->invokeArgs( $page,array($history,$chart,$dateformat,$detail,$showDetail) );
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
}