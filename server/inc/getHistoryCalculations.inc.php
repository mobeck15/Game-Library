<?php
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ");
}
$GLOBALS[__FILE__]=1;

function getHistoryCalculations($gameID="",$connection=false,$start=false,$end=false){
	//include_once('utility.inc.php');
	if($connection==false){
		require $GLOBALS['rootpath']."/inc/auth.inc.php";
		$conn = new mysqli($servername, $username, $password, $dbname);
	} else {
		$conn = $connection;
	}
	
	require_once $GLOBALS['rootpath']."/inc/getsettings.inc.php";
	$settings=getSettings($conn);
	
	$sql = "select `HistoryID`,`Timestamp`,`Title` as 'Game', `System`, `Data`, `Time`, `Notes`, `Achievements`, `AchievementType`, `Levels`, `LevelType`, `Status`, `Review`, `BaseGame`, `RowType`, `kwMinutes`, `kwIdle`, `kwCardFarming`, `kwCheating`, `kwBeatGame`, `kwShare`, `GameID`, `ParentGameID`, `LaunchDate`, `RowType` ";
	$sql .= " from `gl_history` ";
	$sql .= " JOIN `gl_products` on `gl_history`.`GameID` = `gl_products`.`Game_ID`";
	
	if(!($start===false || $end===false)){
		$sql .= " where `Timestamp` >= '" . date("Y-m-d",$start) ."'";
		$sql .= " AND `Timestamp` <= '" . date("Y-m-d",$end) ."'";
		
	} elseif ($gameID <> "" ) {
		$sql .= " where GameID = " . $gameID ;
		$sql .= " OR ParentGameID = " . $gameID ;
	}
	
	$sql .= " order by `Timestamp` ASC";
	
	if($result = $conn->query($sql)){
		if ($result->num_rows > 0){
			while($row = $result->fetch_assoc()) {
				$date = strtotime($row['Timestamp']);
				
				if(date("H:i:s",$date) == "00:00:00") {
					$row['Timestamp']= date("n/j/Y",$date);
				} else {
					$row['Timestamp']= date("n/j/Y h:i:s A",$date) ;
				}
				
				if($row['Achievements']<>0){
					$row['Achievements']= (int) $row['Achievements'];
				} else {
					$row['Achievements'] = "";
				}
				
				if($row['Levels']<>0){
					$row['Levels']= (int) $row['Levels'];
				} else {
					$row['Levels'] = "";
				}
				
				if($row['Review']<>0){
					$row['Review']= (int) $row['Review'];
				} else {
					$row['Review'] = "";
				}
				
				$row['KeyWords']="";
				if($row['BaseGame']==1) {
					$row['KeyWords'] .= "Base Game";
					if($row['kwMinutes']==1 OR $row['kwIdle']==1 OR $row['kwCardFarming']==1 OR $row['kwCheating']==1 OR $row['kwShare']==1 OR $row['kwBeatGame']==1) {$row['KeyWords'] .= ", ";}
					
					$row['UseGame']=$row['ParentGameID'];

				} else {
					$row['UseGame']=$row['GameID'];
					//$row['UseGame']=$row['ParentGameID'];
				}
				$row['ParentGame']=$row['ParentGameID'];
				if($row['kwMinutes']==1) {
					$row['KeyWords'] .= "Minutes";
					if($row['kwIdle']==1 OR $row['kwCardFarming']==1 OR $row['kwCheating']==1 OR $row['kwShare']==1 OR $row['kwBeatGame']==1) {$row['KeyWords'] .= ", ";}
				}
				if($row['kwIdle']==1) {
					$row['KeyWords'] .= "Idle";
					if($row['kwCardFarming']==1 OR $row['kwCheating']==1 OR $row['kwShare']==1 OR $row['kwBeatGame']==1) {$row['KeyWords'] .= ", ";}
				}
				if($row['kwCardFarming']==1) {
					$row['KeyWords'] .= "Card Farming";
					if($row['kwCheating']==1 OR $row['kwShare']==1 OR $row['kwBeatGame']==1) {$row['KeyWords'] .= ", ";}
				}
				if($row['kwCheating']==1) {
					$row['KeyWords'] .= "Cheating";
					if($row['kwShare']==1 OR $row['kwBeatGame']==1) {$row['KeyWords'] .= ", ";}
				}
				if($row['kwShare']==1) {
					$row['KeyWords'] .= "Share";
					if($row['kwBeatGame']==1) {$row['KeyWords'] .= ", ";}
				}
				if($row['kwBeatGame']==1) {$row['KeyWords'] .= "Beat Game";}
				
				$row['LaunchDate']= date("n/j/Y",strtotime($row['LaunchDate']));
				
				if ($row['Status']<>""){
					$final_Status[$row['GameID']]=$row['Status'];
				}
				if ($row['Review']<>""){
					$final_Rating[$row['GameID']]=$row['Review'];
				}		
				$activity[]=$row;
			}
			
			
			foreach ($activity as &$row) {
				if (isset($final_Status[$row['GameID']])){
					$row['FinalStatus']=$final_Status[$row['GameID']];
				} else {
					$row['FinalStatus']="Active";
				}
				if (isset($final_Rating[$row['GameID']])){
					$row['finalRating']=$final_Rating[$row['GameID']];
				} else {
					$row['finalRating']="";
				}
				
				$row['Count']=$settings['status'][$row['FinalStatus']]['Count'];

				//Need to figure out FREE and WANT somehow (not in current sheet version though)
				$row['CountIdle'] = !($row['kwIdle']==1 && $settings['CountIdle']==0);
				$row['CountFarm'] = !($row['kwCardFarming']==1 && $settings['CountFarm']==0);
				$row['CountCheat'] = !($row['kwCheating']==1 && $settings['CountCheat']==0);
				$row['CountShare'] = !($row['kwShare']==1 && $settings['CountShare']==0);
				
				//if($row['HistoryID']==79){
				//	echo "CountShare: " . $row['CountShare'] . " = ". ($row['kwShare']==1) . " + " . ($settings['CountShare']==0);
				//}
				
				$row['FinalCountAll'] = $row['CountIdle'] && $row['CountFarm'] && $row['CountCheat'] && $row['CountShare'] && $row['Count']; // This will include FREE and WANT when complete
				
				$row['FinalCountHours'] = $row['CountIdle'] && $row['CountFarm'] && $row['CountCheat'] && $row['CountShare'] && $row['Count'];
			}
			
			foreach ($activity as &$row) {
				$date = strtotime($row['Timestamp']);

				if(isset($prevstart[$row['GameID']])) {
					$row['prevstart'] = $prevstart[$row['GameID']];
				} 
				
				if (!isset($total[$row['UseGame']][$row['System']])) {
					$total[$row['UseGame']][$row['System']] = 0;
				}
				if (!isset($exclude[$row['UseGame']][$row['System']])) {
					$exclude[$row['UseGame']][$row['System']] = 0;
				}
				
				$prevtotal[$row['GameID']]=array_sum($total[$row['UseGame']]);			

				$timeModifier=60*60;
				if($row['kwMinutes']==true){
					$row['Time']=$row['Time']/60;
				}

				switch ($row['Data']){
					case "Start/Stop":
						if( isset($prevstart[$row['GameID']])) {
							$row['Elapsed']= $date - $prevstart[$row['GameID']];
							
							$row['prevTotSys']=$total[$row['UseGame']][$row['System']];
							if($row['FinalCountHours']==true){
								$total[$row['UseGame']][$row['System']] += ($date - $prevstart[$row['GameID']]);
							} else {
								$exclude[$row['UseGame']][$row['System']] += ($date - $prevstart[$row['GameID']]);
							}
						} else {
							$row['Elapsed']="";
							$row['prevTotSys']= $total[$row['UseGame']][$row['System']];
						}
						break;
					case "add Time":
					case "Add Time":
						$row['Elapsed']=round($row['Time']*$timeModifier);
						
						$row['prevTotSys']= $total[$row['UseGame']][$row['System']];
						
						if($row['FinalCountHours']==true){
							$total[$row['UseGame']][$row['System']] += round($row['Time']*$timeModifier);
						} else {
							$exclude[$row['UseGame']][$row['System']] += round($row['Time']*$timeModifier);
						}
						break;
					case "new Total":
					case "New Total":
						$row['Elapsed']=round((($row['Time']*$timeModifier) - $total[$row['UseGame']][$row['System']]) - $exclude[$row['UseGame']][$row['System']]);
						$row['prevTotSys']= round($total[$row['UseGame']][$row['System']] );//- $exclude[$row['UseGame']][$row['System']]);
						if($row['FinalCountHours']==true){
							$total[$row['UseGame']][$row['System']] = round(($row['Time']*$timeModifier) - $exclude[$row['UseGame']][$row['System']]);
						} else {
							$exclude[$row['UseGame']][$row['System']] += $row['Elapsed'];
						}
						break;
				}

				$row['totalSys']=$total[$row['UseGame']][$row['System']];
				$row['prevTotal']=$prevtotal[$row['GameID']];
				$row['Total']=array_sum($total[$row['UseGame']]);

				if(!isset($firstpair[$row['GameID']])){
					$firstpair[$row['GameID']]=false;
				}
				if( $row['Data'] == "Start/Stop" and $firstpair[$row['GameID']] == false){
					$prevstart[$row['GameID']]=$date;
					$firstpair[$row['GameID']]=true;
				} else {
					if (isset($prevstart[$row['GameID']])) {
						unset($prevstart[$row['GameID']]);
					}
					$firstpair[$row['GameID']]=false;
				}
			}
		} else {
			$activity = false;
		}
	} else {
		$activity = false;
		trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
	}
	
	if($connection==false){
		$conn->close();	
	}	
	
	return $activity;	
}

if (basename($_SERVER["SCRIPT_NAME"], '.php') == "getHistoryCalculations.inc") {
	$GLOBALS['rootpath']="..";
	require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
	require_once $GLOBALS['rootpath']."/inc/functions.inc.php";
	
	$title="History Calculations Inc Test";
	echo Get_Header($title);
	
	$lookupgame=lookupTextBox("History", "HistoryID", "id", "History", $GLOBALS['rootpath']."/ajax/search.ajax.php");
	echo $lookupgame["header"];
	if (!(isset($_GET['id']) && is_numeric($_GET['id']))) {
		?>
		Please specify a hisotry record by ID.
		<form method="Get">
			<?php echo $lookupgame["textBox"]; ?>
			<input type="submit">
		</form>

		<?php
		echo $lookupgame["lookupBox"];
	} else {	
		//$actcalculations=reIndexArray(getHistoryCalculations(""),"GameID");
		$actcalculations=getHistoryCalculations("");
		echo arrayTable($actcalculations[$_GET['id']]);
	}
	echo Get_Footer();
}
?>