<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group htmlpage
 * @coversNothing
 */
class waste_Test_A extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath'].'\waste.php';
        return ob_get_clean();
    }

	/**
	 * @large
	 */
    public function test_waste_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

}