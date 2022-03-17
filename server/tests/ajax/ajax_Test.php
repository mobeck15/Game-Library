<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";

/**
 * @testdox Calling search.ajax.php with an Ajax request
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
	 * @testdox with no parameters
	 * @small
	 */
    public function test_ajax_Load() {
        $args = array();
        $this->assertisString($this->_execute($args));
    }

	/**
	 * @testWith ["stealth","Game"]
	 *           ["stealth","Trans"]
	 *           ["steam","DRM"]
	 *           ["Windows","OS"]
	 *           ["steam","Library"]
	 *           ["stealth","Series"]
	 *           ["game","Type"]
	 *           ["valve","Developer"]
	 *           ["valve","Publisher"]
	 *           ["steam","Store"]
	 * @testdox serching for $type containing "$term"
	 * @group dbconnect
	 * @small
	 */
    public function test_ajax_term($term,$type) {
        $args = array('term'=>$term,'querytype'=>$type);
        $this->assertisString($this->_execute($args));
    }
}