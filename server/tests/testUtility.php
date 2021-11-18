<?php 
//declare(strict_types=1);
//command line: phpunit .\htdocs\Game-Library\server\tests\testUtility.php

use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = "htdocs\Game-Library\server";
require $GLOBALS['rootpath']."\inc\utility.inc.php";



final class testUtility extends TestCase
{
	/**
	 * @covers utility.inc::timeduration
	 */
    public function test_timeduration() {
		//timeduration($time,$inputunit="hours")
        $this->assertEquals("-1:00:00", timeduration(-1,"hours"));
        $this->assertEquals("1:00:00", timeduration(1,"hours"));
        $this->assertEquals("1:30:00", timeduration(1.5,"hours"));
        $this->assertEquals("1:00:00", timeduration(60,"minutes"));
        $this->assertEquals("1:30:00", timeduration(90,"minutes"));
        $this->assertEquals("0:01:00", timeduration(60,"seconds"));
        $this->assertEquals("0:01:30", timeduration(90,"seconds"));
    }

	/**
	 * @covers utility.inc::boolText
	 */
	public function test_boolText() {
		$this->assertEquals('TRUE', boolText(true));
		$this->assertEquals('FALSE', boolText(false));
		$this->assertEquals('TRUE', boolText(1));
		$this->assertEquals('FALSE', boolText(0));
	}

	/**
	 * @covers utility.inc::read_memory_usage
	 */
	public function test_read_memory_usage() {
		$this->assertEquals('64 b', read_memory_usage(64));
		$this->assertEquals('1 kb', read_memory_usage(1024));
		$this->assertEquals('1 mb', read_memory_usage(1048576));
		$this->assertEquals('1.1 mb', read_memory_usage(1148576));
		$this->assertEquals('1.01 mb', read_memory_usage(1058576));
	}

	/**
	 * @covers utility.inc::getAllCpi
	 */
	public function test_getAllCpi() {
		//TODO: Add more functional tests for getAllCpi
		$this->assertIsArray(getAllCpi());
	}

	/**
	 * @covers utility.inc::get_db_connection
	 */
	public function test_get_db_connection() {
		//TODO: Add more functional tests for get_db_connection
		$this->assertIsObject(get_db_connection());
	}

	/**
	 * @covers utility.inc::makeIndex
	 */
	public function test_makeIndex() {
		//Arrange
		$array = array(
			"apple" => array(
				"id" => "4",
				"name" => "apple",
				"rank" => "3",
			),
			"orange" => array(
				"id" => "2",
				"name" => "orange",
				"rank" => "3",
			),
			"banana" => array(
				"id" => "3",
				"name" => "banana",
				"rank" => "1",
			),
			"pear" => array(
				"id" => "1",
				"name" => "pear",
				"rank" => "4",
			),
		);
		
		//Act
		$index=makeIndex($array,"id");
		
		//Assert
		$this->assertIsArray($index);
		$this->assertEquals("pear", $array[$index[1]]["name"]);
	}

	/**
	 * @covers utility.inc::getAllItems
	 */
	public function test_getAllItems() {
		//TODO: Add more functional tests for getAllItems
		$this->assertIsArray(getAllItems());
	}

	/**
	 * @covers utility.inc::getKeywords
	 */
	public function test_getKeywords() {
		//TODO: Add more functional tests for getAllItems
		$this->assertIsArray(getKeywords());
	}

	/**
	 * @covers utility.inc::regroupArray
	 */
	public function test_regroupArray() {
		//Arrange
		$array = array(
			"apple" => array(
				"id" => "4",
				"name" => "apple",
				"rank" => "3",
			),
			"orange" => array(
				"id" => "2",
				"name" => "orange",
				"rank" => "3",
			),
			"banana" => array(
				"id" => "3",
				"name" => "banana",
				"rank" => "1",
			),
			"pear" => array(
				"id" => "1",
				"name" => "pear",
				"rank" => "4",
			),
		);


		$expected = array(
			"3" => array(
				array(
					"id" => "4",
					"name" => "apple",
					"rank" => "3",
				),
				array(
					"id" => "2",
					"name" => "orange",
					"rank" => "3",
				),
			),
			"1" => array(
				array(
					"id" => "3",
					"name" => "banana",
					"rank" => "1",
				),
			),
			"4" => array(
				array(
					"id" => "1",
					"name" => "pear",
					"rank" => "4",
				),
			),
		);
		
		//Act
		$index=regroupArray($array,"rank");
		
		//Assert
		$this->assertIsArray($index);
		$this->assertEquals("banana", $index[1][0]["name"]);
		$this->assertEquals($expected, $index);
		
	}

