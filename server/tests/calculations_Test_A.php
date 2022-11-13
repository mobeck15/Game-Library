<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group htmlpage
 * @coversNothing
 */
class calculations_Test_A extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require_once $GLOBALS['rootpath']."\calculations.php";
        return ob_get_clean();
    }

	/**
	 * @large
	 * @uses PriceCalculation 
	 */
    public function test_calculations_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @large
	 * @uses PriceCalculation 
	 */
    public function test_calculations_Fave() {
        $args = array('fav'=>"default");
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @large
	 * @uses PriceCalculation 
	 */
    public function test_calculations_Custom() {
        $args = array('fav'=>"Custom",'col'=>"Title,Type",'Sortby' => "PurchaseDate",'SortDir' => SORT_DESC);
        $this->assertisString($this->_execute($args));
    }
}