<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group htmlpage
 * @coversNothing
 */
class testaddhistory extends TestCase {

    private function _execute(array $params = array()) {
        $_SERVER['QUERY_STRING']="";
		$_GET = $params;
        ob_start();
		require $GLOBALS['rootpath']."\addhistory.php";
        return ob_get_clean();
    }

	/**
	 * @small
	 */
    public function test_addhistory_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @large
	 * @group steamapi
	 */
    public function test_addhistory_Steam() {
        $args = array("mode"=>"steam");
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @large
	 * @group steamapi
	 */
    public function test_addhistory_Edit() {
        $args = array("HistID"=>1);
        $this->assertisString($this->_execute($args));
    }
}