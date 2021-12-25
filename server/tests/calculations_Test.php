<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 */
class testcalculations extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require_once $GLOBALS['rootpath']."\calculations.php";
        return ob_get_clean();
    }

	/**
	 * @group slow
	 * @medium
	 * @uses PriceCalculation 
	 * Time: 00:05.895, Memory: 272.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_calculations_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @group slow
	 * @medium
	 * @uses PriceCalculation 
	 * Time: 00:06.559, Memory: 294.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_calculations_Fave() {
        $args = array('fav'=>"default");
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @group slow
	 * @medium
	 * @uses PriceCalculation 
	 * Time: 00:05.746, Memory: 268.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_calculations_Custom() {
        $args = array('fav'=>"Custom",'col'=>"Title,Type",'Sortby' => "PurchaseDate",'SortDir' => SORT_DESC);
        $this->assertisString($this->_execute($args));
    }
}