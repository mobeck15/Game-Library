<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group htmlpage
 * @coversNothing
 * @testdox activity_Test.php activity.php
 */
class Activity_Test_A extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath']."\activity.php";
        return ob_get_clean();
    }

	/**
	 * @medium
	 * @testdox activity.php page loads with no parameters
	 */
    public function test_Activity_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

}