	/**
	 * @covers utility.inc::getSortArray
	 */
	public function test_getSortArray() {
		//Arrange
		$array = array(
			"apple" => array(
				"id" => "4",
				"name" => "apple",
				"rank" => "3",
			),
			"orange" => array(
				"id" => "2",
				"name" => "orange",
				"rank" => "3",
			),
			"banana" => array(
				"id" => "3",
				"name" => "banana",
				"rank" => "1",
			),
			"pear" => array(
				"id" => "1",
				"name" => "pear",
				"rank" => "4",
			),
		);
		
		//Act
		$index=getSortArray($array,"id");
		
		//Assert
		$this->assertIsArray($index);
	}

	/**
	 * @covers utility.inc::getActiveSortArray
	 * /
	public function test_getActiveSortArray() {
		//$this->assertIsObject(getActiveSortArray());
	}

	/**
	 * @covers utility.inc::getHrsNextPosition
	 * /
	public function test_getHrsNextPosition() {
		//$this->assertIsObject(getHrsNextPosition());
	}

	/**
	 * @covers utility.inc::reIndexArray
	 * /
	public function test_reIndexArray() {
		//$this->assertIsObject(reIndexArray());
	}

	/**
	 * @covers utility.inc::getGameDetail
	 * /
	public function test_getGameDetail() {
		//$this->assertIsObject(getGameDetail());
	}

	/**
	 * @covers utility.inc::combinedate
	 * /
	public function test_combinedate() {
		//$this->assertIsObject(combinedate());
	}

	/**
	 * @covers utility.inc::RatingsChartData
	 * /
	public function test_RatingsChartData() {
		//$this->assertIsObject(RatingsChartData());
	}

	/**
	 * @covers utility.inc::getCleanStringDate
	 * /
	public function test_getCleanStringDate() {
		//$this->assertIsObject(getCleanStringDate());
	}

	/**
	 * @covers utility.inc::daysSinceDate
	 * /
	public function test_daysSinceDate() {
		//$this->assertIsObject(daysSinceDate());
	}

	/**
	 * @covers utility.inc::getTimeLeft
	 * /
	public function test_getTimeLeft() {
		//$this->assertIsObject(getTimeLeft());
	}

	/**
	 * @covers utility.inc::arrayTable
	 * /
	public function test_arrayTable() {
		//$this->assertIsObject(arrayTable());
	}

	/**
	 * @covers utility.inc::lookupTextBox
	 * /
	public function test_lookupTextBox() {
		//$this->assertIsObject(lookupTextBox());
	}

	/**
	 * @covers utility.inc::getVariance
	 * /
	public function test_getVariance() {
		//$this->assertIsObject(getVariance());
	}

	/**
	 * @covers utility.inc::getVariancePct
	 * /
	public function test_getVariancePct() {
		//$this->assertIsObject(getVariancePct());
	}

	/**
	 * @covers utility.inc::getPriceperhour
	 * /
	public function test_getPriceperhour() {
		//$this->assertIsObject(getPriceperhour());
	}

	/**
	 * @covers utility.inc::getLessXhour
	 * /
	public function test_getLessXhour() {
		//$this->assertIsObject(getLessXhour());
	}

	/**
	 * @covers utility.inc::getHourstoXless
	 * /
	public function test_getHourstoXless() {
		//$this->assertIsObject(getHourstoXless());
	}

	/**
	 * @covers utility.inc::getHrsToTarget
	 * /
	public function test_getHrsToTarget() {
		//$this->assertIsObject(getHrsToTarget());
	}
	/* */
}
