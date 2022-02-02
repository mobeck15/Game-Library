<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 * @coversNothing
 * @group purchases
 */
class testviewbundle extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath'].'\viewbundle.php';
        return ob_get_clean();
    }

	/**
	 * @medium
	 */
    public function test_viewbundle_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @medium
	 */
    public function test_viewbundle_bundleid() {
		//Skyrim bundle
        $args = array('id'=>11);
        $this->assertisString($this->_execute($args));

		//Realm of the mad god (parent bundle contains data)
        $args = array('id'=>21);
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @medium
	 */
    public function test_viewbundle_edit() {
        $args = array('id'=>11,'edit'=>1);
        $this->assertisString($this->_execute($args));
    }}