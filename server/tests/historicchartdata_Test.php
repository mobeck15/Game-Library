<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group htmlpage
 * @coversNothing
 */
class testHistoricChartData extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath']."\historicchartdata.php";
        return ob_get_clean();
    }

	/**
	 * @medium
	 */
    public function test_HistoricChartData_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

}