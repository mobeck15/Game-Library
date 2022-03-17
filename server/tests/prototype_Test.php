<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 * @coversNothing
 */
class testprototype extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath'].'\prototype\index.php';
        return ob_get_clean();
    }

	/**
	 * @small
	 */
    public function test_prototype_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

}