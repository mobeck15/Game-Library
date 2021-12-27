<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 */
class testaddhistory extends TestCase {

    private function _execute(array $params = array()) {
        $_SERVER['QUERY_STRING']="";
		$_GET = $params;
        ob_start();
		require $GLOBALS['rootpath']."\addhistory.php";
        return ob_get_clean();
    }

	/**
	 * @group fast
	 * @small
	 * Time: 00:00.045, Memory: 26.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_addhistory_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @group slow
	 * @medium
	 * @group steamapi
	 * Time: 00:06.858, Memory: 306.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_addhistory_Steam() {
        $args = array("mode"=>"steam");
        $this->assertisString($this->_execute($args));
    }

}