<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 */
class testActivity extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require_once $GLOBALS['rootpath']."\activity.php";
        return ob_get_clean();
    }

	/**
	 * @group fast
	 * @covers activity.php
	 * Time: 00:00.221, Memory: 46.00 MB
	 * (1 test, 2 assertions)
	 */
    public function test_Activity_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

}