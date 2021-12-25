<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 */
class testviewbundle extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath'].'\viewbundle.php';
        return ob_get_clean();
    }

	/**
	 * @group long
	 * @medium
	 * Time: 00:01.337, Memory: 144.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_viewbundle_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

}