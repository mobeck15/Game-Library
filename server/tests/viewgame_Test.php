<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @group page
 */
class testviewgame extends TestCase {

    private function _execute(array $params = array()) {
        $_GET = $params;
        ob_start();
		require $GLOBALS['rootpath'].'\viewgame.php';
        return ob_get_clean();
    }

	/**
	 * @group slow
	 * @medium
	 * Time: 00:07.173, Memory: 276.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_viewgame_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @group slow
	 * @medium
	 * Time: 00:07.173, Memory: 276.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_viewgame_gameid() {
        $args = array('id'=>515);
        $this->assertisString($this->_execute($args));

    }

	/**
	 * @group slow
	 * @medium
	 * Time: 00:07.173, Memory: 276.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_viewgame_edit() {
        $args = array('id'=>17,'edit'=>1);
        $this->assertisString($this->_execute($args));
		//start/stop calculations are broken

    }
	//Other Scenarios
	//ParentGame title not equal to parent game
	/*
		SELECT a.`Game_ID`,a.`Title`,a.`ParentGame`, a.`ParentGameID`, b.`Title` FROM `gl_products` a
		join `gl_products` b on a.`ParentGameID` = b.`Game_ID`
		where b.`Title` <> a.`ParentGame`
		;
	*/
	
}