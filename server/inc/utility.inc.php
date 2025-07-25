<?php
//DONE: add control function to prevent loading multiple times.
/*
 * Checks if this file has already been loaded in a previous include statement and throws an warning if true.
 */
declare(strict_types=1);
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ",E_USER_WARNING  );
}
$GLOBALS[__FILE__]=1;


/* 
 * takes an integer and an inputunit as an input and returns a duration formatted string hh:mm:ss
 * inputunit can be "hours" (default), "minutes", or "seconds"
 */
function timeduration(float $time,$inputunit="hours"){
	$positive=true;
	
	if($time<0){
		$time=(abs($time));
		$positive=false;
	}
	switch ($inputunit){
		case "hours":
			$time=$time*60;
		case "minutes":
			$time=$time*60;
		case "seconds":
	}

	$ms=$time-floor($time);
	$printms=sprintf("%03d",floor($ms*1000));
	$s=floor($time) % 60;
	$m=floor(($time-$s) / 60) % 60;
	//$h=floor($time / 3600);
	$h=intdiv((int)floor($time) , 3600);

	$output=$h.":".substr("0".$m,-2).":".substr("0".$s,-2);
	if($time<1 and $time<>0){
		$output .= " ".$printms;
	}
	if($positive==false){
		$output = "-".$output;
	}
	return $output;
}

/* 
 * takes input boolean variables and returns text TRUE or FALSE.
 */
function boolText($boolValue){
	if($boolValue) {
		$return="TRUE";
	} else {
		$return="FALSE";
	}
	return $return;
}

/* 
 * Reads PHP memory usage and returns a string formatted in KB or MB.
 */
function read_memory_usage($mem_usage=false) {
	if($mem_usage===false) {
		$mem_usage = memory_get_usage(true);
	}
   
	if ($mem_usage < 1024)
		return $mem_usage." b";
	elseif ($mem_usage < 1048576)
		return round($mem_usage/1024,2)." kb";
	else
		return round($mem_usage/1048576,2)." mb";
}

/**
 * @deprecated 
 */
function getAllCpi($connection=false){
	$dataAccessObject = new dataAccess();
	$cpi = $dataAccessObject->getAllCPI();

	return $cpi;
}

/**
 * @deprecated 
 */
function get_db_connection(){
	require $GLOBALS['rootpath']."/inc/auth.inc.php";
	
	$conn = new mysqli($servername, $username, $password, $dbname);

	/* check connection */
	if (mysqli_connect_errno()) {
		trigger_error("Connect failed: %s\n", mysqli_connect_error());  // @codeCoverageIgnore
		exit(); // @codeCoverageIgnore
	}

	/* change character set to utf8 */
	if (!$conn->set_charset("utf8")) {
		trigger_error("Error loading character set utf8: %s\n", $conn->error);  // @codeCoverageIgnore
	}
	
	return $conn;
}

function makeIndex($array,$indexKey){
	if(!is_array($array) OR count($array) == 0) {
		trigger_error("Array not provided (or empty array) for MakeIndex Function");
	}
	
	$errorlist=array();
	foreach ($array as $key => $value) {
		if(isset($index[$value[$indexKey]]) && !in_array($value[$indexKey],$errorlist)){
			$errorlist[]= $value[$indexKey];
			trigger_error($indexKey . " '" . $value[$indexKey]. "' is not a unique key, some data may be lost.");
		}
		if($value[$indexKey]<>''){
			$index[$value[$indexKey]]=$key;
		}
	}	

	return $index;
}

