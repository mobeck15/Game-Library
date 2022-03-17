<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require_once $GLOBALS['rootpath']."/inc/functions.inc.php";
$title="Settings";
echo Get_Header($title);

	//TODO: Update settings to include new values: cntTraded, CountDupes, WeightMSRP, WeightPlay, WeightWant, Inflation, CountWntAs
	//TODO: Add Restore Defaults Button
	//TODO: Update to gl6 style (raw HTML instead of echos)
	$conn=get_db_connection();
	
	if(isset($_POST['Tax'])){
		//Set values to off for unchecked checkboxes. (Unchecked boxes are not submitted with forms.)
		if(!isset($_POST['CountFarm'])) {$_POST['CountFarm']="Off";}
		if(!isset($_POST['CountShare'])) {$_POST['CountShare']="Off";}
		if(!isset($_POST['CountIdle'])) {$_POST['CountIdle']="Off";}
		if(!isset($_POST['CountCheat'])) {$_POST['CountCheat']="Off";}
		if(!isset($_POST['AdjustforInflation'])) {$_POST['AdjustforInflation']="Off";}
		if(!isset($_POST['CountFree'])) {$_POST['CountFree']="Off";}
		if(!isset($_POST['CountNever'])) {$_POST['CountNever']="Off";}
		if(!isset($_POST['CountWantX'])) {$_POST['CountWantX']="Off";}
		
		if(!isset($_POST['Active-Active'])) {$_POST['Active-Active']="Off";}
		if(!isset($_POST['Broken-Active'])) {$_POST['Broken-Active']="Off";}
		if(!isset($_POST['Done-Active'])) {$_POST['Done-Active']="Off";}
		if(!isset($_POST['Inactive-Active'])) {$_POST['Inactive-Active']="Off";}
		if(!isset($_POST['Never-Active'])) {$_POST['Never-Active']="Off";}
		if(!isset($_POST['On_Hold-Active'])) {$_POST['On_Hold-Active']="Off";}
		if(!isset($_POST['Unplayed-Active'])) {$_POST['Unplayed-Active']="Off";}

		if(!isset($_POST['Active-Count'])) {$_POST['Active-Count']="Off";}
		if(!isset($_POST['Broken-Count'])) {$_POST['Broken-Count']="Off";}
		if(!isset($_POST['Done-Count'])) {$_POST['Done-Count']="Off";}
		if(!isset($_POST['Inactive-Count'])) {$_POST['Inactive-Count']="Off";}
		if(!isset($_POST['Never-Count'])) {$_POST['Never-Count']="Off";}
		if(!isset($_POST['On_Hold-Count'])) {$_POST['On_Hold-Count']="Off";}
		if(!isset($_POST['Unplayed-Count'])) {$_POST['Unplayed-Count']="Off";}
		
		if(($GLOBALS['Debug_Enabled'] ?? false)) {trigger_error('$_POST variable values: <pre>'. var_export($_POST, true) . "</pre>", E_USER_NOTICE);}
		
		foreach ($_POST as $setting_label => $setting_value) {
			switch ($setting_label) {
				//Numeric
				case "Tax":
				case "TrackHours":
				case "LessStat":
				case "XhourGet" :
				case "MinPlay" :
				case "MinTotal" :
				case "WantX" :
					$updateType="Numeric";
					//UPDATE `gl_settings` SET `SettingNum` = '8.580' WHERE `gl_settings`.`Setting` = 'Tax';
					$update_SQL = "UPDATE `gl_settings` SET `SettingNum` = '".$setting_value."' WHERE `gl_settings`.`Setting` = '".$setting_label."';";
					break;
				//True-False
				case "CountFarm":
				case "CountShare":
				case "CountIdle":
				case "CountCheat":
				case "AdjustforInflation":
				case "CountFree":
				case "CountNever":
				case "CountWantX":
					$updateType="Boolean";
					if($setting_value=="on") {$setting_value=1;} else {$setting_value=0;}
					$update_SQL = "UPDATE `gl_settings` SET `SettingNum` = '".$setting_value."' WHERE `gl_settings`.`Setting` = '".$setting_label."';";
					break;
				//Date 
				case "StartStats":
					$updateType="Date";
					//             UPDATE `gl_settings` SET `SettingDate` = '2005-08-22'         WHERE `gl_settings`.`Setting` = 'StartStats';
					$update_SQL = "UPDATE `gl_settings` SET `SettingDate` = '".$setting_value."' WHERE `gl_settings`.`Setting` = '".$setting_label."';";
					break;
				//Status
				case "Active-Active":
				case "Broken-Active":
				case "Done-Active":
				case "Inactive-Active":
				case "Never-Active":
				case "On_Hold-Active":
				case "Unplayed-Active":
				
				case "Active-Count":
				case "Broken-Count":
				case "Done-Count":
				case "Inactive-Count":
				case "Never-Count":
				case "On_Hold-Count":
				case "Unplayed-Count":
					$updateType="Status";
					if($setting_value=="on") {$setting_value=1;} else {$setting_value=0;}

					list($realLabel, $valueType) = explode("-", $setting_label);

					$update_SQL = "UPDATE `gl_status` SET `".$valueType."` = '".$setting_value."' WHERE `gl_status`.`Status` = '".$realLabel."';";
					break;
			}

			if(($GLOBALS['Debug_Enabled'] ?? false)) {trigger_error("Running SQL Query to update $updateType settings: ". $update_SQL, E_USER_NOTICE);}
			
			if ($conn->query($update_SQL) === TRUE) {
				if(!isset($savesucess)){$savesucess=true;}
				if(($GLOBALS['Debug_Enabled'] ?? false)) { trigger_error("Setting record updated successfully for $setting_label", E_USER_NOTICE);}
			} else {
				$savesucess=false;
				trigger_error( "Error updating record: " . $conn->error ,E_USER_ERROR );
			}
			
		}
		If ($savesucess) {echo "Settins Saved";}
		echo "<hr>";
	}
	
	$settings=getsettings($conn);
	
	$conn->close();	
	
	//TODO: Add column in settings to pull setting description from database. Also update description for all settings
	echo "<form action=\"settings.php\" method=\"post\">";
	echo "<table border=0>";
	echo "<thead>";
	echo "<tr>";
	echo "<th>Setting</th>";
	echo "<th>New Value</th>";
	echo "<th>Current Value</th>";
	echo "<th>Description</th>";
	echo "</tr>";
	echo "</thead>";
	
	echo "<tr>";
	echo "<th>Tax</th>";
	echo "<td><input type=\"number\" name=\"Tax\" min=\"0\" max=\"100\" step=\"0.01\" value=\"" . ($settings['Tax']) ."\">%</td>";  
	echo "<th>Tax Multiplier</th>";
	echo "<td>" . (100+$settings['Tax']) ."%</td>";  
	echo "</tr>";

	echo "<tr>";
	echo "<th>Track by hours at</th>";
	echo "<td><input type=\"number\" name=\"TrackHours\" min=\"0\" step=\"0\" value=\"" . ($settings['TrackHours']) ."\"></td>";
	echo "<td>" . timeduration($settings['TrackHours'],"hours")	 ."</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<th>$ less for stats</th>";
	echo "<td><input type=\"number\" name=\"LessStat\" min=\"0\" step=\"0.01\" value=\"" . ($settings['LessStat']) ."\"></td>";
	echo "<td>$" . $settings['LessStat']	 ." less for stats</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<th>X hour gets...</th>";
	echo "<td><input type=\"number\" name=\"XhourGet\" min=\"0\" step=\"1\" value=\"" . ($settings['XhourGet']) ."\"></td>";
	echo "<td>" . $settings['XhourGet']	 ." hour gets...</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<th>Daily Stats Start</th>"; 
	echo "<td><input type=\"date\" name=\"StartStats\" value=\"" . date("Y-m-d",$settings['StartStats'])  ."\"></td>";
	echo "<td>" .  date("n/j/Y",$settings['StartStats']) 	 ."</td>";
	echo "<th class='hidden'>Tracking Started</th><td class='hidden'>3/29/2014</td>";
	echo "<th class='hidden'>Oldest Record</th><td class='hidden'>8/22/2013</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<th colspan=2>Filters</th>";
	echo "</tr>";

	echo "<tr>";
	echo "<th>Count Card Farming</th>";
	//echo "<td>" . boolText($settings['CountFarm'])	 ."</td>";
	if($settings['CountFarm']){$checked="CHECKED";} else {$checked="";}
	echo "<td><label class=\"switch\">";
	  echo "<input type=\"checkbox\" name=\"CountFarm\" $checked>";
	  echo "<span class=\"slider round\"></span>";
	echo "</label></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<th>Count Share Time</th>";
	//echo "<td>" . boolText($settings['CountShare'])	 ."</td>";
	if($settings['CountShare']){$checked="CHECKED";} else {$checked="";}
	echo "<td><label class=\"switch\">";
	  echo "<input type=\"checkbox\" name=\"CountShare\" $checked>";
	  echo "<span class=\"slider round\"></span>";
	echo "</label></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<th>Count Idle Time</th>";
	//echo "<td>" . boolText($settings['CountIdle'])	 ."</td>";
	if($settings['CountIdle']){$checked="CHECKED";} else {$checked="";}
	echo "<td><label class=\"switch\">";
	  echo "<input type=\"checkbox\" name=\"CountIdle\" $checked>";
	  echo "<span class=\"slider round\"></span>";
	echo "</label></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<th>Count Cheating</th>";
	//echo "<td>" . boolText($settings['CountCheat'])	 ."</td>";
	if($settings['CountCheat']){$checked="CHECKED";} else {$checked="";}
	echo "<td><label class=\"switch\">";
	  echo "<input type=\"checkbox\" name=\"CountCheat\" $checked>";
	  echo "<span class=\"slider round\"></span>";
	echo "</label></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<th>Minimum Play Time (Seconds)</th>";
	echo "<td><input type=\"number\" name=\"MinPlay\" min=\"0\" step=\"1\" value=\"" . ($settings['MinPlay']) ."\"></td>";
	echo "<td>" . timeduration($settings['MinPlay'],"seconds") ."</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<th>Minimum Total (Seconds)</th>";
	echo "<td><input type=\"number\" name=\"MinTotal\" min=\"0\" step=\"1\" value=\"" . ($settings['MinTotal']) ."\"></td>";
	echo "<td>" . timeduration($settings['MinTotal'],"seconds") ."</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<th>Adjust for Inflation</th>";
	//echo "<td>" . boolText(false) ."</td>";
	if(false){$checked="CHECKED";} else {$checked="";}
	echo "<td><label class=\"switch\">";
	  echo "<input type=\"checkbox\" name=\"AdjustforInflation\" $checked>";
	  echo "<span class=\"slider round\"></span>";
	echo "</label></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<th>Count Free</th>";
	//echo "<td>" . boolText($settings['CountFree']) ."</td>";
	if($settings['CountFree']){$checked="CHECKED";} else {$checked="";}
	echo "<td><label class=\"switch\">";
	  echo "<input type=\"checkbox\" name=\"CountFree\" $checked>";
	  echo "<span class=\"slider round\"></span>";
	echo "</label></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<th>Count NEVER</th>";
	//echo "<td>" . boolText($settings['CountNever']) ."</td>";
	if($settings['CountNever']){$checked="CHECKED";} else {$checked="";}
	echo "<td><label class=\"switch\">";
	  echo "<input type=\"checkbox\" name=\"CountNever\" $checked>";
	  echo "<span class=\"slider round\"></span>";
	echo "</label></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<th>Want X</th>";
	echo "<td><input type=\"number\" name=\"WantX\" min=\"0\" step=\"0\" value=\"" . ($settings['WantX']) ."\"></td>";
	echo "<td>" . $settings['WantX'] ."</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<th>Count Want ".$settings['WantX']." and less</th>";
	//echo "<td>" . boolText($settings['CountWantX']) ."</td>";
	if($settings['CountWantX']){$checked="CHECKED";} else {$checked="";}
	echo "<td><label class=\"switch\">";
	  echo "<input type=\"checkbox\" name=\"CountWantX\" $checked>";
	  echo "<span class=\"slider round\"></span>";
	echo "</label></td>";
	echo "</tr>";

	echo "<tr class='hidden'>";
	echo "<th>Count Want 0 as:</th>";
	echo "<td>" . "" ."</td>";
	echo "</tr>";

	echo "<tr class='hidden'>";
	echo "<th>Unless Played</th>";
	echo "<td>" . "" ."</td>";
	echo "</tr>";

	echo "<tr class='hidden'>";
	echo "<th>Unless Approved</th>";
	echo "<td>" . "" ."</td>";
	echo "</tr>";
	

	echo "<tr><th>Status</th><td><table>";
	echo "<tr>";
	echo "<th>Status</th>";
	echo "<th>Active</th>";
	echo "<th>Count</th>";
	echo "</tr>";
	foreach ($settings['status'] as $key2 => $row2) {
		echo "<tr>";
		echo "<td>".$key2 ."</td>";
		//echo "<td>".boolText($row2['Active']) ."</td>";
		if($row2['Active']){$checked="CHECKED";} else {$checked="";}
		echo "<td><label class=\"switch\">";
		  echo "<input type=\"checkbox\" name=\"".$key2."-Active\" $checked>";
		  echo "<span class=\"slider round\"></span>";
		echo "</label></td>";
		//echo "<td>".boolText($row2['Count']) ."</td>";
		if($row2['Count']){$checked="CHECKED";} else {$checked="";}
		echo "<td><label class=\"switch\">";
		  echo "<input type=\"checkbox\" name=\"".$key2."-Count\" $checked>";
		  echo "<span class=\"slider round\"></span>";
		echo "</label></td>";
		echo "</tr>";
	}
	echo "</table></td></tr>";
	echo "<tr><th><input type=\"submit\" value=\"Save\"></th><td></tr>";
	echo "</table>";
	echo "</form>";
	
	echo Get_Footer(); ?>