<?php
//DONE: add control function to prevent loading multiple times.
/*
 * Checks if this file has already been loaded in a previous include statement and throws an warning if true.
 */
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ",E_USER_WARNING  );
}
$GLOBALS[__FILE__]=1;


/* 
 * takes an integer and an inputunit as an input and returns a duration formatted string hh:mm:ss
 * inputunit can be "hours" (default), "minutes", or "seconds"
 */
function timeduration($time,$inputunit="hours"){
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

	//echo "Time: "; var_dump($time);
	if($time=="") {$time=0;}
	$s=$time % 60;
    $m=(($time-$s) / 60) % 60;
    $h=floor($time / 3600);
	if($positive){
		return $h.":".substr("0".$m,-2).":".substr("0".$s,-2);
	} else {
		return "-".$h.":".substr("0".$m,-2).":".substr("0".$s,-2);

	}
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
function read_memory_usage() {
	$mem_usage = memory_get_usage(true);
   
	if ($mem_usage < 1024)
		return $mem_usage." b";
	elseif ($mem_usage < 1048576)
		return round($mem_usage/1024,2)." kb";
	else
		return round($mem_usage/1048576,2)." mb";
}

function getAllCpi($connection=false){
	if($connection==false){
		require_once "inc/auth.inc.php";
		//$conn = new mysqli($servername, $username, $password, $dbname);
$conn=get_db_connection();
	} else {
		$conn = $connection;
	}
	$sql = "select
		`gl_cpi`.`year`,
		`gl_cpi`.`month`,
		`gl_cpi`.`cpi`
		from `gl_cpi`
		where `gl_cpi`.`cpi`<>0
		order by `gl_cpi`.`Year` ASC, `gl_cpi`.`Month` ASC";
	if($result = $conn->query($sql)){
		while($row = $result->fetch_assoc()) {
			//var_dump($row);
			$cpi[$row['year']][$row['month']]=$row['cpi'];
			$cpi['Current']=$row['cpi'];
		}
	} else {
		trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
	}
	if($connection==false){
		$conn->close();	
	}	
	return $cpi;
}


function get_db_connection(){
	
	require $GLOBALS['rootpath']."/inc/auth.inc.php";
	
	$conn = new mysqli($servername, $username, $password, $dbname);

	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* change character set to utf8 */
	if (!$conn->set_charset("utf8")) {
		printf("Error loading character set utf8: %s\n", $conn->error);
	} else {
		//printf("Current character set: %s\n", $conn->character_set_name());
	}
	
	return $conn;

}

/*
 *  GL5 Version - Need to re-work for GL6
 */
//TODO: Update to GL6

function makeIndex($array,$indexKey){
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
		require $GLOBALS['rootpath']."/inc/auth.inc.php";
		$conn = new mysqli($servername, $username, $password, $dbname);
	} else {
		$conn = $connection;
	}
	$sql="select * from `gl_items` " ;
	if ($gameID <> "" ) {
		$sql .= " where ProductID = " . $gameID ;
		$sql .= " OR ParentProductID = " . $gameID ;
	}
	$sql .= " order by `DateAdded` ASC, `Time Added` ASC, `Sequence` ASC";
	if($result = $conn->query($sql)){
		$cpi=getAllCpi($conn);

		while($row = $result->fetch_assoc()) {
			
			$date=strtotime($row['DateAdded']);
			if(strtotime($row['DateAdded']) == 0) {
				$row['DateAdded'] = "";
			} else {
				$row['DateAdded'] = date("n/j/Y",$date);
			}
			
			$time = strtotime($row['Time Added']);
			if(date("H:i:s",$time) == "00:00:00") {
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

		
	} else {
		$items = false;
		trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
	}
	
	if($connection==false){
		$conn->close();	
	}	
	
	return $items;
}

function getKeywords($gameID="",$connection=false){
	if($connection==false){
		require_once "inc/auth.inc.php";
		$conn = new mysqli($servername, $username, $password, $dbname);
	} else {
		$conn = $connection;
	}
	$sql="SELECT * FROM `gl_keywords` ";
	if ($gameID <> "" ) {
		$sql .= "WHERE `ProductID` = " . $game['Game_ID'];
	}
	if($result = $conn->query($sql)) {
		while($row2 = $result->fetch_assoc()) {
			$keywords[$row2['ProductID']][$row2['KwType']][]=$row2['Keyword'] ;
		}
	} else {
		$keywords=false;
		trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
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
	return $index;
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

function getHrsNextPosition($SortValue,$SortArray,$time){
	$Marker=0;
	if($SortValue<>0){
		$calculated=getPriceperhour($SortValue,$time);
		//echo "Search for: " . $calculated ." in ";
		foreach ($SortArray as $value){
			//echo "Value: " . $value ;
			//echo " " . ($value < $calculated ? "True" : "False") .  ", ";
			if($value<$calculated){
				//echo " FOUND ";
				$Marker=$value;
				//echo "Marker: ". $Marker;
				break;
			}
		}
	}
	
	$hrsToTarget=getHrsToTarget($SortValue,$time,$Marker);
	//echo "Price per hr (".$calculated.') | (Price (' . $SortValue . ") / Target (" . $Marker . ")=".timeduration($SortValue/$Marker,"hours").") - Hours " . timeduration($time,"seconds") . " = " . timeduration($hrsToTarget,"hours")."<br>";
	
	//var_dump($SortArray);
	
	return $hrsToTarget;
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
		require_once "inc/auth.inc.php";
		$conn = new mysqli($servername, $username, $password, $dbname);
	} else {
		$conn = $connection;
	}
	$games=getGames($gameID,$conn);
	
	foreach ($games as $row) {
		if($row['Game_ID']==$gameID){
			$GameData=$row;
		} 
		$GameFamily[]=$row;
	}
	
	$GameData['GameFamily']=$GameFamily;
	$GameData['History']=getHistoryCalculations($gameID,$conn);
	$GameData['Activity']=getActivityCalculations($gameID,$GameData['History'],$conn);
	if($connection==false){
		$conn->close();	
	}	

	return $GameData;
}

function combinedate($date,$time,$sequence){
	//Not owned has no sequence - causes problems
	
	//If ($date=="") {$date="5/12/2010";}
	If ($sequence=="") {$sequence=0;}
	
	//echo "\$newDate=strtotime(".var_export($date,true)." . \" \" . ".var_export($time,true).")+".var_export($sequence,true).";<br>";
	//echo "Date: "; var_dump($date); echo "<br>";
	//echo "Time: "; var_dump($time); echo "<br>";
	//echo "Sequence: "; var_dump($sequence); echo "<br>";
	
	$newDate=strtotime($date . " " . $time)+$sequence;
	
	if(date("H:i:s",$newDate) == "00:00:00") {
		$newDate= date("n/j/Y",$newDate);
	} else {
		$newDate= date("n/j/Y H:i:s",$newDate) ;
	}

	return $newDate;
}


function RatingsChartData($scale=100,$ConnOrCalculationsArray="",$fieldsArray="All"){ 
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
			//var_dump($row);
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
				
				//echo "useDataValue "; var_dump($useDataValue); echo "<br>";
				//echo "fieldScale "; var_dump($fieldScale); echo "<br>";
				//echo "scale "; var_dump($scale); echo "<br>";
				$useDataValue=ceil(($useDataValue/$fieldScale)*$scale);
				
				if(!isset($chartData[$field][$useDataValue])){
					$chartData[$field][$useDataValue]=0;
				}
				$chartData[$field][$useDataValue]++;
			} //break;
		} 
	}
	return $chartData;
}

