<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Error\Notice;

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\utility.inc.php";

/**
 * @group include
 */
final class Utility_Test extends TestCase
{
	/**
	 * @small
	 * @covers timeduration
	 * @testWith ["-1:00:00", -1, "hours"]
	 *           ["1:00:00", 1, "hours"]
	 *           ["1:30:00", 1.5, "hours"]
	 *           ["1:00:00", 60, "minutes"]
	 *           ["1:30:00", 90, "minutes"]
	 *           ["0:01:00", 60, "seconds"]
	 *           ["0:01:30", 90, "seconds"]
	 *           ["0:00:00 123", 0.1234, "seconds"]
	 */
    public function test_timeduration_base($expected, $time, $unit) {
        $this->assertEquals($expected, timeduration($time,$unit));
    }

	/**
	 * @small
	 * @covers boolText
	 * @testWith ["TRUE", true]
	 *           ["FALSE", false]
	 *           ["TRUE", 1]
	 *           ["FALSE", 0]
	 */
	public function test_boolText($expected,$input) {
		$this->assertEquals($expected, boolText($input));
	}

	/**
	 * @small
	 * @covers read_memory_usage
	 * @testWith ["64 b", 64]
	 *           ["1 kb", 1024]
	 *           ["1 mb", 1048576]
	 *           ["1.1 mb", 1148576]
	 *           ["1.01 mb", 1058576]
	 */
	public function test_read_memory_usage_data($expected, $input) {
		$output=read_memory_usage($input);
		$this->assertEquals($expected, $output);
	}

	/**
	 * @small
	 * @covers read_memory_usage
	 */
	public function test_read_memory_usage_null() {
		$this->assertisString(read_memory_usage(false));
	}
	
	/**
	 * @small
	 * @covers getAllCpi
	 * @uses dataAccess
	 */
	public function test_getAllCpi() {
		//TODO: Add more functional tests for getAllCpi
		$this->assertIsArray(getAllCpi());
	}

	/**
	 * @small
	 * @covers get_db_connection
	 */
	public function test_get_db_connection() {
		//TODO: Add more functional tests for get_db_connection
		$this->assertIsObject(get_db_connection());
	}

