<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group htmlpage
 * @coversNothing
 */
class testsettings extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath'].'\settings.php';
        return ob_get_clean();
    }

	/**
	 * @small
	 */ 
    public function test_settings_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

}