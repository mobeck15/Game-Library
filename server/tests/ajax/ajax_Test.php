<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 */
class testajax extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath'].'\ajax\search.ajax.php';
        return ob_get_clean();
    }

	/**
	 * @group fast
	 * @small
	 * Time: 00:00.027, Memory: 26.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_ajax_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @group untimed
	 * @small
	 * Time
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