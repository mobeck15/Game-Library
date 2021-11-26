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
		//TODO: Add more functional tests for getKeywords
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
		
		$expected = array(
			"apple" => "4",
			"orange" => "2",
			"banana" => "3",
			"pear" => "1",
		);
		
		//Act
		$index=getSortArray($array,"id");
		
		//Assert
		$this->assertIsArray($index);
		$this->assertEquals($expected, $index);
	}

	/**
	 * @covers utility.inc::getActiveSortArray
	 */
	public function test_getActiveSortArray() {
		//Arrange
		$array = array(
			"apple" => array(
				"id" => "4",
				"name" => "apple",
				"Active" => true,
			),
			"orange" => array(
				"id" => "2",
				"name" => "orange",
				"Active" => true,
			),
			"banana" => array(
				"id" => "3",
				"name" => "banana",
				"Active" => false,
			),
			"pear" => array(
				"id" => "1",
				"name" => "pear",
				"Active" => true,
			),
		);
		
		$expected = array(
			"apple" => "4",
			"orange" => "2",
			"pear" => "1",
		);
		
		//Act
		$index=getActiveSortArray($array,"id");
		
		//Assert
		$this->assertIsArray($index);
		$this->assertEquals($expected, $index);
	}

	/**
	 * @covers utility.inc::getNextPosition
	 */
	public function test_getNextPosition() {
		$sortedArray = array(20,15,10,5,3,1,0);
		
		$value = 10;	$seconds = 60*60;
		$this->assertEquals(5,getNextPosition($value,$sortedArray,$seconds));

		$value = 10;	$seconds = 60*30;
		$this->assertEquals(5,getNextPosition($value,$sortedArray,$seconds));

		$value = 10;	$seconds = 60*60*5;
		$this->assertEquals(1,getNextPosition($value,$sortedArray,$seconds));
	}

	/**
	 * @covers utility.inc::getHrsNextPosition
	 */
	public function test_getHrsNextPosition() {
		$sortedArray = array(20,15,10,5,3,1,0);
		
		$value = 10;	$seconds = 60*60;
		$this->assertEquals(1,getHrsNextPosition($value,$sortedArray,$seconds));

		$value = 10;	$seconds = 60*30;
		$this->assertEquals(1.5,getHrsNextPosition($value,$sortedArray,$seconds));
	}

	/**
	 * @covers utility.inc::reIndexArray
	 */
	public function test_reIndexArray() {
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
		$index=reIndexArray($array,"id");
		
		//Assert
		$this->assertIsArray($index);
		$this->assertEquals("pear", $index[1]["name"]);
		
		try {
			$index=reIndexArray($array,"rank");
		} catch (Exception $ex) {
			$this->assertEquals("rank is not a unique key, some data may be lost",$ex->getMessage());
		}
	}

	/**
	 * @covers utility.inc::getGameDetail
	 */
	public function test_getGameDetail() {
		$array=getGameDetail(514);
		$this->assertIsArray($array);
		$this->assertEquals("The Elder Scrolls V: Skyrim",$array['Title']);
	}

	/**
	 * @covers utility.inc::combinedate
	 */
	public function test_combinedate() {
		//"" is 1/1/1970 , " " is todays date
		$newDate= date("n/j/Y",strtotime(" "));
		
		$this->assertIsString(combinedate("1/1/1990","6:00 PM",1));
		$this->assertEquals("1/1/1990 18:00:01",combinedate("1/1/1990","6:00 PM",1));
		$this->assertEquals("1/1/1990 00:00:01",combinedate("1/1/1990","",1));
		$this->assertEquals("1/1/1990 18:00:00",combinedate("1/1/1990","6:00 PM",0));
		$this->assertEquals($newDate. " 18:00:01",combinedate("","6:00 PM",1));
	}

	/**
	 * @covers utility.inc::RatingsChartData
	 */
	public function test_RatingsChartData() {
		//TODO: Cleanup variables to make tests more intuative.
		//Arrange
		$fieldarray0 = "Invalid";
		
		$fieldarray1 = "All";

		$fieldarray2 = "Metascore";

		$fieldarray3 = array(
				"Metascore", //1 - 100
				"UserMetascore", //1 - 100
				"SteamRating", //1 - 100
				"Review", //1 - 4
				"Want", //1 - 5
		);
		
		$fieldarray4 = array(
				"Metascore", //1 - 100
				"UserMetascore", //1 - 100
				"Want", //1 - 5
		);
		
		$calculations=array(
			array(
				"Metascore" => 79, //1 - 100
				"UserMetascore" => 44, //1 - 100
				"SteamRating" => 30, //1 - 100
				"Review" => 3, //1 - 4
				"Want" => 2, //1 - 5
			),
			array(
				"Metascore" => 29, //1 - 100
				"UserMetascore" => 14, //1 - 100
				"SteamRating" => 90, //1 - 100
				"Review" => 1, //1 - 4
				"Want" => 1, //1 - 5
			),
			array(
				"Metascore" => 69, //1 - 100
				"UserMetascore" => 84, //1 - 100
				"SteamRating" => 70, //1 - 100
				"Review" => 4, //1 - 4
				"Want" => 5, //1 - 5
			),
			array(
				"Metascore" => 69, //1 - 100
				"UserMetascore" => 84, //1 - 100
				"SteamRating" => 70, //1 - 100
				"Review" => 4, //1 - 4
				"Want" => 5, //1 - 5
			),
		);
		
		$metascore=array(
			79 => 1,
			29 => 1,
			69 => 2,
		);
		//Act
		
		//Assert
		$this->assertIsArray(RatingsChartData());
		$this->assertIsArray(RatingsChartData(100,$calculations));
		$this->assertEquals(5,count(RatingsChartData(100,$calculations)));
		$this->assertEquals(5,count(RatingsChartData(100,$calculations,$fieldarray1)));
		$this->assertEquals(5,count(RatingsChartData(100,$calculations,$fieldarray3)));

		$this->assertEquals($metascore,RatingsChartData(100,$calculations,$fieldarray3)["Metascore"]);
		
		$this->assertEquals(3,count(RatingsChartData(100,$calculations,$fieldarray4)));
		$this->assertEquals(1,count(RatingsChartData(100,$calculations,$fieldarray2)));
		
		//Function is incomplete and returns an empty array if no calculation data is provided.
		$this->assertEquals(0,count(RatingsChartData()));
	}

	/**
	 * @covers utility.inc::getCleanStringDate
	 */
	public function test_getCleanStringDate() {
		//Arrange
		$dateString = "1/10/2013";
		$date = new DateTime($dateString);
		$datetimeString = "1/10/2013 11:32:00";
		$datetime = new DateTime($datetimeString);
		$datetimeString2 = "1/10/2013 14:32:00";
		$datetime2 = new DateTime($datetimeString2);
		//Act
		
		//Assert
		$this->assertEquals($dateString,getCleanStringDate($date->getTimestamp()));
		$this->assertEquals($datetimeString,getCleanStringDate($datetime->getTimestamp()));
		$this->assertEquals($datetimeString2,getCleanStringDate($datetime2->getTimestamp()));
	}

	/**
	 * @covers utility.inc::daysSinceDate
	 */
	public function test_daysSinceDate() {
		//TODO: Add mock for Time() function in daysSinceDate()
		//Arrange
		$date = new DateTime();
		$days55 = new DateInterval('P55D');
		$date = $date->sub($days55);
		
		//Act
		
		//Assert
		$this->assertEquals(55,daysSinceDate($date->getTimestamp()));
	}

	/**
	 * @covers utility.inc::getTimeLeft
	 */
	public function test_getTimeLeft() {
		$this->assertEquals(15,getTimeLeft(55,40*60*60,"Active"));
		$this->assertEquals(0,getTimeLeft(55,40*60*60,"Done"));
		$this->assertEquals(0,getTimeLeft(55,60*60*60,"Active"));
	}

	/**
	 * @covers utility.inc::arrayTable
	 */
	public function test_arrayTable() {
		$array=array(
			"a string",
			667667,
			array(
				"sub array (string)",
				88888,
			),
			15.7,
		);
		
		$html="<table><tr><th>0</th><td>string (8)</td><td>a string</td></tr><tr><th>1</th><td>integer</td><td>667667</td></tr><tr><th>2</th><td>array</td><td><table><tr><th>0</th><td>string (18)</td><td>sub array (string)</td></tr><tr><th>1</th><td>integer</td><td>88888</td></tr></table></td></tr><tr><th>3</th><td>double</td><td>15.7</td></tr></table>";
		
		$this->assertIsString(arrayTable($array));
		$this->assertEquals($html,arrayTable($array));
	}

	/**
	 * @covers utility.inc::lookupTextBox
	 * /
	public function test_lookupTextBox() {
		//$this->assertIsObject(lookupTextBox());
	}

	//Below functions can be removed once priceobjects are fully working.
	
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
	 */
	public function test_getHrsToTarget() {
		$value = 10;		$seconds = 0;		$targetvalue = 5;
		$this->assertEquals(2,getHrsToTarget($value,$seconds,$targetvalue));
		
		$value = 10;		$seconds = 60*60;		$targetvalue = 5;
		$this->assertEquals(1,getHrsToTarget($value,$seconds,$targetvalue));
		
		$value = 10;		$seconds = 60*30;		$targetvalue = 5;
		$this->assertEquals(1.5,getHrsToTarget($value,$seconds,$targetvalue));
	}
}
