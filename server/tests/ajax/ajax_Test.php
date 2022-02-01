<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 * @coversNothing
 */
class ajax_Test extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath'].'\ajax\search.ajax.php';
        return ob_get_clean();
    }

	/**
	 * @small
	 */
    public function test_ajax_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @group dbconnect
	 * @small
	 */
    public function test_ajax_term() {
        $args = array('term'=>"stealth");
        $this->assertisString($this->_execute($args));

        $args = array('term'=>"stealth",'querytype'=>"Trans");
        $this->assertisString($this->_execute($args));

        $args = array('term'=>"steam",'querytype'=>"DRM");
        $this->assertisString($this->_execute($args));

        $args = array('term'=>"Windows",'querytype'=>"OS");
        $this->assertisString($this->_execute($args));

        $args = array('term'=>"steam",'querytype'=>"Library");
        $this->assertisString($this->_execute($args));

        $args = array('term'=>"stealth",'querytype'=>"Series");
        $this->assertisString($this->_execute($args));

        $args = array('term'=>"game",'querytype'=>"Type");
        $this->assertisString($this->_execute($args));

        $args = array('term'=>"valve",'querytype'=>"Developer");
        $this->assertisString($this->_execute($args));

        $args = array('term'=>"valve",'querytype'=>"Publisher");
        $this->assertisString($this->_execute($args));

        $args = array('term'=>"steam",'querytype'=>"Store");
        $this->assertisString($this->_execute($args));

    }
}