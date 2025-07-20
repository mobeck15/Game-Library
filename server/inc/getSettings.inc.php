<?php
/*
 * Checks if this file has already been loaded in a previous include statement and throws an warning if true.
 */
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ");
}
$GLOBALS[__FILE__]=1;


//TODO: Update to make a settings class and use dataAccess.


/*
 * Gets the settings from the database and returns a structured array.
 */
function getsettings($connection=false){
	if (isset($GLOBALS["SETTINGS"]))
	{
		//echo "SETTINGS DEFINED ";
		return $GLOBALS["SETTINGS"];
	} else {
	
		//TODO: Update settings to include new values: cntTraded, CountDupes, WeightMSRP, WeightPlay, WeightWant, Inflation, CountWntAs
		
		//If connection was not provided, include the authorization variables and establish a connection.
		if($connection==false){
			$conn = get_db_connection();
		} else {
			$conn = $connection;
		}
		
		//Query the database for all settings.
		$sql="select * from `gl_settings`";
		if($result = $conn->query($sql)){
			/*
			 * Setting rows have all three data types but should only contain a non-null value in one of them.
			 * Numeric (including Boolean 1/0) = SettingNum
			 * Date/Time = SettingDate
			 * Strings = SettingText
			 */
			while($row = $result->fetch_assoc()) {
				if ($row['SettingNum'] <> "") {
					$settings[$row['Setting']]=0+$row['SettingNum'];
				}
				if ($row['SettingDate'] <> "") {
					$settings[$row['Setting']]=strtotime($row['SettingDate']);
				}
				if ($row['SettingText'] <> "") {
					$settings[$row['Setting']]=$row['SettingText'];
				}
				
			}
		} else {
			$settings = false; 
			trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql); 
		}

		//Query the database for the status settings.
		$sql="select * from `gl_status`";
		if($result = $conn->query($sql)){
			while($row = $result->fetch_assoc()) {
				$settings['status'][$row['Status']]['Active']=$row['Active'];
				$settings['status'][$row['Status']]['Count']=$row['Count'];
			}
		} else {
			$settings = false; 
			trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql); 
		}
		
		//If connection was not provided, close the connection created for this function.
		if($connection==false){
			$conn->close();	
		}	
		
		//Allow URL Parameter override for the CountFree setting.
		if(isset($_GET['CountFree']) && $_GET['CountFree']==0){
			$settings['CountFree']=false;
		}

		$GLOBALS["SETTINGS"] = $settings;
		
		return $settings;
	}
}

if (basename($_SERVER["SCRIPT_NAME"], '.php') == "getsettings.inc") {
	$GLOBALS['rootpath']="..";
	require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
	require_once $GLOBALS['rootpath']."/inc/functions.inc.php";
	
	$title="Settings Inc Test";
	echo Get_Header($title);
	
	$lookupgame=lookupTextBox("History", "HistoryID", "id", "History", $GLOBALS['rootpath']."/ajax/search.ajax.php");
	echo $lookupgame["header"];
	$Settings=getsettings();
	echo arrayTable($Settings);
	echo Get_Footer();
}
?>
