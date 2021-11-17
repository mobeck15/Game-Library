<?php declare(strict_types=1);
//command line: phpunit .\htdocs\Game-Library\server\tests\testUtility.php

use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
require "htdocs\Game-Library\server\inc\utility.inc.php";



final class testUtility extends TestCase
{
	/**
	 * @covers foo::bar
	 */
    //public function test_bar() {
        //$this->assertEquals('Hello', bar('Hello'));
        //$this->assertEquals('Hi', bar('Hi'));
    //}

	/**
	 * @covers utility.inc::timeduration
	 */
    public function test_add() {
		//timeduration($time,$inputunit="hours")
        $this->assertEquals("1:00:00", timeduration(1,"hours"));
        $this->assertEquals("1:30:00", timeduration(1.5,"hours"));
        $this->assertEquals("1:00:00", timeduration(60,"minutes"));
        $this->assertEquals("1:30:00", timeduration(90,"minutes"));
        $this->assertEquals("0:01:00", timeduration(60,"seconds"));
        $this->assertEquals("0:01:30", timeduration(90,"seconds"));
    }
}
