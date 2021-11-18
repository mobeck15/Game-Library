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
		$this->assertIsArray(getAllCpi());
	}

	/**
	 * @covers utility.inc::get_db_connection
	 */
	public function test_get_db_connection() {
		$this->assertIsObject(get_db_connection());
	}

	/**
	 * @covers utility.inc::makeIndex
	 */
	public function test_makeIndex() {
		//Arrange
		
		//Act
		
		//Assert
		//$this->assertIsObject(makeIndex());
	}
}
