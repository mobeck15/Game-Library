<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\getPurchases.class.php";

/**
 * @group include
 */
final class getPurchases_Test extends TestCase
{
	/**
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
	 */
    public function test_getPurchases_base() {
        $this->assertisArray(getPurchases());
   }
   
	/**
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
	 */
    public function test_getPurchases_conn() {
		$conn=get_db_connection();
        $this->assertisArray(getPurchases("6",$conn));
 		$conn->close();
  }
   
	/**
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
	 */
    public function test_getPurchases_specific() {
        $this->assertisArray(getPurchases(1054));
   }
}