function getAllItems($gameID="",$connection=false){
	if($connection==false){
		$conn = get_db_connection();
	} else {
		$conn = $connection;
	}
	$sql="select * from `gl_items` " ;
	if ($gameID <> "" ) {
		$sql .= " where ProductID = " . $gameID ;
		$sql .= " OR ParentProductID = " . $gameID ;
	}
	$sql .= " order by `DateAdded` ASC, `Time Added` ASC, `Sequence` ASC";
	
	$items = false;
	
	if($result = $conn->query($sql)){
		$items=array();
		while($row = $result->fetch_assoc()) {
			
			$date=strtotime($row['DateAdded'] ?? "");
			if(strtotime($row['DateAdded'] ?? "") == 0) {
				$row['DateAdded'] = "";
			} else {
				$row['DateAdded'] = date("n/j/Y",$date);
			}
			
			$time = strtotime($row['Time Added'] ?? "");
			if($time === false OR date("H:i:s",$time) == "00:00:00") {
				$row['Time Added']= "";
			} else {
				$row['Time Added']= date("H:i:s",$time) ;
			}
			
			$row['AddedDateTime']=new DateTime($row['DateAdded'] . " " . $row['Time Added']);
			$row['AddedTimeStamp']=strtotime($row['DateAdded'] . " " . $row['Time Added'])+$row['Sequence'];
			if(date("H:i:s",$row['AddedTimeStamp']) == "00:00:00") {
				$row['PrintAddedTimeStamp']= date("n/j/Y",$row['AddedTimeStamp']);
			} else {
				$row['PrintAddedTimeStamp']= date("n/j/Y H:i:s",$row['AddedTimeStamp']) ;
			}

			if($row['Sequence']==0){$row['Sequence']="";}
			if($row['Tier']==0){$row['Tier']="";}
			if($row['SizeMB']==0){$row['SizeMB']="";}
			
			
			
			$items[]=$row;
		}
		
		//DONE: change Paid calculation to use only the first bundle ****PAID****
		$firstdate = array();
		foreach($items as $row) {
			if(isset($firstdate[$row["ProductID"]])){
				if($firstdate[$row["ProductID"]] > strtotime($row['PrintAddedTimeStamp'])) {
					$firstdate[$row["ProductID"]]=strtotime($row['PrintAddedTimeStamp']);
				}
			} else {
				$firstdate[$row["ProductID"]]=strtotime($row['PrintAddedTimeStamp']);
			}
		}

		foreach($items as &$row) {
			if($firstdate[$row["ProductID"]] == strtotime($row['PrintAddedTimeStamp'])) {
				$row["FirstItem"] = True;
			} else {
				$row["FirstItem"] = false;
			}
		}
	}
	
	if($connection==false){
		$conn->close();	
	}	
	
	return $items;
}

function getKeywords($gameID="",$connection=false){
	if($connection==false){
		$conn = get_db_connection();
	} else {
		$conn = $connection;
	}
	$sql="SELECT * FROM `gl_keywords` ";
	if ($gameID <> "" ) {
		$sql .= "WHERE `ProductID` = " . $gameID;
	}
	$keywords = false;
	if($result = $conn->query($sql)) {
		$keywords=array();
		while($row2 = $result->fetch_assoc()) {
			$keywords[$row2['ProductID']][$row2['KwType']][]=$row2['Keyword'] ;
		}
	}
	if($connection==false){
		$conn->close();	
	}
	return $keywords;
}

function regroupArray($array,$indexKey){
	foreach ($array as $key => $value) {
		$index[$value[$indexKey]][]=$value;
	}
	return $index ?? array();
}

function getSortArray($SourceArray,$SortField){
	foreach ($SourceArray as $key => $row){
		$SortArray[$key]  = $row[$SortField];
	}
	
	return $SortArray;
}

function getActiveSortArray($SourceArray,$SortField){
	foreach ($SourceArray as $key => $row){
		if($row['Active']==true){
			$SortArray[$key]  = $row[$SortField];
		}
	}
	
	return $SortArray;
}

function getNextPosition($SortValue,$SortArray,$time){
	$Marker=0;
	if($SortValue<>0){
		$calculated=getPriceperhour($SortValue,$time);
		foreach ($SortArray as $value){
			if($value<$calculated){
				$Marker=$value;
				break;
			}
		}
	}
	return $Marker;
}

function getHrsNextPosition($SortValue,$SortArray,$time){
	$Marker = getNextPosition($SortValue,$SortArray,$time);
	
	return getHrsToTarget($SortValue,$time,$Marker);
}

function reIndexArray($array,$indexKey){
	$iserror=false;
	foreach ($array as $key => $value) {
		if(isset($newArray[$value[$indexKey]])){
			$iserror=true;
		}
		$newArray[$value[$indexKey]]=$value;
	}	
	if($iserror==true) { 
		trigger_error($indexKey . " is not a unique key, some data may be lost");
	}
	
	return $newArray;
}

function getGameDetail($gameID,$connection=false){
	if($connection==false){
		$conn = get_db_connection();
	} else {
		$conn = $connection;
	}
	
	require_once $GLOBALS['rootpath']."/inc/getGames.inc.php";
	$games=getGames($gameID,$conn);
	
	foreach ($games as $row) {
		if($row['Game_ID']==$gameID){
			$GameData=$row;
		} 
		$GameFamily[]=$row;
	}
	
	$GameData['GameFamily']=$GameFamily;

	require_once $GLOBALS['rootpath']."/inc/getHistoryCalculations.inc.php";
	$GameData['History']=getHistoryCalculations($gameID,$conn);

	require_once $GLOBALS['rootpath']."/inc/getActivityCalculations.inc.php";
	$GameData['Activity']=getActivityCalculations($gameID,$GameData['History'],$conn);
	if($connection==false){
		$conn->close();	
	}	

	return $GameData;
}

