<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 * @coversNothing
 */
class testviewitem extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath'].'\viewitem.php';
        return ob_get_clean();
    }

	/**
	 * @medium
	 */
    public function test_viewitem_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @medium
	 */
    public function test_viewitem_itemid() {
        $args = array('id'=>13);
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @medium
	 */
    public function test_viewitem_edit() {
        $args = array('id'=>14,'edit'=>1);
        $this->assertisString($this->_execute($args));
    }

}