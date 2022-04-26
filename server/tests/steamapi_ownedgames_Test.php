<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group htmlpage
 * @coversNothing
 */
class teststeamapi_ownedgames extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath'].'\steamapi_ownedgames.php';
        return ob_get_clean();
    }

	/**
	 * @large
	 * @group steamapi
	 */
    public function test_steamapi_ownedgames_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

}