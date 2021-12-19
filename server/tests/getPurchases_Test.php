<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getPurchases.inc.php";
//require_once $GLOBALS['rootpath']."\inc\utility.inc.php";

final class getPurchases_Test extends TestCase
{
	/**
	 * @covers getPurchases
	 * @uses get_db_connection
	 * @uses CalculateGameRow
	 * @uses getActivityCalculations
	 * @uses getAllCpi
	 * @uses getAllItems
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses timeduration
	 * @uses combinedate
	 * @uses getCleanStringDate
	 */
    public function test_getPurchases() {
        $this->assertisArray(getPurchases());

		$conn=get_db_connection();
        $this->assertisArray(getPurchases("",$conn));
   }
	
	/**
	 * @covers getPurchases
	 * @uses CalculateGameRow
	 * @uses getActivityCalculations
	 * @uses getAllCpi
	 * @uses getAllItems
	 * @uses getGames
	 * @uses getHistoryCalculations
	 * @uses getsettings
	 * @uses makeIndex
	 * @uses timeduration
	 * @uses combinedate
	 * @uses getCleanStringDate
	 */
    public function test_getPurchases_specific() {
        $this->assertisArray(getPurchases(1054));
   }
}