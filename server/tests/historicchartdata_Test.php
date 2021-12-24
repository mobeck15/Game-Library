<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 */
class testHistoricChartData extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require_once $GLOBALS['rootpath']."\historicchartdata.php";
        return ob_get_clean();
    }

	/**
	 * @group fast
	 * @covers HistoricChartData.php
	 * Time: 00:00.230, Memory: 36.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_HistoricChartData_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

}