<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 */
class testviewallhistory extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath'].'\viewallhistory.php';
        return ob_get_clean();
    }

	/**
	 * @group short
	 * @small
	 * Time: 00:00.028, Memory: 26.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_viewallhistory_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @group untimed
	 * @small
	 * Time: 
	 */
    public function test_viewallhistory_history() {
        $args = array('num'=>30,'Sort'=>"Played");
        $this->assertisString($this->_execute($args));
    }

}