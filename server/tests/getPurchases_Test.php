<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getPurchases.inc.php";
//require_once $GLOBALS['rootpath']."\inc\utility.inc.php";

//Time: 00:08.725, Memory: 156.00 MB
//(2 tests, 3 assertions)
/**
 * @group include
 */
final class getPurchases_Test extends TestCase
{
	/**
	 * @group slow
	 * @medium
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
	 * Time: 00:03.553, Memory: 154.00 MB
	 * OK (1 test, 1 assertion)
	 */
    public function test_getPurchases_base() {
        $this->assertisArray(getPurchases());
   }
   
	/**
	 * @group slow
	 * @medium
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
	 * Time: 00:03.613, Memory: 154.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_getPurchases_conn() {
		$conn=get_db_connection();
        $this->assertisArray(getPurchases("6",$conn));
   }
   
	/**
	 * @group slow
	 * @medium
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
	 * Time: 00:03.109, Memory: 156.00 MB
	 * (1 test, 1 assertion)
	 */
    public function test_getPurchases_specific() {
        $this->assertisArray(getPurchases(1054));
   }
}