function combinedate($date,$time,$sequence){
	If ($sequence=="") {
		$sequence=0;
	}
	
	$newDate=strtotime($date . " " . $time)+$sequence;
	
	$newDate=getCleanStringDate($newDate);

	return $newDate;
}

function getCleanStringDate($datevalue) {
	if(date("H:i:s",$datevalue) == "00:00:00") {
		$stringdate= date("n/j/Y",$datevalue);
	} else {
		$stringdate= date("n/j/Y H:i:s",$datevalue);
	}
	return $stringdate;
}

function RatingsChartData($scale=100,$ConnOrCalculationsArray="",$fieldsArray="All"){ 
	//TODO: Break RatingsChartData into multiple functions
	$chartData=array();
	if($ConnOrCalculationsArray<>"" and gettype($ConnOrCalculationsArray)=="array"){
		$calculations=$ConnOrCalculationsArray;
		if(gettype($fieldsArray)<>"array"){
			if($fieldsArray=="All"){
				$fields[]="Metascore"; //1 - 100
				$fields[]="UserMetascore"; //1 - 100
				$fields[]="SteamRating"; //1 - 100
				$fields[]="Review"; //1 - 4
				$fields[]="Want"; //1 - 5
			} else {
				$fields[]=$fieldsArray;
			}
		} else {
			$fields=$fieldsArray;
		}
		
		foreach($calculations as $row){
			foreach($fields as $field){
				$useDataValue=$row[$field];
				switch($field){
					default:
					case "Metascore":
					case "UserMetascore":
					case "SteamRating":
						$fieldScale=100;
					break;
					case "Review":
						$fieldScale=4;
					break;
					case "Want":
						$fieldScale=5;
					break;
				}
				
				if($useDataValue=="") {$useDataValue=0;}
				
				$useDataValue=ceil(($useDataValue/$fieldScale)*$scale);
				
				if(!isset($chartData[$field][$useDataValue])){
					$chartData[$field][$useDataValue]=0;
				}
				$chartData[$field][$useDataValue]++;
			}
		} 
	}
	return $chartData;
}

function daysSinceDate($date) {
	if(!is_numeric($date)) {
		$daysSince=0;
	} else {
		if($date>0){
			$secondsinaday=(60*60*24);
			$nowdays = floor(time()/$secondsinaday);
			$pastdays = floor($date/$secondsinaday);
			$daysSince=$nowdays-$pastdays;
		} else {
			$daysSince="";
		}
	}
	return $daysSince;
}

function getTimeLeft($timetobeat,$totaltime,$status) {
	$timeleft=$timetobeat-($totaltime/60/60);
	if($timeleft<0 || $status=="Done"){
		$timeleft=0;
	}
	return $timeleft;
}

function arrayTable($DataArray){
	//TODO: Make object display components as seperate functions.
	$output="";
	$output .= "<table>";
	foreach($DataArray as $stat => $value){
		$output .= "<tr><th>$stat</th><td>";
		$output .= gettype($value);
		if(gettype($value) == "string") {
			$output .= " (". strlen($value) . ")";
		} elseif(gettype($value) == "object") {
			$output .= " (". get_class($value) . ")";
		}
		$output .= "</td><td>";
		if(is_array($value)) {
			$output .= arrayTable($value);
		} else {
			switch (gettype($value)) {
				case "boolean":
					$output .= boolText($value);
					break;
				case "object":
					switch (get_class($value)) {
						case "DateTime":
							$output .= $value->getTimestamp();
							$output .= " (" . $value->format("Y-m-d  g:i:s A") . ")"; //G 24hr no leading zeros | A AM/PM | g 12hr format no leadig zero
							break;
						case "PriceCalculation":
							$output .= "<table>";
							$output .= "<tr><th>getPrice</th><td>".$value->getPrice()."</td><td>".$value->getPrice(true)."</td></tr>";
							$output .= "<tr><th>getVarianceFromMSRP</th><td>".$value->getVarianceFromMSRP()."</td><td>".$value->getVarianceFromMSRP(true)."</td></tr>";
							$output .= "<tr><th>getVarianceFromMSRPpct</th><td>".$value->getVarianceFromMSRPpct()."</td><td>".$value->getVarianceFromMSRPpct(true)."</td></tr>";
							$output .= "<tr><th>getPricePerHourOfTimeToBeat</th><td>".$value->getPricePerHourOfTimeToBeat()."</td><td>".$value->getPricePerHourOfTimeToBeat(true)."</td></tr>";
							$output .= "<tr><th>getPricePerHourOfTimePlayed</th><td>".$value->getPricePerHourOfTimePlayed()."</td><td>".$value->getPricePerHourOfTimePlayed(true)."</td></tr>";
							$output .= "<tr><th>getPricePerHourOfTimePlayedReducedAfter1Hour</th><td>".$value->getPricePerHourOfTimePlayedReducedAfter1Hour()."</td><td>".$value->getPricePerHourOfTimePlayedReducedAfter1Hour(true)."</td></tr>";
							$output .= "<tr><th>getHoursTo01LessPerHour</th><td>".$value->getHoursTo01LessPerHour()."</td><td>".$value->getHoursTo01LessPerHour(true)."</td></tr>";
							$output .= "<tr><th>getHoursToDollarPerHour 5</th><td>".$value->getHoursToDollarPerHour(5)."</td><td>".$value->getHoursToDollarPerHour(5,true)."</td></tr>";
							$output .= "<tr><th>getHoursToDollarPerHour 3</th><td>".$value->getHoursToDollarPerHour(3)."</td><td>".$value->getHoursToDollarPerHour(3,true)."</td></tr>";
							
							$output .= "</table>";
							break;
						default:
							$output .= print_r($value,true);
							break;
					}
					break;
				default:
					$output .= nl2br(htmlspecialchars("".$value));
					break;
			}
		}
		$output .= "</td></tr>";
	}
	$output .= "</table>";
	return $output;
}


