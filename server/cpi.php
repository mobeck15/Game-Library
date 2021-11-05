<?php
$time_start = microtime(true);
$path = $_SERVER['DOCUMENT_ROOT'];
include $path."/gl6/inc/php.ini.inc.php";

include 'inc/functions.inc.php';
$title="CPI";
echo Get_Header($title);

include "inc/auth.inc.php";
//$conn = new mysqli($servername, $username, $password, $dbname);
$conn=get_db_connection();

if(isset($_POST['CPI'])){
	foreach ($_POST as $key => &$value) {
		if($value=="") {
			$value="null";
		} else {
			$value="'".$conn->real_escape_string($value)."'";
		}
	}
	unset($value);
	
	$testquery="SELECT count(*) as count FROM `gl_cpi` where `Year`=".$_POST['Year']." and `Month`=".$_POST['Month'].";";
		
	if ($result = $conn->query($testquery)) {
		$row = $result->fetch_assoc(); 
		if($row['count']==1){
			if($GLOBALS['Debug_Enabled']) { trigger_error("Data already exists, Update Required", E_USER_NOTICE);}
			$insert_SQL = "UPDATE cpi SET CPI = ".$_POST['CPI']." WHERE `Year`=".$_POST['Year']." and `Month`=".$_POST['Month'].";";
		} else {
			if($GLOBALS['Debug_Enabled']) { trigger_error("Data does not exist, Insert Required", E_USER_NOTICE);}
			$insert_SQL  = "INSERT INTO `gl_cpi` (`Year`, `Month`, `CPI`)";
			$insert_SQL .= "VALUES (";
			$insert_SQL .= $_POST['Year'].", ";
			$insert_SQL .= $_POST['Month'].", ";
			$insert_SQL .= $_POST['CPI'].");";
		}
	} else {
		trigger_error( "Attempting query: " . $testquery . "<br>" . $conn->error ,E_USER_ERROR );
	}
	
	if($GLOBALS['Debug_Enabled']) {trigger_error("Running SQL Query: ". $insert_SQL, E_USER_NOTICE);}
		
		if ($conn->query($insert_SQL) === TRUE) {
			if($GLOBALS['Debug_Enabled']) { trigger_error("Item record inserted successfully", E_USER_NOTICE);}
		} else {
			trigger_error( "Error inserting record: " . $conn->error ,E_USER_ERROR );
		}
	echo "<hr>";
}


$cpi=getAllCpi($conn);
	
//var_dump($cpi);

echo "<table border=0>";
echo "<thead>";
echo "<tr>";
echo "<th rowspan=2>Year</th>";
echo "<th rowspan=2>Jan</th>";
echo "<th rowspan=2>Feb</th>";
echo "<th rowspan=2>Mar</th>";
echo "<th rowspan=2>Apr</th>";
echo "<th rowspan=2>May</th>";
echo "<th rowspan=2>June</th>";
echo "<th rowspan=2>July</th>";
echo "<th rowspan=2>Aug</th>";
echo "<th rowspan=2>Sep</th>";
echo "<th rowspan=2>Oct</th>";
echo "<th rowspan=2>Nov</th>";
echo "<th rowspan=2>Dec</th>";
echo "<th>Annual</th>";
echo "<th colspan=2>Percent Change</th>";
echo "</tr>";
echo "<tr>";
echo "<th style='top:77px;'>Avg</th>";
echo "<th style='top:77px;'>Dec-Dec</th>";
echo "<th style='top:77px;'>Avg-Avg</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
	
foreach ($cpi as $key => $row) {
	if($key<>"Current"){
		echo "<tr>";
		echo "<td class=\"numeric\">" . $key . "</td>";
		echo "<td class=\"numeric\">" . (isset($row[1]) ? (float) $row[1] : "") . "</td>";
		echo "<td class=\"numeric\">" . (isset($row[2]) ? (float) $row[2] : "") . "</td>";
		echo "<td class=\"numeric\">" . (isset($row[3]) ? (float) $row[3] : "") . "</td>";
		echo "<td class=\"numeric\">" . (isset($row[4]) ? (float) $row[4] : "") . "</td>";
		echo "<td class=\"numeric\">" . (isset($row[5]) ? (float) $row[5] : "") . "</td>";
		echo "<td class=\"numeric\">" . (isset($row[6]) ? (float) $row[6] : "") . "</td>";
		echo "<td class=\"numeric\">" . (isset($row[7]) ? (float) $row[7] : "") . "</td>";
		echo "<td class=\"numeric\">" . (isset($row[8]) ? (float) $row[8] : "") . "</td>";
		echo "<td class=\"numeric\">" . (isset($row[9]) ? (float) $row[9] : "") . "</td>";
		echo "<td class=\"numeric\">" . (isset($row[10]) ? (float) $row[10] : "") . "</td>";
		echo "<td class=\"numeric\">" . (isset($row[11]) ? (float) $row[11] : "") . "</td>";
		echo "<td class=\"numeric\">" . (isset($row[12]) ? (float) $row[12] : "") . "</td>";
		$cpi[$key]['Avg']=array_sum($row) / count($row);
		echo "<td class=\"numeric\">" . sprintf("%.3f",$cpi[$key]['Avg']) . "</td>";
		if(isset($cpi[$key-1])){
			if(isset($row[12])){
				$cpi[$key]['perDec']=($row[12] - $cpi[$key-1][12]) / $cpi[$key-1][12];
				echo "<td class=\"numeric\">" . sprintf("%.2f",$cpi[$key]['perDec']*100) . "%</td>";
			} else {
				echo "<td class=\"numeric\"></td>";
			}
			$cpi[$key]['perAvg']=($cpi[$key]['Avg'] - $cpi[$key-1]['Avg']) / $cpi[$key-1]['Avg'];
			echo "<td class=\"numeric\">" . sprintf("%.2f",$cpi[$key]['perAvg']*100) . "%</td>";
			
		}
		echo "</tr>";
	}
	//var_dump($key);
	//var_dump($row);
}

//Assumes all months are filled in sequentially
$useMonth=count($row)+1;
$useYear=$key;

if ($useMonth==13) {
	$useMonth=1;
	$useYear++;
}
	
echo "</tbody>";
echo "</table>";
?>

<p>
<details>
<summary>
Add new records
</summary>
<p>Source data from <a href="http://www.usinflationcalculator.com/inflation/consumer-price-index-and-annual-percent-changes-from-1913-to-2008/" target=_new>Consumer Price Index</a></p>

<form action="cpi.php" method="post">
<label>Year: <input type="number" name="Year" min="1913" step="1" value="<?php echo $useYear ?>"></label>
<label>Month: <input type="number" name="Month" min="1" max="12" step="1" value="<?php echo $useMonth ?>"></label>
<label>CPI Value: <input type="number" name="CPI" min="0" step="0.0001" value=""></label>
<input type="submit" value="Save">
</form>

</details>

<?php echo Get_Footer(); ?>