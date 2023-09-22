<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getsettings.inc.php";

class settingsPage extends Page
{
	public function __construct() {
		$this->title="Settings";
	}
	
	public function buildHtmlBody(){
		$output="";
		

	//TODO: Update settings to include new values: cntTraded, CountDupes, WeightMSRP, WeightPlay, WeightWant, Inflation, CountWntAs
	//TODO: Add Restore Defaults Button
	//TODO: Update to gl6 style (raw HTML instead of echos)
	$conn=get_db_connection();
	
	if(isset($_POST['Tax'])){
		$this->getDataAccessObject()->updateAllSettings($_POST);
	}
	
	$settings=getsettings($conn);
	
	$conn->close();	
	
	//TODO: Add column in settings to pull setting description from database. Also update description for all settings
	$output .= "<form action=\"settings.php\" method=\"post\">";
	$output .= "<table border=0>";
	$output .= "<thead>";
	$output .= "<tr>";
	$output .= "<th>Setting</th>";
	$output .= "<th>New Value</th>";
	$output .= "<th>Current Value</th>";
	$output .= "<th>Description</th>";
	$output .= "</tr>";
	$output .= "</thead>";
	
	$output .= "<tr>";
	$output .= "<th>Tax</th>";
	$output .= "<td><input type=\"number\" name=\"Tax\" min=\"0\" max=\"100\" step=\"0.01\" value=\"" . ($settings['Tax']) ."\">%</td>";  
	$output .= "<th>Tax Multiplier</th>";
	$output .= "<td>" . (100+$settings['Tax']) ."%</td>";  
	$output .= "</tr>";

	$output .= "<tr>";
	$output .= "<th>Track by hours at</th>";
	$output .= "<td><input type=\"number\" name=\"TrackHours\" min=\"0\" step=\"0\" value=\"" . ($settings['TrackHours']) ."\"></td>";
	$output .= "<td>" . timeduration($settings['TrackHours'],"hours")	 ."</td>";
	$output .= "</tr>";
	
	$output .= "<tr>";
	$output .= "<th>$ less for stats</th>";
	$output .= "<td><input type=\"number\" name=\"LessStat\" min=\"0\" step=\"0.01\" value=\"" . ($settings['LessStat']) ."\"></td>";
	$output .= "<td>$" . $settings['LessStat']	 ." less for stats</td>";
	$output .= "</tr>";
	
	$output .= "<tr>";
	$output .= "<th>X hour gets...</th>";
	$output .= "<td><input type=\"number\" name=\"XhourGet\" min=\"0\" step=\"1\" value=\"" . ($settings['XhourGet']) ."\"></td>";
	$output .= "<td>" . $settings['XhourGet']	 ." hour gets...</td>";
	$output .= "</tr>";
	
	$output .= "<tr>";
	$output .= "<th>Daily Stats Start</th>"; 
	$output .= "<td><input type=\"date\" name=\"StartStats\" value=\"" . date("Y-m-d",$settings['StartStats'])  ."\"></td>";
	$output .= "<td>" .  date("n/j/Y",$settings['StartStats']) 	 ."</td>";
	$output .= "<th class='hidden'>Tracking Started</th><td class='hidden'>3/29/2014</td>";
	$output .= "<th class='hidden'>Oldest Record</th><td class='hidden'>8/22/2013</td>";
	$output .= "</tr>";
	
	$output .= "<tr>";
	$output .= "<th colspan=2>Filters</th>";
	$output .= "</tr>";

	$output .= "<tr>";
	$output .= "<th>Count Card Farming</th>";
	//$output .= "<td>" . boolText($settings['CountFarm'])	 ."</td>";
	if($settings['CountFarm']){$checked="CHECKED";} else {$checked="";}
	$output .= "<td><label class=\"switch\">";
	  $output .= "<input type=\"checkbox\" name=\"CountFarm\" $checked>";
	  $output .= "<span class=\"slider round\"></span>";
	$output .= "</label></td>";
	$output .= "</tr>";

	$output .= "<tr>";
	$output .= "<th>Count Share Time</th>";
	//$output .= "<td>" . boolText($settings['CountShare'])	 ."</td>";
	if($settings['CountShare']){$checked="CHECKED";} else {$checked="";}
	$output .= "<td><label class=\"switch\">";
	  $output .= "<input type=\"checkbox\" name=\"CountShare\" $checked>";
	  $output .= "<span class=\"slider round\"></span>";
	$output .= "</label></td>";
	$output .= "</tr>";

	$output .= "<tr>";
	$output .= "<th>Count Idle Time</th>";
	//$output .= "<td>" . boolText($settings['CountIdle'])	 ."</td>";
	if($settings['CountIdle']){$checked="CHECKED";} else {$checked="";}
	$output .= "<td><label class=\"switch\">";
	  $output .= "<input type=\"checkbox\" name=\"CountIdle\" $checked>";
	  $output .= "<span class=\"slider round\"></span>";
	$output .= "</label></td>";
	$output .= "</tr>";

	$output .= "<tr>";
	$output .= "<th>Count Cheating</th>";
	//$output .= "<td>" . boolText($settings['CountCheat'])	 ."</td>";
	if($settings['CountCheat']){$checked="CHECKED";} else {$checked="";}
	$output .= "<td><label class=\"switch\">";
	  $output .= "<input type=\"checkbox\" name=\"CountCheat\" $checked>";
	  $output .= "<span class=\"slider round\"></span>";
	$output .= "</label></td>";
	$output .= "</tr>";

	$output .= "<tr>";
	$output .= "<th>Minimum Play Time (Seconds)</th>";
	$output .= "<td><input type=\"number\" name=\"MinPlay\" min=\"0\" step=\"1\" value=\"" . ($settings['MinPlay']) ."\"></td>";
	$output .= "<td>" . timeduration($settings['MinPlay'],"seconds") ."</td>";
	$output .= "</tr>";

	$output .= "<tr>";
	$output .= "<th>Minimum Total (Seconds)</th>";
	$output .= "<td><input type=\"number\" name=\"MinTotal\" min=\"0\" step=\"1\" value=\"" . ($settings['MinTotal']) ."\"></td>";
	$output .= "<td>" . timeduration($settings['MinTotal'],"seconds") ."</td>";
	$output .= "</tr>";

	$output .= "<tr>";
	$output .= "<th>Adjust for Inflation</th>";
	//$output .= "<td>" . boolText(false) ."</td>";
	if(false){$checked="CHECKED";} else {$checked="";}
	$output .= "<td><label class=\"switch\">";
	  $output .= "<input type=\"checkbox\" name=\"AdjustforInflation\" $checked>";
	  $output .= "<span class=\"slider round\"></span>";
	$output .= "</label></td>";
	$output .= "</tr>";

	$output .= "<tr>";
	$output .= "<th>Count Free</th>";
	//$output .= "<td>" . boolText($settings['CountFree']) ."</td>";
	if($settings['CountFree']){$checked="CHECKED";} else {$checked="";}
	$output .= "<td><label class=\"switch\">";
	  $output .= "<input type=\"checkbox\" name=\"CountFree\" $checked>";
	  $output .= "<span class=\"slider round\"></span>";
	$output .= "</label></td>";
	$output .= "</tr>";

	$output .= "<tr>";
	$output .= "<th>Count NEVER</th>";
	//$output .= "<td>" . boolText($settings['CountNever']) ."</td>";
	if($settings['CountNever']){$checked="CHECKED";} else {$checked="";}
	$output .= "<td><label class=\"switch\">";
	  $output .= "<input type=\"checkbox\" name=\"CountNever\" $checked>";
	  $output .= "<span class=\"slider round\"></span>";
	$output .= "</label></td>";
	$output .= "</tr>";

	$output .= "<tr>";
	$output .= "<th>Want X</th>";
	$output .= "<td><input type=\"number\" name=\"WantX\" min=\"0\" step=\"0\" value=\"" . ($settings['WantX']) ."\"></td>";
	$output .= "<td>" . $settings['WantX'] ."</td>";
	$output .= "</tr>";

	$output .= "<tr>";
	$output .= "<th>Count Want ".$settings['WantX']." and less</th>";
	//$output .= "<td>" . boolText($settings['CountWantX']) ."</td>";
	if($settings['CountWantX']){$checked="CHECKED";} else {$checked="";}
	$output .= "<td><label class=\"switch\">";
	  $output .= "<input type=\"checkbox\" name=\"CountWantX\" $checked>";
	  $output .= "<span class=\"slider round\"></span>";
	$output .= "</label></td>";
	$output .= "</tr>";

	$output .= "<tr class='hidden'>";
	$output .= "<th>Count Want 0 as:</th>";
	$output .= "<td>" . "" ."</td>";
	$output .= "</tr>";

	$output .= "<tr class='hidden'>";
	$output .= "<th>Unless Played</th>";
	$output .= "<td>" . "" ."</td>";
	$output .= "</tr>";

	$output .= "<tr class='hidden'>";
	$output .= "<th>Unless Approved</th>";
	$output .= "<td>" . "" ."</td>";
	$output .= "</tr>";
	

	$output .= "<tr><th>Status</th><td><table>";
	$output .= "<tr>";
	$output .= "<th>Status</th>";
	$output .= "<th>Active</th>";
	$output .= "<th>Count</th>";
	$output .= "</tr>";
	foreach ($settings['status'] as $key2 => $row2) {
		$output .= "<tr>";
		$output .= "<td>".$key2 ."</td>";
		//$output .= "<td>".boolText($row2['Active']) ."</td>";
		if($row2['Active']){$checked="CHECKED";} else {$checked="";}
		$output .= "<td><label class=\"switch\">";
		  $output .= "<input type=\"checkbox\" name=\"".$key2."-Active\" $checked>";
		  $output .= "<span class=\"slider round\"></span>";
		$output .= "</label></td>";
		//$output .= "<td>".boolText($row2['Count']) ."</td>";
		if($row2['Count']){$checked="CHECKED";} else {$checked="";}
		$output .= "<td><label class=\"switch\">";
		  $output .= "<input type=\"checkbox\" name=\"".$key2."-Count\" $checked>";
		  $output .= "<span class=\"slider round\"></span>";
		$output .= "</label></td>";
		$output .= "</tr>";
	}
	$output .= "</table></td></tr>";
	$output .= "<tr><th><input type=\"submit\" value=\"Save\"></th><td></tr>";
	$output .= "</table>";
	$output .= "</form>";
	
		return $output;
	}
}	