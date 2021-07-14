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
		include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/auth.inc.php";
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
	//trigger_error("Memory Used: ".read_memory_usage(), E_USER_NOTICE);
	include "inc/auth.inc.php";
	//echo"<p>Connection:"; var_dump(debug_backtrace());
	//trigger_error("Memory Used: ".read_memory_usage(), E_USER_NOTICE);
	
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
		include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/auth.inc.php";
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
		include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/auth.inc.php";
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
		include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/auth.inc.php";
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

?>
