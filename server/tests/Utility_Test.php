<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\utility.inc.php";

final class Utility_Test extends TestCase
{
	/**
	 * @covers timeduration
	 */
    public function test_timeduration() {
        $this->assertEquals("-1:00:00", timeduration(-1,"hours"));
        $this->assertEquals("1:00:00", timeduration(1,"hours"));
        $this->assertEquals("1:30:00", timeduration(1.5,"hours"));
        $this->assertEquals("1:00:00", timeduration(60,"minutes"));
        $this->assertEquals("1:30:00", timeduration(90,"minutes"));
        $this->assertEquals("0:01:00", timeduration(60,"seconds"));
        $this->assertEquals("0:01:30", timeduration(90,"seconds"));
    }

	/**
	 * @covers boolText
	 */
	public function test_boolText() {
		$this->assertEquals('TRUE', boolText(true));
		$this->assertEquals('FALSE', boolText(false));
		$this->assertEquals('TRUE', boolText(1));
		$this->assertEquals('FALSE', boolText(0));
	}

	/**
	 * @covers read_memory_usage
	 */
	public function test_read_memory_usage() {
		$this->assertisString(read_memory_usage(false));
		$this->assertEquals('64 b', read_memory_usage(64));
		$this->assertEquals('1 kb', read_memory_usage(1024));
		$this->assertEquals('1 mb', read_memory_usage(1048576));
		$this->assertEquals('1.1 mb', read_memory_usage(1148576));
		$this->assertEquals('1.01 mb', read_memory_usage(1058576));
	}

	/**
	 * @covers getAllCpi
	 * @uses get_db_connection
	 */
	public function test_getAllCpi() {
		//TODO: Add more functional tests for getAllCpi
		
		$conn=get_db_connection();
		$this->assertIsArray(getAllCpi($conn));
		$this->assertIsArray(getAllCpi());
	}

	/**
	 * @covers get_db_connection
	 */
	public function test_get_db_connection() {
		//TODO: Add more functional tests for get_db_connection
		$this->assertIsObject(get_db_connection());
	}

	/**
	 * @covers makeIndex
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
	 * @covers makeIndex
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
		
		$this->expectNotice();
		$this->expectNoticeMessage("id '4' is not a unique key, some data may be lost.");
		
		//Act
		$index=makeIndex($array,"id");
	}
	
	/**
	 * @covers getAllItems
	 * @uses get_db_connection
	 */
	public function test_getAllItems() {
		//TODO: Add more functional tests for getAllItems

		$this->assertIsArray(getAllItems());

		$conn=get_db_connection();
		$this->assertIsArray(getAllItems("",$conn));
		
		$this->assertIsArray(getAllItems("262"));
		$this->assertIsArray(getAllItems("999999999"));
		
	}

	/**
	 * @covers getAllItems
	 * @uses get_db_connection
	 */
	public function test_getAllItemsError() {
		$this->expectNotice();
		getAllItems(";");
	}

	/**
	 * @covers getKeywords
	 * @uses get_db_connection
	 */
	public function test_getKeywords() {
		//TODO: Add more functional tests for getKeywords
		$this->assertIsArray(getKeywords());
		$this->assertIsArray(getKeywords(1));
		$conn=get_db_connection();
		$this->assertIsArray(getKeywords("",$conn));
	}

	/**
	 * @covers getKeywords
	 */
	public function test_getKeywordsError() {
		$this->expectNotice();
		getKeywords(";");
	}

	/**
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
	 * @covers getGameDetail
	 * @uses CalculateGameRow
	 * @uses getActivityCalculations
	 * @uses getAllCpi
	 * @uses getGames
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
	}

	/**
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
	 * @covers daysSinceDate
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
		$this->assertEquals(0,daysSinceDate("O"));
		$this->assertEquals("",daysSinceDate(-1));
	}

	/**
	 * @covers getTimeLeft
	 */
	public function test_getTimeLeft() {
		$this->assertEquals(15,getTimeLeft(55,40*60*60,"Active"));
		$this->assertEquals(0,getTimeLeft(55,40*60*60,"Done"));
		$this->assertEquals(0,getTimeLeft(55,60*60*60,"Active"));
	}

	/**
	 * @covers arrayTable
	 * @uses boolText
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
			false,
			new DateTime("1/1/1990"),
			(object)[1]
		);
		
		$html="<table><tr><th>0</th><td>string (8)</td><td>a string</td></tr><tr><th>1</th><td>integer</td><td>667667</td></tr><tr><th>2</th><td>array</td><td><table><tr><th>0</th><td>string (18)</td><td>sub array (string)</td></tr><tr><th>1</th><td>integer</td><td>88888</td></tr></table></td></tr><tr><th>3</th><td>double</td><td>15.7</td></tr><tr><th>4</th><td>boolean</td><td>FALSE</td></tr><tr><th>5</th><td>object (DateTime)</td><td>631148400 (1990-01-01  12:00:00 AM)</td></tr><tr><th>6</th><td>object (stdClass)</td><td>stdClass Object\n(\n    [0] => 1\n)\n</td></tr></table>";
		
		$this->assertIsString(arrayTable($array));
		$this->assertEquals($html,arrayTable($array));
	}

	/**
	 * @covers arrayTable
	 * @uses PriceCalculation
	 * @uses timeduration
	 */
	public function test_arrayTablePrice() {
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
	 * @covers getPriceperhour
	 */
	public function test_getPriceperhour_utility() {

		$result = getPriceperhour( 10 , 20*60*60 );
		$this->assertEquals(.5,$result);

		$result = getPriceperhour( 10 , .9*60*60 );
		$this->assertEquals(10,$result);
	}

	/**
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
	 * @covers getHrsToTarget
	 */
	public function test_getHrsToTarget() {
		$value = 10;		$seconds = 0;		$targetvalue = 5;
		$this->assertEquals(2,getHrsToTarget($value,$seconds,$targetvalue));
		
		$value = 10;		$seconds = 60*60;		$targetvalue = 5;
		$this->assertEquals(1,getHrsToTarget($value,$seconds,$targetvalue));
		
		$value = 10;		$seconds = 60*30;		$targetvalue = 5;
		$this->assertEquals(1.5,getHrsToTarget($value,$seconds,$targetvalue));
	}
	
	/**
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
		$this->assertEquals($lookupBox,$output["lookupBox"]);
	}
	
}

/*
-'(?)<input id='1'      size=30 ><script>\r\n
+'(?)<input id='1'      size=30 ><script>\r\n
-                 $(function() {\r\n
+                 $(function() {\r\n
-                               $('#1').autocomplete({ \r\n
+                               $('#1').autocomplete({ \r\n
-                                       source: function(request, response) {\r\n
+                                       source: function(request, response) {\r\n
-                                               $.getJSON(\r\n
+                                               $.getJSON(\r\n
-                                                       './ajax/search.ajax.php',\r\n
+                                                       './ajax/search.ajax.php',\r\n
-                                                       { term:request.term, querytype:'Game' }, \r\n
+                                                       { term:request.term, querytype:'Game' }, \r\n
-                                                       response\r\n
+                                                       response\r\n
-                                               );\r\n
+                                               );\r\n
-                                       },\r\n
+                                       },\r\n
-                                       select: function (event, ui) { \r\n
+                                       select: function (event, ui) { \r\n
-                                               $('#2').val(ui.item.inputidxyz);\r\n
+                                               $('#2').val(ui.item.inputidxyz);\r\n
-                                       } }\r\n
+                                       } }\r\n
-                               );\r\n
+                               );\r\n
-                       } );\r\n
+                       } );\r\n
-               </script>'
+               </script>'
*/