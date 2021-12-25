<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 */
class testgamestatuschart extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath']."\gamestatuschart.php";
        return ob_get_clean();
    }

	/**
	 * @group slow
	 * @medium
	 * Time: 00:06.662, Memory: 364.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_gamestatuschart_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

}