function getCleanStringDate($datevalue) {
	if(date("H:i:s",$datevalue) == "00:00:00") {
		$stringdate= date("n/j/Y",$datevalue);
	} else {
		$stringdate= date("n/j/Y H:i:s",$datevalue);
	}
	return $stringdate;
}

function daysSinceDate($date) {
	if(!is_numeric($date)) {
		$daysSince=0;
	}
	
	if($date>0){
		$daysSince=floor((time()-$date) / (60 * 60 * 24));
	} else {
		$daysSince="";
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
					$output .= nl2br(htmlspecialchars($value));
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

//Remove once price class is functional
function getVariance($price,$msrp) {
	$variance=0;
	if($msrp<>0){
		$variance=$price-$msrp;
	}
	return $variance;
}

function getVariancePct($price,$msrp) {
	$variance=0;
	if($msrp<>0){
		$variance=(1-($price/$msrp))*100;
	}
	return $variance;
}

function getPriceperhour($price,$time){
	$hours=$time/60/60;
	if($hours<1){
		$priceperhour=$price;
	} else {
		$priceperhour=$price/$hours;
	}
	//$priceperhour=sprintf("%.2f",$priceperhour);
	return $priceperhour;
}

function getLessXhour($price,$time,$xhour=1){
	$hours=$time/60/60;
	if($hours<1){
		$priceperhour=$price;
	} else {
		$priceperhour=$price/$hours;
	}
	
	if($xhour+$hours==0) {
		$LessXhour=0;
	} else {
		//echo "LessXhour=" . "priceperhour: ". $priceperhour . " -( price: " . $price . " /(max( xhour: " .$xhour. ",hours: ".$hours. " )+ xhour: ". $xhour. " ))<br>";
		$LessXhour=$priceperhour-($price/(max($xhour,$hours)+$xhour));
	}
	
	//$LessXhour=sprintf("%.2f",$LessXhour);
	return $LessXhour;
}

function getHourstoXless($price,$time,$xless=.01){
	$priceperhour=getPriceperhour($price,$time);
	$hoursxless=getHrsToTarget($price,$time,$priceperhour-$xless);
	
	return $hoursxless;
}

function getHrsToTarget($CalcValue,$time,$target){
	if($target>0){
		$hourstotarget= $CalcValue/$target-$time/60/60;
	} else {
		$hourstotarget=0;
	}
	
	return $hourstotarget;
}

//REMOVE END
?>
