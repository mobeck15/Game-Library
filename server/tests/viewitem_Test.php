<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 */
class testviewitem extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath'].'\viewitem.php';
        return ob_get_clean();
    }

	/**
	 * @group long
	 * @medium
	 * Time: 00:01.378, Memory: 144.00 MB
	 * (1 test, 1 assertion) 
	 */
    public function test_viewitem_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @group long
	 * @medium
	 * Time 
	 */
    public function test_viewitem_itemid() {
        $args = array('id'=>13);
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @group long
	 * @medium
	 * Time 
	 */
    public function test_viewitem_edit() {
        $args = array('id'=>14,'edit'=>1);
        $this->assertisString($this->_execute($args));
    }

}