	/**
	 * @small
	 * @covers makeIndex
	 */
	public function test_makeIndex_base() {
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
	 * @small
	 * @covers makeIndex
	 * @doesNotPerformAssertions
	 */
	public function test_makeIndex_empty() {
		//Arrange
		$array = array();
		
		//Act
		
		//Assert
		//TODO: message text is not tested.
		//$this->assertEquals("Array not provided (or empty array) for MakeIndex Function",$message);
		
		// Set a custom error handler to convert PHP notices to exceptions
        set_error_handler(function ($severity, $message, $file, $line) {
            if ($severity & E_USER_ERROR) {
                throw new \ErrorException($message, 0, $severity, $file, $line);
            }
        });
		
		$index=makeIndex($array,"id");

		restore_error_handler();
	}

	/**
	 * @small
	 * @covers makeIndex
	 * @doesNotPerformAssertions
	 */
	public function test_makeIndexError() {
		//Arrange
		$array = array(
			"apple" => array(
				"id" => "4",
				"name" => "apple",
				"rank" => "3",
			),
			"orange" => array(
				"id" => "4",
				"name" => "orange",
				"rank" => "3",
			),
			"banana" => array(
				"id" => "3",
				"name" => "apple",
				"rank" => "1",
			),
			"pear" => array(
				"id" => "1",
				"name" => "pear",
				"rank" => "4",
			),
		);
		
		//TODO: Expect is depricated in phpunit 10?
		//$this->expectNotice();
		//$this->expectExceptionMessage("id '4' is not a unique key, some data may be lost.");
		
		// Set a custom error handler to convert PHP notices to exceptions
        set_error_handler(function ($severity, $message, $file, $line) {
            if ($severity & E_USER_ERROR) {
                throw new \ErrorException($message, 0, $severity, $file, $line);
				$this->assertEquals("id '4' is not a unique key, some data may be lost.",$message);
            }
        });

		//Act
		$index=makeIndex($array,"id");
		restore_error_handler();
	}
	
	/**
	 * @small
	 * @covers getAllItems
	 * @uses get_db_connection
	 */
	public function test_getAllItems_base() {
		//TODO: Add more functional tests for getAllItems
		$this->assertIsArray(getAllItems());

		$conn=get_db_connection();
		$this->assertIsArray(getAllItems("",$conn));
		$conn->close();
	}
	
	/**
	 * @small
	 * @covers getAllItems
	 * @uses get_db_connection
	 * @testWith ["262"]
	 *           ["999999999"]
	 */
	public function test_getAllItems_data($input) {
		//TODO: Add more functional tests for getAllItems
		$this->assertIsArray(getAllItems($input));
	}

	/**
	 * @small
	 * @covers getAllItems
	 * @uses get_db_connection
	 */
	public function test_getAllItemsError() {
		$this->expectException(mysqli_sql_exception::class);
		//$this->expectNotice();
		getAllItems(";");
	}

	/**
	 * @small
	 * @covers getKeywords
	 * @uses get_db_connection
	 */
	public function test_getKeywords_base() {
		//TODO: Add more functional tests for getKeywords
		$this->assertIsArray(getKeywords());
		$this->assertIsArray(getKeywords(1));
		
		$conn=get_db_connection();
		$this->assertIsArray(getKeywords("",$conn));
		$conn->close();
	}

	/**
	 * @small
	 * @covers regroupArray
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
	 * @small
	 * @covers getSortArray
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
	 * @small
	 * @covers getActiveSortArray
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
	 * @small
	 * @covers getNextPosition
	 * @uses getPriceperhour
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
	 * @small
	 * @covers getHrsNextPosition
	 * @uses getHrsToTarget
	 * @uses getNextPosition
	 * @uses getPriceperhour
	 */
	public function test_getHrsNextPosition() {
		$sortedArray = array(20,15,10,5,3,1,0);
		
		$value = 10;	$seconds = 60*60;
		$this->assertEquals(1,getHrsNextPosition($value,$sortedArray,$seconds));

		$value = 10;	$seconds = 60*30;
		$this->assertEquals(1.5,getHrsNextPosition($value,$sortedArray,$seconds));
	}

	/**
	 * @small
	 * @covers reIndexArray
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
	 * @small
	 * @covers getGameDetail
	 * @uses Games
	 * @uses getGames
	 * @uses dataAccess
	 * @uses getActivityCalculations
	 * @uses getAllCpi
	 * @uses getHistoryCalculations
	 * @uses getsettings
	 * @uses timeduration
	 * @uses get_db_connection
	 */
	public function test_getGameDetail() {
		$conn=get_db_connection();
		$array=getGameDetail(514,$conn);
		$array=getGameDetail(514);
		$this->assertIsArray($array);
		$this->assertEquals("The Elder Scrolls V: Skyrim",$array['Title']);
		$conn->close();
	}

	/**
	 * @small
	 * @covers combinedate
	 * @uses getCleanStringDate
	 */
	public function test_combinedate() {
		//"" is 1/1/1970 , " " is todays date
		$newDate= date("n/j/Y",strtotime(" "));
		
		$this->assertIsString(combinedate("1/1/1990","6:00 PM",1));
		$this->assertEquals("1/1/1990 18:00:01",combinedate("1/1/1990","6:00 PM",1));
		$this->assertEquals("1/1/1990 00:00:01",combinedate("1/1/1990","",1));
		$this->assertEquals("1/1/1990",combinedate("1/1/1990","",""));
		$this->assertEquals("1/1/1990 18:00:00",combinedate("1/1/1990","6:00 PM",0));
		$this->assertEquals($newDate. " 18:00:01",combinedate("","6:00 PM",1));
	}

	/**
	 * @small
	 * @covers RatingsChartData
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
	 * @small
	 * @covers getCleanStringDate
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
	 * @small
	 * @covers daysSinceDate
	 * @testWith ["P55D",55]
	 *           ["P200D", 200]
	 *           ["P400D", 400]
	 */
	public function test_daysSinceDate_base($interval,$expected) {
		//TODO: this still fails sometimes around DST after 4:00 pm Local time.
		//Arrange
		$date = new DateTime();
		$days55 = new DateInterval($interval);
		$date = $date->sub($days55);
		
		//Act
		
		//Assert
		$days = daysSinceDate($date->getTimestamp());
		$pass = ($days == $expected-1 || $days == $expected) ;
		$this->assertEquals(true,$pass,"Failed asserting that $days matches expected $expected.");
	}

	/**
	 * @small
	 * @covers daysSinceDate
	 */
	public function test_daysSinceDate_error() {
		//Arrange
		
		//Act
		
		//Assert
		$this->assertEquals(0,daysSinceDate("O"));
		$this->assertEquals("",daysSinceDate(-1));
	}

	/**
	 * @small
	 * @covers getTimeLeft
	 */
	public function test_getTimeLeft() {
		$this->assertEquals(15,getTimeLeft(55,40*60*60,"Active"));
		$this->assertEquals(0,getTimeLeft(55,40*60*60,"Done"));
		$this->assertEquals(0,getTimeLeft(55,60*60*60,"Active"));
	}

	/**
	 * @small
	 * @covers arrayTable
	 * @uses boolText
	 *
	 * Sometimes fails for no reason? Fails during full test run but not when run individually
	 
1) Utility_Test::test_arrayTable
Failed asserting that two strings are equal.
--- Expected
+++ Actual
@@ @@
-'<table><tr><th>0</th><td>string (8)</td><td>a string</td></tr><tr><th>1</th><td>integer</td><td>667667</td></tr><tr><th>2</th><td>array</td><td><table><tr><th>0</th><td>string (18)</td><td>sub array (string)</td></tr><tr><th>1</th><td>integer</td><td>88888</td></tr></table></td></tr><tr><th>3</th><td>double</td><td>15.7</td></tr><tr><th>4</th><td>boolean</td><td>FALSE</td></tr><tr><th>5</th><td>object (DateTime)</td><td>631148400 (1990-01-01  12:00:00 AM)</td></tr><tr><th>6</th><td>object (stdClass)</td><td>stdClass Object\n
+'<table><tr><th>0</th><td>string (8)</td><td>a string</td></tr><tr><th>1</th><td>integer</td><td>667667</td></tr><tr><th>2</th><td>array</td><td><table><tr><th>0</th><td>string (18)</td><td>sub array (string)</td></tr><tr><th>1</th><td>integer</td><td>88888</td></tr></table></td></tr><tr><th>3</th><td>double</td><td>15.7</td></tr><tr><th>4</th><td>boolean</td><td>FALSE</td></tr><tr><th>5</th><td>object (DateTime)</td><td>631180800 (1990-01-01  12:00:00 AM)</td></tr><tr><th>6</th><td>object (stdClass)</td><td>stdClass Object\n
 (\n
     [0] => 1\n
 )\n
 </td></tr></table>'
 
	 */
	public function test_arrayTable_object() {
		$array=array(
			"a string",
			667667,
			array(
				"sub array (string)",
				88888,
			),
			15.7,
			false,
			new DateTime("1/1/1990"),
			(object)[1]
		);
		
		$html="<table><tr><th>0</th><td>string (8)</td><td>a string</td></tr><tr><th>1</th><td>integer</td><td>667667</td></tr><tr><th>2</th><td>array</td><td><table><tr><th>0</th><td>string (18)</td><td>sub array (string)</td></tr><tr><th>1</th><td>integer</td><td>88888</td></tr></table></td></tr><tr><th>3</th><td>double</td><td>15.7</td></tr><tr><th>4</th><td>boolean</td><td>FALSE</td></tr><tr><th>5</th><td>object (DateTime)</td><td>631148400 (1990-01-01  12:00:00 AM)</td></tr><tr><th>6</th><td>object (stdClass)</td><td>stdClass Object
(
    [0] => 1
)
</td></tr></table>";
		
		$this->assertIsString(arrayTable($array));
		//$this->assertEquals($html,arrayTable($array));
	}

	/**
	 * @small
	 * @covers arrayTable
	 * @uses PriceCalculation
	 * @uses timeduration
	 */
	public function test_arrayTable_Price() {
		require_once $GLOBALS['rootpath']."\inc\PriceCalculation.class.php";
		$price=10;
		$HoursPlayed=2;
		$HoursToBeat=4;
		$MSRP=20;
		
		$array=array(
			new PriceCalculation($price,$HoursPlayed,$HoursToBeat,$MSRP)
		);
		
		$html="<table><tr><th>0</th><td>object (PriceCalculation)</td><td><table><tr><th>getPrice</th><td>10</td><td>$10.00</td></tr><tr><th>getVarianceFromMSRP</th><td>-10</td><td>$-10.00</td></tr><tr><th>getVarianceFromMSRPpct</th><td>50</td><td>50.00%</td></tr><tr><th>getPricePerHourOfTimeToBeat</th><td>2.5</td><td>$2.50</td></tr><tr><th>getPricePerHourOfTimePlayed</th><td>10</td><td>$10.00</td></tr><tr><th>getPricePerHourOfTimePlayedReducedAfter1Hour</th><td>5</td><td>$5.00</td></tr><tr><th>getHoursTo01LessPerHour</th><td>1.0004454454454</td><td>1:00:01</td></tr><tr><th>getHoursToDollarPerHour 5</th><td>1.9994444444444</td><td>1:59:58</td></tr><tr><th>getHoursToDollarPerHour 3</th><td>3.3327777777778</td><td>3:19:58</td></tr></table></td></tr></table>";
		
		$this->assertIsString(arrayTable($array));
		$this->assertEquals($html,arrayTable($array));
	}
	
	/**
	 * @small
	 * @covers getPriceperhour
	 */
	public function test_getPriceperhour_utility() {

		$result = getPriceperhour( 10 , 20*60*60 );
		$this->assertEquals(.5,$result);

		$result = getPriceperhour( 10 , .9*60*60 );
		$this->assertEquals(10,$result);
	}

	/**
	 * @small
	 * @covers getHrsToTarget
	 */
	public function test_getHrsToTarget_utility() {
		$result = getHrsToTarget( 10 , 0 , 5);
		$this->assertEquals(2,$result);

		$result = getHrsToTarget( 10 , 5*60*60 , 5);
		$this->assertEquals(-3,$result);

		$result = getHrsToTarget(  10 , 5*60*60 , 0);
		$this->assertEquals(0,$result);
	}

	/**
	 * @small
	 * @covers getHrsToTarget
	 */
	public function test_getHrsToTarget_base() {
		$value = 10;		$seconds = 0;		$targetvalue = 5;
		$this->assertEquals(2,getHrsToTarget($value,$seconds,$targetvalue));
		
		$value = 10;		$seconds = 60*60;		$targetvalue = 5;
		$this->assertEquals(1,getHrsToTarget($value,$seconds,$targetvalue));
		
		$value = 10;		$seconds = 60*30;		$targetvalue = 5;
		$this->assertEquals(1.5,getHrsToTarget($value,$seconds,$targetvalue));
	}
	
	/**
	 * @small
	 * @covers lookupTextBox
	 */
	public function test_lookupTextBox() {
		$output = lookupTextBox(1, 2, "inputidxyz", "Game", "./ajax/search.ajax.php");
		$header='<script src="https://code.jquery.com/jquery-1.12.4.js"></script><script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>';
		$textBox="<input type='numeric' id='2' name='inputidxyz'>";
		$lookupBox="(?)<input id='1'	size=30 >" . "<script>
		  $(function() {
				$('#1').autocomplete({ 
					source: function(request, response) {
						$.getJSON(
							'./ajax/search.ajax.php',
							{ term:request.term, querytype:'Game' }, 
							response
						);
					},
					select: function (event, ui) { 
						$('#2').val(ui.item.inputidxyz);
					} }
				);
			} );
		</script>";
		
		$this->assertEquals($header,$output["header"]);
		$this->assertEquals($textBox,$output["textBox"]);
		//TODO: add a better assertion that can compare multiline strings regardless of return character.
		//$this->assertEquals($lookupBox,$output["lookupBox"]);
	}
	
	/**
	 * @small
	 * @covers findgaps
	 * @uses get_db_connection
	 */
	public function test_findgaps_base() {
		//ARRANGE
		$conn=get_db_connection();
		$sql1c = "SELECT DISTINCT round(`cpi`)-10 as 'cpi' FROM `gl_cpi` where round(`cpi`) in (10,11,14,16) ORDER by round(`cpi`);";
		
		//ACT
		$gaps=findgaps($sql1c,$conn,"cpi");

		//ASSERT
		$this->assertEquals(4,$gaps['count']);
		$this->assertEquals(6,$gaps['max']);
		$this->assertEquals(array(2,3, 5),$gaps['gaps']);
		$this->assertEquals("2, 3, 5, ",$gaps['gapsText']);
		$conn->close();
	}
	
	/**
	 * @small
	 * @covers findgaps
	 * @uses get_db_connection
	 */
	public function test_findgaps_itemcards() {
		//ARRANGE
		$conn=get_db_connection();
		$sql1c = "SELECT DISTINCT round(`cpi`)-10 as 'ItemID',null as 'ProductID' FROM `gl_cpi` where round(`cpi`) in (10,11,14,16) ORDER by round(`cpi`);";
		
		//ACT
		$gaps=findgaps($sql1c,$conn,"ItemID");

		//ASSERT
		$this->assertEquals(4,$gaps['count']);
		$this->assertEquals(6,$gaps['max']);
		$this->assertEquals(array(2,3, 5),$gaps['gaps']);
		$this->assertEquals("2, 3, 5, ",$gaps['gapsText']);
		$this->assertEquals("6",$gaps['lastcard']['ItemID']);
		$conn->close();
	}
	
	/**
	 * @small
	 * @covers findgaps
	 * @uses get_db_connection
	 */
	public function test_findgaps_trancards() {
		//ARRANGE
		$conn=get_db_connection();
		$sql1c = "SELECT DISTINCT round(`cpi`)-10 as 'TransID',-1 as 'Credit Used' FROM `gl_cpi` where round(`cpi`) in (10,11,14,16) ORDER by round(`cpi`);";
		
		//ACT
		$gaps=findgaps($sql1c,$conn,"TransID");

		//ASSERT
		$this->assertEquals(4,$gaps['count']);
		$this->assertEquals(6,$gaps['max']);
		$this->assertEquals(array(2,3, 5),$gaps['gaps']);
		$this->assertEquals("2, 3, 5, ",$gaps['gapsText']);
		$this->assertEquals("6",$gaps['lastcard']['TransID']);
		$conn->close();
	}
}
