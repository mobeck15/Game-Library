<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 */
class testtoplists extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath'].'\toplists.php';
        return ob_get_clean();
    }

	/**
	 * @group long
	 * @medium
	 * Time: 00:05.826, Memory: 268.00 MB
	 * (1 test, 1 assertion) 
	 */
    public function test_toplists_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

}