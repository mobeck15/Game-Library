<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 */
class testexport extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath'].'\export.php';
        return ob_get_clean();
    }

	/**
	 * @group fast
	 * @small
	 * Time: 00:00.017, Memory: 26.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_export_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @group untimed
	 * @small
	 * Time
	 * /
    public function test_export_export() {
        $args = array('export'=>"Export");
        $this->assertisString($this->_execute($args));
    }
	
	/**
	 * @group untimed
	 * @small
	 * Time
	 * /
    public function test_export_function() {
		require_once $GLOBALS['rootpath']."/inc/export.inc.php";
		require $GLOBALS['rootpath'].'\inc\auth.inc.php';
		//$tables = array('gl_history','gl_items','gl_keywords','gl_products','gl_settings','gl_status','gl_transactions','gl_cpi');
		$tables = array('gl_cpi');
		
        $output=Export_Database($servername,$username,$password,$dbname,$tables);
		$this->assertisString();
    }
	/* */

}