function lookupTextBox($lookupid, $inputid, $inputname, $querytype="Game", $source="./ajax/search.ajax.php") {
	$headerScript  = '<script src="https://code.jquery.com/jquery-1.12.4.js"></script>';
	$headerScript .= '<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>';
	
	$textBox="<input type='numeric' id='$inputid' name='$inputname'>";
	
	$lookupBox="(?)<input id='$lookupid'	size=30 >";
		
	$lookupScript="<script>
		  $(function() {
				$('#$lookupid').autocomplete({ 
					source: function(request, response) {
						$.getJSON(
							'$source',
							{ term:request.term, querytype:'$querytype' }, 
							response
						);
					},
					select: function (event, ui) { 
						$('#$inputid').val(ui.item.$inputname);
					} }
				);
			} );
		</script>";
		
	return array("header" => $headerScript,
				"textBox" => $textBox,
				"lookupBox" => $lookupBox . $lookupScript);
}

function findgaps($sql,$conn,$idname) {
	//TODO: Reports values that are not gaps.
	$stats=array();
	if($result = $conn->query($sql)){
		$stats['max']=0;
		$stats['count']=0;
		$stats['gaps']=array();
		$stats['gapsText']="";
		$index=0;
		if ($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$stats['count']++;
				$stats['max']=$row[$idname];
				if($row[$idname]<>$index){
					//TODO: BUG: infinite loop sometimes, unknown reasons.
					while($row[$idname]<>$index && $index<100000){
						$stats['gaps'][]=$index;
						$stats['gapsText'] .= $index . ", ";
						$index++;
					}
					//$stats['gaps'][]=$index;
					//$stats['gapsText'] .= $index . ", ";
				}
				$index++;
				$stats['lastrow']=$row;
				if($idname=="TransID" AND (0+$row['Credit Used'])<0){
					$stats['lastcard']=$row;
				}
				if($idname=="ItemID" AND $row['ProductID']==null){
					$stats['lastcard']=$row;
				}
			}
		}
	}
	return $stats;
}

//Remove once price class is functional
function getHrsToTarget($CalcValue,$time,$target){
	//Depricated? used in getHrsNextPosition
	$backtrace=debug_backtrace();
	//trigger_error(__FUNCTION__ . " is depricated, use PriceCalculation Class instead. (Called from ".$backtrace[0]["file"]." line ". $backtrace[0]["line"].")");
	
	if($target>0){
		$hourstotarget= $CalcValue/$target-$time/60/60;
	} else {
		$hourstotarget=0;
	}
	
	return $hourstotarget;
}

function getPriceperhour($price,$time){
	//Depricated? Used in getNextPosition and getHrsNextPosition
	$backtrace=debug_backtrace();
	//trigger_error(__FUNCTION__ . " is depricated, use PriceCalculation Class instead. (Called from ".$backtrace[0]["file"]." line ". $backtrace[0]["line"].")");
	
	$hours=$time/60/60;
	if($hours<1){
		$priceperhour=$price;
	} else {
		$priceperhour=$price/$hours;
	}
	//$priceperhour=sprintf("%.2f",$priceperhour);
	return $priceperhour;
}
//REMOVE END
?>
