<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @testdox cpi_Test.php testing cpi.php
 * @group htmlpage
 * @coversNothing
 */
class testcpi extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath']."\cpi.php";
        return ob_get_clean();
    }

	/**
	 * @testdox page load
	 * @small
	 */
    public function test_cpi_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

}